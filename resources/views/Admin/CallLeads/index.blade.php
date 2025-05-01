@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Call Leads</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('export_CallLead_data') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="service_id">Service Name</label>
                                        <select class="select2 form-control select2-multiple" multiple name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                            <optgroup label="Service">
                                                @foreach( $services as $service )
                                                    <option value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="environments">Environments</label>
                                        <select class="select2 form-control" name="environments" id="environments" data-placeholder="Choose ...">
                                            <optgroup label="Environments">
                                                <option value="1" selected>All</option>
                                                <option value="5">Test Lead</option>
                                                <option value="6">Duplicated</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control"/>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control"/>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        @if( empty($permission_users) || in_array('8-4', $permission_users) )
                                            <div class="col-lg-6">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterCallLeadList">Search</button>
                                            </div>
                                            <div class="col-lg-6">
                                                <button type="submit" class="btn btn-primary col-lg-12">Export</button>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterCallLeadList">Search</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="dataAjaxTableForm">
                            <table id="datatable3" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lead Name</th>
                                    <th>Phone Number</th>
                                    <th>Service</th>
                                    <th>TS</th>
                                    <th>Is Verified</th>
                                    <th>Created At</th>
                                    @if(empty($permission_users) || in_array('8-10', $permission_users))
                                        <th>Action</th>
                                    @endif
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

    <!-- End Of Page 1-->
@endsection
