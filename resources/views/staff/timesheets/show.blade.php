@extends('staff.layouts.layout')

@section('title')
    Detail timesheet
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Detail timesheet</h1>
        </section>

        <section class="content">
            <ul class="list-group">
                <li class="list-group-item"><strong>Date: </strong> {{$timesheet->date}}</li>
                <li class="list-group-item"><strong>Trouble: </strong> {{$timesheet->trouble}}</li>
                <li class="list-group-item"><strong>Plan of the next day: </strong> {{$timesheet->plan_of_next_day}}</li>
                <li class="list-group-item">
                    <strong>Task List: </strong>
                    @foreach($timesheet->tasks as $task)
                        <ul class="list-group">
                            <li class="list-group-item">ID: {{$task->id}}</li>
                            <li class="list-group-item">Content: {{$task->content}}</li>
                            <li class="list-group-item">Hours: {{$task->used_time}}</li>
                        </ul>
                    @endforeach
                </li>
            </ul>
        </section>
    </div>
@endsection
