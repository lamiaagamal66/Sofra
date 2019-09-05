@extends('layouts.app')

@section('page_title')
     Settings   
@endsection
@section('small_title')
@endsection
@section('content')
        <!-- general form elements -->
<div class="box box-primary">
    <div class="box-body">
        @include('partials.validation_errors')
        @include('flash::message')
        <!-- form start -->
        {!! Form::model($model,[
            'action'=>['SettingsController@update',$model->id],
            'id'=>'myForm',
            'role'=>'form',
            'method'=>'Post',
            'files'=>true
            ])!!}
        @include('dashboard.settings.form',compact('model'))
        {!! Form::close()!!}
    </div>
</div><!-- /.box -->
@endsection