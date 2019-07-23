@extends('staff.layouts.layout')

@section('title')
    Dashboard
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Version 2.0</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('staff.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class='row'>
                <div class='col-md-6'>
                    <!-- Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Summay Log</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body" id="divChart">

                        </div><!-- /.box-body -->
{{--                        <div class="box-footer">--}}
{{--                            <form action='#'>--}}
{{--                                <input type='text' placeholder='New task' class='form-control input-sm' />--}}
{{--                            </form>--}}
{{--                        </div><!-- /.box-footer-->--}}
                    </div><!-- /.box -->
                </div><!-- /.col -->
                <div class='col-md-6'>
                    <!-- Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Second Box</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            A separate section to add any kind of widget. Feel free
                            to explore all of AdminLTE widgets by visiting the demo page
                            on <a href="https://almsaeedstudio.com">Almsaeed Studio</a>.
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->

            </div><!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    <script>
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            url: '{{route('staff.getLog')}}',
            type: 'get',
            success: function(result){
                var chart = '';
                result.forEach(function (item) {
                    chart += '<div class="text-blue"><strong>From: '+ item.from_date +'</strong></div>\n' +
                        '                            <div class="text-blue"><strong>To: '+ item.to_date +'</strong></div>';
                    var percentageOfDelayedTimesheet = Math.round(item.delayed_time/item.registed_time * 100);
                    var percentageOfRegistedTimesheetOnTime  = 100 - percentageOfDelayedTimesheet;

                    chart += '<h5>\n' +
                        '        Registed Timesheet On Time Rating\n' +
                        '        <small class="label label-success pull-right">'+ percentageOfRegistedTimesheetOnTime +'%</small>\n' +
                        '    </h5>\n' +
                        '    <div class="progress progress-xxs">\n' +
                        '        <div class="progress-bar progress-bar-success" style="width: '+ percentageOfRegistedTimesheetOnTime +'%"></div>\n' +
                        '    </div>';
                    chart += '<h5>\n' +
                        '        Delayed Timesheet Rating\n' +
                        '        <small class="label label-danger pull-right">'+ percentageOfDelayedTimesheet +'%</small>\n' +
                        '    </h5>\n' +
                        '    <div class="progress progress-xxs">\n' +
                        '        <div class="progress-bar progress-bar-danger" style="width: '+ percentageOfDelayedTimesheet +'%"></div>\n' +
                        '    </div>';
                });
                $('#divChart').html(chart);
            },
            error: function () {
                alert('Ajax bi loi');
            }

        });
    </script>
@endsection
