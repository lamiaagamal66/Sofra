@extends('layouts.app')

@section('page_title')
     Contacts   
@endsection
@section('small_title')
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header">
            
        </div>
        <div class="box-body">
            @include('flash::message')
            {{-- Search By type --}}
            <div class="filter">
                {!! Form::open([
                    'method' => 'get'
                ]) !!}
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::text('keyword',request('keyword'),[
                                'class' => 'form-control',
                                'placeholder' => 'message type'
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
             
            @if($contacts)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Message</th>
                        <th>Message Type</th>
                        <th class="text-center">Delete</th>
                        </thead>
                        <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$contact->name}}</td>
                                <td>{{$contact->email}}</td>
                                <td>{{$contact->mobile}}</td>
                                <td>{{$contact->message}}</td>
                                <td>{{$contact->type}}</td>
                                <td class="text-center">
                                    {!! Form::open([
                                        'action' => ['ContactController@destroy' , $contact->id],
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
            {{-- <div class="pull-left">
                <a href="{{url(route('contact.create'))}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create Contact
                </a>
            </div> --}}
        </div>
    </div>
@endsection