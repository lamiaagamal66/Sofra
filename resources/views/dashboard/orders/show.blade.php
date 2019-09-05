@extends('layouts.app')
@inject('restaurant','App\Models\Restaurant')

@section('page_title')
    Show Order  
@endsection
@section('small_title')
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-body">
            <section class="invoice">
                <!-- title row -->
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="page-header">
                            Order Details
                            <small><i class="fa fa-calendar-o"></i>  {{$order->created_at}} </small>
                        </h2>
                    </div><!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <strong class="page-header"> Client Info </strong>
                        <address>
                            <i class="fa fa-angle-left" aria-hidden="true"></i><b> Order From:</b> {{$order->client->name}}
                            <br>
                            <i class="fa fa-angle-left" aria-hidden="true"></i><b> Mobile Number: </b>{{$order->client->mobile}}
                            <br>
                            <i class="fa fa-angle-left" aria-hidden="true"></i><b> Email: </b>{{$order->client->email}}
                            <br>
                            <i class="fa fa-angle-left" aria-hidden="true"></i> <b>Address: </b>{{$order->address}}
                        </address>
                    </div><!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        <strong class="page-header"> Restaurant Info </strong>
                        <address>
                            <i class="fa fa-angle-left" aria-hidden="true"></i> <b> Restaurant Name: </b> {{$order->restaurant->name}} <br>
                            <i class="fa fa-angle-left" aria-hidden="true"></i><b> Address: </b> {{$order->restaurant->region->name}}
                            <br>
                            <i class="fa fa-angle-left" aria-hidden="true"></i><b> Mobile: </b> {{$order->restaurant->mobile}}
                        </address>
                    </div><!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        <strong class="page-header"> Order Info </strong>
                        <address>
                        <i class="fa fa-angle-left" aria-hidden="true"></i><b> Order Num: </b> {{$order->id}} <br>
                        <i class="fa fa-angle-left" aria-hidden="true"></i><b> Notes: </b> {{$order->note}} <br>
                        <i class="fa fa-angle-left" aria-hidden="true"></i><b> Status: </b> {{$order->status}} <br>
                        <i class="fa fa-angle-left" aria-hidden="true"></i> <b>Total Cost: </b> {{$order->total_cost}}
                    </address>
                    </div><!-- /.col -->
                </div><!-- /.row -->
               
            </section><!-- /.content -->
        </div>
    </div>
@endsection