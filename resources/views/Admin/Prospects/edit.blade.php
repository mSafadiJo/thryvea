@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Edit Prospects</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Prospect Details</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('Prospects.update', $prospect->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="firstname">First Name<span class="requiredFields">*</span></label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder=""
                                       required="" value="{{ $prospect->user_first_name }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lastname">Last Name<span class="requiredFields">*</span></label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="" required=""
                                       value="{{ $prospect->user_last_name }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mobilenumber">Tel Number<span class="requiredFields">*</span></label>
                                <input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="" required="" value="{{ $prospect->user_mobile_number }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="" value="{{ $prospect->email }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="firstname">Business Owner</label>
                                <input type="text" class="form-control" id="owner" name="owner" placeholder="" value="{{ $prospect->user_owner }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lastname">Business Name<span class="requiredFields">*</span></label>
                                <input type="text" class="form-control" id="businessname" name="businessname" placeholder="" required="" value="{{ $prospect->user_business_name }}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="hidden" value="{{ $listOfIds['state_id'] }}" id="State_id_aj_userProfile"/>
                                <input type="hidden" value="{{ $listOfIds['city_id'] }}" id="City_id_aj_userProfile"/>
                                <input type="hidden" value="{{ $listOfIds['zip_code'] }}" id="zip_code_aj_userProfile"/>
                                <input type="hidden" id="zip_code_id" name="zip_code_id" required="" value="{{ $prospect->zip_code_id }}">
                                <label for="state">State<span class="requiredFields">*</span></label>
                                <select class="select2 form-control" id="stateList" name="state" required data-placeholder="Choose ...">
                                    <optgroup label="States">
                                        <option>--Choose State--</option>
                                        <?php
                                        if ( isset($states) && !empty($states) ){
                                        foreach( $states as $state ){
                                        $idstate = $codestate = '';
                                        if ( isset( $state['state_id']) ){
                                            $idstate = $state['state_id'];
                                        }
                                        if ( isset( $state['state_code']) ){
                                            $codestate = $state['state_code'];
                                        }
                                        ?>
                                        <option value="<?php echo $idstate; ?>"
                                        <?php
                                            if( $listOfIds['state_id'] == $idstate )   {
                                                echo "selected";
                                            }
                                            ?>
                                        ><?php echo $codestate; ?></option>
                                        <?php
                                        }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="city">City</label>
                                <select class="select2 form-control" id="cityList" name="city" data-placeholder="Choose ...">
                                    <optgroup label="States">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="zipcode">Zip-Code</label>
                                <select class="select2 form-control" id="zipcodeList" name="zipcode" data-placeholder="Choose ...">
                                    <optgroup label="ZipCodes">

                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="streetname">Street Name</label>
                                <input type="text" class="form-control" id="streetname" name="streetname" placeholder="" value="{{  $listOfIds['street'] }}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="status">User Type</label>
                                <select class="select2 form-control" id="user_type" name="user_type" required data-placeholder="Choose ...">
                                    <optgroup label="Buyers Type">
                                        <option value="3" @if( $prospect['user_type'] == 3 ) selected @endif>Buyer</option>
                                        <option value="4" @if( $prospect['user_type'] == 4 ) selected @endif>Aggregator</option>
                                        <option value="5" @if( $prospect['user_type'] == 5 ) selected @endif>Seller</option>
                                        <option value="6" @if( $prospect['user_type'] == 6 ) selected @endif>Enterprise</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="service">Services<span class="requiredFields">*</span></label>
                                <select id="service" class="select2 form-control" name="service" required data-placeholder="Choose ...">
                                    <optgroup label="Service">
                                        <option>--Choose Service--</option>
                                        @foreach( $services as $service )
                                            <option value="{{ $service->service_campaign_name }}"
                                                    @if( $prospect['service']  == $service->service_campaign_name ) selected @endif>
                                                {{ $service->service_campaign_name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="user_visibility">User Status<span class="requiredFields">*</span></label>
                                <select class="select2 form-control" id="user_visibility" name="user_visibility" required data-placeholder="Choose ...">
                                    <optgroup label="Buyers Type">
                                        <option value="1" @if( $prospect['user_visibility'] == 1 ) selected @endif>Active</option>
                                        <option value="2" @if( $prospect['user_visibility'] == 2 ) selected @endif>InActive</option>
                                        <option value="3" @if( $prospect['user_visibility'] == 3 ) selected @endif>Closed</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sdr_claimer">SDR Claimer</label>
                                <input type="text" class="form-control" id="sdr_claimer" name="sdr_claimer" value="{{ $prospect->sdr_claimer }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sales_claimer">Sales Claimer</label>
                                <input type="text" class="form-control" id="sales_claimer" name="sales_claimer" value="{{ $prospect->sales_claimer }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info waves-effect waves-light pull-right">Save changes</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End row -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted">
                            @foreach( $errors->all() as $error )
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 5-->
@endsection
