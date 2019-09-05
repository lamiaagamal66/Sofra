@extends('layouts.app')

@section('page_title')
     Regions   
@endsection
@section('small_title')
     
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">List of Regions </h3>
        </div>

        <div class="box-body">
            @include('flash::message')
            @if($regions)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <th>#</th>
                        <th>Region</th>
                        <th>City</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Delete</th>
                        </thead>
                        <tbody>
                            @foreach($regions as $region)
                                <tr id="removable{{$region->id}}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$region->name}}</td>
                                    <td>{{optional($region->city)->name}}</td>
                                    <td class="text-center">
                                        <a href="{{url(route('region.edit' , $region->id ))}}"  class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                    </td>
                                    <td class="text-center">
                                        {!! Form::open([
                                            'action' => ['RegionController@destroy' , $region->id],
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
            <div class="pull-right">
                <a href="{{url('admin/region/create')}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Region
                </a>
            </div>                 
        </div>

    </div>
@endsection