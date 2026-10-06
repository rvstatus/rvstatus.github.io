<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

/**
 * Salary Repository
 *
 * handles all database operations related to salary module
 */
class SalaryRepository
{
    /**
     * get salary calendar (year/month list)
     *
     * @param string $created_by
     * @return \Illuminate\Support\Collection
     */
    public function get_salary_calendar($created_by)
    {
        return DB::table('pay_mst_ps_emp as mu')
            ->select('mu.year', 'mu.month')
            ->where('mu.created_by', $created_by)
            ->orderBy('mu.year')
            ->orderBy('mu.month')
            ->get();
    }

    /**
     * get employee list with salary details
     *
     * @param object $request
     * @param int $plimit
     * @param string $created_by
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get_employee_list($request, $plimit, $created_by)
    {
        $yrs = $request->selYear ?: date('Y');
        $mons = $request->selMonth ?: date("m", strtotime("-1 month"));
        $employeeQuery = DB::table('pay_mst_ps_emp as ps')
            ->join('m_emp as emp', 'emp.emp_id', '=', 'ps.emp_id')
            ->select('ps.emp_id')
            ->where('ps.year', $yrs)
            ->where('ps.month', $mons)
            ->where('ps.created_by', $created_by)
            ->groupBy('ps.emp_id');
        $salaryQuery = DB::table('pay_emp_trn_salary as salary')
            ->join('m_emp as emp', 'emp.emp_id', '=', 'salary.emp_id')
            ->where('emp.created_by', '=', $created_by)
            ->select(
                'salary.emp_id',
                DB::raw('SUM(salary.basic_salary) AS basicSalary'),
                DB::raw('SUM(salary.insentive) AS insentive'),
                DB::raw('SUM(salary.PF) AS PF'),
                DB::raw('SUM(salary.ESI) AS ESI'),
                DB::raw('SUM(salary.NET_salary) AS netSalary'),
                DB::raw('SUM(salary.total) AS totalSalary'),
                DB::raw('MIN(salary.id) AS salaryId')
            )
            ->where('salary.year', $yrs)
            ->where('salary.month', $mons)
            ->groupBy('salary.emp_id');
        return DB::query()
            ->fromSub($employeeQuery, 'mu')
            ->leftJoin('m_emp as emp', function ($join) use ($created_by) {
                $join->on('mu.emp_id', '=', 'emp.emp_id')
                    ->where('emp.created_by', '=', $created_by);
            })
            ->leftJoinSub($salaryQuery, 'salary', function ($join) use ($created_by) {
                $join->on('salary.emp_id', '=', 'mu.emp_id')
                    ->where('emp.created_by', '=', $created_by);
            })
            ->where('emp.created_by', $created_by)
            ->select(
                'salary.salaryId',
                'salary.basicSalary',
                'salary.insentive',
                'salary.PF',
                'salary.ESI',
                'salary.netSalary',
                'salary.totalSalary',
                'emp.emp_id',
                'emp.emp_name'
            )
            ->orderBy('emp.emp_id', 'ASC')
            ->paginate($plimit);
    }

    /**
     * get employees who do not have salary entry
     *
     * @param string $created_by
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function get_user_salary_detail($request, $created_by)
    {
        $yrs = $request->selYear ?: date('Y');
        $mons = $request->selMonth ?: date("m", strtotime("-1 month"));
        return DB::table('pay_mst_ps_emp as mu')
            ->leftJoin('m_emp as emp', 'mu.emp_id', '=', 'emp.emp_id')
            ->select(
                'emp.emp_name',
                'emp.emp_id'
            )
            ->where('mu.year', $yrs)
            ->where('mu.month', $mons)
            ->where('mu.created_by', $created_by)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('pay_emp_trn_salary as salary')
                    ->whereColumn('salary.emp_id', 'mu.emp_id')
                    ->whereColumn('salary.year', 'mu.year')
                    ->whereColumn('salary.month', 'mu.month')
                    ->whereColumn('salary.day', 'mu.day');
            })
            ->groupBy(
                'emp.emp_id',
                'emp.emp_name'
            )
            ->orderBy('emp.emp_id', 'ASC')
            ->get();
    }

    /**
     * insert salary data
     *
     * @param object $request
     * @param string $created_by
     * @return bool
     */
    public function insert_salary($request, $created_by)
    {
        $i = $request->iterator;

        return DB::table('pay_emp_trn_salary')->insert([
            'emp_id' => $request->{'empId' . $i},
            'basic_salary' => $request->{'basicSalary' . $i},
            'insentive' => $request->{'insentive' . $i},
            'PF' => $request->{'pfAmount' . $i} ?? 0,
            'ESI' => $request->{'esiAmount' . $i} ?? 0,
            'NET_salary' => $request->{'netSalary' . $i},
            'total' => $request->{'netSalary' . $i},
            'day' => !empty($request->selDay) ? $request->selDay : null,
            'month' => $request->selMonth,
            'year' => $request->selYear,
            'created_by' => $created_by
        ]);
    }

    /**
     * get all emp detail (not yet added salary)
     *
     * @param object $request
     * @param string $created_by
     * @return \Illuminate\Support\Collection
     */
    public static function get_all_emp_details($request, $created_by)
    {
        /**
         * get selected year/month
         */
        if (!empty($request->year) && !empty($request->month)) {
            $year = $request->year;
            $month = $request->month;
        } else {
            $year = date('Y', strtotime('first day of last month'));
            $month = date('m', strtotime('first day of last month'));
        }

        return DB::table('m_emp as emp')
            ->select(
                'emp.emp_id',
                'emp.emp_name'
            )
            ->where('emp.deleted_flg', 0)
            ->where('emp.created_by', $created_by)

            // exclude employees already in salary table
            ->whereNotIn('emp.emp_id', function ($query) use ($year, $month, $request, $created_by) {

                $query->select('emp_id')
                    ->from('pay_mst_ps_emp')
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('created_by', $created_by);
                // based on the day select or not where case added
                if (!empty($request->day)) {
                    $query->where('day', $request->day);
                } else {
                    $query->whereNull('day');
                }
            })

            ->orderBy('emp.emp_id', 'ASC')
            ->get();
    }

    /**
     * get all filtered emp detail (already assigned salary)
     *
     * @param object $request
     * @param string $created_by
     * @return \Illuminate\Support\Collection
     */
    public static function get_all_filtered_emp_details($request, $created_by)
    {
        /**
         * get selected year/month
         */
        if (!empty($request->year) && !empty($request->month)) {
            $year = $request->year;
            $month = $request->month;
        } else {
            $year = date('Y', strtotime('first day of last month'));
            $month = date('m', strtotime('first day of last month'));
        }

        return DB::table('pay_mst_ps_emp as ps')
            ->select(
                'emp.emp_id',
                'emp.emp_name'
            )
            ->join('m_emp as emp', 'emp.emp_id', '=', 'ps.emp_id')
            ->where('ps.year', $year)
            ->where('ps.month', $month)
            // based on the day select or not where case added
            ->when(!empty($request->day), function ($q) use ($request) {
                $q->where('ps.day', $request->day);
            }, function ($q) {
                $q->whereNull('ps.day');
            })
            ->where('emp.deleted_flg', 0)
            ->where('emp.created_by', $created_by)
            ->where('ps.created_by', $created_by)
            ->orderBy('emp.emp_id', 'ASC')
            ->get();
    }

    /**
     * insert employee filter details for salary module
     *
     * this method replaces existing employees for a given month/year
     * and inserts newly selected employees into pay_mst_ps_emp table.
     *
     * @param object $request
     * @param string $created_by
     * 
     * @return bool
     */
    public static function insert_emp_flr_details($request, $created_by)
    {
        DB::beginTransaction();

        try {
            // remove existing employee mapping for selected month/year
            $query = DB::table('pay_mst_ps_emp')->where('year', $request->year)->where('month', $request->month)->where('created_by', $created_by);
            // based on the day select or not where case added
            if (!empty($request->day)) {
                $query->where('day', $request->day);
            } else {
                $query->whereNull('day');
            }
            $query->delete();
            // prepare insert data array
            $rows = [];
            if (!empty($request->selected) && is_array($request->selected)) {
                foreach ($request->selected as $empId) {
                    $rows[] = [
                        'emp_id'      => $empId,
                        'del_flg'      => 0,
                        'resign_id'   => 0,
                        'title'       => 2,
                        'year'        => $request->year,
                        'month'       => $request->month,
                        'day'         => !empty($request->day) ? $request->day : null,
                        'created_at' => now(),
                        'created_by'   => $created_by,
                        'updated_at' => now(),
                        'updated_by'   => $created_by,
                    ];
                }

                // bulk insert
                $inserted = DB::table('pay_mst_ps_emp')->insert($rows);
                if (!$inserted) {
                    DB::rollBack();
                    return false;
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * get user salary detail
     *
     * retrieve employee details selected for salary processing
     * and exclude employees whose salary has already been
     * generated for the selected year and month.
     *
     * @param string $created_by
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public static function fn_get_user_salary_detail($request, $created_by)
    {
        $day = $request->selDay;
        // get selected year and month
        if (!empty($request->selYear) && !empty($request->selMonth)) {
            $yrs  = $request->selYear;
            $mons = $request->selMonth;
        } else {
            $start = new Carbon('first day of last month');
            $yrs   = $start->format('Y');
            $mons  = $start->format('m');
        }

        // fetch employee details not yet registered in salary table
        return DB::table('pay_mst_ps_emp as mu')
            ->select(
                'emp.emp_name',
                'emp.emp_id',
                'mu.year',
                'mu.month'
            )
            ->distinct()
            ->leftJoin('m_emp as emp', function ($join) use ($created_by) {
                $join->on('emp.emp_id', '=', 'mu.emp_id')
                    ->where('emp.created_by', '=', $created_by);
            })
            ->where('mu.created_by', $created_by)
            ->where('mu.year', $yrs)
            ->where('mu.month', $mons)
            ->where('mu.day', $day)
            ->whereNotExists(function ($query) use ($yrs, $mons, $day, $created_by) {
                $query->select(DB::raw(1))
                    ->from('pay_emp_trn_salary as salary')
                    ->whereColumn('salary.emp_id', 'mu.emp_id')
                    ->where('salary.year', $yrs)
                    ->where('salary.month', $mons)
                    ->where('salary.day', $day)
                    ->where('salary.created_by', $created_by);
            })
            ->orderBy('mu.emp_id', 'ASC')
            ->get();
    }

    /**
     * get salary year list
     *
     * retrieve available salary years for an employee.
     *
     * @param string $created_by
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function get_salary_year_detail($request, $created_by)
    {
        return DB::table('pay_emp_trn_salary as salary')
            ->where('salary.emp_id', $request->empId)
            ->where('salary.created_by', $created_by)
            ->orderBy('salary.year', 'DESC')
            ->orderBy('salary.month', 'DESC')
            ->groupBy('salary.year')
            ->pluck('salary.year', 'salary.year');
    }

    /**
     * get total Salary Summary
     *
     * retrieve total salary, PF, ESI, incentive,
     * and net salary amounts for an employee.
     *
     * @param string $created_by
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function get_total_salary_array_detail($request, $created_by)
    {
        $query = DB::table('pay_emp_trn_salary as salary')
            ->select(
                'salary.year',
                DB::raw('SUM(salary.basic_salary) as basicSalary'),
                DB::raw('SUM(salary.insentive) as insentive'),
                DB::raw('SUM(salary.PF) as pfAmount'),
                DB::raw('SUM(salary.ESI) as esiAmount'),
                DB::raw('SUM(salary.total) as totalSalary'),
                DB::raw('SUM(salary.NET_salary) as netSalary')
            )
            ->where('salary.emp_id', $request->empId)
            ->where('salary.created_by', $created_by)
            // based on each month of the year
            ->where('salary.year', $request->selYear)
            ->where('salary.month', $request->selMonth)
            ->groupBy('salary.year')
            ->orderBy('salary.year', 'DESC')
            ->get();
        return $query;
    }

    /**
     * get salary detail view
     *
     * retrieve paginated salary details
     * for a specific employee.
     *
     * @param string $created_by
     * @param object $request
     * @param int $plimit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get_salary_detail_view($request, $plimit, $created_by)
    {
        $query = DB::table('pay_emp_trn_salary as salary')
            ->leftJoin('m_emp as emp', function ($join) use ($created_by) {
                $join->on('emp.emp_id', '=', 'salary.emp_id')
                    ->where('emp.created_by', '=', $created_by);
            })
            ->select(
                DB::raw('MIN(salary.id) as salaryId'),
                'salary.year',
                'salary.month',
                'salary.day',
                DB::raw('SUM(salary.basic_salary) as basicSalary'),
                DB::raw('SUM(salary.insentive) as insentive'),
                DB::raw('SUM(salary.PF) as pfAmount'),
                DB::raw('SUM(salary.ESI) as esiAmount'),
                DB::raw('SUM(salary.total) as totalSalary'),
                DB::raw('SUM(salary.NET_salary) as netSalary'),
                'emp.emp_id',
                'emp.emp_name'
            )
            ->where('salary.emp_id', $request->empId)
            // based on each month of the year
            ->where('salary.month', $request->selMonth)
            ->where('salary.created_by', $created_by);
        if (!empty($request->yearViseData)) {
            $query->where('salary.year', $request->yearViseData);
        }

        return $query
            ->groupBy(
                'salary.year',
                'salary.month',
                'salary.day',
                'emp.emp_id',
                'emp.emp_name'
            )
            ->orderByDesc('salary.year')
            ->orderByDesc('salary.month')
            ->orderBy('salary.day')
            ->paginate($plimit);
    }

    /**
     * get salary edit detail
     *
     * retrieve salary record for edit screen.
     *
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public static function fn_get_user_salary_edit_detail($request)
    {
        return DB::table('pay_emp_trn_salary as salary')
            ->select(
                'emp.emp_name',
                'emp.emp_id',
                'salary.id as salaryId',
                'salary.basic_salary as basicSalary',
                'salary.insentive as insentive',
                'salary.PF as pfAmount',
                'salary.ESI as esiAmount',
                'salary.total as totalSalary',
                'salary.day',
                'salary.month',
                'salary.year'
            )
            ->leftJoin('m_emp as emp', 'emp.emp_id', '=', 'salary.emp_id')
            ->where('salary.id', $request->salaryId)
            ->get();
    }

    /**
     * get salary view detail
     *
     * retrieve salary record for view screen.
     *
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function get_salary_view_detail($request)
    {
        return DB::table('pay_emp_trn_salary as salary')
            ->select(
                'emp.emp_name',
                'emp.emp_id',
                DB::raw('MIN(salary.id) as salaryId'),
                DB::raw('SUM(salary.basic_salary) as basicSalary'),
                DB::raw('SUM(salary.insentive) as insentive'),
                DB::raw('SUM(salary.PF) as pfAmount'),
                DB::raw('SUM(salary.ESI) as esiAmount'),
                DB::raw('SUM(salary.total) as totalSalary'),
                DB::raw('SUM(salary.NET_salary) as netSalary'),
                'salary.day',
                'salary.month',
                'salary.year'
            )
            ->leftJoin(
                'm_emp as emp',
                'emp.emp_id',
                '=',
                'salary.emp_id'
            )
            ->where('salary.emp_id', $request->empId)
            ->where('salary.year', $request->selYear)
            ->where('salary.month', $request->selMonth)
            ->groupBy(
                'emp.emp_name',
                'emp.emp_id',
                'salary.day',
                'salary.month',
                'salary.year'
            )
            ->get();
    }

    /**
     * update salary details
     *
     * update salary record in salary transaction table.
     *
     * @param object $request
     * @param string $updated_by
     * @return bool
     */
    public function salary_update($request, $updated_by)
    {
        return DB::table('pay_emp_trn_salary')
            ->where('id', $request->salaryId)
            ->update([
                'basic_salary' => $request->basicSalary,
                'insentive'    => $request->insentive,
                'PF'           => $request->pfAmount,
                'ESI'          => $request->esiAmount,
                'NET_salary'   => $request->totalSalary,
                'total'        => $request->totalSalary,
                'updated_at'  => now(),
                'updated_by'    => $updated_by,
            ]);
    }

    /**
     * get already registered salary days
     *
     * @param string $created_by
     * @param int $year
     * @param int $month
     * @return array
     */
    public function get_registered_days($year, $month, $created_by)
    {
        return DB::table('pay_mst_ps_emp as ps')
            ->where('ps.year', $year)
            ->where('ps.month', $month)
            ->where('ps.created_by', $created_by)
            ->whereNotNull('ps.day')
            ->whereNotExists(function ($query) use ($created_by) {

                $query->select(DB::raw(1))
                    ->from('pay_mst_ps_emp as ps2')
                    ->whereColumn('ps2.day', 'ps.day')
                    ->whereColumn('ps2.year', 'ps.year')
                    ->whereColumn('ps2.month', 'ps.month')
                    ->where('ps2.created_by', $created_by)
                    ->whereNotExists(function ($salary) use ($created_by) {

                        $salary->select(DB::raw(1))
                            ->from('pay_emp_trn_salary as salary')
                            ->whereColumn('salary.emp_id', 'ps2.emp_id')
                            ->whereColumn('salary.day', 'ps2.day')
                            ->whereColumn('salary.year', 'ps2.year')
                            ->whereColumn('salary.month', 'ps2.month')
                            ->where('salary.created_by', $created_by);
                    });
            })
            ->groupBy('ps.day')
            ->orderBy('ps.day')
            ->pluck('ps.day')
            ->toArray();
    }
}
