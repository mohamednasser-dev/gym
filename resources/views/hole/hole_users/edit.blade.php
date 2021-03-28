@extends('hole.app')
@section('title' , __('messages.edit'))
@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.edit') }}</h4>
                    </div>
                </div>
            </div>
            <form method="post" action="{{route('holes.update',$data->id)}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-4">
                    <label for="name">{{ __('messages.hole_name') }}</label>
                    <input required type="text" name="name" value="{{$data->name}}" class="form-control" id="name">
                </div>
                <div class="form-group mb-4">
                    <label for="email">{{ __('messages.email') }}</label>
                    <input required type="Email" value="{{$data->email}}" class="form-control" id="email" name="email">
                </div>
                <div class="form-group mb-4">
                    <label for="password">{{ __('messages.password') }}</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group mb-4">
                    <label for="phone">{{ __('messages.phone') }}</label>
                    <input required type="phone" value="{{$data->phone}}" name="phone" class="form-control" id="phone">
                </div>
                <div class="form-group mb-4">
                    <label for="exampleFormControlTextarea1">{{ __('messages.about_hole') }}</label>
                    <textarea class="form-control" name="about_hole" id="exampleFormControlTextarea1" rows="4">
                        {{$data->about_hole}}
                    </textarea>
                </div>
                <div class="form-group mb-4 mt-3">
                    <label for="exampleFormControlFile1">{{ __('messages.logo') }}</label>
                    <div class="row">
                        <div class="col-md-2 product_image">
                            <img style="width: 50%" src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $data->logo }}"  />
                        </div>
                    </div>
                    <div class="custom-file-container" data-upload-id="mySecondImage">
                        <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file" >
                            <input type="file" name="logo" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview">
                        </div>
                    </div>
                </div>
                <h4>{{ __('messages.cover') }}</h4>
                <div class="row">
                    <div class="col-md-2 product_image">
                        <img style="width: 50%" src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $data->cover }}"  />
                    </div>
                </div>
                <div class="custom-file-container" data-upload-id="myFirstImage">
                    <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a
                            href="javascript:void(0)" class="custom-file-container__image-clear"
                            title="Clear Image">x</a></label>
                    <label class="custom-file-container__custom-file">
                        <input type="file" name="cover"
                               class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                    </label>
                    <div class="custom-file-container__image-preview"></div>
                </div>
                <h4>{{ __('messages.appointment') }}</h4>
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

                        html += ' <div id="" class="form-group row">';
                        html += ' ';
                        //error here
                        html += '<div class="col-sm-3 "><input  name="branches[' + i + '][title_ar]" type="text" class="form-control"></div>';
                        html += '<div class="col-sm-3 "><input  name="branches[' + i + '][title_en]" type="text" class="form-control"></div>';
                        html += '<div class="col-sm-3 "><input  name="branches[' + i + '][longitude]" type="text" class="form-control"></div>';
                        html += '<div class="col-sm-3 "><input  name="branches[' + i + '][latitude]" type="text" class="form-control"></div>';
                        html += "</div>";
                        $('#parent').append(html);

                        i++;
                    });
                });
            </script>
@endsection
