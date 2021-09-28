@extends('installer::layouts.master')

@section('template_title')
    {{ trans('installer_messages.requirements.templateTitle')??'Server Requirements' }}
@endsection

@section('title')
    <i class="fa fa-list-ul fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.requirements.title')??'Server Requirement' }}
@endsection

@section('container')

    <h1>Database Setup</h1>

    @if(\Illuminate\Support\Facades\Session::has('error'))

        <p style="color: red">{{\Illuminate\Support\Facades\Session::get('error')}}</p>
    @endif


    <form action="{{route('LaravelInstaller::environment')}}" method="post">
        @csrf
        <input type="text" name="database_hostname" placeholder="Host">
        <input type="text" name="database_name" placeholder="DB Name">
        <input type="text" name="database_username" placeholder="username">
        <input type="text" name="database_password" placeholder="password">
        <input type="text" name="database_port" placeholder="port_number">

        <button class="btn" type="submit">Submit</button>
    </form>

@endsection
