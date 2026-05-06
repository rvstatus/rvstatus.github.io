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
     * get all work category with pagination
     * 
     * @param int $perPage
     * @return $work_category_list
     * 
     */
    public function get_all_work_category_list($perPage)
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
            )->paginate($perPage);
        return $work_category_list;
    }

    /**
     * 
     * toggle work category status (Active / Inactive)
     *
     * @param int $id
     * @param string $updatedBy
     * @return bool
     */
    public function toggle_status($id, $updatedBy)
    {
        // get current record
        $row = DB::table('mst_work_category')->where('id', $id)->first();

        if (!$row) {
            return false;
        }
        // switch status 0 ↔ 1
        $newStatus = ($row->deleted_flg == 0) ? 1 : 0;
        // update record based on newStatus
        $result = DB::table('mst_work_category')
            ->where('id', $id)
            ->update([
                'deleted_flg' => $newStatus,
                'updated_by' => $updatedBy
            ]);
        return $result ? true : false;
    }


    /**
     * 
     * get work category based on id
     *
     * @param int $id
     * @return object|null
     */
    public function get_by_id($id)
    {
        return DB::table('mst_work_category')
            ->where('id', $id)
            ->first();
    }


    /**
     * 
     * get work category based on order by id DESC 
     *
     * @return object|null
     */
    public function get_last_row_work_category()
    {
        $last_id = DB::table('mst_work_category')
            ->orderBy('id', 'desc')
            ->first();
        return $last_id;
    }

    /**
     * 
     * register work category
     *
     * @param string $work_category_name
     * @param string $user
     * @return bool
     */
    public function register_data($work_category_name, $user)
    {

        return DB::table('mst_work_category')->insert([
            'work_category_name' => $work_category_name,
            'created_by' => $user,
            'deleted_flg' => 0
        ]);
    }


    /**
     * 
     * udpate work category based on id
     *
     * @param int $work_category_id
     * @param string $work_category_name
     * @param string $user
     * @return bool
     */
    public function update_data($work_category_id, $work_category_name, $user)
    {
        return DB::table('mst_work_category')
            ->where('id', $work_category_id)
            ->update([
                'work_category_name' => $work_category_name,
                'updated_by' => $user,
            ]);
    }
}
