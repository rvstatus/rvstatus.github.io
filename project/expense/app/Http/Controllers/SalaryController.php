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
     * Salary list screen
     */
    public function index(Request $request)
    {
        // default pagination
        $plimit = $request->input('plimit', config('constants.pagination.salary', 50));

        // session month/year selection
        if (Session::get('year') !== null && Session::get('month') !== null) {
            $request->merge([
                'selYear' => Session::get('year'),
                'selMonth' => Session::get('month'),
            ]);
        }

        // calendar data
        $date = $this->salaryRepository->get_salary_calendar();
        $prev_yrs = $date[0] ?? [];

        $total_yrs1 = array_unique($date->pluck('year')->toArray());

        asort($total_yrs1);

        $total_yrs = array_values($total_yrs1);

        // current month/year logic
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
            'request' => $request
        ]);
    }

    /**
     * salary emp selection popup screen
     */
    public function empselectionpopup(Request $request)
    {
        $employeeUnselect = $this->salaryRepository->getAllEmpDetails($request, Auth::user()->user_id);
        $employeeSelect = $this->salaryRepository->getAllFilteredEmpDetails($request, Auth::user()->user_id);
        return view('salary.empselectionpopup', [
            'request' => $request,
            'employeeUnselect' => $employeeUnselect,
            'employeeSelect' => $employeeSelect
        ]);
    }

    /**
     * Employee Selection Process (Salary Module)
     *
     * This method stores selected employees for salary processing
     * based on year and month, and redirects back to listing page.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function empselectionprocess(Request $request)
    {
        //  insert / update selected employees
        $status = $this->salaryRepository->InsertEmpFlrDetails($request, Auth::user()->user_id);

        // store filter state in session
        Session::flash('year', $request->year);
        Session::flash('month', $request->month);

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
     * Salary Add Screen
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

        $userDetail = $this->salaryRepository->fnGetUserSalaryDetail($request);

        return view('salary.add', [
            'userDetail' => $userDetail,
            'request'    => $request
        ]);
    }

    /**
     *
     * store Salary Details
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

        $insertStatus = false;
        for ($i = 1; $i <= $request->count; $i++) {
            $basicSalary = 'basicSalary' . $i;
            $insentive   = 'insentive' . $i;
            // todo in future
            // $pfAmount    = 'pfAmount' . $i;
            // $esiAmount   = 'esiAmount' . $i;
            $request->merge(['iterator' => $i]);
            if (!empty($request->$basicSalary) || !empty($request->$insentive)) {
                $insertStatus = $this->salaryRepository->insert_salary($request, Auth::user()->user_id);
            }
        }

        Session::flash('month', $request->selMonth);
        Session::flash('year', $request->selYear);
        if ($insertStatus) {
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
}
