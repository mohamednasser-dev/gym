@extends('admin.app')

@section('title' , 'Admin Panel Home')

@section('content')
    @php $rates =  \App\Rate::where('type','hall')->where('admin_approval',2)->get(); @endphp
    @php $coaches =  \App\Rate::where('type','coach')->where('admin_approval',2)->get(); @endphp
    <div class="row" >
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing">
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <a href="{{route('halls.show')}}">
                        <div class="card b-l-card-1 h-100" style="-webkit-box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); -moz-box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); ">
                            <img class="card-img-top" src="{{url('/')}}/admin/assets/img/holes.png" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title mt-2">
                                    {{ __('messages.holes') }}
                                    @if( count($rates) > 0 )
                                        <span class="unreadcount" title="{{ __('messages.new_rates') }}">
                                            <span class="insidecount">
                                                {{count($rates)}}
                                            </span>
                                        </span>
                                    @endif
                                </h5>
                                <p class="card-text mb-4">
                                </p>
                                <a href="{{route('halls.show')}}" class="btn btn-outline-warning mt-2">{{ __('messages.enter') }}</a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <a href="{{route('coaches.show')}}">
                        <div class="card b-l-card-1 h-100" style="-webkit-box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); -moz-box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); ">
                            <img class="card-img-top" src="{{url('/')}}/admin/assets/img/coaches.png" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title mt-2">{{ __('messages.coaches') }}
                                    @if( count($coaches) > 0 )
                                        <span class="unreadcount" title="{{ __('messages.new_rates') }}">
                                            <span class="insidecount">
                                                {{count($coaches)}}
                                            </span>
                                        </span>
                                    @endif
                                </h5>
                                <p class="card-text mb-4"></p>
                                <a href="{{route('coaches.show')}}" class="btn btn-outline-warning mt-2">{{ __('messages.enter') }}</a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card b-l-card-1 h-100" style="-webkit-box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); -moz-box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); ">
                        <img class="card-img-top" src="{{url('/')}}/admin/assets/img/stores.png" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title mt-2">{{ __('messages.stores') }}</h5>
                            <p class="card-text mb-4">

                            </p>
                            <button class="btn btn-outline-warning mt-2">{{ __('messages.enter') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

