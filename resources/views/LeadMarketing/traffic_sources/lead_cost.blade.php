@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Add Lead Cost</h4>
            </div>
        </div>
    </div>
    @if($message = Session::get('message'))
        <div class="alert alert-success fade in alert-dismissible show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size:20px">×</span>
            </button>
            {{ $message }}
        </div>
        <?php Session::forget('message');?>
    @endif
    <h6> ** To Add Lead Cost By Traffic Source **</h6>
    <form id="ShopLeadForm" method="POST" action="{{ route('tsLeadCostSave')}}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="ts_name">TS Name</label>
                    <select class="select2 form-control" name="ts_name" id="ts_name" data-type="2" data-placeholder="Choose ..." required>
                        <optgroup label="TS">
                            <option value="">Choose ...</option>
                            @foreach($traffic_source as $source)
                                <option value="{{$source->id}}-{{$source->name}}">{{$source->name}}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="lead_cost">Lead Cost</label>
                    <input type="text" class="select2 form-control" name="lead_cost" id="lead_cost" data-placeholder="Choose ..." required>
                </div>
            </div>
            <div class="col-lg-4">
                <label for="Cost">From Date</label>
                <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-01') }}" autocomplete="false" class="form-control start_date_pagination"/>
            </div>
            <div class="col-lg-4">
                <label for="Cost">To Date</label>
                <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control end_date_pagination"/>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="submit_btn">.</label>
                    <input class="btn btn-success form-control" id="submit_btn" type="submit" value="Save">
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
    <!-- End Of Page 1-->
@endsection
