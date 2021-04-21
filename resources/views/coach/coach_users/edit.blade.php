@extends('coach.app')

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
            <form method="post" action="{{route('coaches.update',$data->id)}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-4">
                    <label for="name">{{ __('messages.name') }}</label>
                    <input required type="text" value="{{$data->name}}" name="name" class="form-control" id="name">
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
                    <label for="exampleFormControlTextarea1">{{ __('messages.about_coach') }}</label>
                    <textarea class="form-control" name="about_coach" id="exampleFormControlTextarea1" rows="4">
                        {{$data->about_coach}}
                    </textarea>
                </div>
                <div class="form-group mb-4 mt-3">
                    <label for="exampleFormControlFile1">{{ __('messages.image') }}</label>
                    <div class="row">
                        <div class="col-md-2 product_image">
                            <img style="width: 50%" src="{{image_cloudinary_url()}}{{ $data->image }}"  />
                        </div>
                    </div>
                    <div class="custom-file-container" data-upload-id="myFirstImage">
                        <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file" >
                            <input type="file" name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
                    </div>
                </div>
                <h4>{{ __('messages.avilable_times') }}</h4>
                <div class="form-group row">
                    <div class="col-md-6" id="special1_cont">
                        <label for="plan_price">{{ __('messages.from') }}</label>
                        <input type="time" value="{{$data->time_from}}" name="time_from" class="form-control">
                    </div>
                    <div class="col-md-6" id="special2_cont">
                        <label for="plan_price">{{ __('messages.to') }}</label>
                        <input type="time" value="{{$data->time_to}}" name="time_to" class="form-control">
                    </div>
                </div>
                <input type="submit" value="{{ __('messages.edit') }}" class="btn btn-primary">
            </form>
        </div>

@endsection
