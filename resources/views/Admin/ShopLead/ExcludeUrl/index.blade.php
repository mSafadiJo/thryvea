@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Exclude URL</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    @if( empty($permission_users) || in_array('11-1', $permission_users) )
        <h6>** To Add New Exclude URL **</h6>
        @if($message = Session::get('message'))
            <div class="alert alert-danger fade in alert-dismissible show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" style="font-size:20px">×</span>
                </button>
                {{ $message }}
            </div>
            <?php Session::forget('message');?>
        @endif

        <form id="ShopLeadForm" method="POST" action="{{ route('ExcludeUrlSave')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group BuyerIdShopLead">
                        <label for="BuyerIdShopLeadEx">Buyer</label>
                        <select class="select2 form-control" name="BuyerId" id="BuyerIdShopLeadEx" data-type="3" data-placeholder="Choose ..." required>
                            <optgroup label="Buyer">
                                <option value="">Choose ...</option>
                                @foreach($ListOfBuyers as $Buyers)
                                    <option value="{{$Buyers->id}}" @if(old("BuyerId") == $Buyers->id) selected @endif>{{$Buyers->username}} -> {{$Buyers->user_business_name}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @if ($errors->has('BuyerId'))
                            <span class="text-danger">{{ $errors->first('BuyerId') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group CampaignsShopLead">
                        <label for="CampaignsShopLead">Campaigns</label>
                        <select class="select2 form-control" name="CampaignsShopLead" disabled id="CampaignsShopLead" data-placeholder="Choose ..." required>
                            <optgroup label="Campaigns">

                            </optgroup>
                        </select>
                        @if ($errors->has('CampaignsShopLead'))
                            <span class="text-danger">{{ $errors->first('CampaignsShopLead') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="SourcesList">Add URL List</label><br>
                        <span>for example: Url1,Url2,Url3</span>
                        <textarea class="form-control rounded-0" name="UrlList" id="SourcesList" required rows="1">
                            {{ old("UrlList") }}
                        </textarea>
                        @if ($errors->has('UrlList'))
                            <span class="text-danger">{{ $errors->first('UrlList') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <input class="btn btn-success form-control" type="submit" value="Save">
                    </div>
                </div>
            </div>
        </form>
    @endif

    <hr/>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <h6>** All Result **</h6>
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Buyer business name</th>
                                <th>Campaign Name</th>
                                <th>URL List</th>
                                @if( empty($permission_users) || in_array('11-2', $permission_users) || in_array('11-3', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($campaign_Data as $key => $data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data->id}} - {{$data->user_business_name}}</td>
                                    <td>{{$data->campaign_id}} - {{$data->campaign_name}}</td>
                                    <td>
                                        <span style='cursor: pointer;' onclick='return show_script_text_data("{{ $data->campaign_id }}", "URL list", "campaign_sources-", "html")'>{{ \Illuminate\Support\Str::limit(implode(", ", json_decode($data->exclude_url)), $limit = 10, $end = '...') }}</span>
                                        <div id="campaign_sources-{{ $data->campaign_id }}" style="display: none;">{{implode(", ", json_decode($data->exclude_url))}}</div>
                                    </td>
                                    @if( empty($permission_users) || in_array('11-2', $permission_users) || in_array('11-3', $permission_users) )
                                        <td>
                                            @if( empty($permission_users) || in_array('11-2', $permission_users) )
                                                <span class="EditTableDataTable"  onclick='window.location.href= "/ExcludeUrlEdit/{{$data->campaign_id}}"'
                                                      data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-pencil"></i>
                                                </span>
                                            @endif
                                            @if( empty($permission_users) || in_array('11-3', $permission_users) )
                                                <form method="post" action="{{ route('ExcludeUrlDelete', $data->campaign_id) }}" class="DeleteForm" role="form" id="DeleteForm{{ $data->campaign_id }}">
                                                    {{ csrf_field() }}
                                                    <span class="DeleteTableDataTable" aria-label="Delete" data-balloon-pos="up"
                                                          onclick='confirmMsgForDelete("{{ $data->campaign_id }}");'>
                                                        <i class="fa fa-trash-o"></i>
                                                    </span>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <button type="button" id="script_campaign_model_btn" class="btn btn-primary" data-toggle="modal" data-target="#script_campaign_model" style="display: none;"></button>
    <div class="modal fade bd-example-modal-lg" id="script_campaign_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="script_campaign_span"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div id="script_campaign_div"></div>
                                <style>
                                    #script_campaign_div > div {
                                        white-space: break-spaces !important;
                                    }
                                    #script_campaign_div{
                                        word-break: break-all;
                                        white-space: break-spaces;
                                    }
                                </style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function show_script_text_data(id, title, id_text, type) {
            if(type == "val"){
                var text = $('#'+id_text+id).val();
            } else {
                var text = $('#'+id_text+id).html();
            }
            $('#script_campaign_span').html(title);
            $('#script_campaign_div').html(text);
            $('#script_campaign_model_btn').click();
        }
    </script>
    <!-- End Of Page 1-->
@endsection
