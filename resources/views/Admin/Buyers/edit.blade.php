@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Edit Buyers</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Buyer Details</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('Admin_Buyers.updateUser', $user_data->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="firstname"><span class="requiredFields"></span>First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder=""
                                       required="" value="{{ $user_data->user_first_name }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lastname"><span class="requiredFields"></span>Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="" required=""
                                       value="{{ $user_data->user_last_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="email"><span class="requiredFields"></span>Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="" required=""
                                       value="{{ $user_data->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="firstname"><span class="requiredFields"></span>Business Owner</label>
                                <input type="text" class="form-control" id="owner" name="owner" placeholder="" required="" value="{{ $user_data->user_owner }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lastname"><span class="requiredFields"></span>Business Name</label>
                                <input type="text" class="form-control" id="businessname" name="businessname" placeholder="" required="" value="{{ $user_data->user_business_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phonenumber"><span class="requiredFields"></span>Phone Number</label>
                                <input type="number" class="form-control" id="phonenumber" name="phonenumber" placeholder="" required="" value="{{ $user_data->user_phone_number }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mobilenumber"><span class="requiredFields"></span>Mobile Number</label>
                                <input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="" required="" value="{{ $user_data->user_mobile_number }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="hidden" value="{{ $listOfIds['state_id'] }}" id="State_id_aj_userProfile"/>
                                <input type="hidden" value="{{ $listOfIds['city_id'] }}" id="City_id_aj_userProfile"/>
                                <input type="hidden" value="{{ $listOfIds['zip_code'] }}" id="zip_code_aj_userProfile"/>
                                <input type="hidden" id="zip_code_id" name="zip_code_id" required="" value="{{ $user_data->zip_code_id }}">
                                <label for="state">State</label>
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
                                <select class="select2 form-control" id="cityList" name="city" required data-placeholder="Choose ...">
                                    <optgroup label="States">
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="zipcode"><span class="requiredFields"></span>Zip-Code</label>
                                <select class="select2 form-control" id="zipcodeList" name="zipcode" required data-placeholder="Choose ...">
                                    <optgroup label="ZipCodes">

                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="streetname"><span class="requiredFields"></span>Street Name</label>
                                <input type="text" class="form-control" id="streetname" name="streetname" placeholder="" required="" value="{{  $listOfIds['street'] }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="status"><span class="requiredFields"></span>User Type</label>
                                <select class="select2 form-control" id="user_type" name="user_type" required data-placeholder="Choose ...">
                                    <optgroup label="Buyers Type">
                                        <option value="3" @if( $user_data['user_type'] == 3 ) selected @endif>Buyer</option>
                                        <option value="4" @if( $user_data['user_type'] == 4 ) selected @endif>Aggregator</option>
                                        <option value="5" @if( $user_data['user_type'] == 5 ) selected @endif>Seller</option>
                                        <option value="6" @if( $user_data['user_type'] == 6 ) selected @endif>Enterprise</option>
                                        <option value="7" @if( $user_data['user_type'] == 7 ) selected @endif>RevShare Seller</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="profit_percentage">Profit Percentage</label>
                                <input type="text" class="form-control" id="profit_percentage" name="profit_percentage" placeholder="0.5"
                                       value="{{ $user_data['profit_percentage'] }}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="user_visibility"><span class="requiredFields"></span>User Status</label>
                                <select class="select2 form-control" id="user_visibility" name="user_visibility" required data-placeholder="Choose ...">
                                    <optgroup label="Buyers Type">
                                        <option value="1" @if( $user_data['user_visibility'] == 1 ) selected @endif>Active</option>
                                        <option value="2" @if( $user_data['user_visibility'] == 2 ) selected @endif>InActive</option>
                                        <option value="3" @if( $user_data['user_visibility'] == 3 ) selected @endif>Closed</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        @php
                            $permissionUser = (empty($user_data['permission_users']) ? array() : json_decode($user_data['permission_users'], true));
                        @endphp
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="remove_permission"><span class="requiredFields"></span>Choose the permissions to remove for this buyer</label>
                                <select class="select2 form-control" multiple id="remove_permission" name="remove_permission[]" data-placeholder="Choose ...">
                                    <optgroup label="Choose the permissions to remove for this buyer">
                                        <option value="1" @if(in_array(1, $permissionUser)) selected @endif>Update Campaigns</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="streetname"><span class="requiredFields"></span>Is Verified</label>
                                @if( !empty($user_data['email_verified_at']) )
                                    <input type="text" disabled class="form-control" name="email_verified_at" value="Verified">
                                @else
                                    <label class="switch">
                                        <input type="checkbox" name="is_verified" id="is_verified" value="1"
                                        <?php
                                            if( $user_data['email_verified_at'] !== null ){
                                                echo "checked";
                                            }
                                            ?>>
                                        <span class="slider round"></span>
                                    </label>
                                @endif
                            </div>
                        </div>

                        <hr>
                        <div class="col-sm-6" >
                            <div class="form-group">
                                <label for="user_auto_pay_status" style="display: block;">Auto Pay Campaigns</label>
                                <label class="form-group switch">
                                    <input type="checkbox" name="user_auto_pay_status" id="user_auto_pay_status" value="1"
                                    <?php
                                        if($user_data['user_auto_pay_status'] !== null){
                                            if($user_data['user_auto_pay_status'] == 1){
                                                echo "checked";
                                            }
                                        }
                                        ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_auto_pay_amount">Auto Pay Amount</label>
                                <input type="number" class="form-control" id="user_auto_pay_amount" name="user_auto_pay_amount" placeholder=""
                                       @if( $user_data['user_auto_pay_amount'] ) value="{{ $user_data['user_auto_pay_amount'] }}" @else value="0" @endif>
                            </div>
                        </div>
                        <hr>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="contracts">Contracts</label>
                                <input type="file" class="form-control" id="contracts" name="contracts[]" multiple>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="documents">Documents</label>
                                <input type="file" class="form-control" id="documents" name="documents[]" multiple>
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
                            @if( $errormsg !== '' )
                                {{ $errormsg }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Change Password</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('Admin_Buyers.updateUser.Password', $user_data->id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password"><span class="requiredFields"></span>New Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder=""
                                       required="" value="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password1"><span class="requiredFields"></span>Confirm Password</label>
                                <input type="password" class="form-control" id="password1" name="password_confirmation" placeholder=""
                                       required="" value="">
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
                            @if( $errormsg !== '' )
                                {{ $errormsg }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Contracts & Documents</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Contracts</label><br>
                            @if( !empty($user_data['contracts']) )
                                @foreach( json_decode($user_data['contracts'], true) as $val )
                                    <a href="{{ asset('uploads/buyers/contracts/'.$val) }}">{{ $val }}</a><br>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Documents</label><br>
                            @if( !empty($user_data['documents']) )
                                @foreach( json_decode($user_data['documents'], true) as $val )
                                    <a href="{{ asset('uploads/buyers/documents/'.$val) }}">{{ $val }}</a><br>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 5-->
@endsection
