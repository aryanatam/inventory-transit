@extends('voyager::master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('app.generic.update_data'))

@section('page_header')
<h1 class="page-title">
    <i class="{{ $icon }}"></i>
    {{ __('app.generic.update_data') }}
</h1>
@stop

@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-bordered">

                <form action="{{ route('stock-code-item.update-data') }}" class="form-edit-add" method="post" enctype="multipart/form-data" >
                    <!-- CSRF TOKEN -->
                    {{ csrf_field() }}

                    <div class="panel-body">
                        <div class="form-group>
                            <label for="name">Import CSV file of Stock Code Items</label>
                            <input type="file" name="import_file" accept=".csv"/>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary save pull-right">Save</button>
                        <div class="clearfix"></div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@stop

@section('javascript')
@stop
