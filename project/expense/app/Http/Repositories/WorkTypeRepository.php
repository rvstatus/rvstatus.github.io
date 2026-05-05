<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\WorkType;
use Illuminate\Support\Facades\DB;

class WorkTypeRepository extends BaseRepository
{
    public function getModelClass()
    {
        return WorkType::class;
    }

    /**
     * 
     * get all work type lists with pagination
     * 
     * @return $work_type_list
     * 
     */
    public function get_all_work_type_list()
    {
        $work_type_list = DB::table('mst_work_type')
            ->select(
                'id',
                'work_type_name',
                'category_id',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'deleted_flg'
            )->get();
        return $work_type_list;
    }
}
