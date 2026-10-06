<?php

namespace App\Http\Controllers;

use App\Repositories\UserApprovalRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class UserApprovalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $userApprovalRepo;

    public function __construct(UserApprovalRepository $userApprovalRepo)
    {
        $this->userApprovalRepo = $userApprovalRepo;
    }

    /**
     * Show the User Approval list page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = config('constants.pagination.user_approval');
        $users = $this->userApprovalRepo->getUsers($perPage);

        return view('admin.user_approval_list', compact('users'));
    }

    /**
     * Approve the selected user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request)
    {
        $result = $this->userApprovalRepo->approveUser($request->user_id);;
        $response['message'] = Lang::get('messages.user_approval.approve.fail');
        $response['design'] = 'alert-danger';
        if ($result) {
            $response['message'] = Lang::get('messages.user_approval.approve.success');
            $response['design'] = 'alert-success';
        }
        return back()->with('response', $response);
    }

    /**
     * Reject the selected user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request)
    {
        $result = $this->userApprovalRepo->rejectUser($request->user_id);

        $response['message'] = Lang::get('messages.user_approval.reject.fail');
        $response['design'] = 'alert-danger';

        if ($result) {
            $response['message'] = Lang::get('messages.user_approval.reject.success');
            $response['design'] = 'alert-success';
        }

        return back()->with('response', $response);
    }

    /**
     * Change the selected user status to pending.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pending(Request $request)
    {
        $result = $this->userApprovalRepo->pendingUser($request->user_id);

        $response['message'] = Lang::get('messages.user_approval.pending.fail');
        $response['design'] = 'alert-danger';

        if ($result) {
            $response['message'] = Lang::get('messages.user_approval.pending.success');
            $response['design'] = 'alert-success';
        }

        return back()->with('response', $response);
    }
}
