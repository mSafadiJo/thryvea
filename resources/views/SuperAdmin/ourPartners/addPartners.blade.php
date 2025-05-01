@extends('layouts.adminapp')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Add Partners</h4>
            </div>
        </div>
    </div>
    <div class="card-box">
    <div class="row">
        <div class="col-md-12">
            @if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('OurPartners.store') }}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="form-group">
                    <h5>Enter Partner Name Below:</h5>
                    <input type="text" name="partner" class="form-control" placeholder="Enter New Partner Here" />
                    <h5>Enter more than one partner seperated by tab</h5>
                    <textarea type="text" name="partnerArea" class="form-control"></textarea>
                    <h5>Upload an Excel sheet with partner's names, the column should be named partner</h5>
                    <input type="file" name="file" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success" />
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
