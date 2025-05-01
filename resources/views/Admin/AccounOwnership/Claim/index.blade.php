@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Claims</h4>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('List OF Claims') }}</h3>
{{--                        <div class="pull-right clearfix">--}}
{{--                            <form id="sort_table_form" action="" method="GET">--}}
{{--                                <div class="box-inline pad-rgt pull-left">--}}
{{--                                    <div class="select" style="min-width: 200px;">--}}
{{--                                        <select class="form-control select2" name="type" id="type" onchange="sort_list_table()">--}}
{{--                                            <option value="">Sort by</option>--}}
{{--                                            <option value="buyers_claims.created_at,desc" @isset($col_name , $query) @if($col_name == 'buyers_claims.created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>--}}
{{--                                            <option value="buyers_claims.created_at,asc" @isset($col_name , $query) @if($col_name == 'buyers_claims.created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>--}}
{{--                                            <option value="users.username,desc" @isset($col_name , $query) @if($col_name == 'users.username' && $query == 'desc') selected @endif @endisset>{{__('Username (High > Low)')}}</option>--}}
{{--                                            <option value="users.username,asc" @isset($col_name , $query) @if($col_name == 'users.username' && $query == 'asc') selected @endif @endisset>{{__('Username (Low > High)')}}</option>--}}
{{--                                            <option value="buyers_claims.admin_name,desc" @isset($col_name , $query) @if($col_name == 'buyers_claims.admin_name' && $query == 'desc') selected @endif @endisset>{{__('Admin Name (High > Low)')}}</option>--}}
{{--                                            <option value="buyers_claims.admin_name,asc" @isset($col_name , $query) @if($col_name == 'buyers_claims.admin_name' && $query == 'asc') selected @endif @endisset>{{__('Admin Name (Low > High)')}}</option>--}}
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
                        <table  class="table table-striped table-bordered" cellspacing="0" width="100%" id="datatable">
                            <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Type</th>
                                <th>Admin Name</th>
                                <th>Claim</th>
                                <th>Created At</th>
                                <th>Confirmed by</th>
                                <th>Confirmed At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($claims) )
                                @foreach($claims as $key => $claim)
                                    <tr>
                                        <td>{{ $claim->username }} ({{ $claim->user_business_name }})</td>
                                        <td>
                                            @if( $claim->type == 2 )
                                                Sellers
                                            @else
                                                Buyers
                                            @endif
                                        </td>
                                        <td>{{ $claim->admin_name }}</td>
                                        <td>{{ $claim->claim_type }}</td>
                                        <td>{{ date('Y/m/d', strtotime($claim->created_at)) }}</td>
                                        <td>
                                            @if( $claim->is_claim == 1 )
                                                {{ $claim->confirmed_by_name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if( $claim->is_claim == 1 )
                                                {{ date('Y/m/d', strtotime($claim->confirmed_at)) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if( $claim->is_claim == 0 )
                                                <button type="button" class="btn btn-success" id="agreeClaim"
                                                    onclick="return changeClaimStatus('1', '{{ $claim->buyers_claims_id }}');">Agree</button>
                                            @else
                                                <button type="button" class="btn btn-danger" id="ignoreClaim"
                                                        onclick="return changeClaimStatus('0', '{{ $claim->buyers_claims_id }}');">Ignore</button>
                                            @endif
                                            / <button type="button" class="btn btn-danger" id="deleteClaim"
                                                      onclick="return changeClaimStatus('2', '{{ $claim->buyers_claims_id }}');">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
{{--                        <div class="clearfix">--}}
{{--                            <div class="pull-right">--}}
{{--                                {{ $claims->appends(request()->input())->links() }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
