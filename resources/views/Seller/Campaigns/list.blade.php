@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Campaigns</h4>
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
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="service_id">Service Name</label>
                                    <select class="select2 form-control select2-multiple" multiple name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                        <optgroup label="Service">
                                            @if( !empty( $data['services'] ) )
                                                @foreach( $data['services'] as $service )
                                                    <option value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="buyer_id">Sellers</label>
                                    <select class="select2 form-control select2-multiple" multiple name="seller_id[]" id="seller_id" data-placeholder="Choose ...">
                                        <optgroup label="Buyer Name">
                                            @if( !empty( $data['sellers'] ) )
                                                @foreach( $data['sellers'] as $seller )
                                                    <option value="{{ $seller->id }}"
                                                            @if( $data['id'] == $seller->id ) selected @endif>{{ $seller->user_business_name }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="buyer_id">.</label>
                                <button type="button" class="btn btn-primary col-lg-12" id="FilterSellerCampaignAdmainAjax">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div style="margin-bottom: 5%;"></div>
                <h6>List Of Campaigns</h6>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="dataAjaxTableCampaign">
                            <table class="table table-striped table-bordered" cellspacing="0" width="100%"
                                   @if( empty($permission_users) || in_array('12-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vendor Id</th>
                                    <th>Campaign Name</th>
                                    <th>Campaign Type</th>
                                    <th>Seller</th>
                                    <th>Service</th>
                                    <th>Profit</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Active</th>
                                    @if( empty($permission_users) || in_array('12-1', $permission_users) || in_array('12-2', $permission_users) || in_array('12-3', $permission_users) || in_array('12-7', $permission_users) )
                                        <th>Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @if( !empty($campaigns) )
                                    @foreach( $campaigns as $campaign )
                                        <tr>
                                            <td>{{ $campaign->campaign_id }}</td>
                                            <td>{{ $campaign->vendor_id }}</td>
                                            <td>{{ $campaign->campaign_name }}</td>
                                            <td>
                                                @if( $campaign->is_ping_account == 1 )
                                                    PING & POST
                                                @else
                                                    Direct POST
                                                @endif
                                            </td>
                                            <td>{{ $campaign->user_business_name }}</td>
                                            <td>{{ $campaign->service_campaign_name }}</td>
                                            <td>{{ $campaign->campaign_budget_bid_exclusive }}</td>
                                            <td>{{ date('Y/m/d', strtotime($campaign->created_at)) }}</td>
                                            @if( empty($permission_users) || in_array('12-2', $permission_users) )
                                                <td>
                                                    <select name="campaign_status" class="form-control" style="height: unset;width: 80%;" id="admincampaign_status_table_Ajax_changing-{{ $campaign->campaign_id }}"
                                                            onchange="return admincampaign_status_table_Ajax_changing('{{ $campaign->campaign_id }}');"
                                                            @if( !( empty($permission_users) || in_array('12-5', $permission_users) ) && $campaign->campaign_status_id == 3 ) disabled @endif >
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
                                            @else
                                                @if ($campaign->campaign_status_id == 1 )
                                                    <td>Running</td>
                                                @elseif ($campaign->campaign_status_id == 4 )
                                                    <td>Pause</td>
                                                @else
                                                    <td>Stopped</td>
                                                @endif
                                            @endif
                                            <td>
                                                @if( $campaign->campaign_visibility == 1 )
                                                    Active
                                                @else
                                                    Not Active
                                                @endif
                                            </td>
                                            @if( empty($permission_users) || in_array('12-1', $permission_users) || in_array('12-2', $permission_users) || in_array('12-3', $permission_users) || in_array('12-7', $permission_users) )
                                                <td>
                                                    @if( $campaign->campaign_visibility == 1 )
                                                        @if( empty($permission_users) || in_array('12-2', $permission_users) || in_array('12-7', $permission_users) )
                                                            <a href="{{ route("Campaigns.edit", $campaign->campaign_id) }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        @endif
                                                        @if( empty($permission_users) || in_array('12-1', $permission_users) )
                                                            <span style="cursor: pointer;color: #36c736;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Clone" data-trigger="hover" data-animation="false">
                                                                <i class="mdi mdi-plus-circle-outline" data-toggle="modal" data-target="#con-close-modal"
                                                                   onclick="return document.getElementById('campaign_id_menu').value = '{{ $campaign->campaign_id }}';"></i>
                                                            </span>
                                                        @endif
                                                        @if( empty($permission_users) || in_array('12-3', $permission_users) )
                                                            <form method="post" action="{{ route( 'Campaigns.destroy', $campaign->campaign_id ) }}" class="DeleteForm" role="form" id="DeleteForm{{ $campaign->campaign_id }}">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <span class="DeleteTableDataTable"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                                                      onclick='confirmMsgForDelete("{{ $campaign->campaign_id }}");'>
                                                                    <i class="fa fa-trash-o"></i>
                                                                </span>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                            <form action="{{ route('Admin.Seller.Campaigns.AddClone') }}" method="post">
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
                </div>

                @if( empty($permission_users) || in_array('12-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="addedSectionLogo pull-right"
                                 onclick='window.location.href ="{{ route('Campaigns.create') }}";'>
                                <i class="mdi mdi-plus-circle-outline"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
