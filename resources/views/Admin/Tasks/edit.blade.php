@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Edit Task</h4>
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
                                <form id="form-horizontal row" action="{{ route('Update_Tasks_Management', $task->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row m-t-20">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="firstname">Task Name<span class="requiredFields">*</span></label>
                                                        <input type="text" class="form-control" id="tasktname" name="tasktname" value="{{ $task->task_name }}" placeholder="" required="">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="priority">priority<span class="requiredFields">*</span></label>
                                                        <select id="priorityList" class="select form-control" name="priority" required data-placeholder="Choose ...">
                                                            <optgroup label="priority">
                                                                <option value="High" @if( $task->priority == "High" ) selected @endif>High</option>
                                                                <option value="Medium" @if( $task->priority == "Medium" ) selected @endif>Medium</option>
                                                                <option value="Low" @if( $task->priority == "Low" ) selected @endif>Low</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="description">Description<span class="requiredFields">*</span></label>
                                                        <textarea  class="form-control" id="description" name="description" placeholder="" required>
                                                            {{ $task->description }}
                                                        </textarea>
                                                    </div>
                                                </div>

                                                <input type="hidden" class="form-control" id="buyername" name="buyername" placeholder="" value="{{Auth::user()->username}}">


                                                @if( Auth::user()->role_id == 1 )


                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="status">status<span class="requiredFields"></span></label>
                                                            <select id="statusList" class="select form-control" name="status" data-placeholder="Choose ...">
                                                                <optgroup label="status">
                                                                    <option value="Initiation" @if( $task->status == "Initiation" ) selected @endif>Initiation</option>
                                                                    <option value="In Process" @if( $task->status == "In Process" ) selected @endif>In Process</option>
                                                                    <option value="Final Stage" @if( $task->status == "Final Stage" ) selected @endif>Final Stage</option>
                                                                    <option value="Testing Stage" @if( $task->status == "Testing Stag" ) selected @endif>Testing Stage</option>
                                                                    <option value="Close" @if( $task->status == "Close" ) selected @endif>Close</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    @php
                                                        $signed_to = json_decode($task->signed_to, true);
                                                    @endphp
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="Signed To">Assigned To<span class="requiredFields"></span></label>
                                                            <select id="SignedToList" class="select2 form-control select2-multiple" multiple name="signedto[]" data-placeholder="Choose ...">
                                                                <optgroup label="Signed To">
                                                                    <option value="" @if( empty($signed_to) || in_array("", $signed_to) ) selected @endif>-- Choose Signed To --</option>
                                                                    <option value="Mahmood" @if( in_array("Mahmood", $signed_to) ) selected @endif>Mahmood</option>
                                                                    <option value="Tariq" @if( in_array("Tariq", $signed_to) ) selected @endif>Tariq</option>
                                                                    <option value="Salam" @if( in_array("Salam", $signed_to) ) selected @endif>Salam</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
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
@endsection