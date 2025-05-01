@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Pixels</h4>
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
                <h4 class="header-title">Pixels List</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <table  class="table table-striped table-bordered" cellspacing="0" width="100%"
                                @if( empty($permission_users) || in_array('21-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pixels Type</th>
                                <th>Domain</th>
                                <th>Traffic Source</th>
                                <th>pixels Code</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $pixels as $pixel )
                                <tr>
                                    <td>{{ $pixel->id }}</td>
                                    <td>{{ $pixel->type }}</td>
                                    <td>{{ $pixel->domain_name }}</td>
                                    <td>{{ $pixel->ts_name }}</td>
                                    <td>{{ $pixel->pixels_name }}</td>
                                    <td>{{ date('Y/m/d', strtotime($pixel->created_at)) }}</td>
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

