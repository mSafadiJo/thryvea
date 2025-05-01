@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Promo Codes</h4>
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
                <h4 class="header-title">Promo Codes List</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%"
                               @if( empty($permission_users) || in_array('2-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Promo Code</th>
                                    <th>Value</th>
                                    <th>Start Date</th>
                                    <th>To Date</th>
                                    <th>Created At</th>
                                    @if( empty($permission_users) || in_array('2-2', $permission_users) || in_array('2-3', $permission_users) )
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($promoCodes))
                                @foreach($promoCodes as $item)
                                    <tr>
                                        <td>{{ $item->promo_code_id }}</td>
                                        <td>{{ $item->promo_code }}</td>
                                        <td>{{ $item->promo_code_value }}</td>
                                        <td>{{ date('Y/m/d', strtotime($item->promo_code_from_date)) }}</td>
                                        <td>{{ date('Y/m/d', strtotime($item->promo_code_to_date)) }}</td>
                                        <td>{{ date('Y/m/d', strtotime($item->created_at)) }}</td>
                                        @if( empty($permission_users) || in_array('2-2', $permission_users) || in_array('2-3', $permission_users) )
                                            <td>
                                                @if( empty($permission_users) || in_array('2-2', $permission_users) )
                                                    <span class="EditTableDataTable"  onclick='window.location.href= "{{ route( 'PromoCode.edit', $item->promo_code_id ) }}"'
                                                          data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-pencil"></i>
                                                    </span>
                                                @endif
                                                @if( empty($permission_users) || in_array('2-3', $permission_users) )
                                                    <form method="post" action="{{ route( 'PromoCode.destroy', $item->promo_code_id ) }}" class="DeleteForm" role="form" id="DeleteForm{{ $item->promo_code_id }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <span class="DeleteTableDataTable"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                                              onclick='confirmMsgForDelete("{{ $item->promo_code_id }}");'>
                                                                <i class="fa fa-trash-o"></i>
                                                        </span>
                                                    </form>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if( empty($permission_users) || in_array('2-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="addedSectionLogo" onclick="window.location.href = '{{ route("PromoCode.create") }}'">
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