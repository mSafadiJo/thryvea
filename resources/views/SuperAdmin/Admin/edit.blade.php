@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Edit Admin</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">User Details</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('AdminManagment.update', $user_data->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
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
                        @if( Auth::user()->role_id == 1 )
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="user_role">User Type<span class="requiredFields">*</span></label>
                                    <select class="select2 form-control" id="user_role" name="user_role" data-placeholder="Choose ...">
                                        <optgroup label="User Type">
                                            <option value="2" @if( $user_data->role_id == 2 ) selected @endif>Admin</option>
                                            <option value="1" @if( $user_data->role_id == 1 ) selected @endif>Super Admin</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="user_account_type">Account Type<span class="requiredFields">*</span></label>
                                    <select class="select2 form-control" id="user_account_type" name="user_account_type" required data-placeholder="Choose ...">
                                        <optgroup label="Account Type">
                                            <option value="Sales" @if( $user_data->account_type == "Sales" ) selected @endif>Sales</option>
                                            <option value="Account Manager" @if( $user_data->account_type == "Account Manager" ) selected @endif>Account Manager</option>
                                            <option value="Sdr" @if( $user_data->account_type == "Sdr" ) selected @endif>Sdr</option>
                                            <option value="Operation" @if( $user_data->account_type == "Operation" ) selected @endif>Operation</option>
                                            <option value="IT" @if( $user_data->account_type == "IT" ) selected @endif>IT</option>
                                            <option value="Marketing" @if( $user_data->account_type == "Marketing" ) selected @endif>Marketing</option>
                                            <option value="Managers" @if( $user_data->account_type == "Managers" ) selected @endif>Managers</option>
                                            <option value="Lead Review" @if( $user_data->account_type == "Lead Review" ) selected @endif>Lead Review</option>
                                            <option value="Call Center" @if( $user_data->account_type == "Call Center" ) selected @endif>Call Center</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" class="form-control" id="user_role" name="user_role" required=""
                                   value="{{ $user_data->role_id }}">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="user_account_type">Account Type<span class="requiredFields">*</span></label>
                                    <select class="select2 form-control" id="user_account_type" name="user_account_type" required data-placeholder="Choose ...">
                                        <optgroup label="Account Type">
                                            <option value="Sales" @if( $user_data->account_type == "Sales" ) selected @endif>Sales</option>
                                            <option value="Account Manager" @if( $user_data->account_type == "Account Manager" ) selected @endif>Account Manager</option>
                                            <option value="Sdr" @if( $user_data->account_type == "Sdr" ) selected @endif>Sdr</option>
                                            <option value="Operation" @if( $user_data->account_type == "Operation" ) selected @endif>Operations</option>
                                            <option value="IT" @if( $user_data->account_type == "IT" ) selected @endif>IT</option>
                                            <option value="Marketing" @if( $user_data->account_type == "Marketing" ) selected @endif>Marketing</option>
                                            <option value="Managers" @if( $user_data->account_type == "Managers" ) selected @endif>Managers</option>
                                            <option value="Lead Review" @if( $user_data->account_type == "Lead Review" ) selected @endif>Lead Review</option>
                                            <option value="Call Center" @if( $user_data->account_type == "Call Center" ) selected @endif>Call Center</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        @endif
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

                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label>Admin Icon</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input type="file" name="adminIcon1" class="imgInpCustom" data-id="1">
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>

                                <img id='img-upload-1'
                                     @if( !empty($user_data['adminIcon1']) ) src="{{ asset('/images/salesDashboard/'.$user_data['adminIcon1']) }}" @endif/>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label>Admin Icon</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input type="file" name="adminIcon2" class="imgInpCustom" data-id="2">
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>

                                <img id='img-upload-2'
                                     @if( !empty($user_data['adminIcon2']) ) src="{{ asset('/images/salesDashboard/'.$user_data['adminIcon2']) }}" @endif/>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label>Admin Icon</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input type="file" name="adminIcon3" class="imgInpCustom" data-id="3">
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>

                                <img id='img-upload-3'
                                     @if( !empty($user_data['adminIcon3']) ) src="{{ asset('/images/salesDashboard/'.$user_data['adminIcon3']) }}" @endif/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @include('SuperAdmin.Admin.permission')
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
    <!-- End Of Page 5-->
@endsection
