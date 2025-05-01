@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Edit Buyers Rev_Share</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Rev_Share</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('Rev_ShareSave') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="campaign_id">Campaign</label>
                                <select class="select2 form-control" name="campaign_id" id="campaign_id" required="" data-placeholder="Choose ...">
                                    <optgroup label="Campaign Name">
                                        @if( !empty( $campaigns ) )
                                            @foreach( $campaigns as $campaign )
                                                <option value="{{ $campaign->campaign_id }}">{{ $campaign->campaign_name }}</option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" id="price" name="price" placeholder="" required="">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control"/>
                        </div>
                        <div class="col-lg-4">
                            <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control"/>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary col-lg-12">submit</button>
                        </div>
                    </div>
                </form>
                <!-- End row -->
                <div>
                    @if ($message = Session::get('message'))
                        <div class="alert fade in alert-dismissible show" style="background-color: #e2e3e5;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true" style="font-size:20px">×</span>
                            </button>
                            {{ $message }}
                        </div>
                        <?php Session::forget('message');?>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 5-->
@endsection
