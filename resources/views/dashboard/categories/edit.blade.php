@extends('layouts.app')

@section('page_title')
     Update Category  
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
            'action'=>['CategoryController@update' ,$model->id],
            'method'=>'PUT'
            ])!!}
        @include('dashboard.categories.form')
        {!! Form::close()!!}
    </div>
</div><!-- /.box -->

@endsection