@extends('layouts.app')

@section('page_title')
     Payment Types   
@endsection
@section('small_title')     
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header">
        </div>
        <div class="box-body">
            @include('flash::message')
            @if('$payment_types')
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delete</th>
                    </thead>
                    <tbody>
                        @foreach($payment_types as $types)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$types->name}}</td>
                                <td class="text-center"><a href="{{url(route('payment-type.edit' , $types->id ))}}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a></td>
                                <td class="text-center">
                                        {!! Form::open([
                                            'action' => ['PaymentTypeController@destroy' , $city->id],
                                            'method' => 'delete'
                                        ]) !!}
                                        <button type="submit" class="btn btn-danger btn-xs" data-token="{{ csrf_token() }}">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                        {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="alert alert-danger" role="alert">
                    No Data 
                </div>
            @endif
        
        </div>
        <div class="box-footer">
            <div class="pull-left">
                <a href="{{url(route('payment-type.create'))}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Payment Type
                </a>
            </div>
        </div>
    </div>
    @endsection