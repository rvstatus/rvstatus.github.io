@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/employee/detail.css') }}" rel="stylesheet">
<div class="container">
    <div class="panel panel-default">
        <!-- HEADER -->
        <div class="panel-heading emp-panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="employee-title">Employee Detail</h4>
                </div>
            </div>
        </div>
        <!-- BUTTON ROW -->
        <div class="panel-body employee-panel-body">
            <div class="row employee-action-row">
                <div class="col-md-12">
                    <a href="{{ url('/employee_list') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <a href="javascript:void(0)" class="btn btn-warning btn-sm" id="btn_edit">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <!-- edit form -->
                    <form id="edit_form" method="POST" action="{{ url('/employee_edit') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="employee_id" value="{{ $employee->id }}">
                    </form>
                </div>
            </div>
            @if(session()->has('response'))
            <div class="alert {{ session('response.design') }}">
                {{ session('response.message') }}
            </div>
            @endif
            <!-- PERSONAL DETAILS -->
            <fieldset class="employee-fieldset">
                <legend>Personal Details</legend>
                <table class="table table-bordered detail-table">
                    <tr>
                        <th>Employee Name</th>
                        <td>{{ $employee->emp_name }}</td>
                        <th>Employee ID</th>
                        <td>{{ $employee->emp_id }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>
                            @if($employee->gender == 1)
                            Male
                            @elseif($employee->gender == 2)
                            Female
                            @else
                            -
                            @endif
                        </td>
                        <th>Mobile Number</th>
                        <td>{{ $employee->mobile_no }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td colspan="3">
                            {{ $employee->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td colspan="3">
                            {{ $employee->address }}
                        </td>
                    </tr>
                </table>
            </fieldset>
            <!-- WORK DETAILS -->
            <fieldset class="employee-fieldset">
                <legend>Work Details</legend>
                <table class="table table-bordered detail-table">
                    <tr>
                        <th>Category</th>
                        <td>{{ $employee->work_category_name ?? '-' }}</td>
                        <th>Salary</th>
                        <td>
                            {{ $employee->salary ? '₹ '.number_format($employee->salary,0,'.',',') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Join Date</th>
                        <td>
                            {{ !empty($employee->join_date) ? date('d-m-Y', strtotime($employee->join_date)) : '-' }}
                        </td>
                        <th>Leave Date</th>
                        <td>
                            {{ !empty($employee->leave_date) ? date('d-m-Y', strtotime($employee->leave_date)) : 'Nil' }}
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
</div>
<script src="{{ asset('resources/assets/js/employee/detail.js') }}"></script>
@endsection