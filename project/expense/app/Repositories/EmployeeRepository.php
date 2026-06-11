<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

/**
 * Employee Repository
 * handles all database operations related to employees
 */
class EmployeeRepository extends BaseRepository
{
    /**
     * Model Class
     */
    public function getModelClass()
    {
        return Employee::class;
    }

    /**
     * get employee list
     *
     * @param int $perPage
     * @param string $createdBy
     * @return \Illuminate\Support\Collection
     */
    public function get_employee_list($perPage, $createdBy)
    {
        return DB::table('m_emp as e')
            ->leftJoin(
                'mst_work_category as wc',
                'wc.id',
                '=',
                'e.category_id'
            )
            ->select(
                'e.id',
                'e.emp_id',
                'e.emp_name',
                'e.gender',
                'e.mobile_no',
                'e.email',
                'e.address',
                'e.join_date',
                'e.leave_date',
                'e.salary',
                'e.category_id',
                'wc.work_category_name',
                'e.created_by',
                'e.created_at'
            )
            // ->where('e.deleted_flg', 0)
            ->where('e.created_by', $createdBy)
            ->orderBy('e.id', 'DESC')
            ->paginate($perPage);
    }

    /**
     * get employee code genereate
     * 
     * @return string
     */
    public function get_next_employee_code()
    {
        $last = DB::table('m_emp')->orderBy('id', 'DESC')->first();
        if (!$last) {
            return 'EMP00001';
        }
        $number = (int) substr($last->emp_id, 3);
        return 'EMP' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * insert employee data
     * 
     * @param string $emp_name
     * @param int $gender
     * @param string $mobile_no
     * @param string $email
     * @param string $address
     * @param string $join_date
     * @param int $category_id
     * @param float $salary
     * @param string $emp_id
     * @param string $created_by
     * 
     * @return bool
     */
    public function insert_employee($emp_name, $gender, $mobile_no, $email, $address, $join_date, $category_id, $salary, $emp_id, $created_by)
    {
        return DB::table('m_emp')
            ->insert(
                [
                    'emp_name' => $emp_name,
                    'gender' => $gender,
                    'mobile_no' => $mobile_no,
                    'email' => $email,
                    'address' => $address,
                    'join_date' => $join_date,
                    'category_id' => $category_id,
                    'salary' => $salary,
                    'emp_id' => $emp_id,
                    'created_by' => $created_by,
                ]
            );
    }

    /**
     * get active employee list by created user
     *
     * @param string $createdBy
     * @return \Illuminate\Support\Collection
     */
    public function get_active_employee_list_by_user($createdBy)
    {
        $active_emp_list = DB::table('m_emp')
            ->select('emp_name', 'emp_id')
            ->where('deleted_flg', 0)
            ->where('created_by', $createdBy)
            ->get();
        return $active_emp_list;
    }

    /**
     * get employee detail by id
     *
     * @param int $id
     * @param string $createdBy
     * @return object|null
     */
    public function get_employee_by_id($id, $createdBy)
    {
        return DB::table('m_emp as e')
            ->leftJoin('mst_work_category as wc', 'wc.id', '=', 'e.category_id')
            ->select(
                'e.id',
                'e.emp_id',
                'e.emp_name',
                'e.gender',
                'e.mobile_no',
                'e.email',
                'e.address',
                'e.join_date',
                'e.leave_date',
                'e.salary',
                'e.category_id',
                'wc.work_category_name',
                'e.created_by',
                'e.created_at',
                'e.deleted_flg'
            )
            ->where('e.id', $id)
            // ->where('e.deleted_flg', 0)
            ->where('e.created_by', $createdBy)
            ->first();
    }

    /**
     * update employee
     *
     * @param int $id
     * @param string $emp_name
     * @param int $gender
     * @param string $mobile_no
     * @param string $email
     * @param string $address
     * @param string $join_date
     * @param int $category_id
     * @param float $salary
     * @param string $created_by
     *
     * @return bool
     */
    public function update_employee($id, $emp_name, $gender, $mobile_no, $email, $address, $join_date, $category_id, $salary, $created_by)
    {
        return DB::table('m_emp')
            ->where('id', $id)
            ->where('created_by', $created_by)
            ->update(
                [
                    'emp_name' => $emp_name,
                    'gender' => $gender,
                    'mobile_no' => $mobile_no,
                    'email' => $email,
                    'address' => $address,
                    'join_date' => $join_date,
                    'category_id' => $category_id,
                    'salary' => $salary,
                    'updated_at' => now(),
                ]
            );
    }

    /**
     * delete employee
     *
     * @param int $id
     * @param string $created_by
     *
     * @return bool
     */
    public function delete_employee($id, $created_by)
    {
        return DB::table('m_emp')
            ->where('id', $id)
            ->where('created_by', $created_by)
            ->update(
                [
                    'deleted_flg' => 1,
                    'updated_at' => now(),
                ]
            );
    }

    /**
     * revert employee (undo delete)
     *
     * @param int $id
     * @param string $created_by
     *
     * @return bool
     */
    public function revert_employee($id, $created_by)
    {
        return DB::table('m_emp')
            ->where('id', $id)
            ->where('created_by', $created_by)
            ->update([
                'deleted_flg' => 0,
                'updated_at' => now(),
            ]);
    }
}
