@extends('staff.layouts.layout')

{{--@section('title')--}}
{{--    Edit | Timesheet--}}
{{--@endsection--}}

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Edit Timesheet
            </h1>
        </section>
        <section class="content">
            <div class="container">
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
                <form action="/staff/timesheets/{{$timesheet->id}}" method="post" role="form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('put')
                    <div class="form-group">
                        <label for="inputDate" class="col-sm-2 control-label">Date:</label>
                        <div class="col-sm-10">
                            <input type="date" name="date" id="inputDate" class="form-control" value="{{$timesheet->date}}" title=""
                                   required="required">
                        </div>
                    </div>

                    <div class="form-group" id="tasks">
                        @foreach($timesheet->tasks as $task)
                            <label for="" class="col-sm-2 control-label">ID:</label>
                            <div class="col-sm-10">
                                <input type="text" name="tasks[ids][{{$task->id}}]" id="" class="form-control" value="{{$task->id}}" title=""
                                       required="required">
                            </div>
                            <label for="" class="col-sm-2 control-label">Content:</label>
                            <div class="col-sm-10">
                                <input type="text" name="tasks[contents][{{$task->id}}]" id="" class="form-control" value="{{$task->content}}" title=""
                                       required="required">
                            </div>
                            <label for="" class="col-sm-2 control-label">Hours:</label>
                            <div class="col-sm-10">
                                <input type="text" name="tasks[hours][{{$task->id}}]" id="" class="form-control" value="{{$task->used_time}}" title=""
                                       required="required">
                            </div>
                        @endforeach
                    </div>
                    <div class="col-sm-12">
                        <button id="addTask">Add</button>
                    </div>
                    <div class="form-group">
                        <label for="inputTrouble" class="col-sm-2 control-label">Trouble:</label>
                        <div class="col-sm-10">
                            <input type="text" name="trouble" id="inputTrouble" class="form-control" value="{{$timesheet->trouble}}" title=""
                                   required="required">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPlan" class="col-sm-2 control-label">Plan of the next day:</label>
                        <div class="col-sm-10">
                            <input type="text" name="plan" id="inputPlan" class="form-control" value="{{$timesheet->plan_of_next_day}}" title=""
                                   required="required">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#addTask').click(function () {
                let ele = '<label for="" class="col-sm-2 control-label">ID:</label>\n' +
                    '                        <div class="col-sm-10">\n' +
                    '                            <input type="text" name="tasks[ids][]" id="" class="form-control" value="" title=""\n' +
                    '                                   required="required">\n' +
                    '                        </div>\n' +
                    '                        <label for="" class="col-sm-2 control-label">Content:</label>\n' +
                    '                        <div class="col-sm-10">\n' +
                    '                            <input type="text" name="tasks[contents][]" id="" class="form-control" value="" title=""\n' +
                    '                                   required="required">\n' +
                    '                        </div>\n' +
                    '                        <label for="" class="col-sm-2 control-label">Hours:</label>\n' +
                    '                        <div class="col-sm-10">\n' +
                    '                            <input type="text" name="tasks[hours][]" id="" class="form-control" value="" title=""\n' +
                    '                                   required="required">\n' +
                    '                        </div>';
                $('#tasks').append(ele);
            });
        });
    </script>
@endsection
