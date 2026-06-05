<?php

namespace App\Http\Controllers;

use App\Repositories\WorkTypeRepository;
use App\Repositories\ProjectTypeRepository;
use App\Repositories\ExpenseDashBoardRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ExpenseDashBoardController extends Controller
{
     /**
      * Create a new controller instance.
      *
      * @return void
      */

     /**
      * work type repository
      */
     protected $workTypeRepository;
     /**
      * project type repository
      */
     protected $projectTypeRepository;
     /**
      * expense dashboard repository
      */
     protected $expenseDashBoardRepository;
     public function __construct()
     {
          $this->workTypeRepository = new WorkTypeRepository();
          $this->projectTypeRepository = new ProjectTypeRepository();
          $this->expenseDashBoardRepository = new ExpenseDashBoardRepository();
     }

     /**
      * Show the application expense dashboard.
      *
      * @param $request
      * @return \Illuminate\Http\Response
      */
     public function expense_dashboard(Request $request)
     {

          $project_type = $request->input('project_type');
          $work_type =  $request->input('work_type');
          $user_id = Auth::user()->user_id;
          $dashboard_data = $this->expenseDashBoardRepository->get_dashboard_expense_summary($project_type, $work_type, $user_id);

          $dashboard_data['total_exp'] = $this->formatExpenseValue($dashboard_data['total_exp'], Lang::get('message.dashboard.expense.total_exp'));
          $dashboard_data['total_today_exp'] = $this->formatExpenseValue($dashboard_data['total_today_exp'], Lang::get('message.dashboard.expense.total_today_exp'));
          $dashboard_data['total_yesterday_exp'] = $this->formatExpenseValue($dashboard_data['total_yesterday_exp'], Lang::get('message.dashboard.expense.total_yesterday_exp'));
          $dashboard_data['total_last_seven_day_exp'] = $this->formatExpenseValue($dashboard_data['total_last_seven_day_exp'], Lang::get('message.dashboard.expense.total_last_seven_day_exp'));
          $dashboard_data['total_current_month_exp'] = $this->formatExpenseValue($dashboard_data['total_current_month_exp'], Lang::get('message.dashboard.expense.total_current_month_exp'));
          $dashboard_data['total_last_month_exp'] = $this->formatExpenseValue($dashboard_data['total_last_month_exp'], Lang::get('message.dashboard.expense.total_last_month_exp'));

          $work_type_list = $this->workTypeRepository->get_dashboard_filter_work_type_list();
          $project_type_list = $this->projectTypeRepository->get_dashboard_filter_project_list();
          return view('dashboard/index', array_merge(
               $dashboard_data,
               [
                    'work_type_list' => $work_type_list,
                    'project_type_list' => $project_type_list,
               ]
          ))
               ->with([
                    'selected_project_type' => $project_type,
                    'selected_work_type' => $work_type,
               ]);
     }

     /**
      * 
      * format expense value
      *
      * @param mixed $amount
      * @param string $message
      * @return string
      */
     private function formatExpenseValue($amount, $message)
     {
          if ($amount == "") {
               return $message;
          }

          return number_format($amount, 2);
     }
}
