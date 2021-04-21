@extends('coach.app')

@section('title' , __('messages.add_new_coach'))

@section('content')
<div class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.add_new_coach') }}</h4>
             </div>
        </div>
    </div>
    @if (session('status'))
        <div class="alert alert-danger mb-4" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
            <strong>Error!</strong> {{ session('status') }} </button>
        </div>
    @endif

    <form method="post" action="{{route('coaches.store')}}" enctype="multipart/form-data">
     @csrf
    <div class="form-group mb-4">
        <label for="name">{{ __('messages.name') }}</label>
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
        <label for="exampleFormControlTextarea1">{{ __('messages.about_coach') }}</label>
        <textarea class="form-control" name="about_coach" id="exampleFormControlTextarea1" rows="4"></textarea>
    </div>
    <div class="form-group mb-4 mt-3">
        <label for="exampleFormControlFile1">{{ __('messages.image') }}</label>
        <div class="custom-file-container" data-upload-id="myFirstImage">
            <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
            <label class="custom-file-container__custom-file" >
                <input type="file" required name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
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
            <input type="time" name="time_from" class="form-control">
        </div>
        <div class="col-md-6" id="special2_cont">
            <label for="plan_price">{{ __('messages.to') }}</label>
            <input type="time" name="time_to" class="form-control">
        </div>
    </div>
    <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
</form>
</div>

@endsection
