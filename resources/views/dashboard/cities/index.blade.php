@extends('layouts.app')

@section('page_title')
     Cities   
@endsection
@section('small_title')     
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">List of Cities </h3>
        </div>
        <div class="box-body">
            @include('flash::message')
            @if('$cities')
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delete</th>
                    </thead>
                    <tbody>
                        @foreach($cities as $city)
                            <tr id="removable{{$city->id}}">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$city->name}}</td>
                                <td class="text-center"><a href="{{url(route('city.edit' , $city->id ))}}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a></td>
                                <td class="text-center">
                                        {!! Form::open([
                                            'action' => ['CityController@destroy' , $city->id],
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
                <a href="{{url(route('city.create'))}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New City
                </a>
            </div>
        </div>
    </div>
    @endsection