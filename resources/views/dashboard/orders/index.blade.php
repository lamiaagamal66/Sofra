@extends('layouts.app')
@inject('restaurant','App\Models\Restaurant')

@section('page_title')
     Orders   
@endsection
@section('small_title')
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-body">
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
                                'placeholder' => 'Search Address|Client'
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        {!! Form::select('restaurant_id',$restaurant->pluck('name','name')->toArray(),request('restaurant_id'),[
                            'class' => 'form-control',
                            'placeholder' => 'Search Restaurant'
                        ]) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::select('status',[
                            'pending' ,
                            'accepted',
                            'rejected',
                        ],request('status'),[
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

            @if($orders)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <th>#</th>
                    <th>Restaurant</th>
                    <th>Total Cost</th>
                    {{-- <th>Address</th> --}}
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-center">Details</th>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td> {{optional($order->restaurant)->name}} </td>
                            <td>{{$order->total_cost}}</td>
                            {{-- <td>{{$order->address}}</td> --}}
                            <td class=" btn-warning text-center">{{$order->status}}</td>
                            <td>{{$order->created_at}}</td>
                            <td class="text-center">
                                <a href="{{url(route('order.show',$order->id))}}" class="btn btn-success btn-xs">Show Details </a>
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