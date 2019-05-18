@extends('layouts.master')
@php $nav_path = ['[[route_path]]']; @endphp
@section('page-title')
View {{$[[model_singular]]->name}}
@stop
@section('page-header-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('[[view_folder]].index') }}">[[display_name_singular]]</a></li>
    <li class="breadcrumb-item active" aria-current="location">{{$[[model_singular]]->name}}</li>
</ol>

@stop
@section('page-header-title')
View [[display_name_singular]]

@stop

@section('content')

<div class="row mb-4">
    <div class="col-md-5">

     [[foreach:columns]]
        @component('../components/std-show-field', ['value' => $[[model_singular]]->[[i.name]]])
        [[i.display]]
        @endcomponent
     [[endforeach]]


        <div class="row">
            <div class="col-md-6">
                <a href="/[[route_path]]/{{ $[[model_singular]]->id }}/edit">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Edit
                    </button>
                </a>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ url('/[[route_path]]') }}" class="btn btn-sm btn-default float-right">Return to List</a>
            </div>
        </div>

    </div>
</div>

@endsection
