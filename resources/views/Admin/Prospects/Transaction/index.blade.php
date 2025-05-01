@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Prospect Transactions</h4>
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
                <!--Panel heading-->
                @if( empty($permission_users) || in_array('14-5', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12 pull-right">
                            <a href="{{ route('Prospects.create')}}" class="btn btn-rounded btn-info pull-right" data-toggle="modal" data-target="#con-close-modal"><i class="fa fa-plus"></i> {{__('Prospects')}}</a>
                        </div>
                    </div>
                @endif
                <br>

                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('List OF Prospects') }}</h3>
                        {{--<div class="pull-right clearfix">--}}
                            {{--<form id="sort_table_form" action="" method="GET">--}}
                                {{--<div class="box-inline pad-rgt pull-left">--}}
                                    {{--<div class="select" style="min-width: 200px;">--}}
                                        {{--<select class="form-control select2" name="type" id="type" onchange="sort_list_table()">--}}
                                            {{--<option value="">Sort by</option>--}}
                                            {{--<option value="prospect_transactions.created_at,desc" @isset($col_name , $query) @if($col_name == 'prospect_transactions.created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>--}}
                                            {{--<option value="prospect_transactions.created_at,asc" @isset($col_name , $query) @if($col_name == 'prospect_transactions.created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="box-inline pad-rgt pull-left">--}}
                                    {{--<div class="" style="min-width: 200px;">--}}
                                        {{--<select class="form-control select2" name="search" id="search" onchange="sort_list_table()">--}}
                                            {{--<option value="">Search by</option>--}}
                                            {{--<option value="Call" @isset($sort_search) @if($sort_search == 'Call') selected @endif @endisset>{{__('Call')}}</option>--}}
                                            {{--<option value="Email" @isset($sort_search) @if($sort_search == 'Email') selected @endif @endisset>{{__('Email')}}</option>--}}
                                            {{--<option value="Meeting" @isset($sort_search) @if($sort_search == 'Meeting') selected @endif @endisset>{{__('Meeting')}}</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</form>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        {{--<table class="table table-striped table-bordered" cellspacing="0" width="100%">--}}
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Business Name</th>
                                <th>Type</th>
                                <th>Note</th>
                                <th>Claimer</th>
                                <th>Created By</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($prospect_transactions as $key => $prospect_transaction)
                                <tr>
                                    {{--<td>{{ ($key+1) + ($prospect_transactions->currentPage() - 1)*$prospect_transactions->perPage() }}</td>--}}
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $prospect_transaction->prospect_username }}</td>
                                    <td>{{ $prospect_transaction->prospect_user_business_name }}</td>
                                    <td>{{ $prospect_transaction->type }}</td>
                                    <td>
                                        <a style="cursor: pointer;" class="trx_description_a" data-id="{{ $prospect_transaction->id }}">{{\Illuminate\Support\Str::limit($prospect_transaction->note , $limit = 20, $end = '...') }}</a>
                                        <span style="display: none" id="description-{{ $prospect_transaction->id }}">{{ $prospect_transaction->note }}</span>
                                    </td>
                                    <td>
                                        <b>SDR:</b> {{ !empty($prospect_transaction->sdr_claimer) ? $prospect_transaction->sdr_claimer : "---" }}<br/>
                                        <b>Sales:</b> {{ !empty($prospect_transaction->sales_claimer) ? $prospect_transaction->sales_claimer : "---" }}
                                    </td>
                                    <td>{{ $prospect_transaction->admin_username }}</td>
                                    <td>{{  date('Y/m/d h:i A', strtotime($prospect_transaction->created_at)) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{--<div class="clearfix">--}}
                            {{--<div class="pull-right">--}}
                                {{--{{ $prospect_transactions->appends(request()->input())->links() }}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->

    <button type="hidden" data-toggle="modal" data-target="#myModal-descriptions" id="button-model-trx-desc" style="display: none;">.</button>
    <div class="modal fade" id="myModal-descriptions" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Description Details</h4>
                </div>
                <div class="modal-body">
                    <p id="descriptions-text"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Start Model Sections -->
    <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add Transactions</h4>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="{{ route('Prospects.transaction_store') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="transaction_type">Transaction Type<span class="requiredFields">*</span></label>
                                                <select class="form-control" name="type" required>
                                                    <option value="Call">Call</option>
                                                    <option value="Email">Email</option>
                                                    <option value="Meeting">Meeting</option>
                                                </select>
                                                <input type="hidden" class="form-control" value="{{ $id }}" name="prospect_id" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="transaction_type">Transaction Note</label>
                                                <textarea class="form-control" name="note">

                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light pull-right">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Model Sections -->
@endsection
