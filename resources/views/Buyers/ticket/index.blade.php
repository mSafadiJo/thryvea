@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 1-->
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
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('success');?>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('error');?>
                        @endif
                    </div>
                </div>
                <h6>Add Ticket</h6>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#issues">Issues</button>
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#returnlead">Return Lead</button>
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#returnPolicy">Return Policy</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h6>Issues</h6>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Ticket Message</th>
                                <th>Status</th>
                                <th>Status Message</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($ticket_issues) )
                                @foreach($ticket_issues as $item)
                                    <tr>
                                        <td>{{ $item->ticket_id }}</td>
                                        <td>Issue</td>
                                        <td>{{ $item->ticket_message }}</td>
                                        <td>
                                            @if( $item->ticket_status == 1 )
                                                Not Started
                                            @elseif( $item->ticket_status == 2 )
                                                Open
                                            @elseif( $item->ticket_status == 3 )
                                                Close
                                            @elseif( $item->ticket_status == 4 )
                                                Reject
                                            @elseif( $item->ticket_status == 5 )
                                                In Progress
                                            @endif
                                        </td>
                                        <td>
                                            @if( $item->ticket_status == 4 )
                                                <button type="button" class="btn btn-primary" onclick="showTicketMassage('{{ $item->ticket_id }}');">show</button>
                                                <textarea id="showTicketMassage-{{ $item->ticket_id }}" name="showTicketMassage-{{ $item->ticket_id }}" style="display: none;">
                                                    {{ $item->reject_text }}
                                                </textarea>
                                            @endif
                                        </td>
                                        <td>{{ date('Y/m/d', strtotime($item->created_at)) }}</td>
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
    <!-- End Of Page 1-->

    <!-- Modal -->
    <div class="modal" id="returnlead" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Why do you want to return this lead?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="{{ route('buyersTicket.store.returnlead') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @if( !empty($reason_lead_returneds) )
                            @foreach( $reason_lead_returneds as $key => $item )
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="container-dayoff">{{ $item->reason_lead_returned_name }}
                                            <input type="radio" class="user_privileges_service" name="reason_returned" value="{{ $item->reason_lead_returned_id }}" @if($key == 0) required @endif>
                                            <span class="checkmark-dayoff"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="firstname">Lead id#</label>
                                    <input type="text" class="form-control" id="lead_id" name="lead_id" placeholder="" required value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="firstname">Ticket Message</label>
                                    <textarea class="form-control" name="ticket_message" id="ticket_message" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="issues" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Issue Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('buyersTicket.store.issues') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="firstname">Ticket Message</label>
                                    <textarea class="form-control" name="ticket_message" id="ticket_message" required></textarea>
                                </div>
                            </div>
                        </div>
                        <input type="submit" style="display: none;" id="issuesTicketform">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                            onclick="document.getElementById('issuesTicketform').click();document.getElementById('issuesTicketform').disabled = true;">Add</button>
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
                                <textarea class="form-control" id="status_msg_ticket" required></textarea>
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

    <!-- Modal  -->
    <div class="modal" id="returnPolicy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Return Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <ul>
                                    <li>Disconnected numbers / Nonworking Numbers.</li>
                                    <li>Bogus Information.</li>
                                    <li>Miscategorized leads (Does not match your filters).</li>
                                    <li>Duplicates.</li>
                                    <li>Consumers with no interest.</li>
                                </ul>
                                <p>- Leads cannot be returned after 15 days of receiving them.</p>
                                <p>- A maximum of 15% of the leads received will be approved and acknowledged as returns.</p>
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

@endsection
