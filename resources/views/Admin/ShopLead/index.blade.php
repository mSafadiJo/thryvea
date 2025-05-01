@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Sources Percentage</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    @if( empty($permission_users) || in_array('11-1', $permission_users) )
        <h6> ** To Add New Sources Percentage **</h6>
        <form id="ShopLeadForm" method="POST" action="{{ route('saveShopLead')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group BuyerIdShopLead">
                        <label for="BuyerIdShopLead">Buyer</label>
                        <select class="select2 form-control" name="BuyerID" id="BuyerIdShopLead" data-type="1" data-placeholder="Choose ...">
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
                    <div class="form-group CampaignsShopLead">
                        <label for="county">Campaigns</label>
                        <select class="select2 form-control" name="CampaignsShopLead" disabled id="CampaignsShopLead" data-placeholder="Choose ...">

                            <optgroup label="Campaigns">

                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group LeadSource">
                        <label for="county">Lead Source</label>
                        <select class="select2 form-control" name="LeadSourceShopLead" disabled id="LeadSourceShopLead" data-placeholder="Choose ...">
                            <optgroup label="Lead Source">
                                <option value="">Choose ...</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row inputShopLead">

            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <input class="btn btn-success form-control" id="submitShopLead" type="submit" disabled value="Save">
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
                                <th>Buyer business name</th>
                                <th>Campaign Name</th>
                                <th>Source & percentage</th>
                                <th>Created At</th>
                                @if( empty($permission_users) || in_array('11-2', $permission_users) || in_array('11-3', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $campaign_Data as $key => $Data )
                                <tr>
                                    <td>
                                        {{$key+1}}
                                    </td>
                                    <td>
                                        {{$Data->id}}- {{$Data->user_business_name}}
                                    </td>
                                    <td>
                                        {{$Data->campaign_id}} - {{$Data->campaign_name}}
                                    </td>
                                    <td>
                                        @foreach( $platforms as $key )
                                            @php
                                                $percentage_value_array =  json_decode($Data->percentage_value,true) ;
                                            @endphp

                                            @if(!empty($percentage_value_array)&&array_key_exists($key->id, $percentage_value_array))
                                                {{ $key->name . " : " . $percentage_value_array[$key->id] . "," }}
                                            @endif
                                        @endforeach

                                    </td>
                                    <td>
                                        {{$Data->created_percentage_value}}
                                    </td>
                                    @if( empty($permission_users) || in_array('11-2', $permission_users) || in_array('11-3', $permission_users) )
                                        <td>
                                            @if( empty($permission_users) || in_array('11-2', $permission_users) )
                                                <span class="EditTableDataTable"  onclick='window.location.href= "/ShopLeadsEdit/{{$Data->campaign_id}}"'
                                                      data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-pencil"></i>
                                                </span>
                                            @endif
                                            @if( empty($permission_users) || in_array('11-3', $permission_users) )
                                                <form method="post" action="{{ route('ShopLeadsDelete', $Data->campaign_id) }}" class="DeleteForm" role="form" id="DeleteForm{{ $Data->campaign_id }}">
                                                    {{ csrf_field() }}
                                                    <span class="DeleteTableDataTable" aria-label="Delete" data-balloon-pos="up"
                                                          onclick='confirmMsgForDelete("{{ $Data->campaign_id }}");'>
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
