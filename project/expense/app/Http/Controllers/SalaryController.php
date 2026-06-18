<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SalaryRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

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
        $this->salaryRepository->InsertEmpFlrDetails($request, Auth::user()->user_id);

        // store filter state in session
        Session::flash('year', $request->year);
        Session::flash('month', $request->month);
        Session::flash('message', 'Employees Selected Successfully!');
        Session::flash('type', 'alert-success');

        // clear temporary request values
        $request->merge([
            'selected' => [],
            'removed'  => []
        ]);

        // redirect back to salary list
        return Redirect::to(
            'salary/index?mainmenu=' .
                $request->mainmenu .
                '&time=' . date('YmdHis')
        );
    }
}
