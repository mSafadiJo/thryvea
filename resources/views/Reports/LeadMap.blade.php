@extends('layouts.adminapp')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('map_search') }}" method="GET">
                {{ csrf_field() }}
                <div class="row mt-3">
                    <div class="col-lg-2">
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

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="select2 form-control" name="status" id="status" data-placeholder="Choose ...">
                                <optgroup label="Status">
                                    <option value="1" @if($status_map == 1) selected @endif>All</option>
                                    <option value="2" @if($status_map == 2) selected @endif>Sold</option>
                                    <option value="3" @if($status_map == 3) selected @endif>UnSold</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="service_id">Start Date</label>
                            <input type="text" id="datepicker1" name="start_date" placeholder="From Date"
                                   value="@if(!empty($from_date)) {{ $from_date }} @else {{ date('Y-m-01') }}@endif"
                                   autocomplete="false" class="form-control"/>
                        </div>

                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="service_id">End Date</label>
                            <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="@if(!empty($to_date)) {{ $to_date }} @else {{ date('Y-m-t') }} @endif" autocomplete="false" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-lg-2">
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

            <div id="clicked-state">The State Is : <span> </span> <br/> Touch the map to see the results</div>
            <div id="mapLead" style=""></div>
            <div id="notif" style="width: 500px;"></div>
        </div>
        <div class="col-md-3">

            <ul class="ul_map">
                <h3 class="text-center"> ** Lead Volume **</h3>
                <div class="state_map">
                    <i class="icon_map map_1Lead fa fa-square"></i>
                    <li style="display: inline-block;">200+</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_2Lead fa fa-square"></i>
                    <li style="display: inline-block;">160 - 199</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_3Lead fa fa-square"></i>
                    <li style="display: inline-block;">90 - 159</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_4Lead fa fa-square"></i>
                    <li style="display: inline-block;">75 - 89</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_5Lead fa fa-square"></i>
                    <li style="display: inline-block;">50 - 74</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_6Lead fa fa-square"></i>
                    <li style="display: inline-block;">1 - 49</li>
                </div>
                <div class="state_map">
                    <i class="icon_map map_7Lead fa fa-square"></i>
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
                        <th>Lead#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lead_volume_data as $val)
                        <tr>
                            <td>{{ $val->states }}</td>
                            <td>{{ $val->totallead }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
