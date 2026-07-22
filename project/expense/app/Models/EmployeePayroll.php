<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePayroll extends Model
{
    protected $table = 'pay_mst_ps_emp';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'emp_id',
        'del_flg',
        'resign_id',
        'title',
        'year',
        'month',
        'day',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
