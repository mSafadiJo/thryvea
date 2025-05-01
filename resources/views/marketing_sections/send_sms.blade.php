@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Send SMS</h4>
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
                        <form action="{{ route('Admin.Marketing.SendSMS.submit') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="phone_list">Phone Numbers List</label>
                                        <input type="file" class="form-control" name="phone_list" id="phone_list" accept=".xls,.xlsx,.csv" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <textarea class="form-control" name="content" id="content" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
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
            if( document.getElementById("phone_list").files.length > 0 && $('#content').val().trim().length > 0 ){
                $(this).prop('disabled', true);
                $(this).html('Sending...');
                $('#submit_button_form').click();
            } else {
                if( document.getElementById("phone_list").files.length == 0 ){
                    alert("Pleas Add Phone List");
                } else if($('#content').val().trim().length == 0){
                    alert("Pleas Add Content Text");
                }
            }
        });
    </script>
    <!-- End Of Page 1-->
@endsection
