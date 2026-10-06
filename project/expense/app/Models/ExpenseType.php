<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Expense Type Model
 */
class ExpenseType extends Model
{
    protected $table = 'mst_expense_type';

    protected $fillable = [
        'expense_type_name',
        'created_by',
        'updated_by',
        'deleted_flg'
    ];

    public $timestamps = false;
}
