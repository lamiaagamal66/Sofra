@extends('layouts.app')

@section('page_title')
     Offers   
@endsection
@section('small_title')
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">List of Offers </h3>
        </div>
        <div class="box-body">
            @include('flash::message')
            {{-- Search By Restaurant --}}
            <div class="filter">
                {!! Form::open([
                    'method' => 'get'
                ]) !!}
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::text('keyword',request('keyword'),[
                                'class' => 'form-control',
                                'placeholder' => 'Restaurant'
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

            @if($offers)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <th>#</th>
                        <th>name</th>
                        <th>Description</th>
                        <th>Restaurant</th>
                        <th>Image</th>
                        <th>Offer Starting</th>
                        <th>Offer Ending</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Delete</th>
                        </thead>
                        <tbody>
                        @foreach($offers as $offer)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$offer->name}}</td>
                                <td>{{$offer->description}}</td>
                                <td>{{optional($offer->restaurant)->name}}</td>
                                <td>
                                    <img src="{{asset($offer->image)}}"  class="img-circle" style="max-width: 50px;">
                                </td>
                                <td>{{$offer->starting_at}}</td>
                                <td>{{$offer->ending_at}}</td>
                                <td class="text-center"><a href="{{url(route('offer.edit', $offer->id))}}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a></td>
                                <td class="text-center">
                                    {!! Form::open([
                                        'action' => ['OfferController@destroy' , $offer->id],
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
                <a href="{{url(route('offer.create'))}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create Offer
                </a>
            </div>
        </div>
    </div>
@endsection