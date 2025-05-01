@extends('layouts.adminapp')

@section('content')
<!-- Page 1-->
<div class="row">
    <div class="col-lg-12">
        <div class="card-box page-title-box">
            <h4 class="header-title">Buyers Campaigns</h4>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="panel">
                <div class="panel-heading bord-btm clearfix pad-all h-100">
                    <h3 class="panel-title pull-left pad-no">{{ __('List OF Campaigns') }}</h3>
                    {{--<div class="pull-right clearfix">--}}
                        {{--<form id="sort_table_form" action="" method="GET">--}}
                            {{--<div class="box-inline pad-rgt pull-left">--}}
                                {{--<div class="select" style="min-width: 200px;">--}}
                                    {{--<select class="form-control select2" name="type" id="type" onchange="sort_list_table()">--}}
                                        {{--<option value="">Sort by</option>--}}
                                        {{--<option value="users.created_at,desc" @isset($col_name , $query) @if($col_name == 'users.created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>--}}
                                        {{--<option value="users.created_at,asc" @isset($col_name , $query) @if($col_name == 'users.created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>--}}
                                        {{--<option value="users.username,desc" @isset($col_name , $query) @if($col_name == 'users.username' && $query == 'desc') selected @endif @endisset>{{__('Username (High > Low)')}}</option>--}}
                                        {{--<option value="users.username,asc" @isset($col_name , $query) @if($col_name == 'users.username' && $query == 'asc') selected @endif @endisset>{{__('Username (Low > High)')}}</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="box-inline pad-rgt pull-left">--}}
                                {{--<div class="" style="min-width: 200px;">--}}
                                    {{--<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type & Enter">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="dataAjaxTableCampaign">
                        {{--<table class="table table-striped table-bordered" cellspacing="0" width="100%">--}}
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Campaign Name</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($users) )
                                @foreach( $users as $key => $val )
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->user_business_name }}</td>
                                        <td>{{ $val->created_at }}</td>
                                        <td>
                                            <a href="/Admin/Campaign/{{ $val->id }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="List Of Campaign" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-list-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{--<div class="clearfix">--}}
                            {{--<div class="pull-right">--}}
                                {{--{{ $users->appends(request()->input())->links() }}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Of Page 1-->
@endsection
