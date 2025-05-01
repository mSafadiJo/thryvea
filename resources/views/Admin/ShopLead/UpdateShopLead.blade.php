@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Update Sources Percentage</h4>
            </div>
        </div>
    </div>
    <form id="ShopLeadFormUpdate" method="POST" action="{{route('UpdateShopLead')}}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="BuyerID">Buyer</label>
                    <select class="select2 form-control" name="BuyerID">
                        <optgroup label="Buyer">
                            <option value="{{$campaign_Data->user_id}}" selected>{{$campaign_Data->user_business_name}}</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="CampaignsShopLead">Campaigns</label>
                    <select class="select2 form-control" name="CampaignsShopLead">
                        <optgroup label="Campaigns">
                            <option value="{{$campaign_Data->campaign_id}}" selected>{{$campaign_Data->campaign_name}}</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            @foreach($platforms as $platform)
                <div class="col-sm-4">
                    <label for="county">{{$platform->name}}</label>
                    <div class="form-group LeadSource">
                        <input type="text" class="form-control percentageValueUpdate" name="{{$platform->id}}"
                               value="@if(array_key_exists($platform->id,$percentage_value_array)){{$percentage_value_array[$platform->id]}}@endif"/>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <input class="btn btn-success form-control" id="submitShopLeadUpdae" type="submit"  value="Save">
                </div>
            </div>
        </div>
    </form>

    <!-- End Of Page 1-->
@endsection
