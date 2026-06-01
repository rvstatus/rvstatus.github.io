<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserApprovalRepository extends BaseRepository
{
    // NOT USED HERE (you can remove BaseRepository if not needed)
    public function getModelClass()
    {
        return null;
    }

    /**
     * 
     * get user list with pagination
     *
     * @param int $perPage
     * @return object
     * 
     */
    public function getUsers($perPage)
    {
        return DB::table('users')
            ->leftJoin('user_agree', 'users.id', '=', 'user_agree.user_id')
            ->select(
                'users.*',
                'user_agree.agree_status'
            )
            ->where('users.is_admin', 0)
            ->orderBy('users.id', 'desc')
            ->paginate($perPage);
    }

    /**
     * 
     * approve user based on user id
     *
     * @param int $userId
     * @return bool
     * 
     */
    public function approveUser($userId)
    {
        DB::beginTransaction();
        try {
            // update users
            $userUpdated = DB::table('users')->where('id', $userId)->update(['is_approved' => 1]);
            // insert or update user_agree
            $agreeUpdated = DB::table('user_agree')
                ->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'agree_status' => config('constants.user_agree_status.approved'),
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );
            if ($userUpdated === false || !$agreeUpdated) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * 
     * reject user based on user id
     *
     * @param int $userId
     * @return bool
     * 
     */
    public function rejectUser($userId)
    {
        DB::beginTransaction();
        try {

            // update users
            $userUpdated = DB::table('users')->where('id', $userId)->update(['is_approved' => 0]);
            // insert or update user_agree
            $agreeUpdated = DB::table('user_agree')
                ->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'agree_status' => config('constants.user_agree_status.rejected'),
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );
            if ($userUpdated === false || !$agreeUpdated) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            return true;
        } catch (Exception $e) {

            DB::rollBack();
            return false;
        }
    }

    /**
     * 
     * change user status to pending
     *
     * @param int $userId
     * @return bool
     * 
     */
    public function pendingUser($userId)
    {
        DB::beginTransaction();
        try {
            // update users
            $userUpdated = DB::table('users')->where('id', $userId)->update(['is_approved' => 0]);
            // insert or update user_agree
            $agreeUpdated = DB::table('user_agree')
                ->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'agree_status' => 0,
                        'approved_by' => Auth::id(),
                        'approved_at' => null,
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );
            if ($userUpdated === false || !$agreeUpdated) {
                DB::rollBack();
                return false;
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
