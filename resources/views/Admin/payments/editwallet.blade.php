@extends('layouts.adminapp')

@section('content')

    <!-- Page 2-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Edit Payment</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form class="form-horizontal row" id="paymantform" role="form" method="post" action="{{ route('Admin.Buyers.Wallet.Update') }}">
                    {{ csrf_field() }}
                    <div class="form-group inline-block col-12 ">
                        <label class="col-md-12 control-label">Full Name<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Full Name" name="name" value="{{ $payment->payment_full_name }}" required>
                            <input type="hidden" class="form-control" name="id" value="{{ $payment->payment_id }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-9 ">
                        <label class="col-md-6 control-label">Visa Number<span class="requiredFields">*</span></label>
                        <label class="col-md-6 control-label text-right" style="float: right;" id="visatypeLabel"></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" value="{{ $payment->payment_visa_type }}-{{ wordwrap($payment->payment_visa_number , 4 , ' ' , true ) }}" disabled>
                        </div>
                    </div>
                    <div class="form-group inline-block col-3 ">
                        <label class="col-md-12 control-label">CVV<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" maxlength="3" class="form-control" placeholder="000" name="cvv" value="{{ $payment->payment_cvv }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-6 ">
                        <label class="col-md-12 control-label">Exp. Month<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" maxlength="2" class="form-control" placeholder="MM" name="exp_month" value="{{ $payment->payment_exp_month }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-6 ">
                        <label class="col-md-12 control-label">Exp. Year<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" maxlength="4" class="form-control" placeholder="YYYY" name="exp_year" value="{{ $payment->payment_exp_year }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-6 ">
                        <label class="col-md-12 control-label">Address<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Address" name="address" value="{{ $payment->payment_address }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-3 ">
                        <label class="col-md-12 control-label">Zip-Code<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="928745" name="zip_code" value="{{ $payment->payment_zip_code }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-3 m-0" style="padding: 2% 0 0 0;">
                        <label class="col-md-6 control-label text-center">Primary?</label>
                        <label class="col-md-6 switch">
                            <input type="checkbox" name="primary" value="1"
                            <?php
                                if($payment->payment_primary !== null){
                                    if($payment->payment_primary == 1){
                                        echo "checked";
                                    }
                                } else {
                                    echo "checked";
                                }
                                ?>>
                            <span class="slider round"></span>
                        </label>
                        <style>
                            .switch {
                                position: relative;
                                display: inline-block;
                                width: 60px;
                                height: 34px;
                            }

                            .switch input {
                                opacity: 0;
                                width: 0;
                                height: 0;
                            }

                            .slider {
                                position: absolute;
                                cursor: pointer;
                                top: 0;
                                left: 0;
                                right: 0;
                                bottom: 0;
                                background-color: #ccc;
                                -webkit-transition: .4s;
                                transition: .4s;
                            }

                            .slider:before {
                                position: absolute;
                                content: "";
                                height: 26px;
                                width: 26px;
                                left: 4px;
                                bottom: 4px;
                                background-color: white;
                                -webkit-transition: .4s;
                                transition: .4s;
                            }

                            input:checked + .slider {
                                background-color: #2196F3;
                            }

                            input:focus + .slider {
                                box-shadow: 0 0 1px #2196F3;
                            }

                            input:checked + .slider:before {
                                -webkit-transform: translateX(26px);
                                -ms-transform: translateX(26px);
                                transform: translateX(26px);
                            }

                            /* Rounded sliders */
                            .slider.round {
                                border-radius: 34px;
                            }

                            .slider.round:before {
                                border-radius: 50%;
                            }
                        </style>
                    </div>
                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-primary waves-effect waves-light pull-right">Submit</button>
                    </div>
                </form>

                <!-- End row -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted" id="pErrorsShown">
                            @foreach( $errors->all() as $error )
                                {{ $error }}<br>
                            @endforeach
                            @if( $errormsg != '' )
                                {{ $errormsg }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 2-->
@endsection