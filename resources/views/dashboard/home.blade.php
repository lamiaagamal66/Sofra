@extends('layouts.app')
@inject('restaurant','App\Models\Restaurant')
@inject('order','App\Models\Order')
@inject('client','App\Models\Client')

@section('page_title')
    Dashboard   
@endsection
@section('small_title')
    Statistics
@endsection

@section('content')   
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-cutlery"></i></span>
        
                    <div class="info-box-content">
                        <span class="info-box-text">Restaurants</span>
                    <span class="info-box-number">{{ $restaurant->count() }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-tasks"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Completed Orders</span>
                    <span class="info-box-number">{{ $order->where('status','!=','pending')->get()->count() }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
        
                    <div class="info-box-content">
                        <span class="info-box-text">Clients</span>
                        <span class="info-box-number">{{$client->count()}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            
        </div>
    </section>
    <!-- /.content -->

@endsection
