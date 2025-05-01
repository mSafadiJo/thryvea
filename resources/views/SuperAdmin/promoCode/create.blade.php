@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Promo Codes</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Add Promo Code</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('PromoCode.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name"><span class="requiredFields"></span>Promo Code</label>
                                <input type="text" class="form-control" id="promo_code" name="promo_code" required="" value="{{ old('promo_code') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for=description"><span class="requiredFields"></span>Value</label>
                                <input type="number" class="form-control" id="value" name="value" required="" value="{{ old('value') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for=description"><span class="requiredFields"></span>Start Date</label>
                                <input type="text" class="form-control" id="datepicker1" autocomplete="false" name="start_date" required="" value="{{ old('start_date') }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for=description"><span class="requiredFields"></span>End Date</label>
                                <input type="text" class="form-control" id="datepicker2" autocomplete="false" name="end_date" required="" value="{{ old('end_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info waves-effect waves-light pull-right">Add</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End row -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted">
                            @foreach( $errors->all() as $error )
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 5-->
@endsection