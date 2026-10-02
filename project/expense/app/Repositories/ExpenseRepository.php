<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

/**
 * Expense Repository
 * handles all database operations related to expense
 */

class ExpenseRepository extends BaseRepository
{
    public function getModelClass()
    {
        return Expense::class;
    }
    /**
     * Get expense list by user.
     *
     * @param int $perPage
     * @param string $createdBy
     * @return \Illuminate\Support\Collection
     */
    public function get_expense_list_by_user($perPage, $createdBy)
    {

        return DB::table('t_expense as e')
            ->leftJoin('mst_project_type as pt', 'pt.project_type_id', '=', 'e.project_type_id')
            ->leftJoin('mst_expense_type as et', 'et.id', '=', 'e.expense_type_id')
            ->leftJoin('mst_expense_name  as em', 'em.id', '=', 'e.expense_name_id')
            ->select(
                'e.id',

                'e.project_type_id',
                'pt.project_type_name',

                'e.expense_type_id',
                'et.expense_type_name',

                'e.expense_name_id',
                'em.expense_name',

                'e.expense_amount',
                'e.expense_description',
                'e.expense_date',
                'e.vendor_name',
                'e.bill_no',
                'e.remarks',
                'e.created_by',
                'e.created_at',
                'e.deleted_flg'
            )
            ->where('e.deleted_flg', 0)
            ->where('e.created_by', $createdBy)
            ->orderBy('e.expense_date', 'ASC')
            ->orderBy('e.project_type_id', 'ASC')
            ->paginate($perPage);
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

    /**
     * get expense detail by id
     *
     * @param int $id
     * @param string $createdBy
     *
     * @return object|null
     */
    public function get_expense_by_id($id, $createdBy)
    {
        return DB::table('t_expense as e')
            ->leftJoin(
                'mst_project_type as pt',
                'pt.project_type_id',
                '=',
                'e.project_type_id'
            )
            ->leftJoin(
                'mst_expense_type as et',
                'et.id',
                '=',
                'e.expense_type_id'
            )
            ->leftJoin('mst_expense_name  as em', 'em.id', '=', 'e.expense_name_id')
            ->select(
                'e.id',
                'e.project_type_id',
                'pt.project_type_name',
                'e.expense_type_id',
                'et.expense_type_name',
                'e.expense_name_id',
                'em.expense_name',
                'e.expense_amount',
                'e.expense_description',
                'e.expense_date',
                'e.vendor_name',
                'e.bill_no',
                'e.remarks',
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
     * update expense
     *
     * @param int $id
     * @param array $expenseData
     * @param string $createdBy
     *
     * @return bool
     */
    public function update_expense($id, $expenseData, $createdBy)
    {
        return DB::table('t_expense')
            ->where('id', $id)
            ->where('created_by', $createdBy)
            ->update(
                $expenseData
            );
    }


    /**
     * delete expense
     *
     * @param int $id
     * @param string $createdBy
     *
     * @return bool
     */
    public function delete_expense($id, $createdBy)
    {
        return DB::table('t_expense')
            ->where('id', $id)
            ->where('created_by', $createdBy)
            ->update(
                [
                    'deleted_flg' => 1,
                    'updated_at' => now(),
                ]
            );
    }
}
