@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Lead Form Details</h4>
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
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" id="fname" value="{{ $ListOfLeadFormDetails->lead_fname }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" id="lname" value="{{ $ListOfLeadFormDetails->lead_lname }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" value="{{ $ListOfLeadFormDetails->lead_email }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" value="{{ $ListOfLeadFormDetails->lead_phone_number }}" readonly>
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
                            <input type="text" class="form-control" id="state" value="{{ $ListOfLeadFormDetails->state }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="county">County</label>
                            <input type="text" class="form-control" id="county" value="{{ $ListOfLeadFormDetails->county }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" value="{{ $ListOfLeadFormDetails->city }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="Street">Street Address</label>
                            <input type="text" class="form-control" id="Street" value="{{ $ListOfLeadFormDetails->address }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="ZipCode">ZipCode</label>
                            <input type="text" class="form-control" id="ZipCode" value="{{ $ListOfLeadFormDetails->lead_zipcode }}" readonly>
                        </div>
                    </div>
                </div>


                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="nutureofpro">Service</label>
                            <input type="text" class="form-control" id="nutureofpro" value="{{$ListOfLeadFormDetails->offer}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="website">Traffic Source</label>
                            <input type="text" class="form-control" id="traffic_source" value="{{ $ListOfLeadFormDetails->traffic_source }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="numberOfitem">Preferred Contact Method</label>
                            <input type="text" class="form-control" id="numberOfitem" value="{{$ListOfLeadFormDetails->preferred_contact_method}}" readonly>
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
                    {{--                    <div class="col-sm-3">--}}
                    {{--                        <div class="form-group">--}}
                    {{--                            <label for="website">Lead TS</label>--}}
                    {{--                            <input type="text" class="form-control" id="google_ts" value="S1-LF" readonly>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Trusted Form</label>
                            <input type="text" class="form-control" id="trusted_form" value="{{ $ListOfLeadFormDetails->trusted_form }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Lead Id</label>
                            <input type="text" class="form-control" id="leadId" value="{{ $ListOfLeadFormDetails->leadId }}" readonly>
                        </div>
                    </div>
                </div>

                {{--                <div class="row">--}}
                {{--                    <div class="col-sm-12">--}}
                {{--                        <div class="form-group">--}}
                {{--                            <label for="lead_FullUrl">Full URL</label>--}}
                {{--                             <textarea class="form-control" readonly>--}}
                {{--                                    https://windows-estimate.com?ts=s1lf&gclid={{$ListOfLeadFormDetails->gcl_id}}--}}
                {{--                             </textarea>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>

        </div>
    </div>

    <!-- End Of Page 1-->
@endsection
