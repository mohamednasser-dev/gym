@extends('shop_admin.app')
@section('title' , __('messages.show_offers_sections'))
@push('scripts')
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js" type="text/javascript"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("tbody#sortable").sortable({
            items: "tr",
            placeholder: "ui-state-hightlight",
            update: function () {
                var ids = $('tbody#sortable').sortable("serialize");
                var url = "{{ route('offers_control.sort') }}";

                $.post(url, ids + "&_token={{ csrf_token() }}");

                //  console.log(ids);


            }
        });
    </script>
@endpush
@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.show_offers_sections') . " ( " . __('messages.drag&drop') . " )" }}</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table id="without-print" class="table table-hover non-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{ __('messages.category_title') }}</th>
                            <th class="text-center">{{ __('messages.details') }}</th>
                            <th class="text-center">{{ __('messages.edit') }}</th>
                            <th class="text-center">{{ __('messages.delete') }}</th>
                        </tr>
                        </thead>
                        <tbody id="sortable">
                        <?php $i = 1; ?>
                        @foreach ($data['offers_sections'] as $section)
                            <tr id="id_{{ $section['id'] }}">
                                <td><?=$i;?></td>
                                <td>{{ App::isLocale('en') ? $section->title_en : $section->title_ar }}</td>
                                <td class="text-center blue-color"><a
                                        href="{{ route('offers_control.details', $section->id) }}"><i
                                            class="far fa-eye"></i></a></td>
                                <td class="text-center blue-color"><a
                                        href="{{ route('offers_control.edit', $section->id) }}"><i
                                            class="far fa-edit"></i></a></td>
                                <td class="text-center blue-color">
                                    <a href="{{ route('offers_control.delete', $section->id) }}"
                                       onclick='return confirm("{{ __('messages.are_you_sure') }}");'><i
                                            class="far fa-trash-alt"></i></a>
                                </td>
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
