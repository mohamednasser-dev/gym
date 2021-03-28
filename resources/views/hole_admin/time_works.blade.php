@extends('hole_admin.app')
@section('title' , __('messages.add_new_hole'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.appointment') }}</h4>
                    </div>
                </div>
            </div>
            <form method="post" action="{{route('holes.update',$data->id)}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="plan_price"> &nbsp; </label>
                        <div class="form-check pl-0">
                            <div class="custom-control custom-checkbox checkbox-info">
                                <input type="checkbox" @if($time_male != null) checked @endif class="custom-control-input" name="male" value="male" id="male_hole">
                                <label class="custom-control-label" for="male_hole">{{ __('messages.male_hole') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="special1_cont">
                        <label for="plan_price">{{ __('messages.from') }}</label>
                        <input  type="time" @if($time_male != null) value="{{$time_male->time_from}}" @endif name="male_hole_from" class="form-control">
                    </div>
                    <div class="col-md-4" id="special2_cont">
                        <label for="plan_price">{{ __('messages.to') }}</label>
                        <input  type="time"  @if($time_male != null) value="{{$time_male->time_from}}" @endif name="male_hole_to" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="plan_price"> &nbsp; </label>
                        <div class="form-check pl-0">
                            <div class="custom-control custom-checkbox checkbox-info">
                                <input type="checkbox" @if($time_female != null) checked @endif class="custom-control-input" name="female" value="female" id="female_hole">
                                <label class="custom-control-label" for="female_hole">{{ __('messages.female_hole') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="special1_cont">
                        <label for="plan_price">{{ __('messages.from') }}</label>
                        <input  type="time" @if($time_female != null) value="{{$time_female->time_from}}" @endif name="female_hole_from" class="form-control">
                    </div>
                    <div class="col-md-4" id="special2_cont">
                        <label for="plan_price">{{ __('messages.to') }}</label>
                        <input  type="time" @if($time_female != null) value="{{$time_female->time_from}}" @endif name="female_hole_to" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="plan_price"> &nbsp; </label>
                        <div class="form-check pl-0">
                            <div class="custom-control custom-checkbox checkbox-info">
                                <input type="checkbox" @if($time_mix != null) checked @endif class="custom-control-input" name="mix" value="mix" id="mix_hole">
                                <label class="custom-control-label" for="mix_hole">{{ __('messages.mix_hole') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="special1_cont">
                        <label for="plan_price">{{ __('messages.from') }}</label>
                        <input  type="time" @if($time_mix != null) value="{{$time_mix->time_to}}" @endif name="mix_hole_from" class="form-control">
                    </div>
                    <div class="col-md-4" id="special2_cont">
                        <label for="plan_price">{{ __('messages.to') }}</label>
                        <input  type="time" @if($time_mix != null) value="{{$time_mix->time_to}}" @endif name="mix_hole_to" class="form-control">
                    </div>
                </div>
                <h4>{{ __('messages.branches') }}</h4>
                <div class="form-group row">
                    <div class="card-body parent" style='text-align:right' id="parent">
                        <button type='button' class="btn btn-primary mb-2 mr-2" value='Add Button' id='addButton'>
                            {{ __('messages.add_new_branch') }}
                            <i class="fa fa-plus"></i></button>
                        <div id="" class="form-group row">
                            <div class="col-sm-3 ">{{ __('messages.branch_ar') }}</div>
                            <div class="col-sm-3 ">{{ __('messages.branch_en') }}</div>
                            <div class="col-sm-3 ">{{ __('messages.lang') }}</div>
                            <div class="col-sm-3 ">{{ __('messages.lat') }}</div>
                        </div>
                        @foreach($branches as $key =>$branch)
                            <div id="" class="form-group row">
                                <div class="col-sm-3 ">
                                    {{ Form::select('rows[' .$key . '][base_id]',App\Models\Base::pluck('name','id'),$product_comp->base_id
                                    ,["class"=>"form-control custom-select col-12 " ]) }}
                                    <input  name="branches[' + i + '][title_ar]" value="{{$branch->title_ar}}" type="text" class="form-control">
                                </div>
                                <div class='col-sm-6'>

                                    {{ Form::number('rows[' .$key . '][quantity]',$product_comp->quantity,["step" =>'0.01',"class"=>"form-control" ,"required",'placeholder'=>trans('admin.quantity')]) }}

                                </div>
                            </div>
                        @endforeach
                        <div class="panel" style='text-align:right'>
                        </div>
                    </div>
                </div>

                <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
            </form>
        </div>
        @endsection
        @section('scripts')
            <script>
                $(document).ready(function () {
                    var i = 0;
                    $("#addButton").click(function () {
                        var html = '';
                        html += ' <div id="' + i + '" class="form-group row">';
                        html += ' ';
                        html += '<div class="col-sm-3 "><input id="title_ar' + i + '"  name="branches[' + i + '][title_ar]" type="text" class="form-control"></div>';
                        html += '<div class="col-sm-3 "><input  name="branches[' + i + '][title_en]" type="text" class="form-control"></div>';
                        html += '<div class="col-sm-2 "><input  name="branches[' + i + '][longitude]" type="text" class="form-control"></div>';
                        html += '<div class="col-sm-2 "><input  name="branches[' + i + '][latitude]" type="text" class="form-control"></div>';
                        html += "<div class='col-sm-2'> <button onclick='myFun("+i+")' class='btn btn-danger' type='button' data-t-id=" + i + "   id='delete'>" +
                            "                                        <i class='fa fa-trash'></i></button></div>";
                        html += "</div>";
                        $('#parent').append(html);
                        i++;
                    });
                });
                function myFun(j) {
                    document.getElementById(j).style.display = 'none';
                    // document.getElementById("title_ar" + j ).val = '';
                }

            </script>
@endsection
