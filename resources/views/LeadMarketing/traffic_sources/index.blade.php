@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Marketing Traffic Sources</h4>
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

                @if( empty($permission_users) || in_array('13-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12 pull-right">
                            <a href="{{ route("TrafficSources.create") }}" class="btn btn-rounded btn-info pull-right"><i class="fa fa-plus"></i> {{__('Add New Traffic Sources')}}</a>
                        </div>
                    </div>
                @endif
                <br>

                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('List OF Traffic Sources') }}</h3>
{{--                        <div class="pull-right clearfix">--}}
{{--                            <form id="sort_table_form" action="" method="GET">--}}
{{--                                <div class="box-inline pad-rgt pull-left">--}}
{{--                                    <div class="select" style="min-width: 200px;">--}}
{{--                                        <select class="form-control select2" name="type" id="type" onchange="sort_list_table()">--}}
{{--                                            <option value="">Sort by</option>--}}
{{--                                            <option value="marketing_platforms.created_at,desc" @isset($col_name , $query) @if($col_name == 'marketing_platforms.created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>--}}
{{--                                            <option value="marketing_platforms.created_at,asc" @isset($col_name , $query) @if($col_name == 'marketing_platforms.created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>--}}
{{--                                            <option value="marketing_platforms.name,desc" @isset($col_name , $query) @if($col_name == 'marketing_platforms.name' && $query == 'desc') selected @endif @endisset>{{__('Platform  Name (High > Low)')}}</option>--}}
{{--                                            <option value="marketing_platforms.name,asc" @isset($col_name , $query) @if($col_name == 'marketing_platforms.name' && $query == 'asc') selected @endif @endisset>{{__('Platform  Name (Low > High)')}}</option>--}}
{{--                                            <option value="lead_traffic_sources.name,desc" @isset($col_name , $query) @if($col_name == 'lead_traffic_sources.name' && $query == 'desc') selected @endif @endisset>{{__('TS  Name (High > Low)')}}</option>--}}
{{--                                            <option value="lead_traffic_sources.name,asc" @isset($col_name , $query) @if($col_name == 'lead_traffic_sources.name' && $query == 'asc') selected @endif @endisset>{{__('TS  Name (Low > High)')}}</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="box-inline pad-rgt pull-left">--}}
{{--                                    <div class="" style="min-width: 200px;">--}}
{{--                                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type & Enter">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
{{--                        <table class="table table-striped table-bordered" cellspacing="0" width="100%">--}}
                        <table  class="table table-striped table-bordered" cellspacing="0" width="100%"
                                @if( empty($permission_users) || in_array('13-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Platform Name</th>
                                <th>Created At</th>
                                @if( empty($permission_users) || in_array('13-2', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $traffic_sources as $key => $val )
                                <tr>
{{--                                    <td>{{ ($key+1) + ($traffic_sources->currentPage() - 1)*$traffic_sources->perPage() }}</td>--}}
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ $val->platform_name }}</td>
                                    <td>{{ date('Y/m/d', strtotime($val->created_at)) }}</td>
                                    @if( empty($permission_users) || in_array('13-2', $permission_users) )
                                        <td>
                                            <span class="EditTableDataTable"  onclick='window.location.href= "{{ route('TrafficSources.edit', $val->id) }}"'
                                                  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-pencil"></i>
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
{{--                        <div class="clearfix">--}}
{{--                            <div class="pull-right">--}}
{{--                                {{ $traffic_sources->appends(request()->input())->links() }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
