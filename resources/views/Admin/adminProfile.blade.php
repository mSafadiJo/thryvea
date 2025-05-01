@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Profile</h4>
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

                        <div class="col-12 text-center">
                    <span class="text-muted">
                        @if($errors->any())
                            <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        </div>
                        @endif

                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </span>
                        </div>

                </div>
                <form class="form-horizontal" action="{{ route('UpdateUserAdmin') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="firstname"><span class="requiredFields"></span>First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder=""
                                       required="" value="{{ Auth::user()->user_first_name }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lastname"><span class="requiredFields"></span>Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="" required=""
                                       value="{{ Auth::user()->user_last_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="email"><span class="requiredFields"></span>Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="" required=""
                                       value="{{ Auth::user()->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="firstname"><span class="requiredFields"></span>Business Owner</label>
                                <input type="text" class="form-control" id="owner" name="owner" placeholder="" required="" value="{{ Auth::user()->user_owner }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lastname"><span class="requiredFields"></span>Business Name</label>
                                <input type="text" class="form-control" id="businessname" name="businessname" placeholder="" required="" value="{{ Auth::user()->user_business_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phonenumber"><span class="requiredFields"></span>Phone Number</label>
                                <input type="number" class="form-control" id="phonenumber" name="phonenumber" placeholder="" required="" value="{{ Auth::user()->user_phone_number }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mobilenumber"><span class="requiredFields"></span>Mobile Number</label>
                                <input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="" required="" value="{{ Auth::user()->user_mobile_number }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="hidden" value="{{ $listOfIds['state_id'] }}" id="State_id_aj_userProfile"/>
                                <input type="hidden" value="{{ $listOfIds['city_id'] }}" id="City_id_aj_userProfile"/>
                                <input type="hidden" value="{{ $listOfIds['zip_code'] }}" id="zip_code_aj_userProfile"/>
                                <input type="hidden" id="zip_code_id" name="zip_code_id" required="" value="{{ Auth::user()->zip_code_id }}">
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
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info waves-effect waves-light pull-right">Save changes</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End row -->

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
                <form class="form-horizontal" action="{{ route('UpdatePasswprdUserAdmin') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="password"><span class="requiredFields"></span>Old Password</label>
                                <input type="password" class="form-control" id="Oldpassword" name="Oldpassword" placeholder=""
                                       required="" value="">
                            </div>
                        </div>
                    </div>
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
            </div>
        </div>
    </div>
    <!-- End Of Page 5-->
@endsection
