<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Carbon\Carbon;

class ExpenseDashBoardRepository
{
    /**
     * 
     * get dashboard expense summary
     *
     * @param int $project_type
     * @param int $work_type
     * @param string $user_id
     * @return array
     */
    public function get_dashboard_expense_summary($project_type, $work_type, $user_id)
    {
        // total expense
        $total_exp = $this->applyExpenseFilters(
            DB::table('t_expense'),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');

        // total today expense
        $total_today_exp = $this->applyExpenseFilters(
            DB::table('t_expense')
                ->where('working_date', '=', Carbon::today()->toDateString()),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');

        // total yesterday expense
        $total_yesterday_exp = $this->applyExpenseFilters(
            DB::table('t_expense')
                ->whereRaw('DATE(working_date) = ?', [Carbon::yesterday()->toDateString()]),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');

        // total last seven days expense
        $total_last_seven_day_exp = $this->applyExpenseFilters(
            DB::table('t_expense')
                ->whereRaw('DATE(working_date) >= ?', [Carbon::today()->subDays(7)->toDateString()])
                ->whereRaw('DATE(working_date) <= ?', [Carbon::today()->toDateString()]),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');

        // total current month expense
        $total_current_month_exp = $this->applyExpenseFilters(
            DB::table('t_expense')
                ->whereBetween(
                    'working_date',
                    [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->addMonth()->startOfMonth()
                    ]
                ),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');

        // total last month expense
        $total_last_month_exp = $this->applyExpenseFilters(
            DB::table('t_expense')
                ->whereRaw(
                    "DATE_FORMAT(working_date, '%Y-%m') = ?",
                    [Carbon::now()->subMonth()->format('Y-m')]
                ),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');
        return [
            'total_exp' => $total_exp,
            'total_today_exp' => $total_today_exp,
            'total_yesterday_exp' => $total_yesterday_exp,
            'total_last_seven_day_exp' => $total_last_seven_day_exp,
            'total_current_month_exp' => $total_current_month_exp,
            'total_last_month_exp' => $total_last_month_exp
        ];
    }

    /**
     * 
     * helper to apply optional filters
     * 
     * @param Builder $query
     * @param int $project_type
     * @param int $work_type
     * @param string $user_id
     * @return Builder
     */
    public function applyExpenseFilters($query, $project_type, $work_type, $user_id)
    {
        $query->where('created_by', $user_id);

        if (!empty($project_type)) {
            $query->where('project_type_id', $project_type);
        }

        if (!empty($work_type)) {
            $query->where('working_type', $work_type);
        }

        return $query;
    }
}
