@extends('layouts.app')
@inject('model', 'App\Models\Offer')

@section('page_title')
     Create Offer   
@endsection
@section('small_title')
@endsection
@section('content')

<div class="box box-primary">
    <div class="box-body">
        @include('partials.validation_errors')
        {!! Form::model($model,[
            'action'=>'OfferController@store',
            'id'=>'myForm',
            'role'=>'form',
            'method'=>'POST',
            'files'=>true
            ])!!}
        @include('dashboard.offers.form')
        {!! Form::close() !!}
    </div>
</div><!-- /.box -->
@endsection