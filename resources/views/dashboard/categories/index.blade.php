@extends('layouts.app')

@section('page_title')
     Categories   
@endsection
@section('small_title')
     
@endsection
@section('content')
    <div class="box box-default">
        <div class="box-header">
            <h3 class="box-title">List of Categories </h3>
        </div>
        <div class="box-body">
            @include('flash::message')
            @if($categories)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delete</th>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td>{{$category->name}}</td>
                                <td class="text-center"><a href="{{url(route('category.edit' , $category->id ))}}"  class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a></td>
                                <td class="text-center">
                                    {!! Form::open([
                                        'action' => ['CategoryController@destroy' , $category->id],
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
                <a href="{{url(route('category.create'))}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Category
                </a>
            </div>
        </div>
    </div>
@endsection