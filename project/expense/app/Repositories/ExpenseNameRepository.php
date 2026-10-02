<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\ExpenseName;
use Illuminate\Support\Facades\DB;

/**
 * Expense Master Repository
 *
 * Handles all database operations related to expense master.
 */
class ExpenseNameRepository extends BaseRepository
{
    /**
     * Model Class
     *
     * @return string
     */
    public function getModelClass()
    {
        return ExpenseName::class;
    }

    /**
     * Get Active Expense Name List By Expense Type
     *
     * @param int $expenseTypeId
     *
     * @return \Illuminate\Support\Collection
     */
    public function get_active_expense_list($expenseTypeId)
    {
        return DB::table('mst_expense_name')
            ->select('id', 'expense_name')
            ->where('expense_type_id', $expenseTypeId)
            ->where('deleted_flg', 0)
            ->orderBy('expense_name', 'ASC')
            ->get();
    }

    /**
     * Insert Expense
     *
     * @param array $data
     *
     * @return int
     */
    public function insert_expense($data)
    {
        return DB::table('mst_expense_name')->insertGetId($data);
    }

    /**
     * Get Expense By Id
     *
     * @param int $id
     *
     * @return object|null
     */
    public function get_expense_by_id($id)
    {
        return DB::table('mst_expense_name')
            ->where('id', $id)
            ->where('deleted_flg', 0)
            ->first();
    }
}
