@include('include.var')
<!-- jQuery  -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/tether.min.js') }}"></script><!-- Tether for Bootstrap -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
{{--<script src="{{ asset('js/metisMenu.min.js') }}"></script>--}}
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/modernizr.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('js/jquery.core.js') }}"></script>
{{--<script src="{{ asset('js/jquery.app.js') }}"></script>--}}
<!-- themes js -->
<script src="{{ asset('js/themes/theme.js') }}"></script>
<!-- domains js -->
<script src="{{ asset('js/domains/domain.js') }}"></script>

<script src="{{ asset('plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>

<script src="{{ asset('plugins/datatables/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.bootstrap4.min.js') }}"></script>

<script src="{{ asset('ajax/allAjaxPages.js') }}"></script>
<script src="{{ asset('js/AllJs.js') }}"></script>
<script src="{{ asset('js/newLogIn.js') }}"></script>
<script src="{{ asset('js/campaign_services.js') }}"></script>
<script src="{{ asset('js/formsjs.js') }}"></script>

<!-- map files -->
<script src="{{ asset('js/raphael.js') }}"></script>
<script src="{{ asset('js/jquery.usmap.js') }}"></script>
<script src="{{ asset('js/map.js') }}"></script>
<script src="{{ asset('js/ShopLead/ShopLead.js') }}"></script>

<!-- campaign  City And Zipcode Filter files -->
<script src="{{ asset('js/campaignCityAndZipcodeFilter.js') }}"></script>

<script src="{{ asset('js/jquery.creditCardValidator.js') }}"></script>
<script src="{{ asset('js/credit-card-js.js') }}"></script>
<script src="{{ asset('js/timeslotCampaign.js') }}"></script>
{{--<script src="{{ asset('ajax/campainaddress.js') }}"></script>--}}
<script src="{{ asset('ajax/reports.js') }}"></script>
<script src="{{ asset('ajax/fillterAdmin.js') }}"></script>
<script src="{{ asset('js/dillerProject.js') }}"></script>

<!--Form Wizard-->
@if( Request::is("register") )
    <script src="{{ asset('plugins/jquery.stepy.regestration/jquery.stepy.js') }}" type="text/javascript"></script>
@else
    <script src="{{ asset('plugins/jquery.stepy/jquery.stepy.js') }}" type="text/javascript"></script>
@endif

<!--wizard initialization-->
<script src="{{ asset('pages/jquery.wizard-init.js') }}" type="text/javascript"></script>

<!-- plugin js -->
<script src="{{ asset('plugins/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/clockpicker/js/bootstrap-clockpicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- select 2-->
<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.15.0/lodash.min.js"></script>
<script src="{{ asset('plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
{{--<!-- tags input0-->--}}

@if( !(Request::is("BuyersPayPayment") || Route::is("Transaction.Value.Create") || Route::is('Transaction.Value.StoreOther')
     || Route::is('AddValuePayment') || Route::is('Admin.Buyers.payments') || Route::is('Transaction.Value.Create.Admin')
     || Route::is('Transaction.Value.StoreOtherAdmin')) )

    <script src="{{ asset('plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('pages/jquery.form-advanced.init.js') }}" type="text/javascript"></script>
@endif

<script src="{{ asset('plugins/magnific-popup/js/jquery.magnific-popup.min.js') }}" type="text/javascript"></script>

{{--<!-- Init js -->--}}
<script src="{{ asset('pages/jquery.form-pickers.init.js') }}"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

{{--Date Time Picker--}}
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>

@if( Route::is("Sales.Dashboard") )
    <script src="{{ asset('js/SalesDashboard/dashboard.js') }}"></script>
@endif
@if( Route::is("CallCenter.Dashboard") )
    <script src="{{ asset('js/SalesDashboard/callCenterDashboard.js') }}"></script>
@endif
