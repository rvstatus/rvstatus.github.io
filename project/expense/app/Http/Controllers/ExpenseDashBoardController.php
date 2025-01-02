<?php

namespace App\Http\Controllers;

use App\Http\Requests;
// use App\Http\Repositories\ExpenseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseDashBoardController extends Controller
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
     * Show the application expense dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_dashboard()
    {
        // total expense
        $total_exp = DB::table('t_expense')->sum('salary');

        // total today expense
        $total_today_exp = DB::table('t_expense')
                            ->where('working_date', '=', Carbon::today()->toDateString())
                            ->sum('salary');

        // total yesterday expense
        $total_yesterday_exp = DB::table('t_expense')
                                ->whereRaw('DATE(working_date) = ?', [Carbon::yesterday()->toDateString()])
                            ->sum('salary');

        // total last seven days expense
        $total_last_seven_day_exp = DB::table('t_expense')
                            ->whereRaw('DATE(working_date) >= ?', [Carbon::today()->subDays(7)->toDateString()])
                            ->whereRaw('DATE(working_date) <= ?', [Carbon::today()->toDateString()])
                            ->sum('salary');

        // total current month expense
        $total_current_month_exp = DB::table('t_expense')
                            ->whereBetween('working_date', [Carbon::now()->startOfMonth(), Carbon::now()->addMonth()->startOfMonth()])
                            ->sum('salary');

        // total last month expense
        $total_last_month_exp = DB::table('t_expense')
                            ->whereRaw("DATE_FORMAT(working_date, '%Y-%m') = ?", [Carbon::now()->subMonth()->format('Y-m')])
                            ->sum('salary');

        if($total_exp == "") {
             $total_exp = "No Expenses Logged Yet.";
        } else {
             $total_exp = "&#8377; ".number_format($total_exp, 2);
        }
        if($total_today_exp == "") {
             $total_today_exp = "No Expenses Logged Today.";
        } else {
             $total_today_exp = "&#8377; ".number_format($total_today_exp, 2);
        }
        if($total_yesterday_exp == "") {
             $total_yesterday_exp = "No Expenses Logged Yesterday.";
        } else {
             $total_yesterday_exp = "&#8377; ".number_format($total_yesterday_exp, 2);
        }
        if($total_last_seven_day_exp == "") {
             $total_last_seven_day_exp = "No Expenses Logged This Week.";
        } else {
             $total_last_seven_day_exp = "&#8377; ".number_format($total_last_seven_day_exp, 2);
        }
        if($total_current_month_exp == "") {
             $total_current_month_exp = "No Expenses This Month.";
        } else {
             $total_current_month_exp = "&#8377; ".number_format($total_current_month_exp, 2);
        }
        if($total_last_month_exp == "") {
             $total_last_month_exp = "No Expenses Last Month.";
        } else {
             $total_last_month_exp = "&#8377; ".number_format($total_last_month_exp, 2);
        }

        return view('dashboard/index', compact('total_exp','total_today_exp','total_yesterday_exp','total_last_seven_day_exp','total_current_month_exp','total_last_month_exp'));
    }
    
}
