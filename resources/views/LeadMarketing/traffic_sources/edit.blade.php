@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Marketing Traffic Sources</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Update Traffic Sources</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route("TrafficSources.update", $traffic_source->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name"><span class="requiredFields"></span>Name</label>
                                <input type="text" class="form-control" id="name" name="name" required="" value="{{ $traffic_source->name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="marketing_platform_id"><span class="requiredFields"></span>Platforms</label>
                                <select class="select2 form-control" id="marketing_platform_id" name="marketing_platform_id" required data-placeholder="Choose ...">
                                    <optgroup label="Platforms">
                                        @foreach( $platforms as $val )
                                            <option value="{{ $val->id }}" @if( $traffic_source->marketing_platform_id == $val->id ) selected @endif>{{ $val->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info waves-effect waves-light pull-right">Save changes</button>
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
