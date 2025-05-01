@extends('layouts.NavBuyerHome')

@section('content')

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
                                        <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control start_date_pagination"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control end_date_pagination"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary col-lg-12" id="filterLeadReturnBuyers">Search</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input type="hidden" name="type" value="3">
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
                <h6>Return Lead</h6>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="dataAjaxTableLeads">
                            <table id="datatable3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lead ID</th>
                                    <th>Lead Name</th>
                                    <th>Service</th>
                                    <th>Lead Phone Number</th>
                                    <th>Reason</th>
                                    <th>Ticket Message</th>
                                    <th>Reject Message</th>
                                    <th>Status</th>
                                    <th>Sold Date</th>
                                    <th>Return Date</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal" id="MassageTicket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ticket, Status Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="firstname">Status Message</label>
                                <textarea class="form-control" id="status_msg_ticket" required>

                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-secondary" id="MassageTicketButtons" data-toggle="modal" data-target="#MassageTicket" style="display: none;">R</button>

    <script>
        function showTicketMassage(id) {
            var text = $('#showTicketMassage-'+id).val();
            $('#status_msg_ticket').val(text);
            $('#MassageTicketButtons').click();
        }
    </script>
@endsection
