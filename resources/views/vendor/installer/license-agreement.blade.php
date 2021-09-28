@extends('installer::layouts.master')

@section('template_title')
    {{ trans('installer_messages.requirements.templateTitle')??'Server Requirements' }}
@endsection

@section('title')
    <i class="fa fa-list-ul fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.requirements.title')??'Server Requirement' }}
@endsection

@section('container')

    <h1>License Agreement</h1>

    <form action="{{route('LaravelInstaller::environment')}}" method="get">
        <input type="text" value="{{true}}" hidden name="is_accept">
        <button class="btn" type="submit">Submit</button>
    </form>

@endsection
