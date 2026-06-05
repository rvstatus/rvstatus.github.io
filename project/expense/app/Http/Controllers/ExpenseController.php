<?php

namespace App\Http\Controllers;

use App\Repositories\ExpenseRepository;
use App\Repositories\WorkCategoryRepository;
use App\Repositories\ProjectTypeRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\WorkTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $expenseRepository;
    /**
     * work category repository
     */
    protected $workCategoryRepository;
    /**
     * project type repository
     */
    protected $projectTypeRepository;
    /**
     * employee repository
     */
    protected $employeeRepository;
    /**
     * work type repository
     */
    protected $workTypeRepository;
    public function __construct(
        ExpenseRepository $expenseRepository,
        WorkCategoryRepository $workCategoryRepository,
        ProjectTypeRepository $projectTypeRepository,
        EmployeeRepository $employeeRepository,
        WorkTypeRepository $workTypeRepository
    ) {
        $this->expenseRepository = $expenseRepository;
        $this->workCategoryRepository = $workCategoryRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->employeeRepository = $employeeRepository;
        $this->workTypeRepository = $workTypeRepository;
    }

    /**
     * Show the application expense list.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_list()
    {
        $exp_list = $this->expenseRepository->get_expense_list_by_user(Auth::user()->user_id);
        return view('expense/list', compact('exp_list'));
    }

    /**
     * Show the application expense register.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_register()
    {
        // get active employee list created by,current user
        $emp_list = $this->employeeRepository->get_active_employee_list_by_user(Auth::user()->user_id);

        // get active work category type list created by,current user
        $work_cat_list = $this->workCategoryRepository->get_active_work_category_list(Auth::user()->user_id);

        // get active work type list created by,current user
        $work_type_list = $this->workTypeRepository->get_active_work_type_list_by_user(Auth::user()->user_id);

        $project_type_list = $this->projectTypeRepository->get_active_project_list(Auth::user()->user_id);

        return view('expense/register', compact('emp_list', 'work_cat_list', 'work_type_list', 'project_type_list'));
    }


    /**
     * Show the application expense register.
     *
     * @return \Illuminate\Http\Response
     */
    public function exp_reg_process(Request $request)
    {
        $request->validate(
            [
                'project_type_name' => 'required',
                'mason_name' => 'required',
                'working_date' => 'required|date|before:now',
                // 'working_hours' => 'required',
                'working_cat' => 'required',
                'working_type' => 'required',
                'salary' => 'required|integer|min:1|not_in:0'
            ],
            [
                // custom error messages for validation
                'project_type_name.required' => Lang::get('messages.expense.validation.project_type_name.required'),

                'mason_name.required' => Lang::get('messages.expense.validation.mason_name.required'),

                'working_date.required' => Lang::get('messages.expense.validation.working_date.required'),
                'working_date.date' => Lang::get('messages.expense.validation.working_date.date'),
                'working_date.before' => Lang::get('messages.expense.validation.working_date.before'),

                'working_cat.required' => Lang::get('messages.expense.validation.working_cat.required'),

                'working_type.required' => Lang::get('messages.expense.validation.working_type.required'),

                'salary.required' => Lang::get('messages.expense.validation.salary.required'),
                'salary.integer' => Lang::get('messages.expense.validation.salary.integer'),
                'salary.min' => Lang::get('messages.expense.validation.salary.min'),
                'salary.not_in' => Lang::get('messages.expense.validation.salary.not_in'),
            ]
        );
        $dt = new Carbon();
        if ($request->working_date) {
            $dt = new Carbon($request->working_date);
        }
        $working_date = $dt->format('Y-m-d');

        $expense_data['project_type_id'] = $request->project_type_name;
        $expense_data['mason_name'] = $request->mason_name;
        $expense_data['working_date'] = $working_date;
        $expense_data['working_hours'] = $request->working_hours;
        $expense_data['working_category'] = $request->working_cat;
        $expense_data['working_type'] = $request->working_type;
        $expense_data['salary'] = $request->salary;
        $expense_data['created_by'] = Auth::user()->user_id;

        $expenseRepository = new ExpenseRepository();
        $result = $expenseRepository->insert_expense($expense_data);
        $response['message'] = "Expense Register Unsuccessfull...";
        $response['design'] = "alert-danger";
        if ($result) {
            $response['message'] = "Expense Register Successfull.";
            $response['design'] = "alert-success";
        }
        return redirect('expense_list')->with('response', $response);
    }
}
