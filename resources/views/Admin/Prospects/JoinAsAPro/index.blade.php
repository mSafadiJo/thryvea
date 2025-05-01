@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Join As A Pro / Contractors</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('List OF Join As A Pro / Contractors') }}</h3>
                        <div class="pull-right clearfix">
                            {{--<form id="sort_table_form" action="" method="GET">--}}
                            {{--<div class="box-inline pad-rgt pull-left">--}}
                            {{--<div class="select" style="min-width: 200px;">--}}
                            {{--<select class="form-control select2" name="type" id="type" onchange="sort_list_table()">--}}
                            {{--<option value="">Sort by</option>--}}
                            {{--<option value="created_at,desc" @isset($col_name , $query) @if($col_name == 'created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>--}}
                            {{--<option value="created_at,asc" @isset($col_name , $query) @if($col_name == 'created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>--}}
                            {{--<option value="full_name,desc" @isset($col_name , $query) @if($col_name == 'full_name' && $query == 'desc') selected @endif @endisset>{{__('Full Name (High > Low)')}}</option>--}}
                            {{--<option value="full_name,asc" @isset($col_name , $query) @if($col_name == 'full_name' && $query == 'asc') selected @endif @endisset>{{__('Full Name (Low > High)')}}</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="box-inline pad-rgt pull-left">--}}
                            {{--<div class="" style="min-width: 200px;">--}}
                            {{--<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type & Enter">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</form>--}}
                            @if( empty($permission_users) || in_array('17-4', $permission_users) )
                                <form action="{{ route('Admin.JoinAsAPro.Export') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="box-inline pad-rgt pull-left">
                                        <div class="" style="min-width: 200px;">
                                            <button type="submit" class="btn btn-rounded btn-info pull-right"><i class="fa fa-download"></i> Export Data</button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {{--<table class="table table-striped table-bordered" cellspacing="0" width="100%">--}}
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Contact</th>
                                <th>Business Name</th>
                                <th>Services</th>
                                <th>IP Address</th>
                                <th>Traffic Sources</th>
                                <th>Source</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($prospects as $key => $prospect)
                                <tr>
                                    {{--<td>{{ ($key+1) + ($prospects->currentPage() - 1)*$prospects->perPage() }}</td>--}}
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $prospect->full_name }}</td>
                                    <td>
                                        {{ $prospect->email }}<br/>
                                        {{ $prospect->phone_number }}
                                        @if( !empty($prospect->city) )
                                            <br/>{{ $prospect->city }}
                                        @endif
                                    </td>
                                    <td>{{ $prospect->business_name }}</td>
                                    <td>{{ $prospect->services }}</td>
                                    <td>{{ $prospect->ip_address }}</td>
                                    <td>
                                        {{ $prospect->source }}<br/>
                                        TS: {{ $prospect->google_ts }}<br/>
                                        G: {{ $prospect->google_g }}<br/>
                                        K: {{ $prospect->google_k }}<br/>
                                        C: {{ $prospect->google_c }}<br/>
                                    </td>
                                    <td>{{ $prospect->resource }}</td>
                                    {{--                                    <td>--}}
                                    {{--                                        <a style="cursor: pointer;" class="trx_description_a" data-id="{{ $prospect->id }}">{{Str::limit($prospect->note , $limit = 20, $end = '...') }}</a>--}}
                                    {{--                                        <span style="display: none" id="description-{{ $prospect->id }}">{{ $prospect->note }}</span>--}}
                                    {{--                                    </td>--}}
                                    <td>{{  date('Y/m/d h:i A', strtotime($prospect->created_at)) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{--<div class="clearfix">--}}
                        {{--<div class="pull-right">--}}
                        {{--{{ $prospects->appends(request()->input())->links() }}--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->

    <button type="hidden" data-toggle="modal" data-target="#myModal-descriptions" id="button-model-trx-desc" style="display: none;">.</button>
    <div class="modal fade" id="myModal-descriptions" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Note Details</h4>
                </div>
                <div class="modal-body">
                    <p id="descriptions-text"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection
