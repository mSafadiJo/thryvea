@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Marketing Platforms</h4>
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
                <!--Panel heading-->
                @if( empty($permission_users) || in_array('13-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12 pull-right">
                            <a href="{{ route("Platforms.create") }}" class="btn btn-rounded btn-info pull-right"><i class="fa fa-plus"></i> {{__('Add New Platform')}}</a>
                        </div>
                    </div>
                @endif
                <br>

                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('List OF Platforms') }}</h3>
{{--                        <div class="pull-right clearfix">--}}
{{--                            <form id="sort_table_form" action="" method="GET">--}}
{{--                                <div class="box-inline pad-rgt pull-left">--}}
{{--                                    <div class="select" style="min-width: 200px;">--}}
{{--                                        <select class="form-control select2" name="type" id="type" onchange="sort_list_table()">--}}
{{--                                            <option value="">Sort by</option>--}}
{{--                                            <option value="created_at,desc" @isset($col_name , $query) @if($col_name == 'created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>--}}
{{--                                            <option value="created_at,asc" @isset($col_name , $query) @if($col_name == 'created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>--}}
{{--                                            <option value="name,desc" @isset($col_name , $query) @if($col_name == 'name' && $query == 'desc') selected @endif @endisset>{{__('Name (High > Low)')}}</option>--}}
{{--                                            <option value="name,asc" @isset($col_name , $query) @if($col_name == 'name' && $query == 'asc') selected @endif @endisset>{{__('Name (Low > High)')}}</option>--}}
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
                                <th>Lead Source</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $platforms as $key => $val )
                                <tr>
{{--                                    <td>{{ ($key+1) + ($platforms->currentPage() - 1)*$platforms->perPage() }}</td>--}}
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ $val->lead_source }}</td>
                                    <td>{{ date('Y/m/d', strtotime($val->created_at)) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
{{--                        <div class="clearfix">--}}
{{--                            <div class="pull-right">--}}
{{--                                {{ $platforms->appends(request()->input())->links() }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
