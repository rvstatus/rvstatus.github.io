<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{

    protected $table = 'pay_emp_trn_salary';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'emp_id',
        'basic_salary',
        'insentive',
        'PF',
        'ESI',
        'total',
        'NET_salary',
        'month',
        'year',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
