<?php

namespace App\Http\Controllers;

use App\Http\Requests;
// use App\Http\Repositories\ExpenseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
                    ->leftJoin('mst_work_category', 'mst_work_category.id', '=', 'exp.working_category')
                    ->leftJoin('mst_work_type', 'mst_work_type.id', '=', 'exp.working_type')
                    ->where('exp.created_by', '=', auth()->user()->name)
                    ->orderBy('exp.working_date','DESC')
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
                    ->select('emp_name','emp_id')
                    ->get();

        $work_cat_list = DB::table('mst_work_category')
                    ->select('work_category_name','id')
                    ->get();

        $work_type_list = DB::table('mst_work_type')
                    ->select('work_type_name','id')
                    ->get();

        return view('expense/register', compact('emp_list','work_cat_list','work_type_list'));
    }


    /**
     * Show the application expense register.
     *
     * @return \Illuminate\Http\Response
     */
    public function exp_reg_process(Request $request)
    {
        $this->validate($request,[
            'mason_name' => 'required|max:50',
            'working_date' => 'required|date|before:now',
            // 'working_hours' => 'required',
            'working_cat' => 'required',
            'working_type' => 'required',
            'salary' => 'required|integer|min:1|not_in:0'
        ]);
        $dt = new Carbon();
        if ($request->working_date) {
            $dt = new Carbon($request->working_date);
        }
        $working_date = $dt->format('Y-m-d');

        $expense_data['mason_name'] = $request->mason_name;
        $expense_data['working_date'] = $working_date;
        $expense_data['working_category'] = $request->working_cat;
        $expense_data['working_type'] = $request->working_type;
        $expense_data['salary'] = $request->salary;
        $expense_data['created_by'] = auth()->user()->name;

        $result = DB::table('t_expense')->insert($expense_data);
        $response['message'] = "expense register unsuccessfull...";
        $response['design'] = "alert-danger";
        if($result) {
            $response['message'] = "expense register successfull...";
            $response['design'] = "alert-success";
        }
        return redirect('expense_list')->with('response', $response);
    }
    
}
