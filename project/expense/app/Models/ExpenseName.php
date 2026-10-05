<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Expense Master Model
 */
class ExpenseName extends Model
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $table = 'mst_expense_name';

    /**
     * Mass Assignable Columns
     *
     * @var array
     */
    protected $fillable = [
        'expense_type_id',
        'expense_name',
        'created_by',
        'updated_by',
        'deleted_flg'
    ];

    /**
     * Disable Laravel Timestamp
     *
     * @var bool
     */
    public $timestamps = false;
}
