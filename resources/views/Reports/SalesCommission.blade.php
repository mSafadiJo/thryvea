@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Sales Commission Report</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker1" placeholder="From Date" autocomplete="false" class="form-control" value=""/>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker2" placeholder="To Date" autocomplete="false" class="form-control" value=""/>
                                </div>
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-primary col-lg-12" id="FilterSalesCommissionReport">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="margin-bottom: 5%;"></div>
                    <h4 class="header-title">Sales Commission Report</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="dataAjaxTableReport">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%"
                                       @if( empty($permission_users) || in_array('3-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Business Name</th>
                                            <th>Type</th>
                                            <th>States</th>
                                            <th>Services</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>1<sup>st</sup> Fund Date</th>
                                            <th>1<sup>st</sup> Fund Value</th>
                                            <th>Payment Type</th>
                                            <th>SDR Claimer</th>
                                            <th>Sales Claimer</th>
                                            <th>Account Manager Claimer</th>
                                            <th>Current Balance</th>
                                            <th>Total Spent</th>
                                            <th>Total Funded</th>
                                            <th>Last Transaction</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button style="display: none;" data-toggle="modal" data-target="#sellerCommissionStateModel" id="sellerCommissionStateModelButton">model</button>
    <div class="modal fade" id="sellerCommissionStateModel" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><span class="span1"></span> <span class="span2"></span></h4>
                </div>
                <div class="modal-body">
                    <p>

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
