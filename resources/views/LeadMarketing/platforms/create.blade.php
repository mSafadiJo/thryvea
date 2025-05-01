@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Marketing Platforms</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Add Platforms</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route("Platforms.store") }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name"><span class="requiredFields"></span>Name</label>
                                <input type="text" class="form-control" id="name" name="name" required="" value="{{ old('name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info waves-effect waves-light pull-right">Add</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End row -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted">
                            @foreach( $errors->all() as $error )
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 5-->
@endsection
