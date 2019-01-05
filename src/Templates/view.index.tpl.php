@extends('layouts.master')

@section('title')

<title>[[model_uc]] List</title>

@endsection

@section('header')

<ol class='breadcrumb'>
    <li><a href='/[route_path]]'>[[model_uc]]</a></li>
    <li class='active'>List</li>
</ol>

<h2>[[model_uc]] List</h2>

@endsection


@section('content')


<[[model_singular]]-grid :params="{
        Page: '{{ $page }}',
        Search: '{{ $search }}',
        sortOrder: '{{ $direction }}',
        sortKey: '{{ $column }}',
        CanAdd: '{{ $can_add }}',
        CanEdit: '{{ $can_edit }}',
        CanShow: '{{ $can_show }}',
        CanDelete: '{{ $can_delete }}',
        CanExcel: '{{ $can_excel }}'
    }"></[[model_singular]]-grid>



@endsection