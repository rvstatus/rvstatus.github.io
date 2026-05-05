@extends('layouts.app')

@section('content')

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;">
            <h4 style="margin:0;">Work Type List</h4>

            <a href="{{ url('/work_type_create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
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
                            <th>Work Type</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Updated By</th>
                            <th>Updated At</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($work_type_list as $key => $row)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <strong>{{ $row->work_type_name }}</strong>
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
                                <a href="{{ url('/work_type_edit/'.$row->id) }}"
                                    class="btn btn-xs btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ url('/work_type_delete/'.$row->id) }}"
                                    class="btn btn-xs btn-danger"
                                    onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No Work Types Found Yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection