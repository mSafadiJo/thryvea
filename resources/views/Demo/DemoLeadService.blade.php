@extends('layouts.appDemo')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Campaign Lead Details</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" id="fname" value="Mike" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" id="lname" value="Smai" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" value="mike@petradigitalmedia.com" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" value="71145155465" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Campaign Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="campname">Campaign Name</label>
                            <input type="text" class="form-control" id="campname" value="Solor Campaign" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="service">Service</label>
                            <input type="text" class="form-control" id="service" value="Solor" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Address</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" value="CALIFORNIA" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="county">County</label>
                            <input type="text" class="form-control" id="county" value="LOS ANGELES => CA" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" value="COVINA => CA" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="Street">Street Address</label>
                            <input type="text" class="form-control" id="Street" value="10685B Hazelhurst Dr. # 25182" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="ZipCode">ZipCode</label>
                            <input type="text" class="form-control" id="ZipCode" value="91722" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="numberOfitem">Lead Token</label>
                            <input type="text" class="form-control" id="numberOfitem" value="RMLRWtp6YSDjmHUFEx0pEufR2XbZDUuOuyrfiab7" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="numberOfitem">Lead ID</label>
                            <input type="text" class="form-control" id="numberOfitem" value="{{ $id }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="bid">Bid</label>
                            <input type="text" class="form-control" id="bid" value="50" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="numberOfitem">Solar Power Solution</label>
                            <input type="text" class="form-control" id="numberOfitem" value="Solar for my Business" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="numberOfitem">Sun Exposure</label>
                            <input type="text" class="form-control" id="numberOfitem" value="Not Sure" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="numberOfitem">Current Utility Provider</label>
                            <input type="text" class="form-control" id="numberOfitem" value="Garrett Electric Utilities" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="numberOfitem">Monthly Electricity Bill</label>
                            <input type="text" class="form-control" id="numberOfitem" value="100$ - 200$" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="numberOfitem">Property Type</label>
                            <input type="text" class="form-control" id="numberOfitem" value="Business" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection