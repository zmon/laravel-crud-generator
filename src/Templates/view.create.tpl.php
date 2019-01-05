@extends('layouts.master')

@section('title')

<title>Create a [[model_uc]]</title>

@endsection

@section('header')

<ol class='breadcrumb'>
    <li><a href='/[[route_path]]'>[[model_uc]]</a></li>
    <li class='active'>Create</li>
</ol>

<h2>Create a New [[model_uc]]</h2>

<hr/>
@endsection


@section('content')
                
        <form action="{{ url('/[[route_path]]'.( isset($model) ? "/" . $model->id : "")) }}" method="POST" class="form-horizontal">

            {{ csrf_field() }}


            [[foreach:columns]]
            [[if:i.type=='id']]
                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]" value="{{ old('[[i.name]]') }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[if:i.type=='text']]
                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]" value="{{ old('[[i.name]]') }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[if:i.type=='number']]
                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]" value="{{ old('[[i.name]]') }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[if:i.type=='date']]
                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]" value="{{ old('[[i.name]]') }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[if:i.type=='unknown']]
                <div class="row">
                    <div class="col-md-9">
                        @component('../components/std-form-group', ['fld' => '[[i.name]]', 'label' => '[[i.display]]'])
                        <input type="text" class="form-control" name="[[i.name]]" value="{{ old('[[i.name]]') }}">
                        @endcomponent
                    </div>
                </div>
            [[endif]]
            [[endforeach]]

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Create
                        </button>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ url('/[[route_path]]') }}" class="btn btn-sm btn-default float-right">Cancel</a>
                    </div>
                </div>
            </div>
        </form>


@endsection