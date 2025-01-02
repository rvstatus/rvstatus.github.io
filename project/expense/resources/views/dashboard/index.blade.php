@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('resources/assets/css/dashboard/index.css') }}">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"> -->
<!-- <div class="container"> -->
    <div class="wrapper">
        <div class="row">
            <div class="col-4 col-m-4 col-sm-4">
                <div class="card">
                    <div class="counter bg-danger" style="color:white;">
                        <p><i class="fa fa-tasks"></i></p>
                        <h3>
                            Today's Expenses
                        </h3>
                        <p style="font-size: 1.2em;">
                            {{ $total_today_exp }}
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
                            {{ $total_yesterday_exp }}
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
                            {{ $total_last_seven_day_exp }}
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
                            {{ $total_current_month_exp }}
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
                            {{ $total_last_month_exp }}
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
                            {{ $total_exp }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
@endsection
