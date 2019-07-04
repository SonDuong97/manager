@extends('adminlte::page')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('title', 'AdminLTE')

@section('content_header')
    <h1>Create User</h1>
@stop

@section('content')
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