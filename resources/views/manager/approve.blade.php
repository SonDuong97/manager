@extends('staff.layouts.layout')

@section('title')
    Approving timesheet
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Approving timesheet</h1>
        </section>

        <section class="content">
            <table id="listTimesheets" class="table display">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Date</th>
                    <th>Trouble</th>
                    <th>Plan of the next day</th>
                    <th>Command</th>
                    <th></th>
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
                ajax: {
                    url: '{{route('manager.get.timesheet')}}',
                },
                columns: [
                    { data: 'id', name: 'timesheets.id' },
                    { data: 'username', name: 'timesheets.username' },
                    { data: 'date', name: 'timesheets.date' },
                    { data: 'trouble', name: 'timesheets.trouble' },
                    { data: 'plan_of_next_day', name: 'timesheets.plan_of_next_day' },
                    // { data: 'approved', name: 'timesheets.approved' },
                    {
                        data: 'id',
                        render: function (data, type, row, meta) {
                            var command = '';
                            command += '<a href="/staff/show-timesheet/'+data+'" class="btn btn-primary margin-r-5"><i class="fa fa-info-circle"></i></a>';
                            command += '<a href="/staff/approve-timesheet/'+data+'" class="btn btn-warning margin-r-5"><i class="fa  fa-angle-double-up"></i></a>';
                            return command;
                        }
                    },
                ]
            });
        });
    </script>
@endsection
