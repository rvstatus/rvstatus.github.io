<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\WorkCategory;
use Illuminate\Support\Facades\DB;

class WorkCategoryRepository extends BaseRepository
{
    public function getModelClass()
    {
        return WorkCategory::class;
    }

    /**
     * 
     * get all work category lists with pagination
     * 
     * @return $work_category_list
     * 
     */
    public function get_all_work_category_list()
    {
        $work_category_list = DB::table('mst_work_category')
            ->select(
                'id',
                'work_category_name',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'deleted_flg'
            )->get();
        return $work_category_list;
    }
}
