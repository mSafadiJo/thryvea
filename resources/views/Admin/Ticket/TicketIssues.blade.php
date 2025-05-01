@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Issue Tickets</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <input type="text" id="datepicker1" placeholder="From Date" autocomplete="false" class="form-control StartDate"
                                                           value="{{ date('Y-m-d', strtotime('-7 days')) }}"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <input type="text" id="datepicker2" placeholder="To Date" autocomplete="false" class="form-control EndDate"
                                                           value="{{ date('Y-m-d') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <select class="select2 form-control select2-multiple" multiple name="ticket_status_id[]" id="ticket_status_id" data-placeholder="Choose ...">
                                                        <option value="1">Not Started</option>
                                                        <option value="5">In Progress</option>
                                                        <option value="2">Open</option>
                                                        <option value="3">Close</option>
                                                        <option value="4">Reject</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary col-lg-12" id="ShowIssueTicketAjax">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content">
                                    <div id="dataAjaxTablecrm">
                                        <table class="table table-striped table-bordered" cellspacing="0" width="100%"
                                               @if( empty($permission_users) || in_array('22-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Type</th>
                                                    <th>username</th>
                                                    <th>Ticket Message</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
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
        </div>
    </div>
    <!-- End Of Page 1-->

    <!-- Button trigger modal -->
    <button type="button" id="rejqctTicketModelButton" class="btn btn-primary" data-toggle="modal" data-target="#rejqctTicketModel" style="display: none;">
    </button>

    <!-- Modal -->
    <div class="modal" id="rejqctTicketModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Why you want to reject this ticket?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="ticket_id" id="form_ticket_id">
                        <input type="hidden" name="type" id="form_type">
                        <input type="hidden" name="status" id="form_status">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="firstname">Ticket Message</label>
                                    <textarea class="form-control" name="ticket_message" id="form_ticket_message" required>

                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="issuesTicketformAdminUserCloseModel">Close</button>
                    <button type="button" class="btn btn-primary" id="issuesTicketformAdminUser">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection
