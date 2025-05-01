@extends('layouts.NavBuyerHome')

@section('content')
    <section class="pt-5 pb-5 revShareSeller">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        @switch(Auth::user()->id)
                            @case(1235)
                                {{--turtle leads ID 1235--}}
                            @case(1268)
                                {{--One Pride: 1268--}}
                            @case(1305)
                                {{--DM: 1305--}}
                            <div class="col-12">
                                <form method="post" action="{{ route('exportRevShareData') }}" class="form-group">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control"/>
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control"/>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <button type="button" class="btn btn-primary col-lg-12" id="filterLeadRevShare">Search</button>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary col-lg-12">Export</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        @break
                        @default
                            <div class="col-lg-4">
                                <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control"/>
                            </div>
                            <div class="col-lg-4">
                                <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control"/>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="button" class="btn btn-primary col-lg-12" id="filterLeadRevShare">Search</button>
                                    </div>
                                </div>
                            </div>
                        @endswitch

                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 col-md-6 margin-30px-bottom xs-margin-20px-bottom">
                    <div class="services-block-three">
                        <a href="javascript:void(0)">
                            <div class="padding-15px-bottom">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                            <h4>Leads</h4>
                            <p class="xs-font-size13 xs-line-height-22" id="leads_count"></p>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 margin-30px-bottom xs-margin-20px-bottom">
                    <div class="services-block-three">
                        <a href="javascript:void(0)">
                            <div class="padding-15px-bottom">
                                <i class="fa fa-usd" aria-hidden="true"></i>
                            </div>
                            <h4>Total Price</h4>
                            <p class="xs-font-size13 xs-line-height-22" id="total_bid_lead"></p>
                        </a>
                    </div>
                </div>

                <div class="services-block-three col-lg-4 col-md-6 margin-30px-bottom xs-margin-20px-bottom">
                    <div class="services-block-three">
                        <a href="javascript:void(0)">
                            <div class="padding-15px-bottom">
                                <i class="fa fa-percent" aria-hidden="true"></i>
                            </div>
                            <h4>Percentage</h4>
                            <p class="xs-font-size13 xs-line-height-22" id="percentage"></p>
                        </a>
                    </div>
                </div>

                <div class="services-block-three col-lg-4 col-md-6 sm-margin-30px-bottom xs-margin-20px-bottom">
                    <div class="services-block-three">
                        <a href="javascript:void(0)">
                            <div class="padding-15px-bottom">
                                <i class="fa fa-diamond"></i>
                            </div>
                            <h4>Profit Percentage</h4>
                            <p class="xs-font-size13 xs-line-height-22" id="profit_percentage"></p>
                        </a>
                    </div>
                </div>

                <div class="services-block-three col-lg-4 col-md-6 xs-margin-20px-bottom">
                    <div class="services-block-three">
                        <a href="javascript:void(0)">
                            <div class="padding-15px-bottom">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                            </div>
                            <h4>Last Transaction</h4>
                            <p class="xs-font-size13 xs-line-height-22" id="last_transaction"></p>
                        </a>
                    </div>
                </div>
                <!-- end -->
            </div>
        </div>
    </section>

@endsection
