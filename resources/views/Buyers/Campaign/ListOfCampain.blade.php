@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 1-->
    {{--    <div class="row">--}}
    {{--        <div class="col-lg-12">--}}
    {{--            <div class="card-box page-title-box">--}}
    {{--                <h4 class="header-title">Dashboard</h4>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <style>
        .input-group-sm>.input-group-btn>select.btn:not([size]):not([multiple]), .input-group-sm>select.form-control:not([size]):not([multiple]), .input-group-sm>select.input-group-addon:not([size]):not([multiple]), select.form-control-sm:not([size]):not([multiple]) {
            height: unset;
        }
    </style>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box CampaignsBuyers">
                <h6>List Of Campaigns</h6>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                {{--<th>ID</th>--}}
                                <th>Campaign Name</th>
                                <th>Campaign Type</th>
                                <th>Service</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($campaigns) )
                                @foreach( $campaigns as $campaign )
                                    <tr>
                                        {{--<td>{{ $campaign->campaign_id }}</td>--}}
                                        <td>{{ $campaign->campaign_name }}</td>
                                        <td>{{ $campaign->campaign_types_name }}</td>
                                        <td>{{ $campaign->service_campaign_name }}</td>
                                        <td>{{ date('Y/m/d', strtotime($campaign->created_at)) }}</td>
                                        <td>
                                            <select name="campaign_status" class="form-control" style="height: unset;width: 100%;" id="campaign_status_table_Ajax_changing-{{ $campaign->campaign_id }}"
                                                    onchange="return campaign_status_table_Ajax_changing('{{ $campaign->campaign_id }}');"
                                                    @if( $campaign->campaign_status_id == 3 ) disabled @endif >
                                                @if(!empty($campaign_status))
                                                    @foreach($campaign_status as $status)
                                                        @if( $status->campaign_status_id == 3 )
                                                            @if( $campaign->campaign_status_id == 3 )
                                                                <option value="{{ $status->campaign_status_id }}-{{ $campaign->campaign_id }}"
                                                                        @if( $campaign->campaign_status_id == $status->campaign_status_id ) selected @endif
                                                                >{{ $status->campaign_status_name }}</option>
                                                            @endif
                                                        @else
                                                            <option value="{{ $status->campaign_status_id }}-{{ $campaign->campaign_id }}"
                                                                    @if( $campaign->campaign_status_id == $status->campaign_status_id ) selected @endif
                                                            >{{ $status->campaign_status_name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            {{--<a href="Campaign/{{ $campaign->campaign_id }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">--}}
                                            {{--<i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>--}}
                                            {{--</a>--}}
                                            <a href="Campaign/Edit/{{ $campaign->campaign_id }}  " class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <span style="cursor: pointer;color: #36c736;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Clone" data-trigger="hover" data-animation="false">
                                                <i class="mdi mdi-plus-circle-outline" data-toggle="modal" data-target="#con-close-modal"
                                                   onclick="return document.getElementById('campaign_id_menu').value ='{{ $campaign->campaign_id }}';"></i>
                                            </span>
                                            {{--<form style="display: inline-block" action="{{ route('CampaignDelete') }}" method="post" id="DeleteCampaignForm-{{ $campaign->campaign_id }}">--}}
                                            {{--{{ csrf_field() }}--}}
                                            {{--<input type="hidden" class="form-control" value="{{ $campaign->campaign_id }}" name="campaign_id">--}}
                                            {{--</form>--}}
                                            {{--<span style="cursor: pointer;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"--}}
                                            {{--onclick="return DeleteCampaignForm('{{ $campaign->campaign_id }}');">--}}
                                            {{--<i class="fa fa-trash-o"></i>--}}
                                            {{--</span>--}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Add Campaign</h4>
                                </div>
                                <div class="modal-body row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <form action="{{ route('CampaignAddClone') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="Campaign_name">Campaign Name<span class="requiredFields">*</span></label>
                                                                <input type="text" class="form-control" value="" name="campaign_name" required>
                                                                <input type="hidden" class="form-control" value="" id="campaign_id_menu" name="campaign_id" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary waves-effect waves-light pull-right">Add</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal -->
                </div>
            </div>
        </div>
    </div>
@endsection
