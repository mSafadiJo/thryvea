@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Tasks</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h6>List Of Tasks</h6>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-bordered taskTable" cellspacing="0" width="100%"
                               @if( empty($permission_users) || in_array('9-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                            <thead>
                            <tr>
                                <th>Task Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Assign From</th>
                                <th>Assigned To</th>
                                <th>Date</th>
                                @if( empty($permission_users) || in_array('9-2', $permission_users) || in_array('9-3', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($tasks) )
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{ $task->task_name }}</td>

                                        <td><a style="cursor: pointer;" data-toggle="modal" data-target="#myModal{{ $task->id }}">{{str_limit($task->description , $limit = 20, $end = '...') }}</a></td>
                                        {{--status test--}}
                                        <td>
                                            @if( empty($permission_users) || in_array('9-2', $permission_users) )
                                            <select name="Task_status" style="height: unset;width: 80%;" class="form-control" id="TaskStatus-{{ $task->id }}"
                                                    onchange="return editTaskStatus('{{ $task->id }}');">
                                                <optgroup label="status">
                                                    @if( $task->status  == "" )
                                                        <option
                                                                @if( $task->status  == "" )
                                                                selected
                                                                @endif
                                                        >-- Choose status --</option>
                                                    @endif
                                                    <option value="Initiation"
                                                            @if( $task->status  == "Initiation" )
                                                            selected
                                                            @endif
                                                    >Initiation</option>
                                                    <option value="In Process"
                                                            @if( $task->status  == "In Process" )
                                                            selected
                                                            @endif
                                                    >In Process</option>
                                                    <option value="Final Stage"
                                                            @if( $task->status  == "Final Stage" )
                                                            selected
                                                            @endif
                                                    >Final Stage</option>
                                                    <option value="Testing Stage"
                                                            @if( $task->status  == "Testing Stage" )
                                                            selected
                                                            @endif
                                                    >Testing Stage</option>
                                                    <option value="Close"
                                                            @if( $task->status  == "Close" )
                                                            selected
                                                            @endif
                                                    >Close</option>
                                                </optgroup>
                                            </select>
                                            @else
                                                {{ $task->status }}
                                            @endif
                                        </td>
                                        {{-- end status test--}}
                                        <td>{{ $task->priority }}</td>
                                        <td>{{ $task->assign_from }}</td>
                                        {{--signed_to test--}}
                                        <td>
                                            @php
                                                //$signed_to = "";
                                                //if( $task->signed_to !== null ){
                                                    $signed_to = json_decode($task->signed_to, true);
                                                    $signed_to = implode(' , ', $signed_to);
                                                //}
                                            @endphp
                                            {{ $signed_to }}
                                        </td>
                                        {{-- end signed_to test--}}
                                        <td>{{  date('Y/m/d', strtotime($task->created_at)) }}</td>
                                        {{--end delete task--}}
                                        @if( empty($permission_users) || in_array('9-2', $permission_users) || in_array('9-3', $permission_users) )
                                            <td>
                                                @if( empty($permission_users) || in_array('9-2', $permission_users) )
                                                    <a href="{{ route('Edit_Tasks_Management', $task->id)  }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                @endif
                                                @if( empty($permission_users) || in_array('9-3', $permission_users) )
                                                    <span data-id='{{ $task->id }}' class="on-default edit-row deleteTask" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-trash-o"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        @endif
                                       {{--end delete task--}}
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if( empty($permission_users) || in_array('9-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="addedSectionLogo pull-right"
                                 onclick='window.location.href ="/Admin/add_tasks";'>
                                <i class="mdi mdi-plus-circle-outline"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->


    <!-- Modal -->
    @if( !empty($tasks) )
        @foreach($tasks as $task)
    <div class="modal fade" id="myModal{{ $task->id }}" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Description Details</h4>
                </div>
                <div class="modal-body">
                    <p>{{$task->description}}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
        @endforeach
    @endif


@endsection
