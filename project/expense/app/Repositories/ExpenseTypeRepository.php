<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\ExpenseType;
use Illuminate\Support\Facades\DB;

/**
 * Expense Type Repository
 * handles all database operations related to expense type
 */
class ExpenseTypeRepository extends BaseRepository
{
    /**
     * Model Class
     */
    public function getModelClass()
    {
        return ExpenseType::class;
    }


    /**
     * get active expense type list
     *
     * @param string $createdBy
     *
     * @return \Illuminate\Support\Collection
     */
    public function get_active_expense_type_list($createdBy)
    {
        return DB::table('mst_expense_type')
            ->select(
                'id',
                'expense_type_name'
            )
            ->where('deleted_flg', 0)
            // ->where('created_by', $createdBy)
            ->orderBy('id', 'ASC')
            ->get();
    }

    /**
     * insert expense type
     *
     * @param array $data
     * @return int
     * 
     */
    public function insert_expense_type($data)
    {
        return DB::table('mst_expense_type')
            ->insertGetId($data);
    }
}
