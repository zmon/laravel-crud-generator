@extends('layouts.master')

@section('title')

<title>Show [[model_uc]]</title>

@endsection

@section('header')

<ol class='breadcrumb'>
    <li><a href='/[[route_path]]'>[[model_uc]]</a></li>
    <li class='active'>Show</li>
</ol>

<h2>Show [[model_uc]]</h2>

@endsection

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