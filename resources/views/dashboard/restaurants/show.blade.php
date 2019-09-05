@extends('layouts.app')

@section('page_title')
{{$restaurant->name}} Restaurant Inormation   
@endsection

@section('content')

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header text-center">
            <img src="{{asset ($restaurant->image) }}" class="img-circle" style="max-width: 15%;" />
            <h3>{{$restaurant->name}} Restaurant</h3>
        </div>
        <div class="box-body">
          {{-- flash message --}}
          @include('flash::message')
            @if ('$restaurant')
            <div class="table-responsive">
                <table class="table margin">
                    <tbody>       
                        <tr>
                          <th>Image</th>
                          {{-- <td>{{$restaurant->id}}</td> --}}
                        </tr>
                        <tr>
                          <th>ID :</th>
                          <td>{{$restaurant->id}}</td>
                        </tr>
                        <tr> 
                          <th>Name :</th>
                          <td>{{$restaurant->name}}</td> 
                        </tr>       
                        <tr> 
                          <th>Email :</th>
                          <td>{{$restaurant->email}}</td> 
                        </tr>  
                        <tr>
                           <th>Created at :</th>
                           <td>{{$restaurant->created_at}}</td>
                        </tr>
                        <tr>
                          <th>Updated at :</th>
                          <td>{{$restaurant->updated_at}}</td>
                        </tr>
                        <tr> 
                          <th>Mobile :</th>
                          <td>{{$restaurant->mobile}}</td> 
                        </tr>
                        <tr> 
                          <th> City :</th>
                          <td>{{optional($restaurant->region->city)->name}}</td> 
                        </tr>
                        <tr> 
                          <th>Region :</th>
                          <td>{{optional($restaurant->region)->name}}</td> 
                        </tr>
                        <tr> 
                          <th>Minimum Cost :</th>
                          <td>{{$restaurant->minimum_cost}}</td> 
                        </tr>
                        <tr> 
                          <th>Delivery Cost :</th>
                          <td>{{$restaurant->delivery_cost}}</td> 
                        </tr>
                        <tr> 
                          <th>Whatsapp :</th>
                          <td>{{$restaurant->whatsapp}}</td> 
                        </tr>
                        <tr> 
                          <th>Api Token :</th>
                          <td>{{$restaurant->api_token}}</td> 
                        </tr>
                        <tr> 
                          <th>Activation :</th>
                            @if ($restaurant->is_active)
                              <td class="">   
                                Active
                              </td>
                            @else
                              <td class="">
                                Deactive
                              </td>
                            @endif 
                        </tr>
                        <tr> 
                          <th>Status :</th>
                          <td>{{$restaurant->status}}</td> 
                        </tr>
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
                @if ($restaurant->is_active)
                  <a href="{{url(route('restaurant.deactivate' , $restaurant->id ))}}" class="btn btn-warning ml-3">   
                      De-Activate
                  </a>
                @else
                  <a href="{{url(route('restaurant.activate' , $restaurant->id ))}}" class="btn btn-success ml-3">
                      Activate
                  </a>
                @endif 
            </div>
        </div>

    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
@endsection
