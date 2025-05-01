@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Lead Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" id="fname" value="{{ $lead->first_name }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" id="lname" value="{{ $lead->last_name }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" value="{{ $lead->phone_number }}" readonly>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Leads Access Log</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="TCPA_Compliant">TCPA Compliant</label>
                            <input type="text" class="form-control" id="TCPA_Compliant" value="@if( $lead->tcpa_compliant == 1 ) Yes @else No @endif" readonly>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="TCPA_Language">TCPA Language</label>
                            <textarea class="form-control" readonly>
                                    {{ $lead->tcpa_consent_text }}
                                </textarea>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="lead_browser_name">Browser Name</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $lead->lead_browser_name }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="lead_timeInBrowseData">Time In Browse Data</label>
                            <input type="text" class="form-control" id="lead_timeInBrowseData" value="{{ $lead->lead_timeInBrowseData }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="lead_ipaddress">IP Address</label>
                            <input type="text" class="form-control" id="lead_ipaddress" value="{{ $lead->lead_ipaddress }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_serverDomain">Domain</label>
                            <input type="text" class="form-control" id="lead_serverDomain" value="{{ $lead->lead_serverDomain }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Web Site</label>
                            <input type="text" class="form-control" id="website" value="{{ $lead->lead_website }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead TS</label>
                            <input type="text" class="form-control" id="google_ts" value="{{ $lead->google_ts }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead C</label>
                            <input type="text" class="form-control" id="google_c" value="{{ $lead->google_c }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead G</label>
                            <input type="text" class="form-control" id="google_g" value="{{ $lead->google_g }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead K</label>
                            <input type="text" class="form-control" id="google_k" value="{{ $lead->google_k }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="website">Lead gclid</label>
                            <input type="text" class="form-control" id="google_gclid" value="{{ $lead->google_gclid }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="website">Lead Token</label>
                            <input type="text" class="form-control" id="token" value="{{ $lead->token }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="website">Lead Visitor ID</label>
                            <input type="text" class="form-control" id="visitor_id" value="{{ $lead->visitor_id }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_FullUrl">Full URL</label>
                            <textarea class="form-control" readonly>
                                    {{ $lead->lead_FullUrl }}
                            </textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_aboutUserBrowser">About User Browser</label>
                            <textarea class="form-control" readonly>
                                    {{ $lead->lead_aboutUserBrowser }}
                            </textarea>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_browser_name">Trusted Form</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $lead->trusted_form }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_browser_name">Lead Id</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $lead->universal_leadid }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
