@extends('layouts.app')
@inject('model', 'App\Models\PaymentType')

@section('page_title')
     Create Payment Type   
@endsection
@section('small_title')
     
@endsection
@section('content')

<div class="box box-primary">
    <div class="box-body">
        @include('partials.validation_errors')
        {!! Form::model($model,[
            'action'=>'PaymentTypeController@store',
            'id'=>'myForm',
            'role'=>'form',
            'method'=>'POST'
            ])!!}
        @include('dashboard.payment_types.form')
        {!! Form::close() !!}
    </div>

</div><!-- /.box -->

@endsection