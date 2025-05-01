@extends('layouts.NavBuyerHome')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" action="{{ route('ExportDataLeadsTable') }}" class="form-group">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control start_date_pagination" required/>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control end_date_pagination" required/>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTablesNew">Search</button>                                                </div>
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
                        </div>
                    </div>
                </div>

                <h6>List Of Leads</h6>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="search" id="searchNew" class="form-control" placeholder="Search" style="width: 22%;float:right;margin-bottom:1%;"/>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div id="table_dataNew">
                            <div class="table-responsiveNew">
                                <table id="pagination-table" class="table table-striped table-bordered"
                                       data-action="/Seller/list_of_leads_RevShare/ajax?page=">
                                    <thead>
                                    <tr>
                                        <th>Lead ID</th>
                                        <th>Lead Name</th>
                                        <th>Price</th>
                                        <th>Profit</th>
                                        <th>Created At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @include('Render.RevShareSeller.RevShareSellerRender')
                                    </tbody>
                                </table>
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
