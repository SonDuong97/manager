@extends('staff.layouts.layout')

@section('title')
    Timesheet List
@endsection

@section('content')
    <div class="content-wrapper">
        @if($errors->any())
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach($errors->all() as $error)
                    <strong>{{$error}}</strong>
                    <br>
                @endforeach
            </div>
        @endif
        <section class="content-header">
            <h1>Timesheet</h1>
        </section>

        <section class="content">
            <a href="{{route('timesheets.create')}}" class="btn btn-primary margin-bottom"><i class="fa fa-user-plus"> New</i></a>
            <table id="listTimesheets" class="table display">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Trouble</th>
                    <th>Plan of the next day</th>
                    <th>Approved</th>
                    <th>Command</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
    <script>
        $(function() {
            $('#listTimesheets').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                ajax: {
                    url: '{{route('timesheets.get_all')}}',
                },
                columns: [
                    { data: 'id', name: 'timesheets.id' },
                    { data: 'date', name: 'timesheets.date' },
                    { data: 'trouble', name: 'timesheets.trouble' },
                    { data: 'plan_of_next_day', name: 'timesheets.plan_of_next_day' },
                    { data: 'approved', name: 'timesheets.approved' },
                    {
                        data: 'id',
                        render: function (data, type, row, meta) {
                            var command = '';
                            command += '<a href="/staff/timesheets/'+data+'" class="btn btn-primary margin-r-5"><i class="fa fa-info-circle"></i></a>';
                            command += '<a href="/staff/timesheets/'+data+'/edit" class="btn btn-warning margin-r-5"><i class="fa fa-edit"></i></a>';
                            return command;
                        }
                    }
                ]
            });
        });
    </script>
@endsection
