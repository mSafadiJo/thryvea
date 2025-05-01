@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Themes</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Add Theme</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('AdminThemeStore') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name"><span class="requiredFields"></span>Theme Name</label>
                                <input type="text" class="form-control" id="name" name="name" required="" value="{{ old('name') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="Service Type">Service Type<span class="requiredFields"></span></label>
                                <select id="service_type" class="select form-control" name="service_type" data-placeholder="Choose ..." required>
                                    <optgroup label="service_type">
                                        <option value="1">Single Service</option>
                                        <option value="2">multiple Services</option>
                                        <option value="3">Ignostic Services</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 ServiceName">
                            <div class="form-group">
                                <label for="Service Name">Service Name<span class="requiredFields"></span></label>
                                <select id="service_id" style="width:100%" class="select2 form-control" multiple name="service_id[]" data-placeholder="Choose Service ...">
                                    <optgroup label="Choose ...">
                                        <option value="">-- Choose service name --</option>
                                        @foreach($fetchAllService as $Service)
                                            <option value="{{ $Service->service_campaign_id }}">{{ $Service->service_campaign_name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>

                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Upload Image</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input type="file" name="Theme_img" id="imgInp" required>
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <img id='img-upload'/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success waves-effect waves-light pull-right">Add</button>
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
