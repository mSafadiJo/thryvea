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
                        <form action="{{ route('buyers_export_lead_data') }}" method="POST">
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
                                        <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary col-lg-12" id="filterLeadBuyers">Search</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input type="hidden" name="type" value="1">
                                                    <button type="submit" class="btn btn-primary col-lg-12">Export</button>
                                                </div>
                                            </div>
                                        </div>
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
                                    <th>Lead ID</th>
                                    <th>Lead Name</th>
                                    <th>Campaign Name</th>
                                    <th>Service</th>
                                    <th>Type</th>
                                    <th>Trusted Form URL</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
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

    <!-- Modal -->
    <button type="button" id="script_lead_model_btn" class="btn btn-primary" data-toggle="modal" data-target="#script_lead_model" style="display: none;"></button>
    <div class="modal fade bd-example-modal-lg" id="script_lead_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="lead_model_title">Lead Note</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="lead_model_note">Lead Note</label>
                                <textarea id="lead_model_note" name="lead_model_note" class="form-control"></textarea>
                                <input type="hidden" class="form-control" value="" id="lead_model_id" name="lead_model_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-danger waves-effect buyer_lead_model_delete">Delete</button>
                                <button type="button" class="btn btn-primary waves-effect waves-light pull-right buyer_lead_model_add">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function show_script_text_data(id, title) {
            let text = $('#show_script_text_data-'+id).val();
            $('#lead_model_title').html(title);
            $('#lead_model_id').val(id);
            $('#lead_model_note').val(text);
            $('#script_lead_model_btn').click();
        }
    </script>
@endsection
