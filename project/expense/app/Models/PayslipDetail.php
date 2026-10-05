<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipDetail extends Model
{

    protected $table = 'pay_payslip_details';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'emp_id',
        'salary_id',
        'year',
        'month',
        'file_name',
        'to_mail_address',
        'cc_mail_address',
        'subject',
        'content',
        'mail_send_tatus',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
