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

        // total salary expense
        $total_salary = $this->getSalaryQuery($user_id)->sum('NET_salary');

        $total_exp = $total_exp + $total_salary;

        // total today expense
        $total_today_exp = $this->applyExpenseFilters(
            DB::table('t_expense')
                ->where('working_date', '=', Carbon::today()->toDateString()),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');

        // total today salary expense
        $total_today_salary = $this->getSalaryQuery($user_id)
            ->where('day', Carbon::today()->day)
            ->where('month', Carbon::today()->month)
            ->where('year', Carbon::today()->year)
            ->sum('NET_salary');

        $total_today_exp = $total_today_exp + $total_today_salary;

        // total yesterday expense
        $yesterday = Carbon::yesterday();
        $total_yesterday_exp = $this->applyExpenseFilters(
            DB::table('t_expense')
                ->whereRaw('DATE(working_date) = ?', [$yesterday->toDateString()]),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');

        // total yesterday salary expense
        $total_yesterday_salary = $this->getSalaryQuery($user_id)
            ->where('day', $yesterday->day)
            ->where('month', $yesterday->month)
            ->where('year', $yesterday->year)
            ->sum('NET_salary');

        $total_yesterday_exp = $total_yesterday_exp + $total_yesterday_salary;

        // total last seven days expense
        $total_last_seven_day_exp = $this->applyExpenseFilters(
            DB::table('t_expense')
                ->whereRaw('DATE(working_date) >= ?', [Carbon::today()->subDays(7)->toDateString()])
                ->whereRaw('DATE(working_date) <= ?', [Carbon::today()->toDateString()]),
            $project_type,
            $work_type,
            $user_id
        )->sum('salary');

        // total last seven days salary expense
        $total_last_seven_day_salary = $this->getSalaryQuery($user_id)
            ->whereRaw(
                "STR_TO_DATE(CONCAT(year,'-',month,'-',day), '%Y-%c-%e') BETWEEN ? AND ?",
                [
                    Carbon::today()->subDays(7)->toDateString(),
                    Carbon::today()->toDateString()
                ]
            )
            ->sum('NET_salary');

        $total_last_seven_day_exp = $total_last_seven_day_exp + $total_last_seven_day_salary;

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

        // total current month salary expense
        $total_current_month_salary = $this->getSalaryQuery($user_id)
            ->where('month', Carbon::now()->month)
            ->where('year', Carbon::now()->year)
            ->sum('NET_salary');

        $total_current_month_exp = $total_current_month_exp + $total_current_month_salary;

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

        // total last month salary expense
        $last_month = Carbon::now()->subMonth();
        $total_last_month_salary = $this->getSalaryQuery($user_id)
            ->where('month', $last_month->month)
            ->where('year', $last_month->year)
            ->sum('NET_salary');

        $total_last_month_exp = $total_last_month_exp + $total_last_month_salary;

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

    /**
     * 
     * get salary query
     *
     * @param string $user_id
     * @return Builder
     */
    private function getSalaryQuery($user_id)
    {
        return DB::table('pay_emp_trn_salary')->where('created_by', $user_id);
    }
}
