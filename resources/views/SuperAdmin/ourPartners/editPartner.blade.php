@extends('layouts.adminapp')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Edit Partners</h4>
            </div>
        </div>
    </div>
    <div class="card-box">
    <div class="row">
        <div class="col-md-12">
            <br />
            <br />
            @if(count($errors) > 0)

                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                    @endif
                    <form method="post" action="{{ route('OurPartners.update', $id) }}">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="PATCH" />
                        <div class="form-group">
                            <input type="text" name="partner" class="form-control" value="{{$partner->partner}}" placeholder="Enter Partner Name" />
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Edit" />
                        </div>
                    </form>
                </div>
        </div>
    </div>
@endsection
