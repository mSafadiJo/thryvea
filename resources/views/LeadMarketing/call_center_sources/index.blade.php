@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Call Center Sources</h4>
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
                @if( empty($permission_users) || in_array('13-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12 pull-right">
                            <a href="{{ route("CallCenterSources.create") }}" class="btn btn-rounded btn-info pull-right"><i class="fa fa-plus"></i> Add New Call Center Sources</a>
                        </div>
                    </div>
                @endif
                <br>

                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">List Of Call Center Sources</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <table  class="table table-striped table-bordered" cellspacing="0" width="100%"
                                @if( empty($permission_users) || in_array('13-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created At</th>
                                @if(empty($permission_users) || in_array('13-2', $permission_users) || in_array('13-3', $permission_users))
                                <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($call_center_source as $val)
                                <tr>
                                    <td>{{ $val->id }}</td>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ date('Y/m/d', strtotime($val->created_at)) }}</td>
                                    @if(empty($permission_users) || in_array('13-2', $permission_users) || in_array('13-3', $permission_users))
                                        <td>
                                            @if(empty($permission_users) || in_array('13-2', $permission_users))
                                                <span class="EditTableDataTable"  onclick='window.location.href= "{{ route('CallCenterSources.edit', $val->id) }}"'
                                                      data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                    <i class="fa fa-pencil"></i>
                                                </span>
                                            @endif
                                            @if(empty($permission_users) || in_array('13-3', $permission_users))
                                                <form method="post" action="{{ route('CallCenterSources.destroy', $val->id) }}" class="DeleteForm" role="form" id="DeleteForm{{ $val->id }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <span class="DeleteTableDataTable"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                                          onclick='confirmMsgForDelete("{{ $val->id }}");'>
                                                        <i class="fa fa-trash-o"></i>
                                                    </span>
                                                </form>
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
