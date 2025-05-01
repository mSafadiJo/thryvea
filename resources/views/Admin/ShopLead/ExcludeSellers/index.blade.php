@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Include/Exclude Sellers</h4>
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
        <h6> ** To Add New Exclude & Include Sellers **</h6>
        <form id="ShopLeadForm" method="POST" action="{{ route('ExcludeAndIncludeSellersSave')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group BuyerIdShopLead">
                        <label for="BuyerIdShopLead">Buyer</label>
                        <select class="select2 form-control" name="BuyerID" id="BuyerIdShopLeadEx" data-type="2" data-placeholder="Choose ..." required>
                            <optgroup label="Buyer">
                                <option value="">Choose ...</option>
                                @foreach($ListOfBuyers as $Buyers)
                                    <option value="{{$Buyers->id}}">{{$Buyers->username}} -> {{$Buyers->user_business_name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group CampaignsShopLead">
                        <label for="CampaignsShopLead">Campaigns</label>
                        <select class="select2 form-control" name="CampaignsShopLead" disabled id="CampaignsShopLead" data-placeholder="Choose ..." required>

                            <optgroup label="Campaigns">

                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="SellerTypes">Types(Include OR Exclude) </label>
                        <select class="select2 form-control" name="SellerTypes" required>
                            <optgroup label="SellerTypes">
                                <option value="Include" selected>Include</option>
                                <option value="Exclude" >Exclude</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group seller_id">
                        <label for="seller_id">Seller</label>
                        <select class="select2 form-control select2-multi" multiple name="seller_id[]" disabled id="seller_idShopLead" data-placeholder="Choose ..." required>
                            <optgroup label="Seller">
                                @foreach($ListOfSellers as $seller)
                                    <option value="{{$seller->id}}">{{$seller->username}} -> {{$seller->user_business_name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <input class="btn btn-success form-control" id="submitShopLeadEx" type="submit" value="Save">
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
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Buyer business name</th>
                                <th>Campaign Name</th>
                                <th>Seller business name</th>
                                <th>Types (Include OR Exclude)</th>
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
                                        {{$Data->campaign_id}}- {{$Data->campaign_name}}
                                    </td>
                                    @php
                                        $list_of_sellers_data = "";
                                        $list_seller_id = json_decode($Data->exclude_include_campaigns, true);
                                    @endphp
                                    @foreach( $ListOfSellers as $seller )
                                        @if(!empty($list_seller_id) && in_array($seller->id, $list_seller_id))
                                            @php
                                                $list_of_sellers_data .= $seller->user_business_name . ", ";
                                            @endphp
                                        @endif
                                    @endforeach
                                    <td>
                                        {{ rtrim($list_of_sellers_data, ', ') }}
                                    </td>
                                    <td>
                                        {{$Data->exclude_include_type}}
                                    </td>
                                    <td>
                                        {{ $Data->created_exclude_include }}
                                    </td>
                                    @if( empty($permission_users) || in_array('11-2', $permission_users) || in_array('11-3', $permission_users) )
                                        <td>
                                            @if( empty($permission_users) || in_array('11-2', $permission_users) )
                                                <span class="EditTableDataTable"  onclick='window.location.href= "/ExcludeAndIncludeSellersEdit/{{$Data->campaign_id}}"'
                                                      data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-pencil"></i>
                                                </span>
                                            @endif
                                            @if( empty($permission_users) || in_array('11-3', $permission_users) )
                                                <form method="post" action="{{ route('ExcludeAndIncludeSellersDelete', $Data->campaign_id) }}" class="DeleteForm" role="form" id="DeleteForm{{ $Data->campaign_id }}">
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
