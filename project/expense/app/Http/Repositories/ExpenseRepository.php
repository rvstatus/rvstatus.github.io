<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
// use App\Models\ExpenseModel AS Model;
use Illuminate\Support\Facades\DB;

class ExpenseRepository extends BaseRepository
{
    public function getEmpList()
    {
        $emp_list = DB::table('m_emp')
                    ->select('emp_name','emp_id')
                    ->get();
        return $emp_list;
    }
}
