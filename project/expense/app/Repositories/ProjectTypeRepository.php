<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\ProjectType;
use Illuminate\Support\Facades\DB;

class ProjectTypeRepository extends BaseRepository
{
    public function getModelClass()
    {
        return ProjectType::class;
    }

    /**
     * 
     * get all project types with pagination
     * 
     * @param int $perPage
     * @return $project_type_list
     * 
     */
    public function get_all_project_type_list($perPage)
    {
        $project_type_list = DB::table('mst_project_type')
            ->select(
                'id',
                'project_type_name',
                'project_type_id',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'deleted_flg'
            )->paginate($perPage);
        return $project_type_list;
    }

    /**
     * 
     * toggle project type status (Active / Inactive)
     *
     * @param int $id
     * @param string $updatedBy
     * @return bool
     */
    public function toggle_status($id, $updatedBy)
    {
        // get current record
        $row = DB::table('mst_project_type')->where('id', $id)->first();

        if (!$row) {
            return false;
        }
        // switch status 0 ↔ 1
        $newStatus = ($row->deleted_flg == 0) ? 1 : 0;
        // update record based on newStatus
        $result = DB::table('mst_project_type')
            ->where('id', $id)
            ->update([
                'deleted_flg' => $newStatus,
                'updated_by' => $updatedBy
            ]);
        return $result ? true : false;
    }


    /**
     * 
     * get project type based on id
     *
     * @param int $id
     * @return object|null
     */
    public function get_by_id($id)
    {
        return DB::table('mst_project_type')
            ->where('id', $id)
            ->first();
    }


    /**
     * 
     * get project type based on order by id DESC 
     *
     * @return object|null
     */
    public function get_last_row_project_type()
    {
        $last_id = DB::table('mst_project_type')
            ->orderBy('id', 'desc')
            ->first();
        return $last_id;
    }

    /**
     * 
     * register project type
     *
     * @param string $project_type_name
     * @param string $user
     * @return bool
     */
    public function register_data($project_type_name, $user)
    {

        // default start
        $nextNumber = 1;
        // get last row
        $last = $this->get_last_row_project_type();
        if ($last) {
            $lastNumber = $last->id;
            $nextNumber = $lastNumber + 1;
        }

        // generate code (P0001)
        $projectTypeCode = 'P' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return DB::table('mst_project_type')->insert([
            'project_type_name' => $project_type_name,
            'project_type_id' => $projectTypeCode,
            'created_by' => $user,
            'deleted_flg' => 0
        ]);
    }


    /**
     * 
     * udpate project type based on id
     *
     * @param int $project_type_id
     * @param string $project_type_name
     * @param string $user
     * @return bool
     */
    public function update_data($project_type_id, $project_type_name, $user)
    {
        return DB::table('mst_project_type')
            ->where('id', $project_type_id)
            ->update([
                'project_type_name' => $project_type_name,
                'updated_by' => $user,
            ]);
    }
}
