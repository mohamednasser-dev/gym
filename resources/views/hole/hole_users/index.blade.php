@extends('hole.app')

@section('title' , __('messages.holes'))

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <h4>{{ __('messages.holes') }}</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <a class="btn btn-info" href="{{route('holes.create')}}"> {{ __('messages.add') }} </a>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">{{ __('messages.logo') }}</th>
                            <th class="text-center">{{ __('messages.hole_name') }}</th>
                            <th class="text-center">{{ __('messages.email') }}</th>
                            <th class="text-center">{{ __('messages.phone') }}</th>
                            <th class="text-center">{{ __('messages.status') }}</th>
                            <th class="text-center">{{ __('messages.details') }}</th>
                            @if(Auth::user()->update_data)
                                <th class="text-center">{{ __('messages.edit') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data as $row)
                            <tr>
                                <td class="text-center"><?=$i;?></td>
                                <td class="text-center"><img src="https://res.cloudinary.com/carsads/image/upload/w_100,q_100/v1581928924/{{ $row->logo }}"  /></td>
                                <td class="text-center">{{ $row->name }}</td>
                                <td class="text-center">{{ $row->email }}</td>
                                <td class="text-center">{{ $row->phone }}</td>
                                <td class="text-center">
                                    @if($row->status == 'active')
                                        <a href="/admin-panel/holes/block/{{$row->id}}">
                                            <span class="badge badge-danger">{{ __('messages.block') }}</span>
                                        </a>
                                    @else
                                        <a href="/admin-panel/holes/active/{{$row->id}}">
                                            <span class="badge badge-success">{{ __('messages.active') }}</span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center blue-color">
                                    <a href="/admin-panel/users/details/{{ $row->id }}"><i class="far fa-eye"></i></a>
                                </td>
                                @if(Auth::user()->update_data)
                                    <td class="text-center blue-color">
                                        <a href="/admin-panel/users/edit/{{ $row->id }}">
                                            <i class="far fa-edit"></i>
                                        </a>
                                    </td>
                                @endif
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '#btn_send', function () {
                user_id = $(this).data('user');
                $("#txt_user_id").val(user_id);
            });
        });
    </script>
@endsection

