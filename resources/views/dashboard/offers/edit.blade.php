@extends('layouts.app')

@section('page_title')
     Edit Offer   
@endsection
@section('small_title')
@endsection
@section('content')

<div class="box box-primary">
    <div class="box-body">
        @include('partials.validation_errors')
        @include('flash::message')
        {!! Form::model($model,[
            'action'=>['OfferController@update',$model->id],
            'id'=>'myForm',
            'role'=>'form',
            'method'=>'PUT',
            'files'=>true
            ])!!}
            <img src="{{asset ($model->image) }}" class="img-circle" style="max-width: 15%;" />
        @include('dashboard.offers.form')
        {!! Form::close() !!}
    </div>
</div><!-- /.box -->
@endsection