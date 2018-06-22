@extends('voyager::master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', "Update Item Stock Code")

@section('page_header')
<h1 class="page-title">
    <i class="{{ $icon }}"></i>
    Update Item Stock Code
</h1>
@stop

@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-bordered">

                <form action="{{ route('stock-code-item.update', $data->sc) }}" class="form-edit-add" method="post" enctype="multipart/form-data" >
                    {{ method_field("PUT") }}
                    <!-- CSRF TOKEN -->
                    {{ csrf_field() }}

                    <div class="panel-body">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="name">S\C</label>
                                <input type="text" class="form-control" name="sc" disabled value="{{$data->sc}}">
                            </div>

                            <div class="form-group">
                                <label for="name">Item Name</label>
                                <input type="text" class="form-control" name="item_name" disabled value="{{$data->item_name}}">
                            </div>

                            <div class="form-group">
                                <label for="name">Desc 1</label>
                                <input type="text" class="form-control" name="desc1" disabled value="{{$data->desc1}}">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="name">Desc 2</label>
                                <input type="text" class="form-control" name="desc2" disabled value="{{$data->desc2}}">
                            </div>

                            <div class="form-group">
                                <label for="name">Desc 3</label>
                                <input type="text" class="form-control" name="desc3" disabled value="{{$data->desc3}}" >
                            </div>

                            <div class="form-group">
                                <label for="name">UOI</label>
                                <input type="text" class="form-control" name="uoi" disabled value="{{$data->uoi}}" >
                            </div>

                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Additional info</label>
                                <input type="text" class="form-control" name="info" value="{{$data->getInfo()}}">
                            </div>
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