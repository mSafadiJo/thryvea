@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Services</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Services List</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <table  class="table table-striped table-bordered" cellspacing="0" width="100%"
                                @if( empty($permission_users) || in_array('1-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service Name</th>
                                <th>Service Description</th>
                                <th>Created At</th>
                                @if( empty($permission_users) || in_array('1-2', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $services as $service )
                                <tr>
                                    <td>{{ $service->service_campaign_id }}</td>
                                    <td>{{ $service->service_campaign_name }}</td>
                                    <td>{{ $service->service_campaign_description }}</td>
                                    <td>{{ date('Y/m/d', strtotime($service->created_at)) }}</td>
                                    @if( empty($permission_users) || in_array('1-2', $permission_users) )
                                        <td>
                                            <span class="EditTableDataTable"  onclick='window.location.href= "AdminServices/Edit/{{ $service->service_campaign_id }}"'
                                                  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-pencil"></i>
                                            </span>

                                            <label class="col-md-6 switch" data-toggle="tooltip"
                                                   @if( $service->service_is_active == 1 ) data-original-title="In Active" @else data-original-title="Active" @endif data-trigger="hover" data-animation="false">
                                                <input type="checkbox" name="serviceactive" id="serviceactive" value="1"
                                                       @if( $service->service_is_active == 1 ) checked @endif
                                                        onchange="return changeServiceStatusSA('{{ $service->service_campaign_id }}', '{{ $service->service_campaign_name }}')">
                                                <span class="slider round"></span>
                                            </label>
                                            <style>
                                                .switch {
                                                    position: relative;
                                                    display: inline-block;
                                                    width: 30px;
                                                    height: 25px;
                                                }

                                                .switch input {
                                                    opacity: 0;
                                                    width: 0;
                                                    height: 0;
                                                }

                                                .slider {
                                                    position: absolute;
                                                    cursor: pointer;
                                                    top: 0;
                                                    left: 0;
                                                    right: 0;
                                                    bottom: 0;
                                                    background-color: #ccc;
                                                    -webkit-transition: .4s;
                                                    transition: .4s;
                                                }

                                                .slider:before {
                                                    position: absolute;
                                                    content: "";
                                                    height: 19px;
                                                    width: 19px;
                                                    left: 3px;
                                                    bottom: 3px;
                                                    background-color: white;
                                                    -webkit-transition: .4s;
                                                    transition: .4s;
                                                }

                                                input:checked + .slider {
                                                    background-color: #64c5b1;
                                                }

                                                input:focus + .slider {
                                                    box-shadow: 0 0 1px #2196F3;
                                                }

                                                input:checked + .slider:before {
                                                    -webkit-transform: translateX(26px);
                                                    -ms-transform: translateX(26px);
                                                    transform: translateX(26px);
                                                }

                                                /* Rounded sliders */
                                                .slider.round {
                                                    border-radius: 34px;
                                                }

                                                .slider.round:before {
                                                    border-radius: 50%;
                                                }
                                            </style>

                                            {{--<form method="post" action="AdminServices/Delete/{{ $service->service_campaign_id }}" class="DeleteForm" role="form" id="DeleteForm{{ $service->service_campaign_id }}">--}}
                                                {{--{{ csrf_field() }}--}}

                                                {{--<span class="DeleteTableDataTable"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"--}}
                                                      {{--onclick='confirmMsgForDelete("{{ $service->service_campaign_id }}");'>--}}
                                                    {{--<i class="fa fa-trash-o"></i>--}}
                                                {{--</span>--}}
                                            {{--</form>--}}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if( empty($permission_users) || in_array('1-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="addedSectionLogo" onclick="window.location.href = 'AdminServices/Add'">
                                <i class="mdi mdi-plus-circle-outline"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
