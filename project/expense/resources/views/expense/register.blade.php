@extends('layouts.app')

@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Expense Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/exp_reg_process') }}">
                        {{ csrf_field() }}

                        <!-- <div class="form-group{{ $errors->has('project_type_name') ? ' has-error' : '' }}">
                            <label for="project_type_name" class="col-md-4 control-label">Project Type Name</label>

                            <div class="col-md-6">
                                <select class="form-control w180" name="project_type_name" id="project_type_name">
                                    <option value="">Select Project Type</option>
                                    @foreach($project_type_list as $project_type)
                                    <option value="{{ $project_type->project_type_id }}" {{old('project_type_name') == $project_type->project_type_id  ? 'selected' : ''}}>{{ $project_type->project_type_name}}</option>
                                    @endforeach
                                </select>
                                @include('errors.views.partials.field-error', ['field' => 'project_type_name'])
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mason_name') ? ' has-error' : '' }}">
                            <label for="mason_name" class="col-md-4 control-label">Mason Name</label>

                            <div class="col-md-6">
                                <select class="form-control w180" name="mason_name" id="mason_name">
                                    <option value="">Select Mason Name</option>
                                    @foreach($emp_list as $emp)
                                    <option value="{{ $emp->emp_id }}" {{old('mason_name') == $emp->emp_id  ? 'selected' : ''}}>{{ $emp->emp_name}}</option>
                                    @endforeach
                                </select>
                                @include('errors.views.partials.field-error', ['field' => 'mason_name'])
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('working_date') ? ' has-error' : '' }}">
                            <label for="working_date" class="col-md-4 control-label">Date</label>

                            <div class="col-md-6">
                                <input id="working_date" type="text" class="form-control w115" name="working_date" value="{{ old('working_date') }}">
                                @include('errors.views.partials.field-error', ['field' => 'working_date'])
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('working_hours') ? ' has-error' : '' }}">
                            <label for="working_hours" class="col-md-4 control-label">Working Hours</label>

                            <div class="col-md-6">
                                <input id="working_hours" type="text" class="form-control w180" name="working_hours" value="{{ old('working_hours') }}">
                                @include('errors.views.partials.field-error', ['field' => 'working_hours'])
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('working_cat') ? ' has-error' : '' }}">
                            <label for="working_cat" class="col-md-4 control-label">Working Category</label>

                            <div class="col-md-6">
                                <select class="form-control w210" name="working_cat" id="working_cat">
                                    <option value="">Select Working Category</option>
                                    @foreach($work_cat_list as $work_cat)
                                    <option value="{{ $work_cat->id }}" {{old('working_cat') == $work_cat->id  ? 'selected' : ''}}>{{ $work_cat->work_category_name}}</option>
                                    @endforeach
                                </select>
                                @include('errors.views.partials.field-error', ['field' => 'working_cat'])
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('working_type') ? ' has-error' : '' }}">
                            <label for="working_type" class="col-md-4 control-label">Working Type</label>

                            <div class="col-md-6">
                                <select class="form-control w185" name="working_type" id="working_type">
                                    <option value="">Select Working Type</option>
                                    @foreach($work_type_list as $work_type)
                                    <option value="{{ $work_type->id }}" {{old('working_type') == $work_type->id  ? 'selected' : ''}}>{{ $work_type->work_type_name}}</option>
                                    @endforeach
                                </select>
                                @include('errors.views.partials.field-error', ['field' => 'working_type'])
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('salary') ? ' has-error' : '' }}">
                            <label for="salary" class="col-md-4 control-label">Salary</label>

                            <div class="col-md-6">
                                <input id="salary" type="text" class="form-control w180" name="salary" value="{{ old('salary') }}">
                                @include('errors.views.partials.field-error', ['field' => 'salary'])
                            </div>
                        </div> -->



                        <!-- Project Type Name -->
                        <div class="form-group d-flex align-items-center">
                            <label for="project_type_name" class="col-md-3">Project Type Name</label>

                            <div class="col-md-5">
                                <select class="form-control w180" name="project_type_name" id="project_type_name">
                                    <option value="">Select Project Type</option>
                                    @foreach($project_type_list as $project_type)
                                    <option value="{{ $project_type->project_type_id }}" {{ old('project_type_name') == $project_type->project_type_id ? 'selected' : '' }}>
                                        {{ $project_type->project_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'project_type_name'])
                            </div>
                        </div>
                        <!-- Mason Name -->
                        <div class="form-group d-flex align-items-center">
                            <label for="mason_name" class="control-label col-md-3">Mason Name</label>

                            <div class="col-md-5">
                                <select class="form-control w180" name="mason_name" id="mason_name">
                                    <option value="">Select Mason Name</option>
                                    @foreach($emp_list as $emp)
                                    <option value="{{ $emp->emp_id }}" {{ old('mason_name') == $emp->emp_id ? 'selected' : '' }}>
                                        {{ $emp->emp_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'mason_name'])
                            </div>
                        </div>

                        <!-- Working Date -->
                        <div class="form-group d-flex align-items-center">
                            <label for="working_date" class="control-label col-md-3">Date</label>

                            <div class="col-md-5">
                                <input id="working_date" type="text" class="form-control w120 datepicker" name="working_date" value="{{ old('working_date') }}" placeholder="dd/mm/yyyy">
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'working_date'])
                            </div>
                        </div>

                        <!-- Working Hours -->
                        <div class="form-group d-flex align-items-center">
                            <label for="working_hours" class="control-label col-md-3">Working Hours</label>

                            <div class="col-md-5">
                                <input id="working_hours" type="text" class="form-control w180 timepicker " name="working_hours" value="{{ old('working_hours') }}">
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'working_hours'])
                            </div>
                        </div>

                        <!-- <div class="form-group d-flex align-items-center">
                            <label for="working_hours" class="control-label col-md-3">Working Hours</label>

                            <div class="col-md-5 d-flex">
                                <input id="working_hours_from" type="time" class="form-control w90" name="working_hours_from" value="{{ old('working_hours_from') }}" style="margin-right: 10px;">
                                <span style="margin: 5px 10px;">to</span>
                                <input id="working_hours_to" type="time" class="form-control w90" name="working_hours_to" value="{{ old('working_hours_to') }}">
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'working_hours_from'])
                                @include('errors.views.partials.field-error', ['field' => 'working_hours_to'])
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="working_hours" class="control-label col-md-3">Working Hours</label>

                            <div class="col-md-5 d-flex">
                                <input id="working_hours_from" type="text" class="form-control w90 timepicker" name="working_hours_from" value="{{ old('working_hours_from') }}" placeholder="From" style="margin-right: 10px;">
                                <span style="margin: 5px 10px;">to</span>
                                <input id="working_hours_to" type="text" class="form-control w90 timepicker" name="working_hours_to" value="{{ old('working_hours_to') }}" placeholder="To">
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'working_hours_from'])
                                @include('errors.views.partials.field-error', ['field' => 'working_hours_to'])
                            </div>
                        </div> -->
                        <!-- Working Hours (From - To) -->
                        <!-- <div class="form-group d-flex align-items-center">
                            <label for="working_hours_from" class="control-label col-md-3">Working Hours</label>

                            <div class="col-md-5 d-flex align-items-center">
                                <input id="working_hours_from" type="text" class="form-control timepicker w90" name="working_hours_from" value="{{ old('working_hours_from') }}" placeholder="From" style="margin-right: 10px;">
                                <span style="margin: 0 10px;">to</span>
                                <input id="working_hours_to" type="text" class="form-control timepicker w90" name="working_hours_to" value="{{ old('working_hours_to') }}" placeholder="To">
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'working_hours_from'])
                                @include('errors.views.partials.field-error', ['field' => 'working_hours_to'])
                            </div>
                        </div> -->

                        <!-- Working Category -->
                        <div class="form-group d-flex align-items-center">
                            <label for="working_cat" class="control-label col-md-3">Working Category</label>

                            <div class="col-md-5">
                                <select class="form-control w210" name="working_cat" id="working_cat">
                                    <option value="">Select Working Category</option>
                                    @foreach($work_cat_list as $work_cat)
                                    <option value="{{ $work_cat->id }}" {{ old('working_cat') == $work_cat->id ? 'selected' : '' }}>
                                        {{ $work_cat->work_category_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'working_cat'])
                            </div>
                        </div>

                        <!-- Working Type -->
                        <div class="form-group d-flex align-items-center">
                            <label for="working_type" class="control-label col-md-3">Working Type</label>

                            <div class="col-md-5">
                                <select class="form-control w185" name="working_type" id="working_type">
                                    <option value="">Select Working Type</option>
                                    @foreach($work_type_list as $work_type)
                                    <option value="{{ $work_type->id }}" {{ old('working_type') == $work_type->id ? 'selected' : '' }}>
                                        {{ $work_type->work_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'working_type'])
                            </div>
                        </div>

                        <!-- Salary -->
                        <div class="form-group d-flex align-items-center">
                            <label for="salary" class="control-label col-md-3">Salary</label>

                            <div class="col-md-5">
                                <input id="salary" type="text" class="form-control w180" name="salary" value="{{ old('salary') }}">
                            </div>

                            <div class="col-md-4">
                                @include('errors.views.partials.field-error', ['field' => 'salary'])
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3 btn-group-responsive">
                                <button
                                    onclick="window.history.back();"
                                    class="btn btn-primary"
                                    type="button">
                                    <i class="fa fa-arrow-left"></i> Back
                                </button>

                                <button
                                    type="reset"
                                    class="btn btn-warning"
                                    onclick="this.blur();">
                                    <i class="fa fa-undo"></i> Clear
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-success">
                                    <i class="fa fa-plus-circle"></i> Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection