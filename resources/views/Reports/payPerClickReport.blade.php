@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Pay Per Click Campaign Reports</h4>
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
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('export_lead_data') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date"
                                           value="{{ date('Y-m-d') }}" autocomplete="false"
                                           class="form-control start_date_pagination"/>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date"
                                           value="{{ date('Y-m-d') }}" autocomplete="false"
                                           class="form-control end_date_pagination"/>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div style="margin-bottom: 5%;"></div>
                <h4 class="header-title">Pay Per Click Campaign Reports</h4>
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
                                       data-action="/Report/PayPerClick_fetch_data?page=">
                                    <thead>
                                    <tr>
                                        <th>Campaign ID</th>
                                        <th>Campaign Name</th>
                                        <th>Number of Click</th>
                                        <th>Number of Conversions</th>
                                        <th>Total Cost</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($campaigns as $campaign)
                                        <tr>
                                            <td>{{ $campaign->campaign_id }}</td>
                                            <td>{{ $campaign->campaign_name }}</td>
                                            <td>{{ (!empty($total_click_leads[$campaign->campaign_id]) ? $total_click_leads[$campaign->campaign_id] : 0) }}</td>
                                            <td>{{ (!empty($total_conversions_leads[$campaign->campaign_id]) ? $total_conversions_leads[$campaign->campaign_id] : 0) }}</td>
                                            <td>${{ (!empty($sum_click_leads[$campaign->campaign_id]) ? $sum_click_leads[$campaign->campaign_id] : 0) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                Showing {{($campaigns->currentPage()-1)* $campaigns->perPage()+($campaigns->total() ? 1:0)}}
                                to {{($campaigns->currentPage()-1)*$campaigns->perPage()+count($campaigns)}}
                                of {{$campaigns->total()}} Results
                                {!! $campaigns->links() !!}
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
