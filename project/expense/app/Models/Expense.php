<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 't_expense';

    protected $fillable = [
        'project_type_id',
        'expense_type_id',
        'expense_name_id',
        'expense_amount',
        'expense_description',
        'expense_date',
        'vendor_name',
        'bill_no',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_flg'
    ];

    public $timestamps = false;
}
