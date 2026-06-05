<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'm_emp';

    protected $fillable = [
        'emp_name',
        'emp_id',
        'gender',
        'category_id',
        'mobile_no',
        'email',
        'address',
        'join_date',
        'leave_date',
        'salary',
        'created_by',
        'updated_by',
        'deleted_flg'
    ];

    public $timestamps = false;
}
