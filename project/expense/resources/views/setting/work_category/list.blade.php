@extends('layouts.app')

@section('content')
<link href="{{ asset('resources/assets/css/setting/workCategory.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">

<div class="container">
    <div class="panel panel-default">

        <div class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;">
            <h4 style="margin:0;">Work Category List</h4>

            @if(session()->has('response'))
            <div id="response_message" class="alert {{ session()->get('response')['design'] }} custom-alert">
                {{ session()->get('response')['message'] }}
            </div>
            @endif

            <a href="javascript:void(0)" onclick="openCreateModal()" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>

        <div class="panel-body">
            <div class="table-responsive">

                <form method="POST" action="{{ url('/work_category_toggle') }}" id="toggleForm">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="work_category_id">

                    <table class="table table-bordered table-hover table-striped">
                        <thead style="background:#f5f5f5;">
                            <colgroup>
                                <col width="3%" />
                                <col />
                                <col width="12%" />
                                <col width="11%" />
                                <col width="12%" />
                                <col width="11%" />
                                <col width="9%" />
                                <col width="5%" />
                            </colgroup>
                            <tr>
                                <th>S.No</th>
                                <th>Work Category</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($work_category_list as $key => $row)
                            <tr>
                                <td class="text-center">
                                    {{ ($work_category_list->currentPage() - 1) * $work_category_list->perPage() + $key + 1 }}
                                </td>

                                <td>
                                    <strong>{{ $row->work_category_name }}</strong>
                                </td>

                                <td>{{ $row->created_by }}</td>

                                <td class="text-center">
                                    {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : '-' }}
                                </td>

                                <td>{{ !empty($row->updated_by) ? $row->updated_by : '-' }}</td>

                                <td class="text-center">
                                    {{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('d-m-Y') : '-' }}
                                </td>

                                <td class="text-center">
                                    @if($row->deleted_flg == 0)
                                    <span class="label label-success">Active</span>
                                    @else
                                    <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>

                                <td class="text-center">

                                    <button type="button"
                                        class="btn btn-xs btn-info"
                                        onclick="openEditModal('{{ $row->id }}','{{ $row->work_category_name }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button"
                                        class="btn btn-xs {{ $row->deleted_flg == 0 ? 'btn-danger' : 'btn-success' }}"
                                        onclick="submitToggle('{{ $row->id }}')">

                                        @if($row->deleted_flg == 0)
                                        <i class="fas fa-trash"></i>
                                        @else
                                        <i class="fas fa-check"></i>
                                        @endif

                                    </button>

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    No Work Categories Found Yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </form>

            </div>

            <div class="text-right">
                {{ $work_category_list->links() }}
            </div>

        </div>
    </div>
</div>

<!-- MODAL -->
<div id="workCategoryModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="workCategoryForm" method="POST">
                {{ csrf_field() }}

                <div class="modal-header">
                    <h4 id="modalTitle">Add Work Category</h4>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="work_category_hidden_id">

                    <div class="form-group d-flex align-items-center">
                        <label class="control-label col-md-3">
                            Work Category Name <span style="color:red;">*</span>
                        </label>

                        <div class="col-md-5 w300">
                            <input type="text"
                                id="work_category_name"
                                name="work_category_name"
                                class="form-control w150">
                        </div>

                        <div class="col-md-5">
                            <small id="error_name" class="text-danger"></small>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="closeWorkCategoryModal()">Cancel</button>

                    <button type="button" id="submitBtn" class="btn btn-primary" onclick="submitWorkCategoryForm()">
                        <i class="fas fa-save"></i> <span id="submitBtnText">Register</span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="{{ asset('resources/assets/js/setting/workCategory.js') }}"></script>

@endsection