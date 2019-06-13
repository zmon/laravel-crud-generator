@extends('layouts.master')
@php $nav_path = ['[[route_path]]']; @endphp
@section('page-title')
View {{$[[model_singular]]->name}}
@stop
@section('page-header-breadcrumbs')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('[[view_folder]].index') }}">[[display_name_plural]]</a></li>
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
            <div class="col-md-4">
                @if ($can_edit)
                <a href="/[[route_path]]/{{ $[[model_singular]]->id }}/edit">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Edit
                    </button>
                </a>
                @endif
            </div>
            <div class="col-md-4  text-center">
                @if ($can_delete)
                <form class="form" role="form" method="POST" action="/[[route_path]]/{{ $[[model_singular]]->id }}">
                    <input type="hidden" name="_method" value="delete">
                    {{ csrf_field() }}

                    <input class="btn btn-danger btn-sm" Onclick="return ConfirmDelete();" type="submit" value="Delete">

                </form>
                @endif
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ url('/[[route_path]]') }}" class="btn btn-sm btn-default float-right">Return to List</a>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')

<script>

    function ConfirmDelete() {
        var x = confirm("Are you sure you want to delete this [[display_name_singular]]?");
        if (x)
            return true;
        else
            return false;
    }

</script>

@endsection
