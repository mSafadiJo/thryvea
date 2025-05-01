@extends('layouts.adminapp')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Session Recording</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('Session Recording List') }}</h3>
                        <div class="pull-right clearfix">
                            <form id="sort_table_form" action="" method="GET">
                                <div class="box-inline pad-rgt pull-left">
                                    <div class="select" style="min-width: 200px;">
                                        <select class="form-control select2" name="type" id="type" onchange="sort_list_table()">
                                            <option value="">Sort by</option>
                                            <option value="created_at,desc" @isset($col_name , $query) @if($col_name == 'created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>
                                            <option value="created_at,asc" @isset($col_name , $query) @if($col_name == 'created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>
                                            <option value="domain_name,desc" @isset($col_name , $query) @if($col_name == 'domain_name' && $query == 'desc') selected @endif @endisset>{{__('Domain (High > Low)')}}</option>
                                            <option value="domain_name,asc" @isset($col_name , $query) @if($col_name == 'domain_name' && $query == 'asc') selected @endif @endisset>{{__('Domain (Low > High)')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="box-inline pad-rgt pull-left">
                                    <div class="" style="min-width: 200px;">
                                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type & Enter">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {{--<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="datatable">--}}
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Visitor Id</th>
                                <th>Domain</th>
                                <th>TS</th>
                                <th>Created At</th>
                                <th>Video</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $Getrecording as $key => $recording )
                            <tr>
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$recording->visitor_id}}
                                </td>
                                <td>
                                    {{$recording->domain_name}}
                                </td>
                                <td>
                                    {{$recording->ts_name}}
                                </td>
                                <td>
                                    {{$recording->created_at}}
                                </td>
                                <td>
                                    <a href="/viewSessionRecordingVideo/{{$recording->visitor_id}}" target="_blank">Show Video</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="clearfix">
                            <div class="pull-right">
                                {{ $Getrecording->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
