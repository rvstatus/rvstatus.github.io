@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('resources/assets/css/dashboard/index.css') }}">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"> -->
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<!-- <div class="container"> -->
<div class="wrapper">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/expense_dashboard') }}">
        {{ csrf_field() }}
        <div class="row g-2 align-items-end responsive-group">
            <!-- Project Type -->
            <div class="col-auto responsive-col responsive-col-label">
                <label for="project_type" class="form-label">Project</label>
            </div>
            <div class="col-auto responsive-col">
                <select name="project_type" id="project_type" class="form-select w180">
                    <option value="">All</option>
                    @foreach($project_type_list as $project_type)
                    <option value="{{ $project_type->project_type_id }}"
                        {{ old('project_type', $selected_project_type) == $project_type->project_type_id ? 'selected' : '' }}>
                        {{ $project_type->project_type_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Work Type -->
            <div class="col-auto responsive-col responsive-col-label">
                <label for="work_type" class="form-label">Work</label>
            </div>
            <div class="col-auto responsive-col">
                <select name="work_type" id="work_type" class="form-select w180">
                    <option value="">All</option>
                    @foreach($work_type_list as $work_type)
                    <option value="{{ $work_type->id }}"
                        {{ old('work_type', $selected_work_type) == $work_type->id ? 'selected' : '' }}>
                        {{ $work_type->work_type_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Button -->
            <div class="col-auto responsive-col">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-4 col-m-4 col-sm-4">
            <div class="card">
                <div class="counter bg-danger" style="color:white;">
                    <p><i class="fa fa-tasks"></i></p>
                    <h3>
                        Today's Expenses
                    </h3>
                    <p style="font-size: 1.2em;">
                        &#8377; {{ $total_today_exp }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-4 col-m-4 col-sm-4">
            <div class="card">
                <div class="counter bg-primary" style="color:white;">
                    <p><i class="fas fa-undo-alt"></i></p>
                    <h3>
                        Yesterday's Expenses
                    </h3>
                    <p style="font-size: 1.2em;">
                        &#8377; {{ $total_yesterday_exp }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-4 col-m-4 col-sm-4">
            <div class="card">
                <div class="counter bg-warning" style="color:white;">
                    <p><i class="fas fa-calendar-week"></i></p>
                    <h3>
                        Last 7 day's Expenses
                    </h3>
                    <p style="font-size: 1.2em;">
                        &#8377; {{ $total_last_seven_day_exp }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-4 col-m-4 col-sm-4">
            <div class="card">
                <div class="counter bg-vio" style="color:white;">
                    <p><i class="fas fa-calendar"></i></p>
                    <h3>
                        Current Month Expenses
                    </h3>
                    <p style="font-size: 1.2em;">
                        &#8377; {{ $total_current_month_exp }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-4 col-m-4 col-sm-4">
            <div class="card">
                <div class="counter bg-success" style="color:white;">
                    <p><i class="fas fa-dollar-sign"></i></p>
                    <h3>
                        Last Month Expenses
                    </h3>
                    <p style="font-size: 1.2em;">
                        &#8377; {{ $total_last_month_exp }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-4 col-m-4 col-sm-4">
            <div class="card">
                <div class="counter bg-yell" style="color:white;">
                    <p><i class="fas fa-file-invoice-dollar" aria-hidden="true"></i></p>
                    <h3>
                        Total Expenses
                    </h3>
                    <p style="font-size: 1.2em;">
                        &#8377; {{ $total_exp }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </div> -->
@endsection