@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Access Log</h4>
            </div>
        </div>
    </div>
    <style>
        .input-group-sm>.input-group-btn>select.btn:not([size]):not([multiple]), .input-group-sm>select.form-control:not([size]):not([multiple]), .input-group-sm>select.input-group-addon:not([size]):not([multiple]), select.form-control-sm:not([size]):not([multiple]) {
            height: unset;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">{{ $title_arr[$section] }}</h4>
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4">
                                <input type="text" id="datepicker1" placeholder="From Date" autocomplete="false" value="{{ date('Y-m-d') }}" class="form-control"/>
                            </div>
                            <div class="col-lg-4">
                                <input type="text" id="datepicker2" placeholder="To Date" autocomplete="false" value="{{ date('Y-m-d') }}" class="form-control"/>
                            </div>
                            <div class="col-lg-4">
                                <input type="hidden" id="section_accesslog" autocomplete="false" value="{{ $section }}" class="form-control"/>
                                <button type="button" class="btn btn-primary col-lg-12" id="accessLogFilterDateGeneral">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="accessLogTables">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>User Name</th>
                                <th>User Role</th>
                                <th>Action</th>
                                <th>Ip Address</th>
                                <th>Location</th>
                                <th>Request Method</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty( $accessLogs ) )
                                @foreach( $accessLogs as $val )
                                    <tr>
                                        <td>{{ $val->section_name }}</td>
                                        <td>{{ $val->user_name }}</td>
                                        <td>{{ $val->role_type }}</td>
                                        <td>{{ $val->action }}</td>
                                        <td>{{ $val->ip_address }}</td>
                                        <td>
                                            <?php
                                            $location = $val->location;
                                            $location = str_replace("{", "", $location);
                                            $location = str_replace("}", "", $location);
                                            $location = str_replace('"', '', $location);
                                            $location = str_replace(',', '<br>', $location);
                                            $location = str_replace(':', '&#160;&#160;&#160;:&#160;&#160;&#160;', $location);
                                            ?>
                                            <input type="hidden" value="{{ $location }}" id="accessLogServicelocation-{{ $val->id }}">
                                            <span onclick='ShowLocationpopup("{{ $val->id }}");' class="showpopup" data-toggle="modal" data-target="#con-close-modal">Show Info</span>
                                        </td>
                                        <td>
                                            <?php
                                            $requestData = $val->request_method;
                                            $requestData = str_replace("{", "", $requestData);
                                            $requestData = str_replace("}", "", $requestData);
                                            $requestData = str_replace('"', '', $requestData);
                                            $requestData = str_replace(',', '<br>', $requestData);
                                            $requestData = str_replace(':', '&#160;&#160;&#160;:&#160;&#160;&#160;', $requestData);
                                            ?>
                                            <input type="hidden" value="{{ $requestData }}" id="accessLogServicerequestData-{{ $val->id }}">
                                            <span onclick='ShowrequestDatapopup("{{ $val->id }}");' class="showpopup" data-toggle="modal" data-target="#con-close-modal">Show Info</span>
                                        </td>
                                        <td>{{ $val->created_at }}</td>
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
    <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="ContentData">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
