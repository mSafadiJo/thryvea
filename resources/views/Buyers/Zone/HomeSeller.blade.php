@extends('layouts.NavBuyerHome')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">leads</p>
                    <h3 class="card-title">
                        @if( !empty($CountSellerLead) )
                            {{ count($CountSellerLead) }}
                        @else
                            0
                        @endif
                    </h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">date_range</i> All Days
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">Daily Sales</p>
                    <h3 class="card-title">{{$CountSellerLeadDailies}}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">Weekly Sales</p>
                    <h3 class="card-title">{{$CountSellerLeadWeekly}}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">Monthly Sales</p>
                    <h3 class="card-title">{{$CountSellerLeadMonthly}}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
