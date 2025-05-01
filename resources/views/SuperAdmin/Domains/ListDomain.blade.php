@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Domains</h4>
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
            <div class="card-box DomainList">
                <h4 class="header-title">Domain List</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <table  class="table table-striped table-bordered" cellspacing="0" width="100%"
                                @if( empty($permission_users) || in_array('20-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Domain Name</th>
                                <th>Service Type</th>
                                <th>Service Name</th>
                                <th>Theme Name</th>
                                <th>Created At</th>
                                @if( empty($permission_users) || in_array('20-2', $permission_users) )
                                    <th>Status</th>
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $domains as $Domain )
                                <tr>
                                    <td>{{ $Domain->id }}</td>
                                    <td>{{ $Domain->domain_name }}</td>

                                    @if( $Domain->Service_type == 1 )
                                        <td>Single Service</td>
                                    @elseif($Domain->Service_type == 2 )
                                        <td>multiple Services</td>
                                    @elseif($Domain->Service_type == 3 )
                                        <td>Ignostic Services</td>
                                    @endif

                                    <td>
                                        @if(isset($services[$Domain->id]))
                                            @foreach(($services[$Domain->id]) as $key => $service)
                                                @if( $key != 0 )
                                                    <b>,</b>
                                                @endif
                                                {{ $service->service_campaign_name }}
                                            @endforeach
                                        @endif
                                    </td>
                                        <td>
                                            {{$Domain->theme_name}}
                                        </td>
                                    <td>{{ date('Y/m/d', strtotime($Domain->created_at)) }}</td>

                                    <td>
                                        @if( empty($permission_users) || in_array('20-2', $permission_users) )
                                            <select name="themeactive" style="height: unset;width: 80%;" class="form-control" id="themeactive-{{ $Domain->id }}"
                                                    onchange="return changeDomainStatus('{{ $Domain->id }}','{{ $Domain->domain_name }}');">
                                                <optgroup label="status">
                                                    @if( $Domain->status  == "" )
                                                        <option
                                                            @if( $Domain->status  == "" )
                                                            selected
                                                            @endif
                                                        >-- Choose status --</option>
                                                    @endif
                                                    <option value="1"
                                                            @if( $Domain->status  == 1 )
                                                            selected
                                                        @endif
                                                    >Active</option>
                                                    <option value="0"
                                                            @if( $Domain->status  == 0 )
                                                            selected
                                                        @endif
                                                    >Inactive</option>
                                                </optgroup>
                                            </select>
                                        @else
                                            {{ $Domain->status }}
                                        @endif
                                    </td>
                                    @if( empty($permission_users) || in_array('20-2', $permission_users) )
                                        <td>
                                            <span class="EditTableDataTable"  onclick='window.location.href= "/admin/domain/edit/{{ $Domain->id }}"'
                                                  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-pencil"></i>
                                            </span>
                                            <span data-id='{{ $Domain->id }}' class="on-default edit-row delete_domain" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-trash-o"></i>
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if( empty($permission_users) || in_array('20-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="addedSectionLogo">
                                <a href="{{route("DomainAddForm")}}">
                                    <i class="mdi mdi-plus-circle-outline"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection


