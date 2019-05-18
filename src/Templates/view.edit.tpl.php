@extends('layouts.master')
@php $nav_path = ['[[route_path]]']; @endphp
@section('page-title')
Edit {{$[[model_singular]]->name}}
@stop
@section('page-help-link', '#TODO')
@section('page-header-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('[[view_folder]].index') }}">[[display_name_singular]]</a></li>
    <li class="breadcrumb-item active" aria-current="location">{{$[[model_singular]]->name}}</li>
</ol>
@stop
@section('page-header-title')
Edit [[display_name_singular]]

@stop


@section('content')
<[[view_folder]]-form csrf_token="{{ csrf_token() }}" :record='{!! json_encode($[[model_singular]],JSON_HEX_APOS) !!}'></[[view_folder]]-form>

@endsection
