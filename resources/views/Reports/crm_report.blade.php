@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">CRM Responses</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('FilterCRMExport') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="buyer_id">Buyer</label>
                                    <select class="form-control select2" name="buyer_id" id="buyer_id" data-placeholder="Choose ...">
                                        @if( !empty( $users ) )
                                            <option class="placeHolderSelect" value="" disabled selected>Please Choose</option>
                                            @foreach( $users as $user )
                                                <option value="{{ $user->id }}">{{ $user->user_business_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="environments">CRM Type</label>
                                    <select class="form-control select2" name="environments" id="environments" data-placeholder="Choose ...">
                                        <optgroup label="CRM Type">
                                            <option value="1"> PING </option>
                                            <option value="2"> POST </option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="Start Date">Start Date</label>
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" autocomplete="false" class="form-control StartDate start_date_pagination" value="{{ date('Y-m-d') }}"/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="End Date">End Date</label>
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" autocomplete="false" class="form-control EndDate end_date_pagination" value="{{ date('Y-m-d') }}"/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="Submit"> <br/></label>
                                            <button type="button" class="btn btn-primary col-lg-12 form-control" id="filterLeadTables">Search</button>
                                        </div>
                                    </div>
                                    @if( empty($permission_users) || in_array('3-4', $permission_users) )
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="Submit"> <br/></label>
                                            <button type="submit" class="btn btn-primary col-lg-12 form-control">Export</button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div style="margin-bottom: 5%;"></div>
                <!--Panel heading-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search" style="width: 22%;float:right;margin-bottom:1%;"/>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div id="table_data">
                            <div class="table-responsive">
                                <table id="pagination-table" class="table table-striped table-bordered"
                                       data-action="/CRMResponse/fetch_data?page=">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>lead Id</th>
                                        <th>PING Id</th>
                                        <th>Campaign Name</th>
                                        <th>Type</th>
                                        <th>Result</th>
                                        <th>Time</th>
                                        <th>Create Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
    @include('include.include_reports')
@endsection
