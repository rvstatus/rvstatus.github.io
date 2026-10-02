@extends('layouts.app')
@section('content')

<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/expense/list.css') }}" rel="stylesheet">

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading exp-panel-heading d-flex justify-content-between align-items-center">
            <h4 style="margin:0;"> {{ trans('labels.expense_list') }} </h4>

            <div>
                @if(session()->has('response'))
                <div id="response_message" class="alert {{ session('response.design') }}" style="margin:0; padding:2px 5px;">
                    {{ session('response.message') }}
                </div>
                @endif
            </div>

            <div>
                <a href="{{ url('/expense_register') }}" class="btn btn-success btn-sm"> <i class="fas fa-plus"></i> {{ trans('labels.register') }} </a>
            </div>
        </div>

        <form id="expense_detail_form" action="{{ url('/expense_detail') }}" method="POST" style="display:none;">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id">
        </form>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <colgroup>
                        <col width="5%">
                        <col width="11%">
                        <col width="10%">
                        <col width="16%">
                        <col width="16%">
                        <col width="14%">
                        <col width="9%">
                        <col>
                    </colgroup>
                    <thead style="background:#f4f6f8;">
                        <tr>
                            <th class="text-center"> {{ trans('labels.sno') }} </th>
                            <th class="text-center"> {{ trans('labels.project') }} </th>
                            <th class="text-center"> {{ trans('labels.date') }} </th>
                            <th class="text-center"> {{ trans('labels.expense_type') }} </th>
                            <th class="text-center"> {{ trans('labels.expense_name') }} </th>
                            <th class="text-center"> {{ trans('labels.vendor_name') }} </th>
                            <th class="text-center"> {{ trans('labels.amount') }} </th>
                            <th class="text-center"> {{ trans('labels.description') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expense_list as $key => $expense)
                        <tr>
                            <td class="text-center">
                                {{ ($expense_list->currentPage() - 1) * $expense_list->perPage() + $key + 1 }}
                            </td>
                            <td>
                                <a href="javascript:void(0)" onclick="projectDetailView('{{ $expense->project_type_id }}')" class="expense-detail-link">
                                    {{ $expense->project_type_name ?? '-' }}
                                </a>
                            </td>
                            <td class="text-center">
                                {{ !empty($expense->expense_date) ? date('d-m-Y', strtotime($expense->expense_date)) : '-' }}
                            </td>
                            <td>
                                <a href="javascript:void(0)" onclick="expenseTypeDetailView('{{ $expense->expense_type_id }}')" class="expense-detail-link">
                                    {{ $expense->expense_type_name ?? '-' }}
                                </a>
                            </td>
                            <td>
                                <a href="javascript:void(0)" onclick="expenseNameDetailView('{{ $expense->expense_name_id }}')" class="expense-detail-link">
                                    {{ $expense->expense_name ?? '-' }}
                                </a>
                            </td>
                            <td>
                                {{ $expense->vendor_name ?? 'Nil' }}
                            </td>
                            <td class="text-right">
                                {!! $expense->expense_amount ? '&#8377; ' . number_format($expense->expense_amount,0,'.',',') : '-' !!}
                            </td>
                            <td>
                                {!! !empty($expense->expense_description) ? nl2br(e($expense->expense_description)) : trans('labels.not_provider') !!}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center no-data-color">
                                {{ trans('labels.no_data_found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="text-right">
                    {{ $expense_list->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('resources/assets/js/expense/list.js') }}"></script>
@endsection