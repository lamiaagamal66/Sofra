@inject('restaurant','App\Models\Restaurant')

<div class="form-group">
    <label for="image">Choose Image : </label>
    {!! Form::file('image', [
    // 'class'=>'form-control'
    ]) !!}
</div>
<div class="form-group">
    <label for="name">Offer Name</label>
    {!! Form::text('name',null,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group col-sm-6">
    <label for="starting_at">Starting at :</label>
    {!! Form::date('starting_at',null,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group col-sm-6">
    <label for="ending_at">Ending at</label>
    {!! Form::date('ending_at',null,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
    <label for="restaurant">Restaurant Name:</label>
    {!! Form::select('restaurant_id',$restaurants,null,[
         'placeholder' => 'Choose Restaurant',
         'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
        <label for="description">Add Description</label>
        {!! Form::textarea('description',null,[
            'class' => 'form-control'
        ]) !!}
    </div>
<div class="form-group">
    <button class="btn btn-primary" type="submit"> Submit</button>
</div>