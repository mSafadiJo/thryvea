@extends('layouts.NavBuyerHome')

@section('content')

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-smile-o" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">leads</p>
                    <h3 class="card-title">{{$LeadsCount}}</h3>
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
                <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-frown-o" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">Return Leads</p>
                    <h3 class="card-title">{{$ticket_returnlead}}</h3>
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
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-building-o" aria-hidden="true"></i>

                    </div>
                    <p class="card-category">Active Campaign</p>
                    <h3 class="card-title">{{$campaignsCount}}</h3>
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
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">Current Balance</p>
                    <h3 class="card-title">${{ (!empty($totalAmmount->total_amounts_value) ? $totalAmmount->total_amounts_value : 0) }}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">date_range</i> All Days
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">Total Spent</p>
                    @php
                        $total_spend_final = (!empty($total_spend) ? (!empty($list_of_return_amount) ? $total_spend - $list_of_return_amount : $total_spend) : 0);
                    @endphp
                    <h3 class="card-title">${{$total_spend_final}}</h3>
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
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-usd" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">Total Funded</p>
                    <h3 class="card-title">${{$total_bid}}</h3>
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
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                    <p class="card-category">Last Transaction</p>
                    <h3 class="card-title">@if( !empty($last_transaction->date) ){{ date('m/d/Y', strtotime($last_transaction->date)) }}@endif</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">date_range</i> All Days
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card home-table">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">List Of Active Service</h4>
                </div>
                <div class="card-body table-responsive h-f">
                    <table class="table table-hover">
                        <thead class="text-warning">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1; ?>
                        @foreach($services as $service)
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$service}}</td>
                            </tr>
                            <?php $count++; ?>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card home-table">
                <div class="card-header card-header-warning">
                    <h4 class="card-title">Today Leads</h4>
                </div>
                <div class="card-body table-responsive h-f">
                    <table class="table table-hover">
                        <thead class="text-warning">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($LeadsToday as $Leads)
                            <tr>
                                <td>{{ $Leads->lead_id }}</td>
                                <td>{{ $Leads->lead_fname . " " . $Leads->lead_lname }}</td>
                                <td>{{ $Leads->lead_email }}</td>
                                <td>{{ $Leads->lead_phone_number }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
