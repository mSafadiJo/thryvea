@extends('layouts.NavBuyerHome')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <style>
        .input-group-sm>.input-group-btn>select.btn:not([size]):not([multiple]), .input-group-sm>select.form-control:not([size]):not([multiple]), .input-group-sm>select.input-group-addon:not([size]):not([multiple]), select.form-control-sm:not([size]):not([multiple]) {
            height: unset;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m') }}-1" autocomplete="false" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-t') }}" autocomplete="false" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary col-lg-12" id="filterListOfClickLeadsBuyer">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h6>List Of Leads Received</h6>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="dataAjaxTableLeads">
                            <table id="datatable3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Campaign ID</th>
                                    <th>Campaign Name</th>
                                    <th>Number of Click</th>
                                    <th>Number of Conversions</th>
                                    <th>Total Cost</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
