@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Themes</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Theme List</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <table  class="table table-striped table-bordered" cellspacing="0" width="100%"
                                @if( empty($permission_users) || in_array('19-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Theme Name</th>
                                <th>Type</th>
                                <th>Services</th>
                                <th>Theme Image</th>
                                <th>Created At</th>
                                <th>Status</th>
                                @if( empty($permission_users) || in_array('19-2', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $themes as $theme )
                                <tr>
                                    <td>{{ $theme->id }}</td>
                                    <td>{{ $theme->theme_name }}</td>
                                    <td>
                                        @if( $theme->service_type == 1 )
                                            Single Service
                                        @elseif( $theme->service_type == 2 )
                                            Multi Service
                                        @elseif( $theme->service_type == 3 )
                                            Ignostic Service
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($theme_services[$theme->id]))
                                            @foreach($theme_services[$theme->id] as $key => $item_theme)
                                                @if( $key != 0 )
                                                    <b>,</b>
                                                @endif
                                                {{ $item_theme->service_campaign_name }}
                                            @endforeach
                                        @else
                                            no service selected
                                        @endif
                                    </td>
                                    <td><a data-toggle="modal" data-target="#exampleModal-{{$theme->id}}" >
                                            <img style="width: 100%; max-height: 12vh; min-height: 12vh;" src="{{ url($theme->theme_img) }}" alt="" /></a></td>
                                    <td>{{ date('Y/m/d', strtotime($theme->created_at)) }}</td>

                                    <td>
                                        @if( empty($permission_users) || in_array('19-2', $permission_users) )
                                            <select name="themeactive" style="height: unset;width: 80%;" class="form-control" id="themeactive-{{ $theme->id }}"
                                                    onchange="return changeThemeStatusSA('{{ $theme->id }}','{{ $theme->theme_name }}');">
                                                <optgroup label="status">
                                                    @if( $theme->status  == "" )
                                                        <option
                                                            @if( $theme->status  == "" )
                                                            selected
                                                            @endif
                                                        >-- Choose status --</option>
                                                    @endif
                                                    <option value="1"
                                                            @if( $theme->status  == 1 )
                                                            selected
                                                        @endif
                                                    >Active</option>
                                                    <option value="0"
                                                            @if( $theme->status  == 0 )
                                                            selected
                                                        @endif
                                                    >Inactive</option>
                                                </optgroup>
                                            </select>
                                        @else
                                            {{ $theme->status }}
                                        @endif
                                    </td>
                                    @if( empty($permission_users) || in_array('19-2', $permission_users) )
                                        <td>
                                            <span class="EditTableDataTable"  onclick='window.location.href= "/admin/themes/edit/{{ $theme->id }}"'
                                                  data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-pencil"></i>
                                            </span>
                                            <span data-id='{{ $theme->id }}' class="on-default edit-row deleteTheme" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-trash-o"></i>
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal-{{$theme->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <img style="width: 100%; max-height: 20vh; min-height: 20vh;" src="{{ url($theme->theme_img) }}" alt="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if( empty($permission_users) || in_array('19-1', $permission_users) )
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="addedSectionLogo">
                                <a href="{{route("ThemeAddForm")}}">
                                    <i class="mdi mdi-plus-circle-outline"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection

