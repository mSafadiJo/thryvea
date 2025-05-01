@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Block Lead Info</h4>
            </div>
        </div>
    </div>

    <style>
        input.block_search_style {
            width: 90%;
            display: inline-block;
            border-radius: 4px 0 0  4px;
        }

        button.block_btn_search_style {
            width: 10%;
            display: inline-block;
            border-radius: 0 4px 4px 0;
            margin-left: -5px;
        }
        div.block_response_style {
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                {{--Phone Number --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Block Phone Numbers</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <input class="form-control block_search_style" type="text" id="block_phone_number_input_text">
                                <button type="button" class="form-control block_btn_search_style block_buttons" id="block_phone_number_btn_search"
                                        data-type="search" data-section="phone_number">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-5 block_response_style" id="block_phone_number_response">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6" id="block_phone_number_success_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger form-control block_buttons" id="block_phone_number_btn_block"
                                                    data-type="block" data-section="phone_number">Block</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="block_phone_number_fail_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success form-control block_buttons" id="block_phone_number_btn_unblock"
                                                    data-type="unblock" data-section="phone_number">UnBlock</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-secondary form-control block_buttons" id="block_phone_number_btn_clear"
                                                    data-type="clear" data-section="phone_number">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Emails --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Block Emails</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <input class="form-control block_search_style" type="text" id="block_email_input_text">
                                <button type="button" class="form-control block_btn_search_style block_buttons" id="block_email_btn_search"
                                        data-type="search" data-section="email">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-5 block_response_style" id="block_email_response">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6" id="block_email_success_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger form-control block_buttons" id="block_email_btn_block"
                                                    data-type="block" data-section="email">Block</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="block_email_fail_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success form-control block_buttons" id="block_email_btn_unblock"
                                                    data-type="unblock" data-section="email">UnBlock</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-secondary form-control block_buttons" id="block_email_btn_clear"
                                                    data-type="clear" data-section="email">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--IP Address --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>IP Address</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <input class="form-control block_search_style" type="text" id="block_ip_address_input_text">
                                <button type="button" class="form-control block_btn_search_style block_buttons" id="block_ip_address_btn_search"
                                        data-type="search" data-section="ip_address">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-5 block_response_style" id="block_ip_address_response">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6" id="block_ip_address_success_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger form-control block_buttons" id="block_ip_address_btn_block"
                                                    data-type="block" data-section="ip_address">Block</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="block_ip_address_fail_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success form-control block_buttons" id="block_ip_address_btn_unblock"
                                                    data-type="unblock" data-section="ip_address">UnBlock</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-secondary form-control block_buttons" id="block_ip_address_btn_clear"
                                                    data-type="clear" data-section="ip_address">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--First Name --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>First Name</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <input class="form-control block_search_style" type="text" id="block_first_name_input_text">
                                <button type="button" class="form-control block_btn_search_style block_buttons" id="block_first_name_btn_search"
                                        data-type="search" data-section="first_name">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-5 block_response_style" id="block_first_name_response">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6" id="block_first_name_success_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger form-control block_buttons" id="block_first_name_btn_block"
                                                    data-type="block" data-section="first_name">Block</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="block_first_name_fail_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success form-control block_buttons" id="block_first_name_btn_unblock"
                                                    data-type="unblock" data-section="first_name">UnBlock</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-secondary form-control block_buttons" id="block_first_name_btn_clear"
                                                    data-type="clear" data-section="first_name">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Last Name --}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Last Name</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <input class="form-control block_search_style" type="text" id="block_last_name_input_text">
                                <button type="button" class="form-control block_btn_search_style block_buttons" id="block_last_name_btn_search"
                                        data-type="search" data-section="last_name">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-5 block_response_style" id="block_last_name_response">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6" id="block_last_name_success_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger form-control block_buttons" id="block_last_name_btn_block"
                                                    data-type="block" data-section="last_name">Block</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="block_last_name_fail_div">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success form-control block_buttons" id="block_last_name_btn_unblock"
                                                    data-type="unblock" data-section="last_name">UnBlock</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-secondary form-control block_buttons" id="block_last_name_btn_clear"
                                                    data-type="clear" data-section="last_name">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
