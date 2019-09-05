@extends('layouts.app')
@inject('model', 'App\Models\Category')

@section('page_title')
     Create Category   
@endsection
@section('small_title')
@endsection
@section('content')     
<!-- general form elements -->
<div class="box box-primary">
    <div class="box-body">
        @include('partials.validation_errors')
        {!! Form::model($model,[
            'action'=>'CategoryController@store',
            'id'=>'myForm',
            'role'=>'form',
            'method'=>'POST'
            ])!!}
        @include('dashboard.categories.form')
        {!! Form::close() !!}
    </div>
</div><!-- /.box -->

@endsection