@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Add Task</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Tasks Details</h4>
                        <!-- Basic Form Wizard -->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="form-horizontal row" action="{{ route('TasksStore') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="firstname">Task Name<span class="requiredFields">*</span></label>
                                                        <input type="text" class="form-control" id="tasktname" name="tasktname" placeholder="" required="">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="priority">priority<span class="requiredFields">*</span></label>
                                                        <select id="priorityList" class="select form-control" name="priority" required="" data-placeholder="Choose ...">
                                                            <optgroup label="priority">
                                                                <option value="High">High</option>
                                                                <option value="Medium" selected>Medium</option>
                                                                <option value="Low">Low</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="description">Description<span class="requiredFields">*</span></label>
                                                        <textarea  class="form-control" id="description" name="description" placeholder="" required> </textarea>
                                                    </div>
                                                </div>

                                                <input type="hidden" class="form-control" id="buyername" name="buyername" placeholder="" value="{{Auth::user()->username}}">

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="status">status<span class="requiredFields"></span></label>
                                                        <select id="statusList" class="select form-control" name="status" data-placeholder="Choose ...">
                                                            <optgroup label="status">
                                                                <option value="Initiation" selected>Initiation</option>
                                                                <option value="In Process">In Process</option>
                                                                <option value="Final Stage">Final Stage</option>
                                                                <option value="Testing Stage">Testing Stage</option>
                                                                <option value="Close">Close</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="Signed To">Assigned To<span class="requiredFields"></span></label>
                                                        <select id="SignedToList" class="select2 form-control select2-multiple" multiple name="signedto[]" data-placeholder="Choose ...">
                                                            <optgroup label="Signed To">
                                                                <option value="">-- Choose Signed To --</option>
                                                                <option value="Mahmood">Mahmood</option>
                                                                <option value="Tariq">Tariq</option>
                                                                <option value="Salam">Salam</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <button type="submit" class="btn btn-info waves-effect waves-light pull-right">Submit</button>
                                </form>
                            </div>
                        </div>

                        <!-- End row -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted" id="pErrorsShown">
                                    @foreach( $errors->all() as $error )
                                        {{ $error }}<br>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--@if(isset($errors))--}}
        {{--<script>--}}
            {{--$(document).ready(function(){--}}
                {{--var stateid = $('#State_id_aj').val();--}}
                {{--var cityId = $('#City_id_aj').val();--}}
                {{--$('#datepicker_Visa').datepicker({--}}
                    {{--format: "mm-yyyy",--}}
                    {{--startView: "months",--}}
                    {{--minViewMode: "months"--}}
                {{--});--}}

                {{--$.ajax({--}}
                    {{--url: urlcitiesSelected,--}}
                    {{--method: "POST",--}}
                    {{--data: {--}}
                        {{--stateid: stateid,--}}
                        {{--cityId: cityId,--}}
                        {{--_token: token--}}
                    {{--},--}}
                    {{--success: function(the_result){--}}
                        {{--$('#cityList').html(the_result.select);--}}
                        {{--$('#stateList option[value='+the_result.state_id+']').attr('selected','selected');--}}
                    {{--}--}}
                {{--});--}}
                {{--$('#datepicker_Visa').datepicker({--}}
                    {{--format: "mm-yyyy",--}}
                    {{--startView: "months",--}}
                    {{--minViewMode: "months"--}}
                {{--});--}}
            {{--});--}}
        {{--</script>--}}
    {{--@endif--}}
    <!-- End Of Page 1-->
@endsection