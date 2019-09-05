@extends('layouts.app')

@section('page_title')
     Clients   
@endsection
@section('small_title')
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header">
        </div>
        <div class="box-body">
            @include('flash::message')
            {{-- Search --}}
            <div class="filter">
                {!! Form::open([
                    'method' => 'get'
                ]) !!}
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::text('keyword',request('keyword'),[
                                'class' => 'form-control',
                                'placeholder' => 'Search name|Mobile|Email|Region'
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Search</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

            @if($clients)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Image</th>
                        <th>Region</th>
                        <th class="text-center">Delete</th>
                        </thead>
                        <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$client->name}}</td>
                                <td>{{$client->email}}</td>
                                <td>{{$client->mobile}}</td>
                                <td>
                                    <img src="{{asset($client->image)}}"  class="img-circle" style="max-width: 50px;">
                                </td>
                                <td> {{optional($client->region)->name}} </td>
                                <td class="text-center">
                                    {!! Form::open([
                                        'action' => ['ClientController@destroy' , $client->id],
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
    </div>
@endsection