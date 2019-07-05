@extends('adminlte::page')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('title', 'AdminLTE')

@section('content_header')
    <h1>Settings</h1>
@stop

@section('content')
    <form action="{{route('settings.timesheets.edit')}}" method="post" class="form-horizontal" role="form">
        @if($errors->any())
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach($errors->all() as $error)
                    <strong>{{$error}}</strong>
                    <br>
                @endforeach
            </div>
        @elseif(session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>{{ session('success') }}</strong>
            </div>
        @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <legend>Settings Timesheet</legend>
        </div>
        <div class="form-group">
            <label for="inputStartTimesheet" class="col-sm-3 control-label">Start timesheet</label>
            <div class="col-sm-9">
                <input type="time" name="startTimesheet" id="inputStartTimesheet" class="form-control" value="{{$startTimesheet->value}}" title="" required="required">
            </div>
        </div>

        <div class="form-group">
            <label for="inputEndTimesheet" class="col-sm-3 control-label">Start timesheet</label>
            <div class="col-sm-9">
                <input type="time" name="endTimesheet" id="inputEndTimesheet" class="form-control" value="{{$endTimesheet->value}}" title="" required="required">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@stop
