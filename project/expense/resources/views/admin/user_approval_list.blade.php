@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<script src="{{ asset('resources/assets/js/user/approval/list.js') }}"></script>
<div class="container">
    <div class="panel panel-default">
        <!-- HEADER -->
        <div class="panel-heading"
            style="display:flex;justify-content:space-between;align-items:center;background:#34495e;color:#fff;">
            <h4 style="margin:0;">
                User Approval List
            </h4>
            @if(session()->has('response'))
            <div id="response_message" class="alert {{ session()->get('response')['design'] }} custom-alert">
                {{ session()->get('response')['message'] }}
            </div>
            @endif
        </div>
        <!-- BODY -->
        <!-- COMMON USER ACTION FORM -->
        <form id="userActionForm" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="user_id" id="user_id">
        </form>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead style="background:#f5f5f5;">
                        <tr>
                            <th width="5%">S.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th width="10%">Status</th>
                            <th width="18%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                        <tr>
                            <td class="text-center">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $key + 1 }}
                            </td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                            <td class="text-center">
                                @if($user->agree_status == 0)
                                <span class="label label-warning">Pending</span>
                                @elseif($user->agree_status == 1)
                                <span class="label label-success">Approved</span>
                                @else
                                <span class="label label-danger">Rejected</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- PENDING --}}
                                @if($user->agree_status == 0 || $user->agree_status == null)
                                <!-- MAKE APPROVE PROCESS -->
                                <button type="button"
                                    class="btn btn-xs btn-success user-action-btn"
                                    data-action="{{ url('/user_approve') }}"
                                    data-user-id="{{ $user->id }}">
                                    <i class="fas fa-check"></i>
                                </button>
                                <!-- MAKE REJECT PROCESS -->
                                <button type="button"
                                    class="btn btn-xs btn-danger user-action-btn"
                                    data-action="{{ url('/user_reject') }}"
                                    data-user-id="{{ $user->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                                {{-- APPROVED --}}
                                @elseif($user->agree_status == 1)
                                <!-- MAKE PENDING PROCESS -->
                                <button type="button"
                                    class="btn btn-xs btn-warning user-action-btn"
                                    data-action="{{ url('/user_pending') }}"
                                    data-user-id="{{ $user->id }}">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <!-- MAKE REJECT PROCESS -->
                                <button type="button"
                                    class="btn btn-xs btn-danger user-action-btn"
                                    data-action="{{ url('/user_reject') }}"
                                    data-user-id="{{ $user->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                                {{-- REJECTED --}}
                                @elseif($user->agree_status == 2)
                                <!-- MAKE PENDING PROCESS -->
                                <button type="button"
                                    class="btn btn-xs btn-warning user-action-btn"
                                    data-action="{{ url('/user_pending') }}"
                                    data-user-id="{{ $user->id }}">
                                    <i class="fas fa-undo-alt"></i>
                                </button>
                                <!-- MAKE APPROVE PROCESS -->
                                <button type="button"
                                    class="btn btn-xs btn-success user-action-btn"
                                    data-action="{{ url('/user_approve') }}"
                                    data-user-id="{{ $user->id }}">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No Users Found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- PAGINATION -->
            <div class="text-right">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection