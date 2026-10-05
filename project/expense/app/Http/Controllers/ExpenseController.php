<?php

namespace App\Http\Controllers;

use App\Repositories\ExpenseRepository;
use App\Repositories\ProjectTypeRepository;
use App\Repositories\ExpenseTypeRepository;
use App\Repositories\ExpenseNameRepository;
use Illuminate\Http\Request;
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
     * project type repository
     */
    protected $projectTypeRepository;

    /**
     * expense type repository
     */
    protected $expenseTypeRepository;

    /**
     * expense master repository
     */
    protected $expenseNameRepository;

    public function __construct(
        ExpenseRepository $expenseRepository,
        ProjectTypeRepository $projectTypeRepository,
        ExpenseTypeRepository $expenseTypeRepository,
        ExpenseNameRepository $expenseNameRepository
    ) {
        $this->expenseRepository = $expenseRepository;
        $this->projectTypeRepository = $projectTypeRepository;
        $this->expenseTypeRepository = $expenseTypeRepository;
        $this->expenseNameRepository = $expenseNameRepository;
    }

    /**
     * Show the application expense list.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_list()
    {
        $perPage = config('constants.pagination.expense');

        $expense_list = $this->expenseRepository->get_expense_list_by_user($perPage, Auth::user()->user_id);
        return view('expense/list', compact('expense_list'));
    }

    /**
     * Show the application expense register.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_register()
    {
        $project_type_list = $this->projectTypeRepository->get_active_project_list(Auth::user()->user_id);
        $expense_type_list = $this->expenseTypeRepository->get_active_expense_type_list(Auth::user()->user_id);
        // empty expense name List.
        // expense names will be loaded based on selected expense type.
        $expense_name_list = [];
        return view('expense/register', compact(
            'project_type_list',
            'expense_type_list',
            'expense_name_list'
        ));
    }


    /**
     * Show the application expense register.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_reg_process(Request $request)
    {
        $request->validate(
            [
                'project_type_id' => 'required',
                'expense_type_id' => 'required',
                'other_expense_type' => 'nullable|max:150',
                'expense_name_id' => 'required',
                'other_expense_name' => 'nullable|max:150',
                'expense_amount' => 'required|numeric|min:1|max:150000',
                'expense_date' => 'required|date_format:d/m/Y|before_or_equal:' . now()->format('Y-m-d'),
                'expense_description' => 'nullable|max:255',
                'vendor_name' => 'nullable|max:100',
                'bill_no' => 'nullable|max:50',
                'remarks' => 'nullable|max:255'
            ],
            [
                'project_type_id.required' => Lang::get('messages.expense.validation.project_type_id.required'),

                'expense_type_id.required' => Lang::get('messages.expense.validation.expense_type_id.required'),
                'other_expense_type.max' => Lang::get('messages.expense.validation.other_expense_type.max'),

                'expense_name_id.required' => Lang::get('messages.expense.validation.expense_name_id.required'),
                'other_expense_name.max' => Lang::get('messages.expense.validation.other_expense_name.max'),

                'expense_amount.required' => Lang::get('messages.expense.validation.expense_amount.required'),
                'expense_amount.numeric' => Lang::get('messages.expense.validation.expense_amount.numeric'),
                'expense_amount.min' => Lang::get('messages.expense.validation.expense_amount.min'),
                'expense_amount.max' => Lang::get('messages.expense.validation.expense_amount.max'),

                'expense_date.required' => Lang::get('messages.expense.validation.expense_date.required'),
                'expense_date.date_format' => Lang::get('messages.expense.validation.expense_date.date_format'),
                'expense_date.before_or_equal' => Lang::get('messages.expense.validation.expense_date.before_or_equal'),

                'expense_description.max' => Lang::get('messages.expense.validation.expense_description.max'),

                'vendor_name.max' => Lang::get('messages.expense.validation.vendor_name.max'),

                'bill_no.max' => Lang::get('messages.expense.validation.bill_no.max'),

                'remarks.max' => Lang::get('messages.expense.validation.remarks.max')
            ]
        );

        $expense_date = Carbon::createFromFormat('d/m/Y', $request->expense_date)->format('Y-m-d');

        $expense_type_id = $request->expense_type_id;
        // if others selected
        if ($request->expense_type_id == 999) {
            $expense_type = [
                'expense_type_name' => $request->other_expense_type,
                'deleted_flg' => 0,
                'created_by' => Auth::user()->user_id,
            ];

            $expense_type_id = $this->expenseTypeRepository->insert_expense_type($expense_type);
        }

        $expense_name_id = $request->expense_name_id;
        // if others selected
        if ($request->expense_name_id == 999) {
            $expense = [
                'expense_type_id' => $expense_type_id,
                'expense_name'    => $request->other_expense_name,
                'deleted_flg'     => 0,
                'created_by'      => Auth::user()->user_id,
            ];
            $expense_name_id = $this->expenseNameRepository->insert_expense($expense);
        }


        $expense_data = [
            'project_type_id' => $request->project_type_id,
            'expense_type_id' => $expense_type_id,
            'expense_name_id' => $expense_name_id,
            'expense_amount' => $request->expense_amount,
            'expense_description' => $request->expense_description,
            'expense_date' => $expense_date,
            'vendor_name' => $request->vendor_name,
            'bill_no' => $request->bill_no,
            'remarks' => $request->remarks,
            'created_by' => Auth::user()->user_id,
        ];

        $expenseRepository = new ExpenseRepository();
        $insert_result = $expenseRepository->insert_expense($expense_data);

        if ($insert_result) {

            return redirect('/expense_list')
                ->with(
                    'response',
                    [
                        'design' => 'alert-success',
                        'message' => Lang::get('messages.expense.create.success'),
                    ]
                );
        }


        return redirect()->back()
            ->withInput()
            ->with(
                'response',
                [
                    'design' => 'alert-danger',
                    'message' => Lang::get('messages.expense.create.fail'),
                ]
            );
    }

    /**
     * expense detail screen
     */
    public function expense_detail(Request $request)
    {
        $expense = $this->expenseRepository
            ->get_expense_by_id(
                $request->id,
                Auth::user()->user_id
            );


        if (!$expense) {

            return redirect('/expense_list')
                ->with(
                    'response',
                    [
                        'design' => 'alert-danger',
                        'message' => Lang::get('messages.expense.detail.not_found'),
                    ]
                );
        }


        return view(
            'expense.detail',
            compact('expense')
        );
    }


    /**
     * expense edit screen
     */
    public function expense_edit(Request $request)
    {
        $expense = $this->expenseRepository
            ->get_expense_by_id(
                $request->id,
                Auth::user()->user_id
            );


        if (!$expense) {

            return redirect('/expense_list')
                ->with(
                    'response',
                    [
                        'design' => 'alert-danger',
                        'message' => Lang::get('messages.expense.detail.not_found'),
                    ]
                );
        }


        $project_type_list = $this->projectTypeRepository
            ->get_active_project_list(
                Auth::user()->user_id
            );

        $expense_name_list = $this->expenseNameRepository
            ->get_active_expense_list(
                $expense->expense_type_id
            );
        $expense_type_list = $this->expenseTypeRepository->get_active_expense_type_list(Auth::user()->user_id);


        return view(
            'expense.edit',
            compact(
                'expense',
                'project_type_list',
                'expense_type_list',
                'expense_name_list'
            )
        );
    }


    /**
     * expense update process
     */
    public function expense_update(Request $request)
    {
        $request->validate(
            [
                'id' => 'required',
                'project_type_id' => 'required',
                'expense_type_id' => 'required',
                'expense_name_id' => 'required',
                'other_expense_name' => 'nullable|max:150',
                'expense_amount' => 'required|numeric|min:1',
                'expense_date' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
                'expense_description' => 'nullable|max:255',
                'vendor_name' => 'nullable|max:100',
                'bill_no' => 'nullable|max:50',
                'remarks' => 'nullable|max:255',
            ],
            [
                'project_type_id.required' => Lang::get('messages.expense.validation.project_type_id.required'),

                'expense_type_id.required' => Lang::get('messages.expense.validation.expense_type_id.required'),

                'expense_name_id.required' => Lang::get('messages.expense.validation.expense_name_id.required'),
                'other_expense_name.max' => Lang::get('messages.expense.validation.other_expense_name.max'),

                'expense_amount.required' => Lang::get('messages.expense.validation.expense_amount.required'),
                'expense_amount.numeric' => Lang::get('messages.expense.validation.expense_amount.numeric'),
                'expense_amount.min' => Lang::get('messages.expense.validation.expense_amount.min'),

                'expense_date.required' => Lang::get('messages.expense.validation.expense_date.required'),
                'expense_date.date' => Lang::get('messages.expense.validation.expense_date.date'),
                'expense_date.before_or_equal' => Lang::get('messages.expense.validation.expense_date.before_or_equal'),

                'expense_description.max' => Lang::get('messages.expense.validation.expense_description.max'),

                'vendor_name.max' => Lang::get('messages.expense.validation.vendor_name.max'),

                'bill_no.max' => Lang::get('messages.expense.validation.bill_no.max'),

                'remarks.max' => Lang::get('messages.expense.validation.remarks.max'),
            ]
        );


        $expense_date = Carbon::createFromFormat('Y-m-d',            $request->expense_date)->format('Y-m-d');


        $update_data = [
            'project_type_id' => $request->project_type_id,
            'expense_type_id' => $request->expense_type_id,
            'expense_name' => $request->expense_name,
            'expense_amount' => $request->expense_amount,
            'expense_description' => $request->expense_description,
            'expense_date' => $expense_date,
            'vendor_name' => $request->vendor_name,
            'bill_no' => $request->bill_no,
            'remarks' => $request->remarks,
        ];


        $update = $this->expenseRepository
            ->update_expense(
                $request->id,
                $update_data,
                Auth::user()->user_id
            );


        if ($update) {

            $expense = $this->expenseRepository
                ->get_expense_by_id(
                    $request->id,
                    Auth::user()->user_id
                );


            return view(
                'expense.detail',
                compact('expense')
            )
                ->with(
                    'response',
                    [
                        'design' => 'alert-success',
                        'message' => Lang::get('messages.expense.update.success'),
                    ]
                );
        }


        return redirect()->back()
            ->withInput()
            ->with(
                'response',
                [
                    'design' => 'alert-danger',
                    'message' => Lang::get('messages.expense.update.fail'),
                ]
            );
    }


    /**
     * expense delete process
     */
    public function expense_delete(Request $request)
    {
        $delete = $this->expenseRepository
            ->delete_expense(
                $request->id,
                Auth::user()->user_id
            );


        if ($delete) {

            return redirect('/expense_list')
                ->with(
                    'response',
                    [
                        'design' => 'alert-success',
                        'message' => Lang::get('messages.expense.delete.success'),
                    ]
                );
        }


        return redirect('/expense_list')
            ->with(
                'response',
                [
                    'design' => 'alert-danger',
                    'message' => Lang::get('messages.expense.delete.fail'),
                ]
            );
    }

    /**
     * Get Expense List By Expense Type
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_expense_list_by_type(Request $request)
    {
        $expense_list = $this->expenseNameRepository
            ->get_active_expense_list(
                $request->expense_type_id
            );

        return response()->json(
            $expense_list
        );
    }

    /**
     * project based expense detail screen
     */
    public function project_expense_detail(Request $request)
    {
        $project = $this->projectTypeRepository->get_by_id( $request->id );
        if (!$project) {
            return redirect('/expense_list')
                ->with(
                    'response',
                    [
                        'design' => 'alert-danger',
                        'message' => Lang::get('messages.expense.detail.not_found'),
                    ]
                );
        }

        $expense_list = $this->expenseRepository->get_expense_list_by_project( $request->id, Auth::user()->user_id );
        return view(
            'expense.project_detail',
            compact(
                'project',
                'expense_list'
            )
        );
    }

}
