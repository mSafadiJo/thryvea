@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Tickets</h4>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item col-6 text-center">
                                <a href="#ReturnLead" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                    Return Lead
                                </a>
                            </li>
                            <li class="nav-item col-6 text-center">
                                <a href="#Issues" data-toggle="tab" aria-expanded="true" class="nav-link">
                                    Issues
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="Issues">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="tab-content">
                                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Type</th>
                                                    <th>username</th>
                                                    <th>Ticket Messsage</th>
                                                    <th>Created At</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if( !empty($ticket_issues) )
                                                    @foreach($ticket_issues as $item)
                                                        <tr>
                                                            <td>{{ $item->ticket_id }}</td>
                                                            <td>Issue</td>
                                                            <td>{{ $nameuser }}</td>
                                                            <td>{{ $item->ticket_message }}</td>
                                                            <td>{{ date('m/d/Y', strtotime($item->created_at)) }}</td>
                                                            <td>
                                                                <select name="ticket_status" class="form-control" style="height: unset;width: 80%;" id="ticket_issues_status_table_Ajax_changing-{{ $item->ticket_id }}"
                                                                        onchange="return ticket_issues_status_table_Ajax_changing('{{ $item->ticket_id }}');"
                                                                        @if( $item->ticket_status == 3 || $item->ticket_status == 4 ) disabled @endif>
                                                                    <option value="1"
                                                                            @if( $item->ticket_status == 1 )
                                                                            selected
                                                                            @endif
                                                                    >Not Started</option>
                                                                    <option value="5"
                                                                            @if( $item->ticket_status == 5 )
                                                                            selected
                                                                        @endif
                                                                    >In Progress</option>
                                                                    <option value="2"
                                                                            @if( $item->ticket_status == 2 )
                                                                            selected
                                                                            @endif
                                                                    >Open</option>
                                                                    <option value="3"
                                                                            @if( $item->ticket_status == 3 )
                                                                            selected
                                                                            @endif
                                                                    >Close</option>
                                                                    <option value="4"
                                                                            @if( $item->ticket_status == 4 )
                                                                            selected
                                                                            @endif
                                                                    >Reject</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show active" id="ReturnLead">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table id="datatable2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Type</th>
                                                        <th>Buyer Name</th>
                                                        <th>Lead ID</th>
                                                        <th>Lead Name</th>
                                                        <th>Lead Phone Number</th>
                                                        <th>Reason</th>
                                                        <th>Ticket Message</th>
                                                        <th>Created At</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if( !empty($ticket_returnlead) )
                                                        @foreach($ticket_returnlead as $item)
                                                            <tr>
                                                                <td>{{ $item->ticket_id }}</td>
                                                                <td>Return Lead</td>
                                                                <td>{{ $nameuser }}</td>
                                                                <td>{{ $item->campaigns_leads_users_id }}</td>
                                                                <td>{{ $item->lead_fname }} {{ $item->lead_lname }}</td>
                                                                <td>{{ $item->lead_phone_number }}</td>
                                                                <td>{{ $item->reason_lead_returned_name }}</td>
                                                                <td>{{ $item->ticket_message }}</td>
                                                                <td>{{ date('m/d/Y', strtotime($item->created_at)) }}</td>
                                                                <td>
                                                                    <select name="ticket_status" class="form-control" style="height: unset;width: 80%;" id="Refundticket_status_table_Ajax_changing-{{ $item->ticket_id }}"
                                                                            onchange="return Refundticket_status_table_Ajax_changing('{{ $item->ticket_id }}');"
                                                                            @if( $item->ticket_status == 3 || $item->ticket_status == 4 ) disabled @endif>
                                                                        <option value="1"
                                                                                @if( $item->ticket_status == 1 )
                                                                                    selected
                                                                                @endif
                                                                        >Not Started</option>
                                                                        <option value="5"
                                                                                @if( $item->ticket_status == 5 )
                                                                                selected
                                                                            @endif
                                                                        >In Progress</option>
                                                                        <option value="2"
                                                                                @if( $item->ticket_status == 2 )
                                                                                selected
                                                                                @endif
                                                                        >Open</option>
                                                                        <option value="3"
                                                                                @if( $item->ticket_status == 3 )
                                                                                selected
                                                                                @endif
                                                                        >Accepted/Close</option>
                                                                        <option value="4"
                                                                                @if( $item->ticket_status == 4 )
                                                                                selected
                                                                                @endif
                                                                        >Reject</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
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
