@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
<div class="table-responsive">
    <table id="pagination-table" class="table table-striped table-bordered" data-action = "/CRMResponse/fetch_data?page=">
        <thead>
        <tr>
            <th>Id</th>
            <th>lead Id</th>
            <th>PING Id</th>
            <th>Campaign Name</th>
            <th>Type</th>
            <th>Result</th>
            <th>Time</th>
            <th>Create Date</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($CrmReport as $Crm)
                <tr>
                    <td>{{ $Crm->id  }}</td>
                    @if($crm_id == 1)
                        <td>{{ $Crm->lead_id  }}</td>
                    @else
                        <td>{{ $Crm->campaigns_leads_users_id  }}</td>
                    @endif
                    <td>{{ $Crm->ping_id  }}</td>
                    <td>{{ $Crm->campaign_name  }}</td>
                    <td>{{ $Type  }}</td>

                    {{--Change response to text only--}}
                    @php
                        $result_response_data = "";
                        $array_char_empty = ['"', "'", '{', '}', '[', ']', '#', '$', '@', '/', '(', ')', '\\'];
                        $array_char_space = [",", '|', '_'];
                        if( !empty($Crm->response) ){
                            $result_response = json_decode($Crm->response, true);
                            if( is_array($result_response) ) {
                                //If Json Response
                                $result_response_data = trim(str_replace($array_char_empty, '', $Crm->response));
                                $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                                $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                                $result_response_data = strip_tags($result_response_data);
                            } else {
                                try {
                                    libxml_use_internal_errors(true);
                                    $result2 = simplexml_load_string($Crm->response);
                                    $result3 = json_encode($result2);
                                    $result4 = json_decode($result3  , TRUE);

                                    if( !empty($result4) ){
                                        if( is_array($result4) ) {
                                            //If XML Response
                                            $result_response_data = trim(str_replace($array_char_empty, '', $result3));
                                            $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                                            $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                                            $result_response_data = strip_tags($result_response_data);
                                        }
                                    }
                                } catch (Exception $e){

                                }
                            }
                        }
                        if( $result_response_data == "" ){
                            //If Empty OR Text Response
                            $result_response_data = trim(str_replace($array_char_empty, '', $Crm->response));
                            $result_response_data = trim(str_replace(":", ': ', $result_response_data));
                            $result_response_data = trim(str_replace($array_char_space, ' ', $result_response_data));
                            $result_response_data = strip_tags($result_response_data);
                        }
                    @endphp
                    <td>{{ $result_response_data }}</td>
                    <td>{{ $Crm->time  }}</td>
                    <td>{{ date('Y/m/d', strtotime($Crm->created_at))  }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    Showing {{($CrmReport->currentPage()-1)* $CrmReport->perPage()+($CrmReport->total() ? 1:0)}} to {{($CrmReport->currentPage()-1)*$CrmReport->perPage()+count($CrmReport)}}  of  {{$CrmReport->total()}}  Results
    {!! $CrmReport->links() !!}
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
</div>
