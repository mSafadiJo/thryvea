@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Setting</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    @if( empty($permission_users) || in_array('25-3', $permission_users) )
        <form id="ShopLeadForm" method="POST" action="{{ route('Admin.site.setting.save')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6" style="border-radius: 10px;background-color: #fff;border: 1px solid rgba(0, 0, 0, .125);padding: 20px;">
                    <div class="form-group">
                        <h5 class="font-weight-semibold">Add New Exclude Sources For Bot Unsold Lead</h5>
                        <span>for example: source1,source2,source3</span>
                        <textarea class="form-control rounded-0" name="SourcesList" id="SourcesList" required rows="1">
                            {{ $sourcesString }}
                        </textarea>
                        @if(empty($sourcesString))
                            <small>No sources added yet.</small>
                        @endif
                    </div>
                    @if( empty($permission_users) || in_array('25-4', $permission_users) )
                        <div class="form-group">
                            <input class="btn btn-success form-control" type="submit" value="Save">
                        </div>
                    @endif
                </div>
            </div>

        </form>
    @endif
    <!-- End Of Page 1-->
@endsection
