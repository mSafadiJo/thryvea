@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="header-title">Wallet</h4>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="header-title" style="float: right;">Total Amount:
                            @if( !empty( $totalAmmount['total_amounts_value'] ) )
                                {{ $totalAmmount['total_amounts_value'] }}$
                            @else
                                0$
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Visa Number</th>
                                <th>CVV</th>
                                <th>Exp. Date</th>
                                <th>Address</th>
                                <th>Zip-Code</th>
                                <th>Primary</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($payments) )
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_full_name }}</td>
                                        <td>{{ $payment->payment_visa_type }}-{{ $payment->payment_visa_number }}</td>
                                        <td>{{ $payment->payment_cvv }}</td>
                                        <td>{{ $payment->payment_exp_month }}/{{ $payment->payment_exp_year }}</td>
                                        <td>{{ $payment->payment_address }}</td>
                                        <td>{{ $payment->payment_zip_code }}</td>
                                        <td>
                                            @if( $payment->payment_primary == 1 )
                                                Ture
                                            @else
                                                False
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route("Admin.Buyers.Wallet.edit", $payment->payment_id) }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <form style="display: inline-block" action="{{ route('Admin.Buyers.Wallet.delete') }}" method="post" id="DeletePaymentnForm-{{ $payment->payment_id }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" class="form-control" value="{{ $payment->payment_id }}" name="payment_id">
                                            </form>
                                            <span style="cursor: pointer;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                                  onclick="return DeletePaymentnForm('{{ $payment->payment_id }}');">
                                                <i class="fa fa-trash-o"></i>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="addedSectionLogo pull-right"
                             onclick='window.location.href ="{{ route('Admin.Buyers.Wallet.create', $user_id) }}";'>
                            <i class="mdi mdi-plus-circle-outline"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection