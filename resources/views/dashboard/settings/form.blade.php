@inject('setting','App\Models\Setting')

<div class="form-group">
    <label for="about_app">About App</label>
    {!! Form::text('about_app',null,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
    <label for="commision">Commission</label>
    {!! Form::number('commision',null,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
    <label for="commision_msg">Commision Message</label>
    {!! Form::text('commision_msg',null,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
    <label for="bank_msg">Bank Message</label>
    {!! Form::text('bank_msg',null,[
        'class' => 'form-control'
    ]) !!}
</div>
<div class="form-group">
    <button class="btn btn-primary" type="submit"> Update Settings</button>
</div>




