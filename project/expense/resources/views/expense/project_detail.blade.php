@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/scroll.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/expense/project_expense_detail.css') }}" rel="stylesheet">

<form name="projectExpenseDetailForm" id="projectExpenseDetailForm" method="POST">
@csrf
<div class="container">
    <div class="panel panel-default project-expense-panel">
        <!-- HEADER -->
        <div class="panel-heading exp-detail-panel-heading">
            <h4 style="margin:0;">
                {{ trans('labels.project_expense_details') }}
            </h4>
        </div>
        <!-- BODY -->
        <div class="panel-body">
            <div class="mb10 header-actions">
                <a href="{{ url('/expense_list') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-arrow-left"></i> {{ trans('labels.back') }}
                </a>
            </div>
            @if(!empty($expense_list) && isset($expense_list[0]))
                <div class="exp-info-box">
                    <div class="exp-info-item">
                        <label>{{ trans('labels.project_type') }} :</label>
                        <span>
                            {{ $expense_list[0]->project_type_name ?? '-' }}
                        </span>
                    </div>
                    <div class="exp-info-item">
                        <label>{{ trans('labels.project_code') }} :</label>
                        <span class="exp-project-code">
                            {{ $expense_list[0]->project_type_id ?? '-' }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="clearfix"></div>
            <div class="table-responsive common-scroll-container common-scroll-container-height expense-detail-table-wrapper">
                <table class="table table-bordered table-hover expense-detail-table">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="15%">
                        <col width="15%">
                        <col width="14%">
                        <col width="11%">
                        <col width="10%">
                        <col>
                    </colgroup>
                    <thead class="common-sticky-header">
                        <tr>
                            <th>{{ trans('labels.sno') }}</th>
                            <th>{{ trans('labels.date') }}</th>
                            <th>{{ trans('labels.expense_type') }}</th>
                            <th>{{ trans('labels.expense_name') }}</th>
                            <th>{{ trans('labels.vendor_name') }}</th>
                            <th>{{ trans('labels.bill_no') }}</th>
                            <th>{{ trans('labels.amount') }}</th>
                            <th>{{ trans('labels.description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expense_list as $key => $expense)
                        <tr>

                            <td class="text-center cmn_vam"> {{ $key + 1 }} </td>
                            <td class="text-center cmn_vam">
                                {{ !empty($expense->expense_date) ? \Carbon\Carbon::parse($expense->expense_date)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="cmn_vam">
                                {{ $expense->expense_type_name ?? '-' }}
                            </td>
                            <td class="cmn_vam">
                                {{ $expense->expense_name ?? '-' }}
                            </td>
                            <td class="cmn_vam">
                                {{ $expense->vendor_name ?? trans('labels.not_provider') }}
                            </td>
                            <td class="cmn_vam">
                                {{ $expense->bill_no ?? trans('labels.not_provider') }}
                            </td>
                            <td class="text-right cmn_vam expense-amount">
                                {!! $expense->expense_amount
                                    ? '&#8377; ' . number_format($expense->expense_amount, 0, '.', ',')
                                    : '-' !!}
                            </td>
                            <td class="cmn_vam">
                                {!! !empty($expense->expense_description)
                                    ? nl2br(e($expense->expense_description))
                                    : trans('labels.not_provider') !!}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center no-data-color" colspan="8"> 
                                {{ trans('labels.no_data_found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <!-- @if(!empty($expense_list) && count($expense_list) > 0)
                    <tfoot>
                        <tr class="expense-total-row">
                            <td colspan="6"></td>
                            <td class="text-right">
                                &#8377; {{ number_format($expense_list->sum('expense_amount'), 0, '.', ',') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif -->
                </table>
            </div>
            @if(!empty($expense_list) && count($expense_list) > 0)
                <div class="expense-total-summary">
                    <span class="expense-total-label">
                        {{ trans('labels.total_amount') }}
                    </span>
                    <span class="expense-total-amount">
                        &#8377; {{ number_format($expense_list->sum('expense_amount'), 0, '.', ',') }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
</form>

@endsection