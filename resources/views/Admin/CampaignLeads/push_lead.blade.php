@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Push Lead</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Push Lead</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('success');?>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('error');?>
                        @endif
                    </div>
                </div>
                <form action="{{ route('Admin.PushLead.submit', $id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="fname">Lead ID</label>
                                <input type="text" class="form-control" id="fname" value="{{ $id }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="lname">Lead Name</label>
                                <input type="text" class="form-control" id="lname" value="{{ $leadsCustomer->lead_fname }} {{ $leadsCustomer->lead_lname }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="lname">Lead Service</label>
                                <input type="text" class="form-control" id="lname" value="{{ $leadsCustomer->service_campaign_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="fname">Zipcode</label>
                                <input type="text" class="form-control" id="fname" value="{{ $leadsCustomer->zip_code_list }}" readonly>
                            </div>
                        </div>
                        <?php
                        $city_arr = explode('=>', $leadsCustomer->city_name);
                        ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="lname">City Name</label>
                                <input type="text" class="form-control" id="lname" value="{{ $city_arr[0] }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="lname">State Name</label>
                                <input type="text" class="form-control" id="lname" value="{{ $leadsCustomer->state_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fname">Campaigns</label>
                                <select class="select2 form-control" name="campaign_id" required id="campaign_id" data-placeholder="Choose ...">
                                    <optgroup label="Campaigns">
                                        @if( !empty( $campaigns ) )
                                            @foreach( $campaigns as $campaign )
                                                @php
                                                    $totalAmmountUser_new_list = \App\TotalAmount::where('user_id', $campaign->user_id)
                                                           ->first(['total_amounts_value']);

                                                    $totalAmmountUser_new = 0;
                                                    if( !empty($totalAmmountUser_new_list) ){
                                                        $totalAmmountUser_new = $totalAmmountUser_new_list->total_amounts_value;
                                                    }

                                                    $is_ping_account = $campaign->is_ping_account;

                                                    $status = 0;
                                                    if( $is_ping_account != 1 ){
                                                        if($campaign->campaign_budget_bid_exclusive != 0 && $campaign->campaign_budget_bid_shared != 0){
                                                            if ($campaign->campaign_budget_bid_exclusive <= $campaign->campaign_budget_bid_shared) {
                                                                if ($campaign->campaign_budget_bid_exclusive - $campaign->virtual_price > $totalAmmountUser_new) {
                                                                    $status = 1;
                                                                }
                                                            } else {
                                                                if ($campaign->campaign_budget_bid_shared - $campaign->virtual_price > $totalAmmountUser_new) {
                                                                    $status = 1;
                                                                }
                                                            }
                                                        } else if($campaign->campaign_budget_bid_exclusive != 0 && $campaign->campaign_budget_bid_shared == 0){
                                                            if ($campaign->campaign_budget_bid_exclusive - $campaign->virtual_price > $totalAmmountUser_new) {
                                                                $status = 1;
                                                            }
                                                        } else {
                                                            if ($campaign->campaign_budget_bid_shared - $campaign->virtual_price > $totalAmmountUser_new) {
                                                                $status = 1;
                                                            }
                                                        }
                                                    } else {
                                                        if ($totalAmmountUser_new <= 50) {
                                                            $status = 1;
                                                        }
                                                    }

                                                    $payment_type_method_status = $campaign->payment_type_method_status;
                                                    $payment_type_method_id = $campaign->payment_type_method_id;
                                                    $payment_type_method_limit = filter_var($campaign->payment_type_method_limit, FILTER_SANITIZE_NUMBER_INT);

                                                    $status2 = 0;
                                                    if (($payment_type_method_status == 1 && in_array($payment_type_method_id, ['3', '4', '5', '6', '7', '8']))) {
                                                        if ($totalAmmountUser_new <= 0) {
                                                            if ($payment_type_method_limit - abs($totalAmmountUser_new) <= 50) {
                                                                $status2 = 1;
                                                            }
                                                        }
                                                    } else {
                                                        if ($status == 1) {
                                                            $status2 = 1;
                                                        }
                                                    }
                                                @endphp
                                                <option value="{{ $campaign->campaign_id }}">
                                                    {{ $campaign->campaign_name }} @if( $status2 == 1 ) (Low Budget) @endif
                                                </option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fname">Type</label>
                                <select class="select2 form-control" name="type" required id="type" data-placeholder="Choose ...">
                                    <optgroup label="Type">
                                        <option value="Shared">Shared</option>
                                        <option value="Exclusive">Exclusive</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="agent_name">Agent Name</label>
                                <select class="select2 form-control" name="agent_name" id="agent_name" data-placeholder="Choose ...">
                                    <optgroup label="Agent Name">
                                        <option value=""></option>
                                        @foreach($agents as $val)
                                            <option value="{{ $val->user_business_name }}">{{ $val->username }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="call_center_source">call center source</label>
                                <select class="select2 form-control" name="call_center_source" id="call_center_source" data-placeholder="Choose ...">
                                    <optgroup label="call center source">
                                        <option value=""></option>
                                        @foreach($call_center_source as $val)
                                            <option value="{{ $val->name }}" @if($leadsCustomer->traffic_source == $val->name) selected @endif>{{ $val->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="submit" value="Push Lead" class="btn btn-primary form-control" onClick="this.form.submit(); this.disabled=true; this.value='Sending…'; ">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
