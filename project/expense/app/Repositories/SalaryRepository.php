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
     * @return \Illuminate\Support\Collection
     */
    public function get_salary_calendar()
    {
        return DB::table('pay_mst_ps_emp as mu')
            ->select('mu.year', 'mu.month')
            ->orderBy('mu.year')
            ->orderBy('mu.month')
            ->get();
    }

    /**
     * get employee list with salary details
     *
     * @param object $request
     * @param int $plimit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get_employee_list($request, $plimit)
    {
        $yrs = $request->selYear ?: date('Y');
        $mons = $request->selMonth ?: date("m", strtotime("-1 month"));
        return DB::table('pay_mst_ps_emp as mu')
            ->select(
                'salary.id as salaryId',
                'salary.basic_salary as basicSalary',
                'salary.insentive',
                'salary.PF',
                'salary.ESI',
                'salary.NET_salary as netSalary',
                'salary.total as totalSalary',
                'emp.emp_id',
                'emp.emp_name'
            )
            ->leftJoin('m_emp as emp', 'mu.emp_id', '=', 'emp.emp_id')
            ->leftJoin('pay_emp_trn_salary as salary', function ($join) use ($yrs, $mons) {
                $join->on('salary.emp_id', '=', 'mu.emp_id')
                    ->where('salary.year', $yrs)
                    ->where('salary.month', $mons);
            })
            ->where('mu.year', $yrs)
            ->where('mu.month', $mons)
            ->orderBy('emp.emp_id')
            ->paginate($plimit);
    }

    /**
     * get employees who do not have salary entry
     *
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function get_user_salary_detail($request)
    {
        $yrs = $request->selYear ?: date('Y');
        $mons = $request->selMonth ?: date("m", strtotime("-1 month"));
        return DB::table('pay_mst_ps_emp as mu')
            ->select(
                'emp.emp_name',
                'emp.emp_id'
            )
            ->leftJoin('m_emp as emp', 'mu.emp_id', '=', 'emp.emp_id')
            ->where('mu.year', $yrs)
            ->where('mu.month', $mons)
            ->whereRaw("mu.emp_id NOT IN (
                SELECT emp_id 
                FROM pay_emp_trn_salary
                WHERE year = ? 
                AND month = ?
            )", [$yrs, $mons])
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
    public static function getAllEmpDetails($request, $created_by)
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

            /**
             * exclude employees already in salary table
             */
            ->whereNotIn('emp.emp_id', function ($query) use ($year, $month) {
                $query->select('emp_id')
                    ->from('pay_mst_ps_emp')
                    ->where('year', $year)
                    ->where('month', $month);
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
    public static function getAllFilteredEmpDetails($request, $created_by)
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
            ->where('emp.deleted_flg', 0)
            ->where('emp.created_by', $created_by)
            ->orderBy('emp.emp_id', 'ASC')
            ->get();
    }

    /**
     * Insert Employee Filter Details for Salary Module
     *
     * This method replaces existing employees for a given month/year
     * and inserts newly selected employees into pay_mst_ps_emp table.
     *
     * @param object $request
     * @param string $create_by
     * 
     * @return bool
     */
    public static function InsertEmpFlrDetails($request, $create_by)
    {
        DB::beginTransaction();

        try {
            // remove existing employee mapping for selected month/year
            DB::table('pay_mst_ps_emp')
                ->where('year', $request->year)
                ->where('month', $request->month)
                ->delete();
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
                        'create_date' => now(),
                        'create_by'   => $create_by,
                        'update_date' => now(),
                        'update_by'   => $create_by,
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
     * Get User Salary Detail
     *
     * Retrieve employee details selected for salary processing
     * and exclude employees whose salary has already been
     * generated for the selected year and month.
     *
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public static function fnGetUserSalaryDetail($request)
    {
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
            ->leftJoin('m_emp as emp', 'emp.emp_id', '=', 'mu.emp_id')
            ->where('mu.year', $yrs)
            ->where('mu.month', $mons)
            ->whereRaw(
                'mu.emp_id NOT IN (
                SELECT emp_id
                FROM pay_emp_trn_salary
                WHERE year = ?
                AND month = ?
            )',
                [$yrs, $mons]
            )
            ->orderBy('mu.emp_id', 'ASC')
            ->get();
    }
}
