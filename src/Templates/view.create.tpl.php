@extends('layouts.master')
@php $nav_path = ['[[route_path]]']; @endphp
@section('page-title')
Create [[display_name_singular]]

@stop
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('[[view_folder]].index') }}">[[display_name_plural]]</a></li>
    <li class="breadcrumb-item active" aria-current="location">Create</li>
</ol>
@stop
@section('page-header-title')
Create a New [[display_name_singular]]

@stop

@section('content')

<[[view_folder]]-form csrf_token="{{ csrf_token() }}"></[[view_folder]]-form>

@endsection
