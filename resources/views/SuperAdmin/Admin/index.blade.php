@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Admins</h4>
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
                <!--Panel heading-->
                @if( empty($permission_users) || in_array('4-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12 pull-right">
                            <a href="{{ route('AdminManagment.create') }}" class="btn btn-rounded btn-info pull-right"><i class="fa fa-plus"></i> {{__('Add New Admin')}}</a>
                        </div>
                    </div>
                @endif
                <br>

                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('List OF Admins') }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Business Name</th>
                                <th>User Type</th>
                                <th>Account Type</th>
                                <th>City</th>
                                <th>Zip Code</th>
                                <th>Created At</th>
                                <th>Active</th>
                                @if( empty($permission_users) || in_array('4-2', $permission_users) || in_array('4-3', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $admins as $key => $admin )
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->username }}</td>
                                    <td>
                                        {{ $admin->email }}<br/>
                                        {{ $admin->user_phone_number }}<br/>
                                        {{ $admin->user_mobile_number }}
                                    </td>
                                    <td>{{ $admin->user_business_name }}</td>
                                    @if( $admin->role_id == 1 )
                                        <td>Super Admin</td>
                                    @else
                                        <td>Admin</td>
                                    @endif
                                    <td>{{ $admin->account_type }}</td>
                                    <td>{{ $admin->zipCode->city->city_name ?? 'N/A' }}</td>
                                    <td>{{ $admin->zipCode->zipCodeList->zip_code_list ?? 'N/A' }}</td>
                                    <td>{{  date('Y/m/d', strtotime($admin->created_at)) }}</td>
                                    <td>
                                        @if( $admin->user_visibility == 1 )
                                            Active
                                        @else
                                            Not Active
                                        @endif
                                    </td>
                                    @if( empty($permission_users) || in_array('4-2', $permission_users) || in_array('4-3', $permission_users) )
                                        <td>
                                            @if( $admin->user_visibility == 1 )
                                                @if( empty($permission_users) || in_array('4-2', $permission_users) )
                                                <a href="{{ route('AdminManagment.edit', $admin->id) }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                @endif
                                                @if( empty($permission_users) || in_array('4-3', $permission_users) )
                                                <form method="post" action="{{ route('AdminManagment.destroy', $admin->id) }}" class="DeleteForm" role="form" id="DeleteForm{{ $admin->id }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <span class="DeleteTableDataTable" aria-label="Delete" data-balloon-pos="up"
                                                          onclick='confirmMsgForDelete("{{ $admin->id }}");'>
                                                        <i class="fa fa-trash-o"></i>
                                                    </span>
                                                </form>
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
