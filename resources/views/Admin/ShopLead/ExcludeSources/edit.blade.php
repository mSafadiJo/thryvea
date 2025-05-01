@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Update Exclude Sources</h4>
            </div>
        </div>
    </div>
    <form id="ShopLeadFormUpdateEx" method="POST" action="{{route('ExcludeSourcesUpdate')}}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="hidden" name="BuyerId" value="{{$campaign_Data->user_id}}">
                    <label for="BuyerID">Buyer</label>
                    <input type="text" class="form-control" disabled id="BuyerID" value="{{$campaign_Data->user_id}} - {{$campaign_Data->user_business_name}}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="hidden" name="CampaignsShopLead" value="{{$campaign_Data->campaign_id}}">
                    <label for="CampaignsShopLead">Campaigns</label>
                    <input type="text" class="form-control" disabled id="CampaignsShopLead" value="{{$campaign_Data->campaign_id}} - {{$campaign_Data->campaign_name}}">
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="SourcesList">Add Sources List</label><br>
                    <span>for example: source1,source2,source3 </span>
                    <textarea class="form-control rounded-0" name="SourcesList" id="SourcesList" required rows="1">{{implode(",", json_decode($campaign_Data->exclude_sources))}}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <input class="btn btn-success form-control" id="" type="submit" value="Save">
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-12 text-center">
            <p class="text-muted">
                @foreach( $errors->all() as $error )
                    {{ $error }}<br>
                @endforeach
            </p>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
