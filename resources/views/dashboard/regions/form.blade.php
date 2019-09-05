@inject('city','App\Models\City')

<div class="form-group">
    <label for="name">Region Name</label>
    {!! Form::text('name',null,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
    {!! Form::select('city_id',$cities,null,[
         'placeholder' => 'Choose City',
         'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
    <button class="btn btn-primary" type="submit"> Submit</button>
</div>