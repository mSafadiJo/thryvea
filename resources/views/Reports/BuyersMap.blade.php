@extends('layouts.adminapp')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('Buyers_map_search') }}" method="GET">
                {{ csrf_field() }}
                <div class="row mt-3">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="service_id">Service Name</label>
                            <select class="select2 form-control select2-multiple" multiple name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                <optgroup label="Service">
                                    @if( !empty( $service_map ) )
                                        @foreach( $service_map as $service )
                                            <option value="{{ $service->service_campaign_id }}"
                                                    @if(!empty($service_id_map))
                                                    @foreach( $service_id_map as $servicesss )
                                                    @if($service->service_campaign_id ==$servicesss)
                                                    selected
                                                @endif
                                                @endforeach
                                                @endif
                                            >{{ $service->service_campaign_name }}</option>
                                        @endforeach
                                    @endif
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="User_Type">User Type</label>
                            <select class="select2 form-control select2-multiple" multiple name="user_type[]" id="user_type" data-placeholder="Choose ...">
                                <optgroup label="User Type">
                                    <option value="3" @if(!empty($UserMap) && in_array(3, $UserMap)) selected @endif> Buyer </option>
                                    <option value="4" @if(!empty($UserMap) && in_array(4, $UserMap)) selected @endif> Aggregator </option>
                                    <option value="6" @if(!empty($UserMap) && in_array(6, $UserMap)) selected @endif> Enterprise </option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="Search"></label>
                                    <button style="margin-top: 3%;" type="submit" class="btn btn-primary col-lg-12" id="">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <div class="row">
        <div class="col-md-9">

            <input id="state_map_1" type="hidden" value="{{json_encode($array_filiter_state_1)}}">
            <input id="state_map_2" type="hidden" value="{{json_encode($array_filiter_state_2)}}">
            <input id="state_map_3" type="hidden" value="{{json_encode($array_filiter_state_3)}}">
            <input id="state_map_4" type="hidden" value="{{json_encode($array_filiter_state_4)}}">
            <input id="state_map_5" type="hidden" value="{{json_encode($array_filiter_state_5)}}">
            <input id="state_map_6" type="hidden" value="{{json_encode($array_filiter_state_6)}}">
            <input id="state_map_7" type="hidden" value="{{json_encode($array_filiter_state_7)}}">

            <div id="clicked-state">The State Is : <span> </span> <br/> Touch the map to see the results</div>
            <div id="map" style=""></div>
            <div id="notif" style="width: 500px;"></div>
        </div>
        <div class="col-md-3">

            <ul class="ul_map">
                <h4 class="text-center"> ** Buyers Volume **</h4>
                <div class="state_map">
                    <i class="icon_map map_1 fa fa-square"></i>
                    <li style="display: inline-block;">10+</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_2 fa fa-square"></i>
                    <li style="display: inline-block;">6-10</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_3 fa fa-square"></i>
                    <li style="display: inline-block;">5</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_4 fa fa-square"></i>
                    <li style="display: inline-block;">4</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_5 fa fa-square"></i>
                    <li style="display: inline-block;">3</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_6 fa fa-square"></i>
                    <li style="display: inline-block;">2</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_7 fa fa-square"></i>
                    <li style="display: inline-block;">1</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_8 fa fa-square"></i>
                    <li style="display: inline-block;">0</li>
                </div>
            </ul>

        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <table class="table table-striped table-bordered lead-buyer-map" id="datatable" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>State</th>
                        <th>Buyer#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($array_data as $val)
                        <tr>
                            <td>{{ $val['state'] }}</td>
                            <td>{{ $val['total'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
