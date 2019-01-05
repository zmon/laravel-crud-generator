@extends('layouts.master')

@section('title')

<title>Change a [[model_uc]]</title>

@endsection

@section('header')

<ol class='breadcrumb'>
    <li><a href='/[[route_path]]'>[[model_uc]]</a></li>
    <li class='active'>Change</li>
</ol>

<h2>Change [[model_uc]]</h2>

<hr/>
@endsection


@section('content')
                
        <form action="{{ url('/[[route_path]]/' . $[[model_singular]]->id ) }}" method="POST" class="form-horizontal">

<form class="form" role="form" method="POST" action="{{ url('/[[route_path]]/' . $[[model_singular]]->id) }}">

{{ csrf_field() }}

                <input type="hidden" name="_method" value="PATCH">

            [[foreach:columns]]
            [[if:i.type=='id']]

                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]"
                               value="{{ old('[[i.name]]',$[[model_singular]]->[[i.name]]) }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[if:i.type=='text']]

                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]"
                               value="{{ old('[[i.name]]',$[[model_singular]]->[[i.name]]) }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[if:i.type=='number']]

                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]"
                               value="{{ old('[[i.name]]',$[[model_singular]]->[[i.name]]) }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[if:i.type=='date']]

                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]"
                               value="{{ old('[[i.name]]',$[[model_singular]]->[[i.name]]) }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]

            [[if:i.type=='unknown']]
                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]"
                               value="{{ old('[[i.name]]',$[[model_singular]]->[[i.name]]) }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[endforeach]]

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Change
                        </button>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ url('/[[route_path]]') }}" class="btn btn-sm btn-default float-right">Cancel</a>
                    </div>
                </div>
            </div>
        </form>


@endsection