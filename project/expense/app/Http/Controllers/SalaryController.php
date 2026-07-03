<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SalaryRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

/**
 * Salary Controller
 */
class SalaryController extends Controller
{
    /**
     * salary repository instance
     */
    protected $salaryRepository;

    /**
     * Constructor Injection
     */
    public function __construct(SalaryRepository $salaryRepository)
    {
        $this->salaryRepository = $salaryRepository;
    }

    /**
     * salary list screen
     */
    public function index(Request $request)
    {
        // default pagination
        $plimit = $request->input('plimit', config('constants.pagination.salary', 50));

        // session month / year selection
        if (Session::get('selYear') !== null && Session::get('selMonth') !== null) {
            $request->merge([
                'selYear' => Session::get('selYear'),
                'selMonth' => Session::get('selMonth'),
            ]);
        }

        // calendar data
        $date = $this->salaryRepository->get_salary_calendar();
        $prev_yrs = [];

        foreach ($date as $row) {
            $prev_yrs[$row->year][] = (int) $row->month;
        }
        // remove duplicate months
        foreach ($prev_yrs as $year => $months) {
            $prev_yrs[$year] = array_values(array_unique($months));
            sort($prev_yrs[$year]);
        }
        $total_yrs1 = array_unique($date->pluck('year')->toArray());

        asort($total_yrs1);

        $total_yrs = array_values($total_yrs1);

        // current month / year logic
        $cur_year = date('Y');
        $cur_month = date("m", strtotime("-1 month"));

        if (count($total_yrs) > 1 && $cur_month == 12) {
            $cur_year = date('Y', strtotime("last month"));
        }

        $curtime = date('YmdHis');

        if ($cur_month == 0) {
            $cur_year--;
            $cur_month = 12;
        }

        // selected filter
        if (!empty($request->selMonth)) {
            $cur_month = $request->selMonth;
            $cur_year = $request->selYear;
        }
        // always include last month
        $lastMonth = date("m", strtotime("-1 month"));
        $lastYear  = date("Y", strtotime("-1 month"));

        if (!isset($prev_yrs[$lastYear])) {
            $prev_yrs[$lastYear] = [];
        }

        if (!in_array((int)$lastMonth, $prev_yrs[$lastYear])) {
            $prev_yrs[$lastYear][] = (int)$lastMonth;
            sort($prev_yrs[$lastYear]);
        }

        // user salary check list
        $userDetail = $this->salaryRepository->get_user_salary_detail($request);
        $selectedUserSalaryCount = count($userDetail);

        // employee list with salary
        $employeeLists = $this->salaryRepository->get_employee_list($request, $plimit);
        return view('salary.index', [
            'employeeLists' => $employeeLists,
            'prev_yrs' => $prev_yrs,
            'cur_year' => $cur_year,
            'cur_month' => $cur_month,
            'total_yrs' => $total_yrs,
            'curtime' => $curtime,
            'selectedUserSalaryCount' => $selectedUserSalaryCount,
            'request' => $request,
            'plimit' => $plimit
        ]);
    }

    /**
     * salary emp selection popup screen
     */
    public function empselectionpopup(Request $request)
    {
        $employeeUnselect = $this->salaryRepository->get_all_emp_details($request, Auth::user()->user_id);
        $employeeSelect = $this->salaryRepository->get_all_filtered_emp_details($request, Auth::user()->user_id);
        return view('salary.empselectionpopup', [
            'request' => $request,
            'employeeUnselect' => $employeeUnselect,
            'employeeSelect' => $employeeSelect
        ]);
    }

    /**
     * employee selection process
     *
     * this method stores selected employees for salary processing
     * based on year and month, and redirects back to listing page.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function empselectionprocess(Request $request)
    {
        //  insert / update selected employees
        $status = $this->salaryRepository->insert_emp_flr_details($request, Auth::user()->user_id);

        // store filter state in session
        Session::flash('selYear', $request->year);
        Session::flash('selMonth', $request->month);

        // clear temporary request values
        $request->merge([
            'selected' => [],
            'removed'  => []
        ]);
        if ($status) {
            // redirect back to salary list with success message
            return Redirect::to('salary/index?mainmenu=' .
                $request->mainmenu .
                '&time=' . date('YmdHis'))->with('response', [
                'design' => 'alert-success',
                'message' => Lang::get('messages.salary.employee_selection.success'),
            ]);
        }
        // redirect back to salary list fail message
        return Redirect::to('salary/index?mainmenu=' .
            $request->mainmenu .
            '&time=' . date('YmdHis'))->with('response', [
            'design' => 'alert-danger',
            'message' => Lang::get('messages.salary.employee_selection.fail'),
        ]);
    }

    /**
     * salary add screen
     */
    public function addSalary(Request $request)
    {
        if (empty($request->selMonth) || empty($request->selYear)) {
            return redirect()->route('salary.index');
        }

        if (empty($request->plimit)) {
            $request->merge([
                'plimit' => 50
            ]);
        }

        $userDetail = $this->salaryRepository->fn_get_user_salary_detail($request);

        return view('salary.add', [
            'userDetail' => $userDetail,
            'request'    => $request
        ]);
    }

    /**
     *
     * store salary details
     * save salary information for selected employees.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProcess(Request $request)
    {
        if (empty($request->selMonth) || empty($request->selYear)) {
            // redirect back to salary list if no request value
            return Redirect::to(
                'salary/index?mainmenu=' .
                    $request->mainmenu .
                    '&time=' . date('YmdHis')
            )->with(
                'response',
                [
                    'design' => 'alert-danger',
                    'message' => Lang::get('messages.salary.index.error'),
                ]
            );
        }

        $insert_status = false;
        for ($i = 1; $i <= $request->count; $i++) {
            $basicSalary = 'basicSalary' . $i;
            $insentive   = 'insentive' . $i;
            // todo in future
            // $pfAmount    = 'pfAmount' . $i;
            // $esiAmount   = 'esiAmount' . $i;
            $request->merge(['iterator' => $i]);
            if (!empty($request->$basicSalary) || !empty($request->$insentive)) {
                $insert_status = $this->salaryRepository->insert_salary($request, Auth::user()->user_id);
            }
        }

        // store filter state in session
        Session::flash('selYear', $request->selYear);
        Session::flash('selMonth', $request->selMonth);
        if ($insert_status) {
            // redirect back to salary list with success message
            return Redirect::to(
                'salary/index?mainmenu=' .
                    $request->mainmenu .
                    '&time=' . date('YmdHis')
            )->with(
                'response',
                [
                    'design' => 'alert-success',
                    'message' => Lang::get('messages.salary.create.success'),
                ]
            );
        } else {
            // redirect back to salary list with fail message
            return Redirect::to(
                'salary/index?mainmenu=' .
                    $request->mainmenu .
                    '&time=' . date('YmdHis')
            )->with(
                'response',
                [
                    'design' => 'alert-danger',
                    'message' => Lang::get('messages.salary.create.fail'),
                ]
            );
        }
    }

    /**
     * salary detail view
     *
     * display employee salary details with yearly summary and total salary information.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function detailView(Request $request)
    {
        // redirect to salary list if employee id is missing
        if (empty($request->empId)) {
            return redirect()->route('salary.index');
        }

        // default pagination
        $plimit = $request->input('plimit', config('constants.pagination.salary', 50));
        // get employee salary details
        $salaryYearArray = $this->salaryRepository->get_salary_year_detail($request);

        $totalSalaryArray = $this->salaryRepository->get_total_salary_array_detail($request);

        $salaryDetail = $this->salaryRepository->get_salary_detail_view($request, $plimit);

        return view('salary.detailview', [
            'salaryDetail'     => $salaryDetail,
            'salaryYearArray'  => $salaryYearArray,
            'totalSalaryArray' => $totalSalaryArray,
            'request'          => $request,
        ]);
    }

    /**
     * salary edit screen
     *
     * load salary edit view for selected salary record.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        if (!isset($request->salaryId)) {
            return $this->index($request);
        }

        $userSalaryEditDetail = $this->salaryRepository->fn_get_user_salary_edit_detail($request);

        return view('salary.edit', [
            'userSalaryEditDetail' => $userSalaryEditDetail[0],
            'request' => $request
        ]);
    }

    /**
     * salary view screen
     *
     * display selected salary record details.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function view(Request $request)
    {
        $request->merge([
            'salaryId' => $request->salaryId ?? Session::get('salaryId'),
        ]);
        $request->merge([
            'plimit' => $request->plimit ?? Session::get('plimit'),
        ]);
        $request->merge([
            'selYear' => $request->selYear ?? Session::get('selYear'),
        ]);
        $request->merge([
            'selMonth' => $request->selMonth ?? Session::get('selMonth'),
        ]);
        // salary id mandatory
        if (empty($request->salaryId)) {
            // redirect to payslip screen
            if (!empty($request->screenName) && $request->screenName === 'payslip_index') {
                return Redirect::to('paySlip/index?mainmenu=paySlip_emp&time=' . date('YmdHis'));
            }
            // redirect salary list
            return $this->index($request);
        }

        // get salary details
        $salaryViewDetail = $this->salaryRepository->get_salary_view_detail($request);

        return view(
            'salary.view',
            [
                'salaryViewDetail' => $salaryViewDetail[0],
                'request' => $request
            ]
        );
    }

    /**
     * update salary details
     *
     * update salary information for selected employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editProcess(Request $request)
    {
        // salary id mandatory
        if (empty($request->salaryId)) {
            return Redirect::to(
                'salary/index?mainmenu=' .
                    $request->mainmenu .
                    '&time=' . date('YmdHis')
            )->with(
                'response',
                [
                    'design' => 'alert-danger',
                    'message' => Lang::get('messages.salary.edit.error'),
                ]
            );
        }

        // update salary details
        $updateStatus = $this->salaryRepository->salary_update(
            $request,
            Auth::user()->user_id
        );
        // set the request salaryId and plimit into session
        Session::flash('salaryId', $request->salaryId);
        Session::flash('plimit', $request->plimit);
        // set the request selYear and selMonth into session
        Session::flash('selYear', $request->selYear);
        Session::flash('selMonth', $request->selMonth);

        // redirect based on update result
        if ($updateStatus) {
            return Redirect::to(
                'salary/view?mainmenu=' .
                    $request->mainmenu .
                    '&time=' . date('YmdHis')
            )->with(
                'response',
                [
                    'design' => 'alert-success',
                    'message' => Lang::get('messages.salary.update.success'),
                ]
            );
        }

        return Redirect::to(
            'salary/edit?mainmenu=' .
                $request->mainmenu .
                '&time=' . date('YmdHis')
        )->with(
            'response',
            [
                'design' => 'alert-danger',
                'message' => Lang::get('messages.salary.update.fail'),
            ]
        );
    }
}
