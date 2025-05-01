@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Send Pro SMS</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('success');?>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('error');?>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('Admin.Marketing.SendProSMS.submit') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="url">Website URL</label><span class="requiredFields">*</span>
                                        <input type="text" class="form-control" name="url" id="url" value="{{ old("url") }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="google_w">TS/W Parameter</label>
                                        <input type="text" class="form-control" name="google_w" id="google_w" value="{{ old("google_w") }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="google_x">C/X Parameter</label>
                                        <input type="text" class="form-control" name="google_x" id="google_x" value="{{ old("google_x") }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="google_y">G/Y Parameter</label>
                                        <input type="text" class="form-control" name="google_y" id="google_y" value="{{ old("google_y") }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="google_z">K/Z Parameter</label>
                                        <input type="text" class="form-control" name="google_z" id="google_z" value="{{ old("google_z") }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="google_token">Token Parameter</label>
                                        <input type="text" class="form-control" name="google_token" id="google_token" value="{{ old("google_token") }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="visitor_id">Visitor ID Parameter</label>
                                        <input type="text" class="form-control" name="visitor_id" id="visitor_id" value="{{ old("visitor_id") }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="phone_list">Phone Numbers List</label><span class="requiredFields">*</span>
                                        <input type="file" class="form-control" name="phone_list" id="phone_list" accept=".xls,.xlsx,.csv" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="content">Content</label><span class="requiredFields">*</span>
                                            <textarea class="form-control" name="content" id="content" required>{{ old("content") }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="schedule_sms" style="display: block;">Schedule SMS</label>
                                            <label class="form-group switch">
                                                <input type="checkbox" name="schedule_sms" id="schedule_sms" value="1" @if(!empty(old('schedule_sms'))) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="schedule_date">Schedule Date</label><span style="color: red;">*required when turning Scheduled SMS on*</span>
                                            <input type="text" name="schedule_date" id="schedule_date" value="{{ old("schedule_date") }}" readonly class="form-control form_datetime">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary form-control" id="submit_button">Send</button>
                                        <button type="submit" class="btn btn-primary form-control" id="submit_button_form" style="display: none">Send</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#submit_button').click(function () {
            if( document.getElementById("phone_list").files.length > 0 && $('#content').val().trim().length > 0 && $('#url').val().trim().length > 0){
                if ($('#schedule_sms').is(':checked') && $('#schedule_date').val().trim().length <= 0) {
                    alert("Pleas Add The Schedule Date");
                } else {
                    $(this).prop('disabled', true);
                    $(this).html('Sending...');
                    $('#submit_button_form').click();
                }
            } else {
                if($('#url').val().trim().length == 0){
                    alert("Pleas Add The URL");
                } else if( document.getElementById("phone_list").files.length == 0 ){
                    alert("Pleas Add Phone List");
                } else if($('#content').val().trim().length == 0){
                    alert("Pleas Add Content Text");
                }
            }
        });
    </script>
    <!-- End Of Page 1-->
@endsection
