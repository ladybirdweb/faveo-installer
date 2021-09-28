@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.requirements.templateTitle')??'Server Requirements' }}
@endsection

@section('title')
    <i class="fa fa-list-ul fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.requirements.title')??'Server Requirement' }}
@endsection

@section('container')

    <h1>Installation is done</h1>

@endsection
