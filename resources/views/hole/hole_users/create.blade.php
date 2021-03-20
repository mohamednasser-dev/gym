@extends('hole.app')

@section('title' , __('messages.add_new_hole'))

@section('content')
<div class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.add_new_hole') }}</h4>
             </div>
        </div>
    </div>
    @if (session('status'))
        <div class="alert alert-danger mb-4" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
            <strong>Error!</strong> {{ session('status') }} </button>
        </div>
    @endif

    <form method="post" action="{{route('holes.store')}}" enctype="multipart/form-data">
     @csrf
    <div class="form-group mb-4">
        <label for="name">{{ __('messages.hole_name') }}</label>
        <input required type="text" name="name" class="form-control" id="name">
    </div>
    <div class="form-group mb-4">
        <label for="email">{{ __('messages.user_email') }}</label>
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
        <div class="form-group mb-4 mt-3">
            <label for="exampleFormControlFile1">{{ __('messages.logo') }}</label>

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
            <label>{{ __('messages.upload') }} ({{ __('messages.multiple_images') }}) <a
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

    <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
</form>
</div>

@endsection
