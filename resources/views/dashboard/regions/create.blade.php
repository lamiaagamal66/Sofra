@extends('layouts.app')
@inject('model', 'App\Models\Region')

@section('page_title')
     Create Region   
@endsection
@section('small_title')
@endsection

@section('content')
<div class="box box-primary">
    <div class="box-body">
        @include('partials.validation_errors')
        {!! Form::model($model,[
            'action'=>'RegionController@store',
            'id'=>'myForm',
            'role'=>'form',
            'method'=>'POST'
            ])!!}
        @include('dashboard.regions.form')
        {!! Form::close() !!}
    </div>

</div><!-- /.box -->
@endsection