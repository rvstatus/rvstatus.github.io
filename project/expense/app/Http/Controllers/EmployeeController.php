<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EmployeeRepository;
use App\Repositories\WorkCategoryRepository;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;

/**
 * Employee Controller
 */
class EmployeeController extends Controller
{
    /**
     * employee repository
     */
    protected $employeeRepository;
    /**
     * work category repository
     */
    protected $workCategoryRepository;
    /**
     * Constructor Injection
     */
    public function __construct(EmployeeRepository $employeeRepository, WorkCategoryRepository $workCategoryRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->workCategoryRepository = $workCategoryRepository;
    }

    /**
     * get the employee list
     */
    public function employee_list()
    {
        $perPage = config('constants.pagination.employee');
        $employee_list = $this->employeeRepository->get_employee_list($perPage, Auth::user()->user_id);
        return view('employee.list', compact('employee_list'));
    }

    /**
     * employee register screen
     */
    public function employee_register()
    {
        $work_cat_list = $this->workCategoryRepository->get_active_work_category_list(Auth::user()->user_id);
        return view('employee.register', compact('work_cat_list'));
    }
    /**
     * employee register process
     */
    public function employee_reg_process(Request $request)
    {
        $request->validate(
            [
                'emp_name' => 'required|min:3|max:50',
                'gender' => 'required',
                'mobile_no' => 'required|digits_between:10,10',
                'email' => 'required|email|max:100',
                'address' => 'required|max:500',
                'join_date' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                'category_id' => 'required',
                'salary' => 'required|numeric|min:1|max:999999',
            ],
            [
                'emp_name.required' => Lang::get('messages.employee.validation.emp_name.required'),
                'emp_name.min' => Lang::get('messages.employee.validation.emp_name.min'),
                'emp_name.max' => Lang::get('messages.employee.validation.emp_name.max'),

                'gender.required' => Lang::get('messages.employee.validation.gender.required'),

                'mobile_no.required' => Lang::get('messages.employee.validation.mobile_no.required'),
                'mobile_no.digits_between' => Lang::get('messages.employee.validation.mobile_no.digits_between'),

                'address.required' => Lang::get('messages.employee.validation.address.required'),
                'address.max' => Lang::get('messages.employee.validation.address.max'),

                'email.required' => Lang::get('messages.employee.validation.email.required'),
                'email.email' => Lang::get('messages.employee.validation.email.email'),

                'join_date.required' => Lang::get('messages.employee.validation.join_date.required'),
                'join_date.date' => Lang::get('messages.employee.validation.join_date.date'),
                'join_date.before_or_equal' => Lang::get('messages.employee.validation.join_date.before_or_equal'),

                'category_id.required' => Lang::get('messages.employee.validation.category_id.required'),

                'salary.required' => Lang::get('messages.employee.validation.salary.required'),
                'salary.numeric' => Lang::get('messages.employee.validation.salary.numeric'),
                'salary.min' => Lang::get('messages.employee.validation.salary.min'),
            ]
        );
        // employee code generate
        $emp_id = $this->employeeRepository->get_next_employee_code();
        // insert employee
        $join_date = date('Y-m-d', strtotime($request->join_date));
        $insert = $this->employeeRepository->insert_employee($request->emp_name, $request->gender, $request->mobile_no, $request->email, $request->address, $join_date, $request->category_id, $request->salary, $emp_id, Auth::user()->user_id);
        if ($insert) {
            return redirect('/employee_list')
                ->with(
                    'response',
                    [
                        'design' => 'alert-success',
                        'message' => Lang::get('messages.employee.create.success'),
                    ]
                );
        }
        return redirect()->back()->withInput()
            ->with(
                'response',
                [
                    'design' => 'alert-danger',
                    'message' => Lang::get('messages.employee.create.fail'),
                ]
            );
    }
}
