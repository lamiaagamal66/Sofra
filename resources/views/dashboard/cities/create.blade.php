@extends('layouts.app')
@inject('model', 'App\Models\City')

@section('page_title')
     Create City   
@endsection
@section('small_title')
     
@endsection
@section('content')

<div class="box box-primary">
    <div class="box-body">
        @include('partials.validation_errors')
        {!! Form::model($model,[
            'action'=>'CityController@store',
            'id'=>'myForm',
            'role'=>'form',
            'method'=>'POST'
            ])!!}
        @include('dashboard.cities.form')
        {!! Form::close() !!}
    </div>

</div><!-- /.box -->

@endsection