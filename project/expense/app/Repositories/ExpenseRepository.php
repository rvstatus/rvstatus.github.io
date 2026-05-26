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

    public function getEmpList()
    {
        $emp_list = DB::table('m_emp')
            ->select('emp_name', 'emp_id')
            ->get();
        return $emp_list;
    }

    public function get_project_list()
    {
        $project_list = DB::table('mst_project_type')
            ->select('project_type_name', 'project_type_id')
            ->get();
        return $project_list;
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
