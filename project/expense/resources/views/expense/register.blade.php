@extends('layouts.app')
@section('content')

<link href="{{ asset('resources/assets/css/expense/register.css') }}" rel="stylesheet">

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading exp-panel-heading">
            <h4> {{ trans('labels.expense_register') }} </h4>
        </div>

        <div class="panel-body">
            @if(session()->has('response'))
            <div class="alert {{ session('response.design') }}">
                {{ session('response.message') }}
            </div>
            @endif

            <form id="expense_register_form" class="form-horizontal exp-form" method="POST" action="{{ url('/expense_reg_process') }}">
                <input type="hidden" id="expense_list_url" value="{{ route('get_expense_list_by_type') }}">
                {{ csrf_field() }}
                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.project_type') }}
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-5">
                        <select id="project_type_id" name="project_type_id" class="form-control">
                            <option value=""> {{ trans('labels.select_project') }} </option>
                            @foreach($project_type_list as $project)
                            <option value="{{ $project->project_type_id }}" {{ old('project_type_id') == $project->project_type_id ? 'selected' : '' }}>
                                {{ $project->project_type_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <span class="text-danger" id="error_project_type_id"></span>
                        @include('errors.views.partials.field-error', ['field' => 'project_type_id'])
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.expense_type') }}
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-5">
                        <select id="expense_type_id" name="expense_type_id" class="form-control">
                            <option value=""> {{ trans('labels.select_expense_type') }} </option>
                            @foreach($expense_type_list as $type)
                            <option value="{{ $type->id }}" {{ old('expense_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->expense_type_name }}
                            </option>
                            @endforeach
                            <option value="999">{{ trans('labels.others') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <span class="text-danger" id="error_expense_type_id"></span>
                        @include('errors.views.partials.field-error', ['field' => 'expense_type_id'])
                    </div>
                </div>
                <div class="form-group d-flex align-items-center" id="other_expense_type_div" style="display:none;">
                    <label class="control-label col-md-3">
                        {{ trans('labels.others') }}
                        <span class="text-danger">*</span>
                    </label>

                    <div class="col-md-5">
                        <input type="text" id="other_expense_type" name="other_expense_type" class="form-control" value="{{ old('other_expense_type') }}" maxlength="150" placeholder="{{ trans('labels.enter_expense_type') }}">
                    </div>

                    <div class="col-md-4">
                        <span class="text-danger" id="error_other_expense_type"></span>
                    </div>
                </div>
                <!-- Expense Name -->
                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.expense_name') }}
                        <span class="text-danger">*</span>
                    </label>

                    <div class="col-md-5">
                        <select id="expense_name_id" name="expense_name_id" class="form-control">
                            <option value=""> {{ trans('labels.select_expense') }} </option>
                            @foreach($expense_name_list as $expense)
                            <option value="{{ $expense->id }}" {{ old('expense_name_id') == $expense->id ? 'selected' : '' }}>
                                {{ $expense->expense_name }}
                            </option>
                            @endforeach
                            <option value="999"> {{ trans('labels.others') }} </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <span class="text-danger" id="error_expense_name_id"></span>
                        @include( 'errors.views.partials.field-error', ['field' => 'expense_name_id'] )
                    </div>

                </div>
                <!-- Other Expense Name -->
                <div
                    class="form-group d-flex align-items-center" id="other_expense_div" style="display:none;">

                    <label class="control-label col-md-3">
                        {{ trans('labels.others') }}
                        <span class="text-danger">*</span>
                    </label>

                    <div class="col-md-5">
                        <input type="text" id="other_expense_name" name="other_expense_name" class="form-control" maxlength="150" value="{{ old('other_expense_name') }}" placeholder="{{ trans('labels.enter_expense_name') }}">
                    </div>

                    <div class="col-md-4">
                        <span class="text-danger" id="error_other_expense_name"></span>
                        @include( 'errors.views.partials.field-error', ['field' => 'other_expense_name'] )
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.expense_date') }}
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-5">
                        <input type="text" id="expense_date" name="expense_date" class="form-control datepicker" value="{{ old('expense_date') }}">
                    </div>
                    <div class="col-md-4">
                        <span class="text-danger" id="error_expense_date"></span>
                        @include('errors.views.partials.field-error', ['field' => 'expense_date'])
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.amount') }}
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-5">
                        <input type="text" id="expense_amount" name="expense_amount" class="form-control" value="{{ old('expense_amount') }}">
                    </div>
                    <div class="col-md-4">
                        <span class="text-danger" id="error_expense_amount"></span>
                        @include('errors.views.partials.field-error', ['field' => 'expense_amount'])
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.vendor_name') }}
                    </label>
                    <div class="col-md-5">
                        <input type="text" id="vendor_name" name="vendor_name" class="form-control" value="{{ old('vendor_name') }}" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <span class="text-danger" id="error_vendor_name"></span>
                        @include('errors.views.partials.field-error', ['field' => 'vendor_name'])
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.bill_no') }}
                    </label>
                    <div class="col-md-5">
                        <input type="text" id="bill_no" name="bill_no" class="form-control" value="{{ old('bill_no') }}" maxlength="50">
                    </div>
                    <div class="col-md-4">
                        <span class="text-danger" id="error_bill_no"></span>
                        @include('errors.views.partials.field-error', ['field' => 'bill_no'])
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.description') }}
                    </label>
                    <div class="col-md-5">
                        <textarea name="expense_description" id="expense_description" class="form-control" rows="3" maxlength="255">{{ old('expense_description') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <span class="text-danger" id="error_expense_description"></span>
                        @include('errors.views.partials.field-error', ['field' => 'expense_description'])
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <label class="control-label col-md-3">
                        {{ trans('labels.remarks') }}
                    </label>
                    <div class="col-md-5">
                        <textarea name="remarks" id="remarks" class="form-control" rows="2" maxlength="255">{{ old('remarks') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <span class="text-danger" id="error_remarks"></span>
                        @include('errors.views.partials.field-error', ['field' => 'remarks'])
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3 btn-group-responsive exp-btn-group">

                        <button type="button" id="btn_back" onclick="window.history.back();" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i>
                            {{ trans('labels.back') }}
                        </button>

                        <button type="reset" id="btn_clear" class="btn btn-warning">
                            <i class="fa fa-undo"></i>
                            {{ trans('labels.cancel') }}
                        </button>

                        <button type="button" class="btn btn-success exp-submit-btn">
                            <i class="fa fa-plus-circle"></i>
                            {{ trans('labels.register') }}
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('resources/assets/js/expense/register.js') }}"></script>
</div>
@endsection