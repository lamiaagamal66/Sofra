@extends('layouts.app')

@section('page_title')
     Edit Region   
@endsection
@section('small_title')
@endsection

@section('content')
<div class="box box-primary">
    <div class="box-body">
        @include('partials.validation_errors')
        @include('flash::message')
        {!! Form::model($model,[
            'action'=>['RegionController@update', $model->id],
            'method'=>'PUT'
            ])!!}
        @include('dashboard.regions.form')
        {!! Form::close() !!}
    </div>

</div><!-- /.box -->
@endsection