@extends('adminlte::page')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('title', 'AdminLTE')

@section('content_header')
    <h1>Create User</h1>
@stop

@section('content')
    <form action="{{route('users.store')}}" method="post" role="form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <legend>User form</legend>
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
        <div class="form-group">
            <label for="inputUsername" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
                <input type="text" name="username" id="inputUsername" class="form-control" value="" title="" required="required">
            </div>
        </div>

        <div class="form-group">
            <label for="inputPassword" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" name="password" id="inputPassword" class="form-control" value="" title="" required="required">
            </div>
        </div>

        <div class="form-group">
            <label for="inputRepassword" class="col-sm-2 control-label">Re-Password</label>
            <div class="col-sm-10">
                <input type="password" name="repassword" id="inputReassword" class="form-control" value="" title="" required="required">
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" name="email" id="inputEmail" class="form-control" value="" title="" required="required">
            </div>
        </div>

        <div class="form-group">
            <label for="inputAvatar" class="col-sm-2 control-label">Avatar</label>
            <div class="col-sm-10">
                <input type="file" name="avatar" id="inputAvatar" class="form-control" value="" title="">
            </div>
        </div>

        <div class="form-group">
            <label for="textDescription" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10">
                <textarea name="description" id="textDescription" class="form-control" rows="5" cols="30" placeholder="text"></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="inputRole" class="col-sm-2 control-label">Role</label>
            <div class="col-sm-10">
                <select name="role" id="inputRole" class="form-control">
                    <option value=""> -- Select One -- </option>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->role_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="inputManager" class="col-sm-2 control-label">Manger</label>
            <div class="col-sm-10">
                <select name="manager" id="inputManager" class="form-control">
                    <option value=""> -- Select One -- </option>
                    @foreach($managers as $manager)
                        <option value="{{$manager->id}}">{{$manager->username}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary center-block">Create</button>
    </form>
@stop

@section('css')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
@stop

@section('script')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
@stop
