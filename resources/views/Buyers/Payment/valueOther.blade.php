@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 2-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Credit/Debit Card</h4>
            </div>
        </div>
    </div>
    <style type="text/css">
        label.col-md-12.control-label {
            float: left;
        }

        .input-group-sm>.input-group-btn>select.btn:not([size]):not([multiple]), .input-group-sm>select.form-control:not([size]):not([multiple]), .input-group-sm>select.input-group-addon:not([size]):not([multiple]), select.form-control-sm:not([size]):not([multiple]) {
            height: unset;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-lg-12">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {!! $message !!}
                            </div>
                            <?php Session::forget('success');?>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {!! $message !!}
                            </div>
                            <?php Session::forget('error');?>
                        @endif
                    </div>
                </div>

                <form class="form-horizontal row" id="paymantform" role="form" method="post" action="{{ route('Transaction.Value.StoreOther') }}">
                    {{ csrf_field() }}
                    <div class="form-group inline-block col-12 ">
                        <label class="col-md-12 control-label">Full Name<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Full Name" name="name" value="{{ old('name') }}" required>
                            <input type="hidden" class="form-control" name="user_id" value="{{ Auth::user()->id }}" required>
                            <input type="hidden" class="form-control" name="type" value="{{ $type }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-12 ">
                        <label class="col-md-12 control-label">Value</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Value" name="value" value="{{ $value }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-9 ">
                        <label class="col-md-6 control-label">Visa Number<span class="requiredFields">*</span></label>
                        <label class="col-md-6 control-label text-right" style="float: right;" id="visatypeLabel"></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="0000-0000-0000-0000" value="{{ old('visanumber') }}" id="visanumber" name="visanumber" maxlength="19" required="">
                            <input type="hidden" class="form-control" name="visatype" id="visatype" value="{{ old('visatype') }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-3 ">
                        <label class="col-md-12 control-label">CVV<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" maxlength="4" class="form-control" placeholder="000" name="cvv" value="{{ old('cvv') }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-6 ">
                        <label class="col-md-12 control-label">Exp. Month<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" maxlength="2" class="form-control" placeholder="MM" name="exp_month" value="{{ old('exp_month') }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-6 ">
                        <label class="col-md-12 control-label">Exp. Year<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" maxlength="4" class="form-control" placeholder="YYYY" name="exp_year" value="{{ old('exp_year') }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-6 ">
                        <label class="col-md-12 control-label">Address<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Address" name="address" value="{{ old('address') }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-3 ">
                        <label class="col-md-12 control-label">Zip-Code<span class="requiredFields">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="928745" name="zip_code" value="{{ old('zip_code') }}" required>
                        </div>
                    </div>
                    <div class="form-group inline-block col-3 m-0" style="padding: 2% 0 0 0;">
                        <label class="col-md-6 control-label text-center">Primary?</label>
                        <label class="col-md-6 switch">
                            <input type="checkbox" name="primary" value="1"
                            <?php
                                if(old('primary') !== null){
                                    if(old('primary') == 1){
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
                        <button type="submit" id="paymentSubmitForm" style="display: none;">Submit</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light pull-right"
                                onclick="return CheckFunction();">Confirm Payment</button>
                    </div>
                </form>

                <!-- End row -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted" id="pErrorsShown">
                            @foreach( $errors->all() as $error )
                                {{ $error }}<br>
                            @endforeach
                            @if ($message = Session::get('errormsg'))
                                <p>{!! $message !!}</p>
                                <?php Session::forget('errormsg');?>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 2-->
@endsection
