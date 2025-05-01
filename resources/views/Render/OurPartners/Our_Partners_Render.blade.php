@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
<div class="table-responsive">
    <table id="pagination-table" class="table table-striped table-bordered" data-action="/OurPartners/FetchData?page=">
        <thead>
        <tr>
            <th>Partner</th>
            <th>Created At</th>
            @if( empty($permission_users) || in_array('24-2', $permission_users) || in_array('24-3', $permission_users) )
                <th>Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach( $partners as $row )
            <tr>
                <td>{{ $row->partner }}</td>
                <td>{{ date('Y/m/d', strtotime($row->created_at)) }}</td>
                <td>
                    @if( empty($permission_users) || in_array('24-2', $permission_users) )
                        <span class="EditTableDataTable"  onclick='window.location.href= "{{ route('OurPartners.edit', $row->id) }}"'
                              data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                            <i class="fa fa-pencil"></i>
                        </span>
                    @endif
                    @if( empty($permission_users) || in_array('24-3', $permission_users) )
                        <form method="post" action="{{ route( 'OurPartners.destroy', $row->id ) }}" class="DeleteForm" role="form" id="DeleteForm{{ $row->id }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <span class="DeleteTableDataTable"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                  onclick='confirmMsgForDelete("{{ $row->id }}");'>
                                <i class="fa fa-trash-o"></i>
                            </span>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    Showing {{($partners->currentPage()-1)* $partners->perPage()+($partners->total() ? 1:0)}} to {{($partners->currentPage()-1)*$partners->perPage()+count($partners)}}  of  {{$partners->total()}}  Results
    {!! $partners->links() !!}
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
</div>
