@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'register-page')

@section('body')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg">{{ trans('adminlte::adminlte.register_message') }}</p>
            @if(session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>{{ session('success') }}</strong>
            </div>
            @endif
            <form action="{{ route('users.register') }}" method="post" role="form" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <div class="form-group has-feedback {{ $errors->has('username') ? 'has-error' : '' }}">
{{--                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"--}}
{{--                           placeholder="{{ trans('adminlte::adminlte.full_name') }}">--}}
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                           placeholder="Username">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{{ trans('adminlte::adminlte.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('repassword') ? 'has-error' : '' }}">
                    <input type="password" name="repassword" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.retype_password') }}">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('repassword'))
                        <span class="help-block">
                            <strong>{{ $errors->first('repassword') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('description') ? 'has-error' : '' }}">
                    <input type="text" name="description" class="form-control"
                           placeholder="Description" value="{{old('description')}}">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('avatar') ? 'has-error' : '' }}">
                    <input type="file" name="avatar" id="inputAvatar" class="form-control" value="{{old('avatar')}}" title="" placeholder="Avatar">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('avatar'))
                        <span class="help-block">
                            <strong>{{ $errors->first('avatar') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback {{ $errors->has('role') ? 'has-error' : '' }}">
                    <select name="role" id="inputRole" class="form-control">
                        <option value=""> -- Select role -- </option>
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->role_name}}</option>
                        @endforeach
                    </select>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('role'))
                        <span class="help-block">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback {{ $errors->has('manager') ? 'has-error' : '' }}">
                    <select name="manager" id="inputManager" class="form-control">
                        <option value=""> -- Select a manager -- </option>
                        @foreach($managers as $manager)
                            <option value="{{$manager->id}}">{{$manager->username}}</option>
                        @endforeach
                    </select>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('manager'))
                        <span class="help-block">
                            <strong>{{ $errors->first('manager') }}</strong>
                        </span>
                    @endif
                </div>

                <button type="submit"
                        class="btn btn-primary btn-block btn-flat"
                >{{ trans('adminlte::adminlte.register') }}</button>
            </form>
            <div class="auth-links">
                <a href="{{ url(config('adminlte.login_url', 'login')) }}"
                   class="text-center">{{ trans('adminlte::adminlte.i_already_have_a_membership') }}</a>
            </div>
        </div>
        <!-- /.form-box -->
    </div><!-- /.register-box -->
@stop
