@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Exclude Buyers</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp

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

    @if( empty($permission_users) || in_array('11-1', $permission_users) )
        <h6> ** To Add New Exclude Buyers **</h6>
        <form id="ShopLeadFormBuyer" method="POST" action="{{ route('ExcludeBuyersSave')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group BuyerIdShopLead">
                        <label for="ExcludeBuyers1">Buyer A</label>
                        <select class="select2 form-control" name="buyer1" id="ExcludeBuyers1" data-placeholder="Choose ..." required>
                            <optgroup label="Buyer">
                                <option value="">Choose ...</option>
                                @foreach($ListOfBuyers as $Buyers)
                                    <option value="{{$Buyers->id}}">{{$Buyers->user_business_name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group BuyerIdShopLead">
                        <label for="ExcludeBuyers2">Buyer B</label>
                        <select class="select2 form-control" name="buyer2" id="ExcludeBuyers2" data-placeholder="Choose ..." required>
                            <optgroup label="Buyer">
                                <option value="">Choose ...</option>
                                @foreach($ListOfBuyers as $Buyers)
                                    <option value="{{$Buyers->id}}">{{$Buyers->user_business_name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group BuyerIdShopLead">
                        <label for="ExcludeBuyers2">.</label>
                        <input class="btn btn-success form-control" id="submitShopLeadBuyer" type="submit" value="Save">
                    </div>
                </div>
            </div>
        </form>
    @endif

    <hr/>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <h6>** All Result **</h6>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Buyer business name A</th>
                                <th>Buyer business name B</th>
                                <th>Created At</th>
                                @if( empty($permission_users) || in_array('11-2', $permission_users) || in_array('11-3', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $getAllData as $key => $Data )
                                <tr>
                                    <td>
                                        {{$key+1}}
                                    </td>
                                    <td>
                                        {{$Data->user_idA}}- {{$Data->user_business_nameA}}
                                    </td>
                                    <td>
                                        {{$Data->user_idB}}- {{$Data->user_business_nameB}}
                                    </td>
                                    <td>
                                        {{ date('m/d/Y H:s', strtotime($Data->created_at)) }}
                                    </td>
                                    @if( empty($permission_users) || in_array('11-3', $permission_users) )
                                        <td>
                                            @if( empty($permission_users) || in_array('11-3', $permission_users) )
                                                <form method="post" action="{{ route('ExcludeBuyersDelete', $Data->id) }}" class="DeleteForm" role="form" id="DeleteForm{{ $Data->id }}">
                                                    {{ csrf_field() }}
                                                    <span class="DeleteTableDataTable" aria-label="Delete" data-balloon-pos="up"
                                                          onclick='confirmMsgForDelete("{{ $Data->id }}");'>
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
