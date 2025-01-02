@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('resources/assets/css/expense/list.css') }}">
@if(session()->has('response'))
    <div class="alert {{ session()->get('response')['design'] }}">
        {{ session()->get('response')['message'] }}
    </div>
@endif
<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <a href="{{ url('/expense_register') }}" class="btn btn-sm btn-primary pull-left"><i class="fa fa-plus-circle"></i> Add New</a>
                            <form class="form-horizontal pull-right">
                                <div class="form-group">
                                    <label>Sort By : </label>
                                    <!-- <select class="form-control">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                    </select> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table">
                        <thead>
                          <colgroup>
                            <col width="5%" />
                            <col />
                            <col width="5%" />
                            <col width="10%" />
                            <col width="15%" />
                            <col width="5%" />
                            <col width="10%" />
                            <col width="15%" />
                          </colgroup>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Work Type</th>
                                <th>Work Category</th>
                                <th>Salary</th>
                                <th>Created By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exp_list as $expense)
                                <tr>
                                    <td>1</td>
                                    <td>{{ $expense->name }}</td>
                                    <td>{{ $expense->working_date }}</td>
                                    <td>{{ $expense->work_type_name }}</td>
                                    <td>{{ $expense->work_category_name }}</td>
                                    <td>{{ $expense->salary }}</td>
                                    <td>{{ $expense->created_by }}</td>
                                    <td>
                                        <ul class="action-list">
                                            <li><a href="javascript:alert('under construction');" class="btn btn-primary"><i class="fas fa-pen"></i></a></li>
                                            <li><a href="javascript:alert('under construction');" class="btn btn-danger"><i class="fa fa-times"></i></a></li>
                                            <li><a href="javascript:alert('under construction');" class="btn btn-sm btn-warning"><i class="fa fa-info-circle"></i></a></li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <!-- <div class="row">
                        <div class="col-sm-6 col-xs-6">showing <b>5</b> out of <b>25</b> entries</div>
                        <div class="col-sm-6 col-xs-6">
                            <ul class="pagination hidden-xs pull-right">
                                <li><a href="#">«</a></li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#">»</a></li>
                            </ul>
                            <ul class="pagination visible-xs pull-right">
                                <li><a href="#">«</a></li>
                                <li><a href="#">»</a></li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
    

@endsection
