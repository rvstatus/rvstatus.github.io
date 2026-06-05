<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\ExpenseModel;
use Illuminate\Support\Facades\DB;

class ExpenseRepository extends BaseRepository
{
    public function getModelClass()
    {
        return ExpenseModel::class;
    }
    /**
     * Get expense list by user.
     *
     * @param string $createdBy
     * @return \Illuminate\Support\Collection
     */
    public function get_expense_list_by_user($createdBy)
    {

        $exp_list = DB::table('t_expense AS exp')
            ->select(
                'mst_project_type.project_type_name AS project_type_name',
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
            ->leftJoin('mst_project_type', 'mst_project_type.project_type_id', '=', 'exp.project_type_id')
            ->leftJoin('mst_work_category', 'mst_work_category.id', '=', 'exp.working_category')
            ->leftJoin('mst_work_type', 'mst_work_type.id', '=', 'exp.working_type')
            ->where('exp.created_by', '=', $createdBy)
            ->orderBy('exp.working_date', 'DESC')
            ->get();
        return $exp_list;
    }

    /**
     * insert a new expense record into the table t_expense in the database.
     *
     * @param array $expense_data
     * @return bool
     */
    public function insert_expense(array $expense_data)
    {
        return DB::table('t_expense')->insert($expense_data);
    }
}
