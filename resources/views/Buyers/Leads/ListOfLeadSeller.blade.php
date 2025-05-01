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
                        <form action="{{ route('export_lead_data') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="service_id">Service Name</label>
                                        <select class="select2 form-control select2-multiple" multiple name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                            <optgroup label="Service">
                                                @if( !empty( $services ) )
                                                    @foreach( $services as $service )
                                                        <option value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary col-lg-12" id="filterLeadSeller">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <h6>List Of Leads Received</h6>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="dataAjaxTableLeads">
                            <table id="datatable3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Lead Name</th>
                                    <th>Campaign Name</th>
                                    <th>Service</th>
                                    <th>Price</th>
                                    <th>Created At</th>
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
