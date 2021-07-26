@extends('store.app')

@section('title' , __('messages.add_new_governorate'))

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_governorate') }}</h4>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="title_ar">{{ __('messages.name_ar') }}</label>
                        <input required type="text" name="title_ar" class="form-control" id="title_ar"
                               placeholder="{{ __('messages.name_ar') }}" value="">
                    </div>
                    <div class="form-group mb-4">
                        <label for="title_en">{{ __('messages.name_en') }}</label>
                        <input required type="text" name="title_en" class="form-control" id="title_en"
                               placeholder="{{ __('messages.name_en') }}" value="">
                    </div>

                    <input type="submit" value="{{ __('messages.add') }}" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
@endsection
