<?php

namespace App\Http\Controllers;

use App\Repositories\ExpenseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

class ExpenseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $expenseRepository;
    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Show the application expense list.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_list()
    {
        $exp_list = DB::table('t_expense AS exp')
            ->select(
                'mst_project_type.project_type_name AS project_type_name',
                'm_emp.emp_name AS name',
                'exp.working_date',
                'exp.working_hours',
                'exp.salary',
                'exp.created_by',
                DB::raw("DATE_FORMAT(exp.created_at, '%Y-%m-%d %H:%i:%s') AS created_date"),
                'mst_work_category.work_category_name',
                'mst_work_type.work_type_name'
            )
            ->leftJoin('m_emp', 'm_emp.emp_id', '=', 'exp.mason_name')
            ->leftJoin('mst_project_type', 'mst_project_type.project_type_id', '=', 'exp.project_type_id')
            ->leftJoin('mst_work_category', 'mst_work_category.id', '=', 'exp.working_category')
            ->leftJoin('mst_work_type', 'mst_work_type.id', '=', 'exp.working_type')
            ->where('exp.created_by', '=', auth()->user()->name)
            ->orderBy('exp.working_date', 'DESC')
            ->get();
        return view('expense/list', compact('exp_list'));
    }

    /**
     * Show the application expense register.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_register()
    {
        // $emp_list = ExpenseRepository::getEmpList();

        $emp_list = DB::table('m_emp')
            ->select('emp_name', 'emp_id')
            ->get();

        $work_cat_list = DB::table('mst_work_category')
            ->select('work_category_name', 'id')
            ->get();

        $work_type_list = DB::table('mst_work_type')
            ->select('work_type_name', 'id')
            ->get();

        $expenseRepository = new ExpenseRepository();
        $project_type_list = $expenseRepository->get_project_list();

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
        $expense_data['created_by'] = auth()->user()->name;

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
