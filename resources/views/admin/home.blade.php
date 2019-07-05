@extends('adminlte::page')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('title', 'AdminLTE')

@section('content_header')
    <h1>Users Table</h1>
@stop

@section('content')
    <a href="{{route('users.create')}}" class="btn btn-primary margin-bottom"><i class="fa fa-user-plus"> New</i></a>
    <table id="listUsers" class="table display">
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Manager</th>
            <th>Command</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
@stop

@section('js')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(function() {
            $('#listUsers').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('users.index')}}',
                },
                columns: [
                    { data: 'id', name: 'users.id' },
                    { data: 'username', name: 'users.username' },
                    { data: 'email', name: 'users.email' },
                    { data: 'role', name: 'users.role' },
                    { data: 'manager', name: 'users.manager_id' },
                    {
                        data: 'id',
                        render: function (data, type, row, meta) {
                            var command = '';
                            command += '<a href="/admin/users/'+data+'/edit" class="btn btn-warning margin-r-5"><i class="fa fa-edit"></i></a>';
                            command += '<a href="#" class="btn btn-danger" onclick="deleteUser('+ data +')"><i id="deleteUnit" class="fa fa-trash-o" aria-hidden="true"></i></a>';
                            return command;
                        }
                    }
                ]
            });
        });

        function deleteUser(id) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            dataType : 'json',
                            url: '/admin/users/' + id,
                            type: 'delete',
                            success: function(result){
                                swal("Poof! Your imaginary file has been deleted!", {
                                    icon: "success",
                                }).then((value) =>{
                                    if (value) {
                                        location.reload();
                                    }
                                });
                            },
                            error: function () {
                                alert('Ajax bi loi');
                            }

                        });

                    } else {
                        swal("Your imaginary file is safe!");
                    }
                });

            // }
        }
    </script>
@stop
