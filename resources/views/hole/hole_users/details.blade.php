@extends('hole.app')

@section('title' , __('messages.hole_details'))

@section('content')

    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">

                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.hole_details') }}</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table table-bordered mb-4">
                        <tbody>
                        <tr>
                            <td class="label-table"> {{ __('messages.hole_name') }}</td>
                            <td>{{ $data->name }}</td>
                        </tr>
                        <tr>
                            <td class="label-table"> {{ __('messages.email') }} </td>
                            <td>{{ $data->email }}</td>
                        </tr>
                        <tr>
                            <td class="label-table"> {{ __('messages.phone') }} </td>
                            <td> {{ $data->phone }} </td>
                        </tr>
                        </tbody>
                    </table>
                    <h4>{{ __('messages.appointment') }}</h4>
                    @php $time_works = \App\Hole_time_work::where('hole_id',$data->id)->get(); @endphp
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('messages.type') }}</th>
                                <th class="text-center">{{ __('messages.from') }}</th>
                                <th class="text-center">{{ __('messages.to') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($time_works as $row)
                            <tr>
                                <td class="text-center">
                                    @if($row->type == 'male')
                                        {{ __('messages.male_hole') }}
                                    @elseif($row->type == 'female')
                                        {{ __('messages.female_hole') }}
                                    @elseif($row->type == 'mix')
                                        {{ __('messages.mix_hole') }}
                                    @endif
                                </td>
                                <td class="text-center">{{ date('g:i a', strtotime($row->time_from )) }}</td>
                                <td class="text-center">{{ date('g:i a', strtotime($row->time_to )) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <label for="">{{ __('messages.logo') }}</label><br>
                    <div class="row">
                        <div class="col-md-2 product_image">
                            <img style="width: 100%"
                                 src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $data->logo }}"/>
                        </div>
                    </div>
                    <label style="margin-top: 20px" for="">{{ __('messages.cover') }}</label><br>
                    <div class="row">
                        <div style="position : relative" class="col-md-2 product_image">
                            <img width="100%"
                                 src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $data->cover }}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection



