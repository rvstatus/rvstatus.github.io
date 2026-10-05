@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/employee/register.css') }}" rel="stylesheet">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Employee Register
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/employee_reg_process') }}">
                        {{ csrf_field() }}
                        <div class="form-group d-flex align-items-center">
                            <label for="emp_name" class="control-label col-md-3">
                                Employee Name
                            </label>
                            <div class="col-md-5">
                                <input id="emp_name" type="text" class="form-control" name="emp_name" value="{{ old('emp_name') }}">
                            </div>
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'emp_name'] ) </div>

                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="gender" class="control-label col-md-3">
                                Gender
                            </label>
                            <div class="col-md-5">
                                <label style="margin-right: 15px;">
                                    <input type="radio" name="gender" value="1" {{ old('gender') == 1 ? 'checked' : '' }}>
                                    Male
                                </label>
                                <label>
                                    <input type="radio" name="gender" value="2" {{ old('gender') == 2 ? 'checked' : '' }}>
                                    Female
                                </label>
                            </div>
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'gender'] ) </div>
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="date_of_birth" class="control-label col-md-3">
                                Date Of Birth
                            </label>
                            <div class="col-md-5">
                                <input id="date_of_birth" type="text" class="form-control datepicker" name="date_of_birth" value="{{ old('date_of_birth') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                            </div>
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'date_of_birth'] ) </div>
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="mobile_no" class="control-label col-md-3">
                                Mobile Number
                            </label>
                            <div class="col-md-5">
                                <input id="mobile_no" type="text" class="form-control" name="mobile_no" value="{{ old('mobile_no') }}">
                            </div>
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'mobile_no'] ) </div>
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="email" class="control-label col-md-3">
                                Email
                            </label>
                            <div class="col-md-5">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'email'] ) </div>
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="address" class="control-label col-md-3">
                                Address
                            </label>
                            <div class="col-md-5">
                                <textarea id="address" name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                            </div>
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'address'] ) </div>
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="category_id" class="control-label col-md-3">
                                Category
                            </label>
                            <div class="col-md-5">
                                <select class="form-control" name="category_id" id="category_id">
                                    <option value=""> Select Category </option>
                                    @foreach($work_cat_list as $work_cat)
                                    <option value="{{ $work_cat->id }}" {{ old('category_id') == $work_cat->id ? 'selected' : '' }}> {{ $work_cat->work_category_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'category_id'] ) </div>
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="join_date" class="control-label col-md-3">
                                Join Date
                            </label>
                            <div class="col-md-5">
                                <input id="join_date" type="text" class="form-control datepicker" name="join_date" value="{{ old('join_date') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                            </div>
                            <!-- <div class="input-group">
                                <input
                                    id="join_date"
                                    type="text"
                                    class="form-control"
                                    name="join_date"
                                    value="{{ old('join_date') }}"
                                    placeholder="dd/mm/yyyy"
                                    autocomplete="off">

                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div> -->
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'join_date'] ) </div>
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label for="salary" class="control-label col-md-3">
                                Salary
                            </label>
                            <div class="col-md-5">
                                <input id="salary" type="text" class="form-control" name="salary" value="{{ old('salary') }}">
                            </div>
                            <div class="col-md-4"> @include( 'errors.views.partials.field-error', ['field' => 'salary'] ) </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3 btn-group-responsive">
                                <button onclick="window.history.back();" class="btn btn-primary" type="button"> <i class="fa fa-arrow-left"></i> Back </button>
                                <button type="reset" class="btn btn-warning"> <i class="fa fa-undo"></i> Clear </button>
                                <button type="submit" class="btn btn-success"> <i class="fa fa-plus-circle"></i> Register </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('resources/assets/js/employee/register.js') }}"></script>
@endsection