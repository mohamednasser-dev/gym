
@extends('hole.app')

@section('title' , __('messages.add_new_hole'))

@section('content')
    <?php
        $lat = '29.280331923084315';
        $lng = '47.95993041992187';
    ?>
{{--    //--------------------------------------------------------}}
<div class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.add_new_hole') }}</h4>
             </div>
        </div>
    </div>
    <form method="post" action="{{route('halls.store')}}" enctype="multipart/form-data">
     @csrf
    <div class="form-group mb-4">
        <label for="name">{{ __('messages.hole_name') }}</label>
        <input required type="text" name="name" class="form-control" id="name">
    </div>
    <div class="form-group mb-4">
        <label for="email">{{ __('messages.email') }}</label>
        <input required type="Email" class="form-control" id="email" name="email">
    </div>
    <div class="form-group mb-4">
        <label for="password">{{ __('messages.password') }}</label>
        <input required type="password" class="form-control" id="password" name="password">
    </div>
    <div class="form-group mb-4">
        <label for="phone">{{ __('messages.phone') }}</label>
        <input required type="phone" name="phone" class="form-control" id="phone">
    </div>
    <div class="form-group mb-4">
        <label for="exampleFormControlTextarea1">{{ __('messages.about_hole') }}</label>
        <textarea class="form-control" name="about_hole" id="exampleFormControlTextarea1" rows="4"></textarea>
    </div>
    <div class="form-group mb-4 mt-3">
        <h4>{{ __('messages.logo') }}</h4>
        <div class="custom-file-container" data-upload-id="mySecondImage">
            <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
            <label class="custom-file-container__custom-file" >
                <input type="file" required name="logo" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                <span class="custom-file-container__custom-file__custom-file-control"></span>
            </label>
            <div class="custom-file-container__image-preview">
            </div>
        </div>
    </div>
    <h4>{{ __('messages.cover') }}</h4>
    <div class="custom-file-container" data-upload-id="myFirstImage">
        <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a
                href="javascript:void(0)" class="custom-file-container__image-clear"
                title="Clear Image">x</a></label>
        <label class="custom-file-container__custom-file">
            <input type="file" required name="cover"
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
                    <input type="checkbox" class="custom-control-input" name="male" value="male" id="male_hole">
                    <label class="custom-control-label" for="male_hole">{{ __('messages.male_hole') }}</label>
                </div>
            </div>
        </div>
        <div class="col-md-4" id="special1_cont">
            <label for="plan_price">{{ __('messages.from') }}</label>
            <input  type="time" name="male_hole_from" class="form-control">
        </div>
        <div class="col-md-4" id="special2_cont">
            <label for="plan_price">{{ __('messages.to') }}</label>
            <input  type="time" name="male_hole_to" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="plan_price"> &nbsp; </label>
            <div class="form-check pl-0">
                <div class="custom-control custom-checkbox checkbox-info">
                    <input type="checkbox" class="custom-control-input" name="female" value="female" id="female_hole">
                    <label class="custom-control-label" for="female_hole">{{ __('messages.female_hole') }}</label>
                </div>
            </div>
        </div>
        <div class="col-md-4" id="special1_cont">
            <label for="plan_price">{{ __('messages.from') }}</label>
            <input  type="time" name="female_hole_from" class="form-control">
        </div>
        <div class="col-md-4" id="special2_cont">
            <label for="plan_price">{{ __('messages.to') }}</label>
            <input  type="time" name="female_hole_to" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="plan_price"> &nbsp; </label>
            <div class="form-check pl-0">
                <div class="custom-control custom-checkbox checkbox-info">
                    <input type="checkbox" class="custom-control-input" name="mix" value="mix" id="mix_hole">
                    <label class="custom-control-label" for="mix_hole">{{ __('messages.mix_hole') }}</label>
                </div>
            </div>
        </div>
        <div class="col-md-4" id="special1_cont">
            <label for="plan_price">{{ __('messages.from') }}</label>
            <input  type="time" name="mix_hole_from" class="form-control">
        </div>
        <div class="col-md-4" id="special2_cont">
            <label for="plan_price">{{ __('messages.to') }}</label>
            <input  type="time" name="mix_hole_to" class="form-control">
        </div>
    </div>
    <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
</form>
</div>
@endsection
@section('scripts')
        <script>
            function myMap() {
                var mapProp= {
                    center:new google.maps.LatLng({{$lat}},{{$lng}}),
                    zoom:5,
                };
                var map = new google.maps.Map(document.getElementById("us1"),mapProp);
            }
        </script>
       <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDPN_XufKy-QTSCB68xFJlqtUjHQ8m6uUY&callback=myMap"></script>



        {{--    <script src="{{url('admin/assets/js/locationpicker.jquery.js')}}"></script>--}}
        <script  src="{{url('/')}}/admin/assets/js/locationpicker.jquery.js"></script>
        <script>
            $('#us1').locationpicker({
                location: {
                    latitude: {{$lat}},
                    longitude: {{$lng}}
                },

                radius: 300,
                markerIcon: "{{url('/images/map-marker.png')}}",
                inputBinding: {
                    latitudeInput: $('#lat'),
                    longitudeInput: $('#lng')
                    // radiusInput: $('#us2-radius'),
                    // locationNameInput: $('#us2-address')
                }

            });
        </script>
@endsection
