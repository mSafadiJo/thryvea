@extends('layouts.adminapp')

@section('content')
    <!-- Page 2-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Payment Methods</h4>
            </div>
        </div>
    </div>
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
                                {{ $message }} {{ Config::get('services.PAYMENT_METHODS') }}
                            </div>
                            <?php Session::forget('success');?>
                        @endif
                    </div>
                </div>
                <form class="form-horizontal row" role="form" method="post" action="{{ route('Admin.Setting.Payment.Store') }}">
                    {{ csrf_field() }}
                    <div class="form-group inline-block col-6">
                        <label class="col-md-12 control-label">Merchant Account</label>
                        <div class="col-md-12">
                            <select class="form-control" name="type" required>
                                <option value="Auth3" @if(Config::get('services.PAYMENT_METHODS') == 'Auth3') selected @endif>Authorize.net 3</option>
                                <option value="Auth4" @if(Config::get('services.PAYMENT_METHODS') == 'Auth4') selected @endif>Authorize.net 4</option>
                                {{--<option value="Auth2" @if(Config::get('services.PAYMENT_METHODS') == 'Auth2') selected @endif>Authorize.net 2</option>--}}
                                {{--<option value="Auth1" @if(Config::get('services.PAYMENT_METHODS') == 'Auth1') selected @endif>Authorize.net 1</option>--}}
                                {{--<option value="NMI" @if(Config::get('services.PAYMENT_METHODS') == 'NMI') selected @endif>NMI Payment</option>--}}
                                <option value="NMI2" @if(Config::get('services.PAYMENT_METHODS') == 'NMI2') selected @endif>NMI2 Payment</option>
                                <option value="NMI3" @if(Config::get('services.PAYMENT_METHODS') == 'NMI3') selected @endif>NMI3 Payment</option>
                                <option value="Stripe" @if(Config::get('services.PAYMENT_METHODS') == 'Stripe') selected @endif>Stripe Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group inline-block col-6">
                        <label class="col-md-12 control-label">Merchant Account For Auto Charge</label>
                        <div class="col-md-12">
                            <select class="form-control" name="type_auto" required>
                                <option value="Auth3" @if(Config::get('services.AUTO_PAYMENT_METHODS') == 'Auth3') selected @endif>Authorize.net 3</option>
                                <option value="Auth4" @if(Config::get('services.AUTO_PAYMENT_METHODS') == 'Auth4') selected @endif>Authorize.net 4</option>
                                {{--<option value="Auth2" @if(Config::get('services.AUTO_PAYMENT_METHODS') == 'Auth2') selected @endif>Authorize.net 2</option>--}}
                                {{--<option value="Auth1" @if(Config::get('services.AUTO_PAYMENT_METHODS') == 'Auth1') selected @endif>Authorize.net 1</option>--}}
                                {{--<option value="NMI" @if(Config::get('services.AUTO_PAYMENT_METHODS') == 'NMI') selected @endif>NMI Payment</option>--}}
                                <option value="NMI2" @if(Config::get('services.AUTO_PAYMENT_METHODS') == 'NMI2') selected @endif>NMI2 Payment</option>
                                <option value="NMI3" @if(Config::get('services.AUTO_PAYMENT_METHODS') == 'NMI3') selected @endif>NMI3 Payment</option>
                                <option value="Stripe" @if(Config::get('services.AUTO_PAYMENT_METHODS') == 'Stripe') selected @endif>Stripe Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-primary waves-effect waves-light pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Of Page 2-->
@endsection
