@extends('layouts.app')
@inject('city','App\Models\City')

@section('page_title')
     Restaurants   
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header"></div>
        <div class="box-body">
            @include('flash::message')
            {{-- Search --}}
            <div class="filter">
                {!! Form::open([
                    'method' => 'get'
                ]) !!}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::text('keyword',request('keyword'),[
                                'class' => 'form-control',
                                'placeholder' => 'Search name|Region'
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! Form::select('city_id',$city->pluck('name','id')->toArray(),request('city_id'),[
                        'class' => 'form-control',
                        'placeholder' => 'City'
                        ]) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::select('status',['Open' ,'Closed'],request('status'),[
                            'class' => 'form-control',
                            'placeholder' => 'Search Status'
                        ]) !!}
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Search</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            @if($restaurants)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>City/Region</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Activate</th>
                        <th class="text-center">Details</th>
                        <th class="text-center">Delete</th>
                        </thead>
                        <tbody>
                        @foreach($restaurants as $restaurant)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$restaurant->name}}</td>
                                <td>{{optional($restaurant->region->city)->name}} / {{optional($restaurant->region)->name}}</td>
                                <td class="text-center">{{$restaurant->status}}</td>
                                <td class="text-center">
                                  @if ($restaurant->is_active)
                                    <a href="{{url(route('restaurant.deactivate' , $restaurant->id ))}}" class="btn btn-warning btn-xs ">   
                                        <i class="fa fa-close"></i> De-Activate
                                    </a>
                                  @else
                                    <a href="{{url(route('restaurant.activate' , $restaurant->id ))}}" class="btn btn-success btn-xs ">
                                        <i class="fa fa-check"></i> Activate
                                    </a>
                                  @endif    
                                </td>
                                <td class="text-center">
                                    <a href="{{url(route('restaurant.show',$restaurant->id))}}" class="btn btn-primary btn-xs">Show Details </a>
                                </td>
                                <td class="text-center">
                                    {!! Form::open([
                                        'action' => ['RestaurantController@destroy' , $restaurant->id],
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
                {{-- <div class="text-center">
                    {!! $restaurants->appends([
                        'name' => request()->input('name'),
                        'city_id' => request()->input('city_id'),
                        'status' => request()->input('status'),
                    ])->render() !!}
                </div> --}}
            @else
                <div class="col-md-4 col-md-offset-4">
                    <div class="alert alert-info bg-blue text-center">لا يوجد سجلات</div>
                </div>
            @endif
        </div>
    </div>
@endsection