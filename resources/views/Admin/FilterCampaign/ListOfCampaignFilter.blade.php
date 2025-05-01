@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Filter Campaign By ZipCode And Service</h4>
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
                    <form action="{{ route('FilterCampaignByZipCodeAndService') }}" method="get" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="service_id">Service Name</label>
                                            <select class="form-control select2" name="service_id" id="service_id" data-placeholder="Choose ...">
                                                <optgroup label="Service">
                                                    <option class="placeHolderSelect" value="" disabled selected>Please Choose</option>
                                                    @foreach( $services as $service )
                                                        <option value="{{ $service->service_campaign_id }}"
                                                                @if($service_id == $service->service_campaign_id) selected @endif>{{ $service->service_campaign_name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="campaign_type">Campaign Type</label>
                                            <select class="form-control select2" name="campaign_type" id="campaign_type" data-placeholder="Choose ...">
                                                <optgroup label="Campaign Type">
                                                    <option class="placeHolderSelect" value="" disabled selected>Please Choose</option>
                                                    <option value="CallCenter" @if($campaign_type == "CallCenter") selected @endif>Call Center</option>
                                                    @foreach( $types as $type )
                                                        <option value="{{ $type->campaign_types_id }}"
                                                                @if($campaign_type == $type->campaign_types_id) selected @endif>{{ $type->campaign_types_name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="zipcodes_reports_Filter">Zipcodes Filter</label>
                                            <input class="form-control" id="zipcodes_reports_Filter" style="width:100%;" placeholder="type a zipcode, scroll for more results" name="zipcodes_reports_Filter"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="submit">.</label>
                                        <button type="submit" class="btn btn-primary col-lg-12" name="submit_btn" value="submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div style="margin-bottom: 5%;"></div>
                    <!--Panel heading-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="dataAjaxTableCampaign">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="datatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Campaign Name</th>
                                        <th>Campaign state</th>
                                        <th>Campaign Type</th>
                                        <th>Buyer</th>
                                        <th>Service</th>
                                        <th>Claimer</th>
                                        <th>Last purchase</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ( $campaigns as $key => $campaign )
                                        <tr @if($key == 0) style="color: #229746" @endif>
                                            <td>{{ $campaign->campaign_id }}</td>
                                            <td>{{ $campaign->campaign_name }}</td>
                                            <td>
                                                @php
                                                    $campaign->state_code = trim(str_replace(['"', '[', ']'], '', $campaign->state_code));
                                                @endphp
                                                <span style='cursor: pointer;' onclick='return show_script_text_data("{{ $campaign->campaign_id }}", "Campaign States", "campaign_states-", "html")'>{{ \Illuminate\Support\Str::limit($campaign->state_code , $limit = 10, $end = '...') }}</span>
                                                <div id="campaign_states-{{ $campaign->campaign_id }}" style="display: none;">{{ str_replace(",", ", ", $campaign->state_code) }}</div>
                                            </td>
                                            <td>{{ $campaign->campaign_types_name }}</td>
                                            <td>{{ $campaign->user_business_name }}</td>
                                            <td>{{ $campaign->service_campaign_name }}</td>
                                            <td>{{ $campaign->acc_manager_username }}</td>
                                            <td>{{ $campaign->Last_Pay }}</td>
                                            <td>{{ date('Y/m/d', strtotime($campaign->created_at)) }}</td>
                                            <td>
                                                @if( empty($permission_users) || in_array('7-2', $permission_users) || in_array('7-7', $permission_users) )
                                                    <a href="{{ route("ShowAdminCampaignEdit", $campaign->campaign_id) }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                @endif
                                                @if(in_array($campaign->campaign_Type, array(4,5,6,7)))
                                                    <span style="caesar: pointer" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Script Text For Call Center" data-trigger="hover" data-animation="false"
                                                          onclick="show_script_text_data('{{ $campaign->campaign_id }}', 'Script Text For Call Center', 'show_script_text_data-', 'val');">
                                                         <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                                                    </span>
                                                    <textarea id="show_script_text_data-{{ $campaign->campaign_id }}" style="display: none;">{{ $campaign->script_text }}</textarea>

                                                    {{--Time Delivery Text--}}
                                                    <span style="caesar: pointer" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Time Delivery" data-trigger="hover" data-animation="false"
                                                          onclick="show_script_text_data('{{ $campaign->campaign_id }}', 'Time Delivery', 'show_time-', 'html');">
                                                         <i class="fa fa-clock-o"></i>
                                                    </span>
                                                    <div id="show_time-{{ $campaign->campaign_id }}" style="display: none;"><span>Time Zone: {{
                                                            ($campaign->campaign_time_delivery_timezone  == "5" || empty($campaign->campaign_time_delivery_timezone) ? "Eastern Time" : "") .
                                                            ($campaign->campaign_time_delivery_timezone  == "6" ? "Central Time" : "") .
                                                            ($campaign->campaign_time_delivery_timezone  == "7" ? "Mountain Time" : "") .
                                                            ($campaign->campaign_time_delivery_timezone  == "8" ? "Pacific Time" : "")
                                                            }}
                                                        </span>
                                                        @if($campaign->campaign_time_delivery_status == "0")
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>Day</th>
                                                                    <th>From</th>
                                                                    <th>TO</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr><td>Sunday</td><?php echo ($campaign->status_sun != "1" ? "<td>" . date('h:i A', strtotime($campaign->start_sun))  . "</td> <td>" . date('h:i A', strtotime($campaign->end_sun)) . "</td>" : "<td> OFF</td><td> OFF</td>"); ?>
                                                                <tr><td>Monday</td><?php echo ($campaign->status_mon != "1" ? "<td>" . date('h:i A', strtotime($campaign->start_mon))  . "</td> <td>" . date('h:i A', strtotime($campaign->end_mon)) . "</td>" : "<td> OFF</td><td> OFF</td>"); ?>
                                                                <tr><td>Tuesday</td><?php echo ($campaign->status_tus != "1" ? "<td>" . date('h:i A', strtotime($campaign->start_tus))  . "</td> <td>" . date('h:i A', strtotime($campaign->end_tus)) . "</td>" : "<td> OFF</td><td> OFF</td>"); ?>
                                                                <tr><td>Wednesday</td><?php echo ($campaign->status_wen != "1" ? "<td>" . date('h:i A', strtotime($campaign->start_wen))  . "</td> <td>" . date('h:i A', strtotime($campaign->end_wen)) . "</td>" : "<td> OFF</td><td> OFF</td>"); ?>
                                                                <tr><td>Thursday</td><?php echo ($campaign->status_thr != "1" ? "<td>" . date('h:i A', strtotime($campaign->start_thr))  . "</td> <td>" . date('h:i A', strtotime($campaign->end_thr)) . "</td>" : "<td> OFF</td><td> OFF</td>"); ?>
                                                                <tr><td>Friday</td><?php echo ($campaign->status_fri != "1" ? "<td>" . date('h:i A', strtotime($campaign->start_fri))  . "</td> <td>" . date('h:i A', strtotime($campaign->end_fri)) . "</td>" : "<td> OFF</td><td> OFF</td>"); ?>
                                                                <tr><td>Saturday</td><?php echo ($campaign->status_sat != "1" ? "<td>" . date('h:i A', strtotime($campaign->start_sat))  . "</td> <td>" . date('h:i A', strtotime($campaign->end_sat)) . "</td>" : "<td> OFF</td><td> OFF</td>"); ?>
                                                                </tbody>
                                                            </table>
                                                        @else
                                                            <br/><span>Time Delivery : 24/7</span>
                                                        @endif
                                                    </div>

                                                    {{-- email text --}}
                                                    <span style="caesar: pointer" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Email & Transfer numbers" data-trigger="hover" data-animation="false"
                                                          onclick="show_script_text_data('{{ $campaign->campaign_id }}', 'Email & Transfer numbers', 'show_email-', 'html');">
                                                         <i class="fa fa-envelope"></i>
                                                    </span>
                                                    <div id="show_email-{{ $campaign->campaign_id }}" style="display: none;">Emails:
                                                        <ul>
                                                            <li>Email a1: {{ $campaign->email1 }}</li>
                                                            <li>Email CC1: {{ $campaign->email2 }}</li>
                                                            <li>Email CC2: {{ $campaign->email3 }}</li>
                                                            <li>Email CC3: {{ $campaign->email4 }}</li>
                                                            <li>Email CC4: {{ $campaign->email5 }}</li>
                                                            <li>Email CC5: {{ $campaign->email6 }}</li>
                                                            @if($campaign->campaign_Type == "6")
                                                                <li>Transfer Numbers: {{ $campaign->transfer_numbers }}</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->

    <!-- Modal -->
    <button type="button" id="script_campaign_model_btn" class="btn btn-primary" data-toggle="modal" data-target="#script_campaign_model" style="display: none;"></button>
    <div class="modal fade bd-example-modal-lg" id="script_campaign_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="script_campaign_span"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div id="script_campaign_div"></div>
                                <style>
                                    #script_campaign_div > div {
                                        white-space: break-spaces !important;
                                    }
                                    #script_campaign_div{
                                        word-break: break-all;
                                        white-space: break-spaces;
                                    }
                                </style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function show_script_text_data(id, title, id_text, type) {
            if(type == "val"){
                var text = $('#'+id_text+id).val();
            } else {
                var text = $('#'+id_text+id).html();
            }
            $('#script_campaign_span').html(title);
            $('#script_campaign_div').html(text);
            $('#script_campaign_model_btn').click();
        }
    </script>
    @include('include.include_reports')
@endsection
