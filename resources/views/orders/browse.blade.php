@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
    </h1>
    @can('add',app($dataType->model_name))
    <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new">
        <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
    </a>
    @endcan
    @can('delete',app($dataType->model_name))
    @include('voyager::partials.bulk-delete')
    @endcan
    @can('edit',app($dataType->model_name))
    @if(isset($dataType->order_column) && isset($dataType->order_display_column))
    <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary">
        <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
    </a>
    @endif
    @endcan
    @include('voyager::multilingual.language-selector')
</div>
@stop

@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    @can('delete',app($dataType->model_name))
                                    <th>
                                        <input type="checkbox" class="select_all">
                                    </th>
                                    @endcan
                                    <th>Project Name</th>
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                <tr>
                                    @can('delete',app($dataType->model_name))
                                    <td>
                                        <input type="checkbox" name="row_id" id="checkbox_{{ $row->id }}" value="{{ $row->id }}">
                                    </td>
                                    @endcan
                                    <td>{{ $row->project_name }}</td>
                                    <td class="no-sort no-click" id="bread-actions">
                                        @can('delete',app($dataType->model_name))
                                        <a href="{{ route('voyager.orders.destroy', $row->id) }}" title="View" class="btn btn-sm btn-danger pull-right delete"><i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">
                                        </a>
                                        @endcan
                                        @can('edit',app($dataType->model_name))
                                        <a href="{{ route('voyager.orders.edit', $row->id) }}" title="View" class="btn btn-sm btn-primary pull-right edit"><i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">
                                        </a>
                                        @endcan
                                        @can('read',app($dataType->model_name))
                                        <a href="{{ route('voyager.orders.show', $row->id) }}" title="View" class="btn btn-sm btn-warning pull-right view"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Single delete modal --}}
<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                    aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->display_name_singular) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @stop

    @section('css')
    <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
    @stop

    @section('javascript')
    <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var table = $('#dataTable').DataTable({!! json_encode(
                array_merge([
                    "order" => [],
                    "language" => __('voyager::datatable'),
                    "columnDefs" => [['searchable' =>  false, 'targets' => -1 ]],
                ],
                config('voyager.dashboard.data_tables', []))
                , true) !!});

            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            });
        });

        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });
    </script>
    @stop
