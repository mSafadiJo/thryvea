@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Update Include & Exclude Sellers</h4>
            </div>
        </div>
    </div>
    <form id="ShopLeadFormUpdateEx" method="POST" action="{{route('ExcludeAndIncludeSellersUpdate')}}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="BuyerID">Buyer</label>
                    <select class="select2 form-control" name="BuyerID"  required>
                        <optgroup label="Buyer">
                            <option value="{{$campaign_Data->user_id}}" selected>{{$campaign_Data->username}} -> {{$campaign_Data->user_business_name}}</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="CampaignsShopLead">Campaigns</label>
                    <select class="select2 form-control" name="CampaignsShopLead" required>
                        <optgroup label="Campaigns">
                            <option value="{{$campaign_Data->campaign_id}}" selected>{{$campaign_Data->campaign_name}}</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="CampaignsShopLead">Types</label>
                    <select class="select2 form-control" name="SellerTypes" required>
                        <optgroup label="Campaigns">
                            <option value="Include" @if ($campaign_Data->exclude_include_type == 'Include') selected @endif>Include</option>
                            <option value="Exclude" @if ($campaign_Data->exclude_include_type == 'Exclude') selected @endif>Exclude</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="seller_id">Seller</label>
                    <select class="select2 form-control select2-multi" multiple name="seller_id[]" id="seller_idEX" data-placeholder="Choose ..." required>
                        <optgroup label="Seller">
                            @php
                                $list_seller_id = json_decode($campaign_Data->exclude_include_campaigns,true);
                            @endphp
                            @foreach($ListOfSellers as $seller)
                                <option value="{{$seller->id}}" @if( in_array($seller->id, $list_seller_id) ) selected @endif>{{$seller->username}} -> {{$seller->user_business_name}}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <input class="btn btn-success form-control" id="submitShopLeadUpdateEx" type="submit" value="Save">
                </div>
            </div>
        </div>
    </form>

    <!-- End Of Page 1-->
@endsection
