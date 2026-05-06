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
     * get all work type with pagination
     * 
     * @param int $perPage
     * @return $work_type_list
     * 
     */
    public function get_all_work_type_list($perPage)
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
            )->paginate($perPage);
        return $work_type_list;
    }

    /**
     * 
     * toggle work type status (Active / Inactive)
     *
     * @param int $id
     * @param string $updatedBy
     * @return bool
     */
    public function toggle_status($id, $updatedBy)
    {
        // get current record
        $row = DB::table('mst_work_type')->where('id', $id)->first();

        if (!$row) {
            return false;
        }
        // switch status 0 ↔ 1
        $newStatus = ($row->deleted_flg == 0) ? 1 : 0;
        // update record based on newStatus
        $result = DB::table('mst_work_type')
            ->where('id', $id)
            ->update([
                'deleted_flg' => $newStatus,
                'updated_by' => $updatedBy
            ]);
        return $result ? true : false;
    }


    /**
     * 
     * get work type based on id
     *
     * @param int $id
     * @return object|null
     */
    public function get_by_id($id)
    {
        return DB::table('mst_work_type')
            ->where('id', $id)
            ->first();
    }


    /**
     * 
     * get work type based on order by id DESC 
     *
     * @return object|null
     */
    public function get_last_row_work_type()
    {
        $last_id = DB::table('mst_work_type')
            ->orderBy('id', 'desc')
            ->first();
        return $last_id;
    }

    /**
     * 
     * register work type
     *
     * @param string $work_type_name
     * @param string $user
     * @return bool
     */
    public function register_data($work_type_name, $user)
    {
        // default start
        $nextNumber = 1;

        // get last row
        $last = $this->get_last_row_work_type();

        if ($last) {
            $lastNumber = $last->id;
            $nextNumber = $lastNumber + 1;
        }
        return DB::table('mst_work_type')->insert([
            'work_type_name' => $work_type_name,
            'category_id' => $nextNumber,
            'created_by' => $user,
            'deleted_flg' => 0
        ]);
    }


    /**
     * 
     * udpate work type based on id
     *
     * @param int $work_type_id
     * @param string $work_type_name
     * @param string $user
     * @return bool
     */
    public function update_data($work_type_id, $work_type_name, $user)
    {
        return DB::table('mst_work_type')
            ->where('id', $work_type_id)
            ->update([
                'work_type_name' => $work_type_name,
                'updated_by' => $user,
            ]);
    }
}
