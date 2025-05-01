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
    {{--Loader--}}
    <div class="loader" style="display: none;"></div>

    <div class="un_loading_loader">
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
                                        <label for="buyer_id">Buyers</label>
                                        <select class="select2 form-control select2-multiple" multiple name="buyer_id[]" id="buyer_id" data-placeholder="Choose ...">
                                            <optgroup label="Buyer Name">
                                                @if( !empty( $data['buyers'] ) )
                                                    @foreach( $data['buyers'] as $buyer )
                                                        <option value="{{ $buyer->id }}"
                                                            @if( $data['id'] == $buyer->id ) selected @endif>{{ $buyer->user_business_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="buyer_id">.</label>
                                    <button type="button" class="btn btn-primary col-lg-12" id="FilterCampaignAdmainAjax">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="margin-bottom: 5%;"></div>
                    <!--Panel heading-->

                    <div class="row">
                        <div class="col-lg-6">
                            <h6>List Of Campaigns</h6>
                        </div>
                        <div class="col-lg-6 pull-right">
                            @if( empty($permission_users) || in_array('7-1', $permission_users) )
                                <a href="/AdminCampaignCreate?buyer_id={{ $data['id'] }}" class="btn btn-rounded btn-info pull-right"><i class="fa fa-plus"></i> {{__('Add New Campaign')}}</a>
                            @endif
                            @if( empty($permission_users) || in_array('7-4', $permission_users) )
                                <form action="{{ route('Admin_Campaign_Export') }}" method="post">
                                    {{ csrf_field() }}
                                    <button type="submit" style="margin-right: 1%;" class="btn btn-rounded btn-info pull-right"><i class="fa fa-download"></i> Export Data</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="dataAjaxTableCampaign">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="datatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Campaign Name</th>
                                        <th>Campaign Type</th>
                                        <th>Buyer</th>
                                        <th>Service</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Active</th>
                                        @if( empty($permission_users) || in_array('7-1', $permission_users) || in_array('7-2', $permission_users) || in_array('7-3', $permission_users) || in_array('7-6', $permission_users) || in_array('7-7', $permission_users))
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if( !empty($campaigns) )
                                        @foreach( $campaigns as $campaign )
                                            <tr>
                                                <td>{{ $campaign->campaign_id }}</td>
                                                <td>{{ $campaign->campaign_name }}</td>
                                                <td>{{ $campaign->campaign_types_name }}</td>
                                                <td>{{ $campaign->user_business_name }}</td>
                                                <td>{{ $campaign->service_campaign_name }}</td>
                                                <td>{{ date('Y/m/d', strtotime($campaign->created_at)) }}</td>
                                                @if( empty($permission_users) || in_array('7-2', $permission_users) )
                                                    <td>
                                                        <select name="campaign_status" class="form-control" style="height: unset;width: 80%;" id="admincampaign_status_table_Ajax_changing-{{ $campaign->campaign_id }}"
                                                                onchange="return admincampaign_status_table_Ajax_changing('{{ $campaign->campaign_id }}');"
                                                            @if( !( empty($permission_users) || in_array('7-5', $permission_users) ) && $campaign->campaign_status_id == 3 ) disabled @endif >
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
                                                    @if($campaign->campaign_status_id == 1)
                                                        <td>Running</td>
                                                    @elseif($campaign->campaign_status_id == 3)
                                                        <td>Pending</td>
                                                    @elseif($campaign->campaign_status_id == 4)
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
                                                @if( empty($permission_users) || in_array('7-1', $permission_users) || in_array('7-2', $permission_users) || in_array('7-3', $permission_users) || in_array('7-6', $permission_users) || in_array('7-7', $permission_users) )
                                                    <td>
                                                        @if( $campaign->campaign_visibility == 1 )
                                                            {{--<a href="AdminCampaign/{{ $campaign->campaign_id }}/details" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">--}}
                                                                {{--<i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>--}}
                                                            {{--</a>--}}
                                                            @if( empty($permission_users) || in_array('7-2', $permission_users) || in_array('7-7', $permission_users) )
                                                                <a href="{{ route("ShowAdminCampaignEdit", $campaign->campaign_id) }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                                <a href="{{ route("Admin.Campaign.ZipCodes.List", $campaign->campaign_id) }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="List OF ZipCodes" data-trigger="hover" data-animation="false">
                                                                    <i class="fa fa-list"></i>
                                                                </a>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('7-2', $permission_users) )
                                                                <span style="cursor: pointer;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="CallTolls Campaign Id" data-trigger="hover" data-animation="false">
                                                                    <i class="fa fa-pencil-square" data-toggle="modal" data-target="#campaign_file_id"
                                                                       onclick="return openmenufunctioncampaign_file('{{ $campaign->campaign_id }}', '{{ $campaign->file_calltools_id }}', '{{ $campaign->campaign_calltools_id }}');"></i>
                                                                </span>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('7-6', $permission_users) )
                                                                <span style="cursor: pointer;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Send Test Lead" data-trigger="hover" data-animation="false"
                                                                      onclick="return sendTestLeadForCamp('{{ $campaign->campaign_id }}');">
                                                                    <i class="fa fa-share" ></i>
                                                                </span>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('7-1', $permission_users) )
                                                                <span style="cursor: pointer;color: #36c736;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Clone" data-trigger="hover" data-animation="false">
                                                                    <i class="mdi mdi-plus-circle-outline" data-toggle="modal" data-target="#con-close-modal"
                                                                       onclick="return document.getElementById('campaign_id_menu').value = '{{ $campaign->campaign_id }}';"></i>
                                                                </span>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('7-3', $permission_users) )
                                                                <form style="display: inline-block" action="{{ route('AdminCampaignDelete') }}" method="post" id="DeleteCampaignForm-{{ $campaign->campaign_id }}">
                                                                    {{ csrf_field() }}
                                                                    <input type="hidden" class="form-control" value="{{ $campaign->campaign_id }}" name="campaign_id">
                                                                </form>
                                                                <span style="cursor: pointer;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                                                      onclick="return DeleteCampaignForm('{{ $campaign->campaign_id }}');">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </span>
                                                            @endif
                                                        @else
                                                            <a href="AdminCampaign/{{ $campaign->campaign_id }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                                                <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                                                            </a>
                                                            <a href="{{ route("Admin.Campaign.ZipCodes.List", $campaign->campaign_id) }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="List OF ZipCodes" data-trigger="hover" data-animation="false">
                                                                <i class="fa fa-list"></i>
                                                            </a>
                                                            @if( empty($permission_users) || in_array('7-1', $permission_users) )
                                                                <span style="cursor: pointer;color: #36c736;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Clone" data-trigger="hover" data-animation="false">
                                                                    <i class="mdi mdi-plus-circle-outline" data-toggle="modal" data-target="#con-close-modal"
                                                                       onclick="return document.getElementById('campaign_id_menu').value ='{{ $campaign->campaign_id }}';"></i>
                                                                </span>
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
                                                <form action="{{ route('AdminCampaignAddClone') }}" method="post">
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

                    <!-- Modal -->
                    <div class="modal" id="campaign_file_id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add CallTools Campaign Id</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <form action="{{ route('AddCallToolsCampaignId') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="Campaign_name">CallTools Campaign Id<span class="requiredFields">*</span></label>
                                                                <input type="text" class="form-control" value="" id="campaign_file_idinputs" name="campaign_file_id" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="File_name">CallTools File Id<span class="requiredFields">*</span></label>
                                                                <input type="text" class="form-control" value="" id="campaign_file_idinputs_go" name="campaign_file_id_go" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="hidden" class="form-control" value="" id="campaign_id_menu2" name="campaign_id" required>
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
                    <script>
                        function openmenufunctioncampaign_file(campaign_id, file_calltools_id, campaign_calltools_id) {
                            document.getElementById('campaign_id_menu2').value = campaign_id;
                            document.getElementById('campaign_file_idinputs').value = file_calltools_id;
                            document.getElementById('campaign_file_idinputs_go').value = campaign_calltools_id;
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
