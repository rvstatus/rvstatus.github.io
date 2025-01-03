@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Expense Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/exp_reg_process') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('project_type_name') ? ' has-error' : '' }}">
                            <label for="project_type_name" class="col-md-4 control-label">Project Type Name</label>

                            <div class="col-md-6">
                                <select class="form-control" name="project_type_name" id="project_type_name">
                                    <option value="">Select Project Type</option>
                                        @foreach($project_type_list as $project_type)
                                            <option value="{{ $project_type->project_type_id }}"  {{old('project_type_name') == $project_type->project_type_id  ? 'selected' : ''}}>{{ $project_type->project_type_name}}</option>
                                        @endforeach
                                </select>
                                @if ($errors->has('project_type_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('project_type_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mason_name') ? ' has-error' : '' }}">
                            <label for="mason_name" class="col-md-4 control-label">Mason Name</label>

                            <div class="col-md-6">
                                <select class="form-control" name="mason_name" id="mason_name">
                                    <option value="">Select Mason Name</option>
                                        @foreach($emp_list as $emp)
                                            <option value="{{ $emp->emp_id }}"  {{old('mason_name') == $emp->emp_id  ? 'selected' : ''}}>{{ $emp->emp_name}}</option>
                                        @endforeach
                                </select>
                                @if ($errors->has('mason_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mason_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('working_date') ? ' has-error' : '' }}">
                            <label for="working_date" class="col-md-4 control-label">Date</label>

                            <div class="col-md-6">
                                <input id="working_date" type="text" class="form-control" name="working_date" value="{{ old('working_date') }}">

                                @if ($errors->has('working_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('working_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('working_hours') ? ' has-error' : '' }}">
                            <label for="working_hours" class="col-md-4 control-label">Working Hours</label>

                            <div class="col-md-6">
                                <input id="working_hours" type="text" class="form-control" name="working_hours" value="{{ old('working_hours') }}">

                                @if ($errors->has('working_hours'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('working_hours') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('working_cat') ? ' has-error' : '' }}">
                            <label for="working_cat" class="col-md-4 control-label">Working Category</label>

                            <div class="col-md-6">
                                <select class="form-control" name="working_cat" id="working_cat">
                                    <option value="">Select Working Category</option>
                                        @foreach($work_cat_list as $work_cat)
                                            <option value="{{ $work_cat->id }}"  {{old('working_cat') == $work_cat->id  ? 'selected' : ''}}>{{ $work_cat->work_category_name}}</option>
                                        @endforeach
                                </select>
                                @if ($errors->has('working_cat'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('working_cat') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('working_type') ? ' has-error' : '' }}">
                            <label for="working_type" class="col-md-4 control-label">Working Type</label>

                            <div class="col-md-6">
                                <select class="form-control" name="working_type" id="working_type">
                                    <option value="">Select Working Type</option>
                                        @foreach($work_type_list as $work_type)
                                            <option value="{{ $work_type->id }}"  {{old('working_type') == $work_type->id  ? 'selected' : ''}}>{{ $work_type->work_type_name}}</option>
                                        @endforeach
                                </select>
                                @if ($errors->has('working_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('working_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('salary') ? ' has-error' : '' }}">
                            <label for="salary" class="col-md-4 control-label">Salary</label>

                            <div class="col-md-6">
                                <input id="salary" type="text" class="form-control" name="salary" value="{{ old('salary') }}">

                                @if ($errors->has('salary'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('salary') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-plus-circle"></i> Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
</script>
@endsection
