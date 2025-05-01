@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Prospects</h4>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <!--Panel heading-->
                <div class="row">
                    <div class="col-lg-12 pull-right">
                        @if( empty($permission_users) || in_array('14-1', $permission_users) )
                            <a href="{{ route('Prospects.create') }}" class="btn btn-rounded btn-info pull-right mr-2"><i class="fa fa-plus"></i> {{__('Add New Prospects')}}</a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 pull-right">
                        <form action="{{ route('Admin.Prospects.Export') }}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="service_id">Service</label>
                                        <select class="select2 form-control select2-multiple" multiple name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                            <optgroup label="Service">
                                                @foreach( $services as $service )
                                                    <option value="{{ $service->service_campaign_name }}">{{ $service->service_campaign_name }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="buyer_id">State</label>
                                        <select class="select2 form-control select2-multiple" name="states[]" id="state_id" multiple="multiple" data-placeholder="Choose ...">
                                            <optgroup label="States">
                                                @foreach($states as $state)
                                                    <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="claimer_prospect">Sales Executive name</label>
                                        <input type="text" class="form-control" id="claimer_prospect" name="claimer_prospect">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m') }}-1" autocomplete="false" class="form-control"/>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-t') }}" autocomplete="false" class="form-control"/>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        @if( empty($permission_users) || in_array('17-4', $permission_users) )
                                            <div class="col-lg-6">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterProspectUsers">Search</button>
                                            </div>
                                            <div class="col-lg-6">
                                                <button type="submit" class="btn btn-primary col-lg-12">Export</button>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterProspectUsers">Search</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <br>
                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('List OF Prospects') }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="dataAjaxTablecrm">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Business Name</th>
                                <th>Contact Name</th>
                                <th>Contact</th>
                                <th>State</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Sales Executive</th>
                                <th>Created At</th>
                                <th>Statistics</th>
                                @if( empty($permission_users) || in_array('14-2', $permission_users) || in_array('14-3', $permission_users)
                                    || in_array('14-4', $permission_users) || in_array('14-5', $permission_users)|| in_array('14-6', $permission_users))
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
                                                <input type="hidden" class="form-control" value="" id="prospect_id_menu" name="prospect_id" required>
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
