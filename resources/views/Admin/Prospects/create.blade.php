@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Add Prospects</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Prospect Details</h4>
                        <!-- Basic Form Wizard -->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="form-horizontal row" action="{{ route('Prospects.store') }}" method="POST">
                                    {{ csrf_field() }}

                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="firstname">First Name<span class="requiredFields">*</span></label>
                                                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="" required=""
                                                               value="{{ old('firstname') }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="lastname">Last Name<span class="requiredFields">*</span></label>
                                                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="" required=""
                                                               value="{{ old('lastname') }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="mobilenumber">Tel Number<span class="requiredFields">*</span></label>
                                                        <input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="" required=""
                                                               value="{{ old('mobilenumber') }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" placeholder="" value="{{ old('email') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="owner">Business Owner</label>
                                                        <input type="text" class="form-control" id="owner" name="owner" placeholder="" value="{{ old('owner') }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="lastname">Business Name<span class="requiredFields">*</span></label>
                                                        <input type="text" class="form-control" id="businessname" name="businessname" placeholder="" required=""
                                                               value="{{ old('businessname') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="state">State<span class="requiredFields">*</span></label>
                                                        <input type="hidden" value="{{ old('state') }}" id="State_id_aj"/>
                                                        <input type="hidden" value="{{ old('city') }}" id="City_id_aj"/>
                                                        <input type="hidden" value="{{ old('zipcode') }}" id="zipcode_aj"/>
                                                        <select id="stateList" class="select2 form-control" name="state" required data-placeholder="Choose ...">
                                                            <optgroup label="States">
                                                                <option>--Choose State--</option>
                                                                <?php
                                                                if ( isset($states) && !empty($states) ){
                                                                foreach( $states as $state ){
                                                                $idstate = $codecontinent = '';
                                                                if ( isset( $state['state_id']) ){
                                                                    $idstate = $state['state_id'];
                                                                }
                                                                if ( isset( $state['state_code']) ){
                                                                    $codecontinent = $state['state_code'];
                                                                }
                                                                ?>
                                                                <option value="<?php echo $idstate; ?>"><?php echo $codecontinent; ?></option>
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
                                                            <optgroup label="Cities">

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
                                                        <input type="text" class="form-control" id="streetname" name="streetname" placeholder=""
                                                               value="{{ old('streetname') }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="streetname">User Type</label>
                                                        <select class="select2 form-control" id="user_type" name="user_type" required data-placeholder="Choose ...">
                                                            <optgroup label="Buyers Type">
                                                                <option value="3" @if( old('user_type') == 3 ) selected @endif>Buyer</option>
                                                                <option value="4" @if( old('user_type') == 4 ) selected @endif>Aggregator</option>
                                                                <option value="5" @if( old('user_type') == 5 ) selected @endif>Seller</option>
                                                                <option value="6" @if( old('user_type') == 6 ) selected @endif>Enterprise</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="service">Services<span class="requiredFields">*</span></label>
                                                        <select id="service" class="select2 form-control" name="service" required data-placeholder="Choose ...">
                                                            <optgroup label="Service">
                                                                <option>--Choose Service--</option>
                                                                @foreach( $services as $service )
                                                                    <option value="{{ $service->service_campaign_name }}"
                                                                        @if( old('service') == $service->service_campaign_name ) selected @endif>
                                                                        {{ $service->service_campaign_name }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="sdr_claimer">SDR Claimer</label>
                                                <input type="text" class="form-control" id="sdr_claimer" name="sdr_claimer"  value="{{ old('sdr_claimer') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="sales_claimer">Sales Claimer</label>
                                                <input type="text" class="form-control" id="sales_claimer" name="sales_claimer"  value="{{ old('sales_claimer') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info waves-effect waves-light pull-right">Submit</button>
                                </form>
                            </div>
                        </div>

                        <!-- End row -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted" id="pErrorsShown">
                                    @foreach( $errors->all() as $error )
                                        {{ $error }}<br>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if(isset($errors))
        <script>
            $(document).ready(function(){
                var stateid = $('#State_id_aj').val();
                var cityId = $('#City_id_aj').val();
                $('#datepicker_Visa').datepicker({
                    format: "mm-yyyy",
                    startView: "months",
                    minViewMode: "months"
                });

                $.ajax({
                    url: urlcitiesSelected,
                    method: "POST",
                    data: {
                        stateid: stateid,
                        cityId: cityId,
                        _token: token
                    },
                    success: function(the_result){
                        $('#cityList').html(the_result.select);
                        $('#stateList option[value='+the_result.state_id+']').attr('selected','selected');
                    }
                });
                $('#datepicker_Visa').datepicker({
                    format: "mm-yyyy",
                    startView: "months",
                    minViewMode: "months"
                });
            });
        </script>
    @endif
    <!-- End Of Page 1-->
@endsection
