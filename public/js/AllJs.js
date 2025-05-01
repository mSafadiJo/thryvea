$(document).ready(function(){
    $('.wordInLinkDiv').on("mouseenter", function (){
        $('.BuyerButton').addClass('widthButtonBS');
    });

    $("#myModal").modal('show');

    if($('#permission_users').val() == "upTo30"){
        $('#datepicker1').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
        });
    }else{
        $('#datepicker1').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            startDate: '-30d'
        });
    }
    
    $('#datepicker2').datepicker({
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });

    $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
    $(".datetimepicker thead tr:first-child th.prev").html('<');
    $(".datetimepicker-days thead tr:nth-child(1) th").removeClass('switch');
    $(".datetimepicker thead tr:first-child th.next").html('>');

    $('.timepicker').timepicker({
        timeFormat: 'h:mm p',
    });

    $("#password1").keyup(function(){
        var pass2 = $("#password1").val();
        var pass1 = $("#password").val();
        if( pass1 != pass2 ){
            $("#password1").css("border-color", "rgba(255,0,0,0.55)");
            $("#password").css("border-color", "#ff00008c");
        } else {
            $("#password1").css("border-color", "green");
            $("#password").css("border-color", "green");
        }
    });

    $("#optionShowModel").change(function(){
        var val = $("#optionShowModel").val();
        var ValueInputPayment = $('#ValueInputPayment').val();
        if( val == 'other' ){
            $('#ButtonforModel').click();
            $('#ValueInputPaymentModel').val(ValueInputPayment);
        }
    });

    $('[data-toggle="tooltip"]').tooltip();
    $('#datatable').DataTable({
        "responsive": true,
        "order": [],

    });
    $('#datatable2').DataTable({
        "responsive": true,
        "order": []
    });
    $('#datatable3').DataTable({
        "responsive": true,
        "order": []
    });
    $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
    //Buttons examples
    var table = $('#datatable-buttons').DataTable({
        "order": [],
        lengthChange: false,
        responsive: true,
        buttons: ['copy', 'excel', 'pdf', 'colvis']
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    var table = $('#datatable-buttons1').DataTable({
        "order": [],
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'colvis']
    });

    table.buttons().container()
        .appendTo('#datatable-buttons1_wrapper .col-md-6:eq(0)');

    var table = $('#datatable-buttons2').DataTable({
        "order": [],
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'colvis']
    });

    table.buttons().container()
        .appendTo('#datatable-buttons2_wrapper .col-md-6:eq(0)');

    var table = $('#datatable-buttons3').DataTable({
        "order": [],
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'colvis']
    });

    table.buttons().container()
        .appendTo('#datatable-buttons3_wrapper .col-md-6:eq(0)');

    var table = $('#datatable4').DataTable({
        scrollX : true,
        scrollY : false,
        scrollCollapse : true,
        "order": []
    });
} );


function confirmMsgForDelete(id){
    var checkstr =  confirm('are you sure you want to delete this?');
    if(checkstr == true){
        document.getElementById("DeleteForm"+id).submit();
    }else{
        return false;
    }
}
//For user Profile
$(document).ready(function(){
    var stateid = $('#State_id_aj_userProfile').val();
    var cityId = $('#City_id_aj_userProfile').val();
    var zipcodeid = $('#zip_code_aj_userProfile').val();
    if( stateid != null && cityId != null && zipcodeid != null ){
        $.ajax({
            url: urlcitiesSelected,
            method: "POST",
            data: {
                stateid: stateid,
                cityId: cityId,
                _token: token
            },
            success: function(the_resultCity){
                $.ajax({
                    url: urlzipCodesSelected,
                    method: "POST",
                    data: {
                        stateid: stateid,
                        cityId: cityId,
                        zipcodeid: zipcodeid,
                        _token: token
                    },
                    success: function(the_result){
                        $('#cityList').html(the_resultCity.select);
                        $('#zipcodeList').html(the_result.select);
                        $('#stateList option[value='+the_result.state_id+']').attr('selected','selected');
                    }
                });
            }
        });
    }
});

//For user Regestration
$(document).ready(function(){
    var stateid = $('#State_id_aj').val();
    var cityId = $('#City_id_aj').val();
    var zipcodeid = $('#zipcode_aj').val();
    if( stateid != null && cityId != null && zipcodeid != null ){
        $.ajax({
            url: urlcitiesSelected,
            method: "POST",
            data: {
                stateid: stateid,
                cityId: cityId,
                _token: token
            },
            success: function(the_resultCity){
                $.ajax({
                    url: urlzipCodesSelected,
                    method: "POST",
                    data: {
                        stateid: stateid,
                        cityId: cityId,
                        zipcodeid: zipcodeid,
                        _token: token
                    },
                    success: function(the_result){
                        $('#cityList').html(the_resultCity.select);
                        $('#zipcodeList').html(the_result.select);
                        $('#stateList option[value='+the_result.state_id+']').attr('selected','selected');
                    }
                });
            }
        });
    }
});

//from homepage
function DeleteCampaignForm(campaign_id){
    var conDele = confirm('are you sure you want to delete this Campaign?');
    if (conDele == true) {
        document.getElementById('DeleteCampaignForm-'+campaign_id).submit();
    }
}
//from Paymennt Deleted
function DeletePaymentnForm(payment_id){
    var conDele = confirm('are you sure you want to delete this Payment?');
    if (conDele == true) {
        document.getElementById('DeletePaymentnForm-'+payment_id).submit();
    }
}

//For Access Log
function ShowLocationpopup(id){
    var contentData = $('#accessLogServicelocation-'+id).val();
    $('.modal-title').html('Location')
    $('#ContentData').html("<p>"+contentData+"</p>");
}
function ShowrequestDatapopup(id){
    var contentData = $('#accessLogServicerequestData-'+id).val();
    $('.modal-title').html('Request Method')
    $('#ContentData').html("<p>"+contentData+"</p>");
}

function openModelpaymenttypemethod(user_id) {
    $('#user_id_payment_method_model').val(user_id);
    $('#paymenttypemethodmodelbutton').click();
}

function suberadminBuyerspan(user_id, role_id, account_id) {
    $('#user_id_Claim_model').val(user_id);
    if( role_id == 2 ){
        if( account_id == "Sdr" ){
            $("#claimselectModel option[value='Sdr']").prop('selected', true);
            $("#ClaimHiddenInput").val("Sdr");
            $("#ClaimHiddenInput").show();
            $("#claimselectModel").hide();
        } else if( account_id == "Account Manager" ){
            $("#claimselectModel option[value='Account Manager']").prop('selected', true);
            $("#ClaimHiddenInput").val("Account Manager");
            $("#ClaimHiddenInput").show();
            $("#claimselectModel").hide();
        } else {
            $("#claimselectModel option[value='Sales']").prop('selected', true);
            $("#ClaimHiddenInput").val("Sales");
            $("#ClaimHiddenInput").show();
            $("#claimselectModel").hide();
        }
    } else {
        $("#ClaimHiddenInput").val("");
        $("#ClaimHiddenInput").hide();
        $("#claimselectModel").show();
    }
    $('#ClaimModelbuttonOpen').click();
}


// ON/OF crm
$(document).ready(function() {
    // $("input[name=crm_type]").change(function(){
    //     var crm = $(this).val();
    //     if ($(this).is(':checked')) {
    //         $(':not(.campaign-crm-div-'+crm+') .input-campaign-crm').val('');
    //         $(':not(.campaign-crm-div-'+crm+') .input-campaign-crm').prop("disabled", true);
    //         $(':not(.campaign-crm-div-'+crm+') .check-campaign-crm').prop('checked', false);
    //         $('.campaign-crm-div-'+crm+' .input-campaign-crm').prop("disabled", false);
    //         $(this).prop('checked', true);
    //     } else {
    //         $(':not(.campaign-crm-div-'+crm+') .input-campaign-crm').val('');
    //         $(':not(.campaign-crm-div-'+crm+') .input-campaign-crm').prop("disabled", true);
    //         $(':not(.campaign-crm-div-'+crm+') .check-campaign-crm').prop('checked', false);
    //         $('.campaign-crm-div-'+crm+' .input-campaign-crm').prop("disabled", true);
    //         $(this).prop('checked', false);
    //     }
    // });

    //For check if is ping or post to remove multi if is ping and post
    $("input[name=is_ping_account]").change(function(){
        let is_multi_crms = $("input[name=is_multi_crms]");
        if ($(this).is(':checked')) {
            is_multi_crms.prop('checked', false);
            is_multi_crms.prop('disabled', true);
            $("#input-campaign-crm-select").prop('multiple', false).attr('name', 'crm_type').removeClass("select2Multiple").select2();
        } else {
            is_multi_crms.prop('disabled', false);
        }
        $("#input-campaign-crm-select").change();
    });

    //to change select to to multi crm or single crm
    $("input[name=is_multi_crms]").change(function(){
        let input_campaign_crm_select = $("#input-campaign-crm-select");
        input_campaign_crm_select.find('option').attr("selected",false);
        if ($(this).is(':checked')) {
            input_campaign_crm_select.prop('multiple', true).attr('name', 'crm_type[]').addClass("select2Multiple").select2();
        } else {
            input_campaign_crm_select.prop('multiple', false).attr('name', 'crm_type').removeClass("select2Multiple").select2();
        }
        $("#input-campaign-crm-select").change();
    });

    //Open CRM's Box when select crm
    $("#input-campaign-crm-select").change(function (){
        let values = $(this).val();
        if( values != null || values != undefined ){
            if ($(this).hasClass('select2Multiple')) {
                $('.campaign-crm-div').hide();
                $.each(values, function( index, value ) {
                    $('.campaign-crm-div-'+value).show();
                });
            } else {
                $(':not(.campaign-crm-div-'+values+') .campaign-crm-div').hide();
                $('.campaign-crm-div-'+values).show();
            }
        }
    });
    $("#input-campaign-crm-select").change();
    //============================================================================================================

    if ($("#deliveryMethod option[value='3']:selected").length > 0){
        $('.crmDynamic').show();
    } else {
        $('.crmDynamic').hide();
        $('#is_ping_account_toggle').prop('checked', false);
    }

    if ($("#deliveryMethod option[value='1']:selected").length > 0 && $("#deliveryMethod option[value='2']:selected").length > 0 ){
        $('.email-phone-Dynamic').show();
    } else {
        $('.email-phone-Dynamic').hide();
    }

    if ($("#deliveryMethod option[value='1']:selected").length > 0 && $("#deliveryMethod option[value='2']:selected").length <= 0 ){
        $('.email-phone-Dynamic').show();
        $('.email-card').hide();
    }

    if ($("#deliveryMethod option[value='1']:selected").length <= 0 && $("#deliveryMethod option[value='2']:selected").length > 0 ){
        $('.email-phone-Dynamic').show();
        $('.phone-card').hide();
    }

    $('#deliveryMethod').change(function(){

        if ($("#deliveryMethod option[value='3']:selected").length > 0){
            $('.crmDynamic').show();
        } else {
            $('.crmDynamic').hide();
            $('#is_ping_account_toggle').prop('checked', false);
        }

        if ($("#deliveryMethod option[value='1']:selected").length > 0 && $("#deliveryMethod option[value='2']:selected").length > 0 ){
            $('.email-phone-Dynamic').show();
            $('.email-card').show();
            $('.phone-card').show();
        } else {
            $('.email-phone-Dynamic').hide();
            $('.email-card').hide();
            $('.phone-card').hide();
        }

        if ($("#deliveryMethod option[value='1']:selected").length > 0 && $("#deliveryMethod option[value='2']:selected").length <= 0 ){
            $('.email-phone-Dynamic').show();
            $('.email-card').hide();
            $('.phone-card').show();
        }

        if ($("#deliveryMethod option[value='1']:selected").length <= 0 && $("#deliveryMethod option[value='2']:selected").length > 0 ){
            $('.email-phone-Dynamic').show();
            $('.phone-card').hide();
            $('.email-card').show();
        }
    });

    $(".select_all_select").click(function(){
        var classes = $(this).data('classes');
        $('.'+classes+' option').prop('selected', true);
        $('.'+classes).trigger("change");
        $("."+classes+" option[value='All Source']").prop('selected', false);//To remove all source when check select all
        $('.'+classes).trigger("change");
    });

    $(".clear_all_select").click(function(){
        var classes = $(this).data('classes');
        $('.'+classes+' option:selected').removeAttr('selected');
        $('.'+classes).trigger("change");
    });

    //User Prevalage
    $(".user_privileges_type").change(function(){
        var type = $(this).data("type");
        if($(this).is(":checked")){
            $(".user_privileges_"+type+"_div").fadeIn();
        } else {
            $(".user_privileges_"+type+"_div").fadeOut();
            $(".user_privileges_"+type+"_div input").prop('checked', false);
        }
    });

});

function return_lead_change_buyer_id(user_id) {
    $("#return_lead_user_id").val(user_id);
}

function sort_list_table(el){
    $('#sort_table_form').submit();
}


$(document).ready(function () {
    $(".trx_description_a").click(function () {
        var id = $(this).data("id");
        var description = $('#description-'+id).html();
        $('#descriptions-text').html(description);
        $('#button-model-trx-desc').click();
    });
});


$('#toggle-btn').on('click', function (e) {
    e.preventDefault();

    if ($(window).outerWidth() > 1194) {
        $('nav.side-navbar').toggleClass('shrink');
        //$('.page').toggleClass('active');
        $('.topbar2').toggleClass('topbar3');
        $('.content-page').toggleClass('content-page2');

    } else {
        $('nav.side-navbar').toggleClass('show-sm');
        //$('.page').toggleClass('active-sm');
    }
});


$(document).ready( function() {
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
    });

    $('.btn-file :file').on('fileselect', function(event, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function(){
        readURL(this);
    });

    function readURLCustom(input, type) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img-upload-'+type).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".imgInpCustom").change(function(){
        var type = $(this).data('id');
        readURLCustom(this, type);
    });
});

///***sewitch input on add pixels on domain ***///
$(document).ready(function(){
    $(".facebookpixels input").on("change", function(e) {
        const checkboxValue = e.currentTarget.checked;

        if (checkboxValue) {
            $("#FPixels").prop('disabled', false);
        } else {
            $("#FPixels").prop('disabled', true);
        }
    });

    $(".GoogleTagManager input").on("change", function(e) {
        const checkboxValue = e.currentTarget.checked;

        if (checkboxValue) {
            $("#GPixelsId").prop('disabled', false);
        } else {
            $("#GPixelsId").prop('disabled', true);
        }
    });

});


//Send test Lead Campaign
function sendTestLeadForCamp(campaign_id) {
    if(confirm("Are you sure do you want to send the test lead!")){
        $('.loader').show();
        $('.un_loading_loader').css('opacity', '0.7');
        $.ajax({
            url: adminCampaign_sendTestLead,
            method: "POST",
            data: {
                campaign_id: campaign_id,
                _token: token
            },
            error: function(){
                // will fire when timeout is reached
                $('.loader').hide();
                $('.un_loading_loader').css('opacity', '1');
                alert('Something went wrong please try again...');
            },
            success: function(the_result){
                console.log(the_result);
                $('.loader').hide();
                $('.un_loading_loader').css('opacity', '1');
                if( the_result >= 1 ){
                    alert('Test lead has been sent successfully...');
                } else {
                    alert('Something went wrong please try again...');
                }
            },
            // timeout: 3000
        });
    }
}

function previewFile(id) {
    const preview = document.getElementById('img-'+id);
    const file = document.querySelector('input[type=file]#file-'+id).files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        // convert image file to base64 string
        $("#img-"+id).removeClass('in-active');
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

$('document').ready(function(){
    $(".filterLeadPingsSubmit").on('click', function (){
        var input_type = $(this).data("type");
        var LeadPingsForm = $('#LeadPingsForm');
        if( input_type == 1 ){
            LeadPingsForm.prop({
                'method': 'GET',
                'action': ""
            });
        } else {
            LeadPingsForm.prop({
                'method': 'POST',
                'action': export_lead_data
            });
        }
        LeadPingsForm.submit();
    });

    //delete AllZipcode2 On Campaign
    $("#deleteAllZipcode2OnCampaign").click(function (e){
        e.preventDefault();
        let confirm_delete_zipcode = 'Are you sure you want to delete all of the zip codes? The page is going to reload.';
        if( confirm(confirm_delete_zipcode) ) {
            $("#deleteAllZipcode2OnCampaignForm").click();
        } else {
            return false;
        }
    });

    var $form = $(".require-validation");
    $('form.require-validation').bind('submit', function(e) {
        var $form         = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
                'input[type=text]', 'input[type=file]',
                'textarea'].join(', '),
            $inputs       = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid         = true;
        $errorMessage.addClass('hide');
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });
        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }

    $('#submitButtonsAddTransaction').click(function() {
        $('#submitButtonsAddTransaction').prop('disabled', true);
        var valueForm = $('#ValueInputPayment').val();
        var payment_id = $('#optionShowModel').val();
        if( valueForm != '' && payment_id != '' ) {
            if (payment_id == 'other') {
                window.location.href = '/Transaction/Value/Create/' + valueForm;
            } else {
                $.ajax({
                    url: GetPaymentDetailsAjax,
                    method: "POST",
                    data: {
                        valueForm: valueForm,
                        payment_id: payment_id,
                        _token: token
                    },
                    success: function (the_result) {
                        $('#full_name').val(the_result.payment_full_name);
                        $('#card-number').val(the_result.payment_visa_number);
                        $('#value').val(the_result.valueForm);
                        $('#payment_id').val(payment_id);
                        $('#card-cvc').val(the_result.payment_cvv);
                        $('#card-expiry-year').val(the_result.payment_exp_year);
                        $('#card-expiry-month').val(the_result.payment_exp_month);
                        $('#paystripe').click();
                    }
                });
            }
        } else {
            $('#submitButtonsAddTransaction').prop('disabled', false);
            alert("Please Fill All Data");
        }
    });
    $(".pay_pal_button").click(function(e){
        var amount = $(".pay_pal_amount").val();
        if( amount == '')
        {
            alert("Please Fill Enter Amount");
            e.preventDefault();
        }
    });

    //delete AllZipcode2 On Campaign
    $("#deleteAllZipcode2OnCampaign").click(function (e){
        e.preventDefault();
        let confirm_delete_zipcode = 'Are you sure you want to delete all of the zip codes? The page is going to reload.';
        if( confirm(confirm_delete_zipcode) ) {
            $("#deleteAllZipcode2OnCampaignForm").click();
        } else {
            return false;
        }
    });

    //To Disabled or enabled Choose the sellers whom you don't want to sell leads to for this campaign on update campaign from admin side
    // $('.update_campaign_admin #LeadSourceList').change(function (){
    //     $('.update_campaign_admin #sellers_list').prop("disabled", true);
    //     $('.update_campaign_admin #LeadSourceList :selected').each(function(i, sel){
    //         if( $(sel).val() == "All Source" || $(sel).val() == "Affiliate" || $(sel).val() == "" ){
    //             $('.update_campaign_admin #sellers_list').prop("disabled", false);
    //         }
    //     });
    // });

    //To set max len for budget input equal 4
    $('#budget_bid_shared').keypress(function() {
        if (this.value.length >= 5) {
            return false;
        }
    });
    $('#budget_bid_exclusive').keypress(function() {
        if (this.value.length >= 5) {
            return false;
        }
    });
});


$(document).ready(function() {
    // navbar toggle small screens
    $('.navbarMobile .navbar-toggler').click(function () {
        setTimeout(function () {
            $('.navbarMobile').toggleClass('leftMoveNav');
            $('.main-panel').toggleClass('leftMoveNav');
            $('#navbarTogglerDemo01').toggleClass('rightNavBM');
            $('#overlayDivSB').fadeToggle();
        }, 500)
    });
    var scWidth = $(window).width();
    $(window).resize(function () {
        if (scWidth > 800) {
            $('.navbarMobile').removeClass('leftMoveNav');
            $('.main-panel').removeClass('leftMoveNav');
            $('#navbarTogglerDemo01').removeClass('rightNavBM');
            $('#navbarTogglerDemo01').removeClass('show');
            $('#overlayDivSB').css('display', 'none');
        }
    });
});

function sellerCommissionStateModel(states, username, type){
    $("#sellerCommissionStateModel .modal-header span.span1").html(username);
    $("#sellerCommissionStateModel .modal-header span.span2").html(type);
    $("#sellerCommissionStateModel .modal-body p").html(states);
    $("#sellerCommissionStateModelButton").click();
}
