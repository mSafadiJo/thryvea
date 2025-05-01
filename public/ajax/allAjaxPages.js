$(document).ready(function () {
    $('#stateList').change(function(){
        var stateid = $('#stateList').val();
        $.ajax({
            url: urlcities,
            method: "POST",
            data: {
                stateid: stateid,
                _token: token
            },
            success: function(the_result){
                $('#cityList').html(the_result.select);
                $('#cityList').change();
            }
        });
    });

    $('#cityList').change(function(){
        var cityid = $('#cityList').val();
        $.ajax({
            url: urlzipCodes,
            method: "POST",
            data: {
                cityid: cityid,
                _token: token
            },
            success: function(the_result){
                $('#zipcodeList').html(the_result.select);
            }
        });
    });

    //Delete All Counties
    $("#deleteAllCountyCamp").click(function () {
        $('.unloader').hide();
        $('.loader').show();
        var id = $("#Campaign_id").val();
        $.ajax({
            url: AdminCampaigndeleteAllCounty,
            method: "POST",
            data: {
                id: id,
                _token: token
            },
            success: function(the_result){
                location.reload();
            }
        });
    });

    //Delete All Cities
    $("#deleteAllCityCamp").click(function () {
        $('.unloader').hide();
        $('.loader').show();
        var id = $("#Campaign_id").val();
        $.ajax({
            url: AdminCampaigndeleteAllCity,
            method: "POST",
            data: {
                id: id,
                _token: token
            },
            success: function(the_result){
                location.reload();
            }
        });
    });

    //Delete All Zipcodes
    $("#deleteAllZipcodeCamp").click(function () {
        $('.unloader').hide();
        $('.loader').show();
        var id = $("#Campaign_id").val();
        $.ajax({
            url: AdminCampaigndeleteAllZipcode,
            method: "POST",
            data: {
                id: id,
                _token: token
            },
            success: function(the_result){
                location.reload();
            }
        });
    });

    //Delete All Counties Expect
    $("#deleteAllCountyCampExpect").click(function () {
        $('.unloader').hide();
        $('.loader').show();
        var id = $("#Campaign_id").val();
        $.ajax({
            url: AdminCampaigndeleteAllCountyExpect,
            method: "POST",
            data: {
                id: id,
                _token: token
            },
            success: function(the_result){
                location.reload();
            }
        });
    });

    //Delete All Cities Expect
    $("#deleteAllCityCampExpect").click(function () {
        $('.unloader').hide();
        $('.loader').show();
        var id = $("#Campaign_id").val();
        $.ajax({
            url: AdminCampaigndeleteAllCityExpect,
            method: "POST",
            data: {
                id: id,
                _token: token
            },
            success: function(the_result){
                location.reload();
            }
        });
    });

    //Delete All Zipcodes Expect
    $("#deleteAllZipcodeCampExpect").click(function () {
        $('.unloader').hide();
        $('.loader').show();
        var id = $("#Campaign_id").val();
        $.ajax({
            url: AdminCampaigndeleteAllZipcodeExpect,
            method: "POST",
            data: {
                id: id,
                _token: token
            },
            success: function(the_result){
                location.reload();
            }
        });
    });

    //Send Test Lead
    $('#sendLeadTestDataBtn').click(function () {
        $('.unloader').hide();
        $('.loader').show();
        var id = $("#Campaign_id").val();
        $.ajax({
            url: AdminCampaignsendTestLead,
            method: "POST",
            data: {
                id: id,
                _token: token
            },
            success: function(the_result){
                $('.unloader').show();
                $('.loader').hide();
                alert('Test Lead have been sent successfully.');
            }
        });
    });
});

//change Status AdminCampaign
function admincampaign_status_table_Ajax_changing(campain_id) {
    var status = $('#admincampaign_status_table_Ajax_changing-'+campain_id).val();
    $.ajax({
        url: AdminCampaignChangeStatus,
        method: "POST",
        data: {
            status: status,
            _token: token
        },
        success: function (the_result) {
            if (the_result == 0) {
                alert('Fail To Update Campaign Status');
            }
        }
    });
}
//change StatusCampaign
function campaign_status_table_Ajax_changing(campain_id) {
    var status = $('#campaign_status_table_Ajax_changing-'+campain_id).val();
    $.ajax({
        url: CampaignChangeStatus,
        method: "POST",
        data: {
            status: status,
            _token: token
        },
        success: function (the_result) {
            if (the_result == 0) {
                alert('Fail To Update Campaign Status');
            }
        }
    });
}

//change Ticket
function ticket_issues_status_table_Ajax_changing(ticket_id) {
    var status = $('#ticket_issues_status_table_Ajax_changing-'+ticket_id).val();
    if( status != 4 ){
        $.ajax({
            url: TicketChangeStatus,
            method: "POST",
            data: {
                status: status,
                type: "Issue Ticket",
                ticket_id: ticket_id,
                _token: token
            },
            success: function (the_result) {
                if (the_result == 0) {
                    alert('Fail To Update Ticket Status');
                } else {
                    if( status == 3 ){
                        $('#ticket_issues_status_table_Ajax_changing-'+ticket_id).prop("disabled", true);
                    }
                }
            }
        });
    } else {
        $('#rejqctTicketModelButton').click();
        $('#form_status').val(status);
        $('#form_type').val("Issue Ticket");
        $('#form_ticket_id').val(ticket_id);
    }
}
function Refundticket_status_table_Ajax_changing(ticket_id) {
    var status = $('#Refundticket_status_table_Ajax_changing-'+ticket_id).val();
    if( status != 4 ){
        $.ajax({
            url: TicketChangeStatus,
            method: "POST",
            data: {
                status: status,
                type: "Return Lead",
                ticket_id: ticket_id,
                _token: token
            },
            success: function (the_result) {
                if (the_result == 0) {
                    alert('Fail To Update Ticket Status');
                } else if( the_result == 'graterThan' ){
                    alert("can't refund your process, you are reach 15% of lead returned ");
                } else {
                    if( status == 3 ){
                        $('#Refundticket_status_table_Ajax_changing-'+ticket_id).prop("disabled", true);
                    }
                }
            }
        });
    } else {
        $('#rejqctTicketModelButton').click();
        $('#form_status').val(status);
        $('#form_type').val("Return Lead");
        $('#form_ticket_id').val(ticket_id);
    }
}

$(document).ready(function () {
    $('#issuesTicketformAdminUser').click(function () {
        $('#issuesTicketformAdminUser').html('Please Wait...');
        $.ajax({
            url: TicketChangeStatus,
            method: "POST",
            data: {
                status: $('#form_status').val(),
                type: $('#form_type').val(),
                ticket_id: $('#form_ticket_id').val(),
                reject_text: $('#form_ticket_message').val(),
                _token: token
            },
            success: function (the_result) {
                if (the_result == 0) {
                    alert('Fail To Update Ticket Status');
                } else {
                    $('#ticket_issues_status_table_Ajax_changing-'+$('#form_ticket_id').val()).prop("disabled", true);
                    $('#Refundticket_status_table_Ajax_changing-'+$('#form_ticket_id').val()).prop("disabled", true);
                }
                $('#issuesTicketformAdminUser').html('Add');
                $('#form_ticket_message').val('')
                $('#issuesTicketformAdminUserCloseModel').click();
            }
        });
    }) ;
});

function changeClaimStatus(status, claim_id) {
    if( status == 2 ){
        var checkstr =  confirm('are you sure you want to delete this?');
        if(checkstr == true){
            $.ajax({
                url: adminClaimChangeStatus,
                method: "POST",
                data: {
                    claim_id: claim_id,
                    status: status,
                    _token: token
                },
                success: function (the_result) {
                    if(the_result == 0){
                        location.reload();
                    } else {
                        alert('Something went wrong!');
                    }
                }
            });
        }else{
            return false;
        }
    } else {
        $.ajax({
            url: adminClaimChangeStatus,
            method: "POST",
            data: {
                claim_id: claim_id,
                status: status,
                _token: token
            },
            success: function (the_result) {
                if(the_result == 0){
                    location.reload();
                } else {
                    alert('Something went wrong!');
                }
            }
        });
    }
}

function changePaymentMethodStatus(status, payment_type_id) {
    var payment_limit = 0;
    if( status == 1 ){
        payment_limit = $('#payment_limit_'+payment_type_id).val();
    }
    if( status == 2 ){
        var checkstr =  confirm('are you sure you want to delete this?');
        if(checkstr == true){
            $.ajax({
                url: adminAccountOwnerShipChangeStatus,
                method: "POST",
                data: {
                    payment_type_id: payment_type_id,
                    payment_limit: payment_limit,
                    status: status,
                    _token: token
                },
                success: function (the_result) {
                    if(the_result == 0){
                        location.reload();
                    }
                }
            });
        }else{
            return false;
        }
    } else {
        $.ajax({
            url: adminAccountOwnerShipChangeStatus,
            method: "POST",
            data: {
                payment_type_id: payment_type_id,
                payment_limit: payment_limit,
                status: status,
                _token: token
            },
            success: function (the_result) {
                if(the_result == 0){
                    location.reload();
                }
            }
        });
    }
}

function changeServiceStatusSA(id, name) {
    var status = 0;
    if ($('#serviceactive').is(':checked')) {
        status = 1;
    }
    var checkstr =  confirm('are you sure you want to change the status for this service?');
    if(checkstr == true) {
        $.ajax({
            url: UpdateStatusServicedSuperAdmin,
            method: "POST",
            data: {
                id: id,
                status: status,
                name: name,
                _token: token
            },
            success: function (the_result) {
                if (the_result != 1) {
                    alert('something went wrong');
                }
            }
        });
    }
}


// Delete record
$(document).on("click", ".deleteTask" , function() {

    var $button = $(this);
    var id = $(this).data('id');
    var table = $('.taskTable').DataTable();

    if(!confirm("Do you really want to do this?")) {
        return false;
    }

    // var el = this;
    $.ajax(
        {
            url: "/deleteTask/"+id,
            type: 'DELETE',
            method: "DELETE",
            data: {
                "id": id,
                "_token": token,
            },
            success: function (){
                console.log("it Works");
                table.row( $button.parents('tr') ).remove().draw();
            }
        });
});

function editTaskStatus(id) {
    var status = $('#TaskStatus-'+id).val();
    var idTask = id ;
    $.ajax({
        url: "/ChangeTaskStatus",
        method: "POST",
        data: {
            status: status,
            id:idTask,
            _token: token
        },
        success: function (the_result) {
            if (the_result == 0) {
                alert('Fail To Update Campaign Status');
            }
        }
    });
}



function editTaskSignedTo(id) {
    var SignedTo = $('#TaskSignedTo-'+id).val();
    var idTask = id ;
    $.ajax({
        url: "/ChangeTaskSignedTo",
        method: "POST",
        data: {
            SignedTo: SignedTo,
            id:idTask,
            _token: token
        },
        success: function (the_result) {
            if (the_result == 0) {
                alert('Fail To Update Campaign Status');
            }
        }
    });
}


//lead Review
$(document).ready(function () {
    var stateid = $('#stateListLeadReview').val();
    var cityID = $('#CityID').val();
    if( stateid && cityID ){
        $.ajax({
            url: getCityReview,
            method: "POST",
            data: {
                stateid: stateid,
                cityID:cityID,
                _token: token
            },
            success: function (the_result) {
                $('#cityListLeadReview').html(the_result.select);
            }
        });
    }

    var stateid = $('#stateListLeadReview').val();
    var countyID = $('#countyID').val();
    if( stateid && countyID ) {
        $.ajax({
            url: getAllCounties,
            method: "POST",
            data: {
                stateVal: stateid,
                countyID: countyID,
                _token: token
            },
            success: function (the_result) {
                $('#countyListLeadReview').html(the_result.select);
            }
        });
    }

    var cityid = $('#CityID').val();
    var zipcodeid = $('#zipcodeID').val();
    if( cityid && zipcodeid ) {
        $.ajax({
            url: getAllZipCodeReview,
            method: "POST",
            data: {
                cityid: cityid,
                stateid: stateid,
                zipcodeid: zipcodeid,
                _token: token
            },
            success: function (the_result) {
                console.log(the_result);
                $('#zipcodeListLeadReview').html(the_result.select);
            }
        });
    }


    $('#stateListLeadReview').change(function () {
        var stateid = $('#stateListLeadReview').val();
        $.ajax({
            url: urlcities,
            method: "POST",
            data: {
                stateid: stateid,
                _token: token
            },
            success: function (the_result) {
                $('#cityListLeadReview').html(the_result.select);
                $('#cityListLeadReview').change();
            }
        });
    });

    $('#cityListLeadReview').change(function () {
        var cityid = $('#cityListLeadReview').val();
        $.ajax({
            url: urlzipCodes,
            method: "POST",
            data: {
                cityid: cityid,
                _token: token
            },
            success: function (the_result) {
                $('#zipcodeListLeadReview').html(the_result.select);
            }
        });
    });
    $('#stateListLeadReview').change(function () {
        var stateid = $('#stateListLeadReview').val();
        $.ajax({
            url: getAllCounties,
            method: "POST",
            data: {
                stateVal: stateid,
                _token: token
            },
            success: function (the_result) {
                $('#countyListLeadReview').html(the_result.select);
                $('#countyListLeadReview').change();
            }
        });
    });
});

//Block lead Info Section
$(document).ready(function () {
    $('.block_buttons').click(function () {
        var type = $(this).data("type");
        var section = $(this).data("section");
        var input_value = $("#block_"+section+"_input_text").val();

        if( $.trim(input_value) != "" ){
            if( type != "clear" ){
                $.ajax({
                    url: block_lead_info_ajax,
                    method: "POST",
                    data: {
                        type: type,
                        section: section,
                        input_value: input_value,
                        _token: token
                    },
                    success: function (status) {
                        if( type == "search" ){
                            $("#block_"+section+"_response").show();
                            if( status == 1 ){
                                $("#block_"+section+"_success_div").show();
                                $("#block_"+section+"_fail_div").hide();
                            } else {
                                $("#block_"+section+"_success_div").hide();
                                $("#block_"+section+"_fail_div").show();
                            }
                        } else if( type == "block" ){
                            if( status == 1 ) {
                                $("#block_"+section+"_response").hide();
                                $("#block_"+section+"_input_text").val("");
                                alert("This " + input_value + " " + section + " was blocked successfully");
                            } else {
                                alert("oops! something went wrong! please try again.");
                            }
                        } else if( type == "unblock" ){
                            if( status == 1 ) {
                                $("#block_"+section+"_response").hide();
                                $("#block_"+section+"_input_text").val("");
                                alert("This "+input_value+" "+section+" was unblocked successfully");
                            } else {
                                alert("oops! something went wrong! please try again.");
                            }
                        }
                    }
                });
            } else {
                $("#block_"+section+"_response").hide();
                $("#block_"+section+"_input_text").val("");
            }
        } else {
            alert("Please fill in the "+section);
        }
    });
});

/////change status for theme
function changeThemeStatusSA(id, name) {
    var status = $('#themeactive-'+id).val();
    $.ajax({
        url: updateStatusThemesUserAdmin,
        method: "POST",
        data: {
            id: id,
            status: status,
            name: name,
            _token: token
        },
        success: function (the_result) {
            if (the_result != 1) {
                alert('something went wrong');
            }
        }
    });
}
function changeDomainStatus(id, name) {
    var status = $('#themeactive-'+id).val();
    $.ajax({
        url: updateStatusDomainsUserAdmin,
        method: "POST",
        data: {
            id: id,
            status: status,
            name: name,
            _token: token
        },
        success: function (the_result) {
            if (the_result != 1) {
                alert('something went wrong');
            }
        }
    });
}

///delete theme without reload
$(document).on("click", ".deleteTheme" , function() {
    var $button = $(this);
    var id = $(this).data('id');
    var table = $('.taskTable').DataTable();
    if(!confirm("Do you really want to do this?")) {
        return false;
    }
    $(this).parents().closest("tr").fadeOut(1000)
        .promise().done(function(){
        $(this).parents().closest("tr").remove();
    });
    $.ajax(
        {
            url: "/AdminTheme/Delete/"+id,
            type: 'POST',
            data: {
                "id": id,
                "_token": token,
            },
            success: function (){

                console.log("it Works");
                table.row( $button.parents('tr') ).remove().draw();
            }
        });
});

///delete domain without reload
$(document).on("click", ".delete_domain" , function() {
    var $button = $(this);
    var id = $(this).data('id');
    var table = $('.taskTable').DataTable();
    if(!confirm("Are you sure?")) {
        return false;
    }
    $(this).parents().closest("tr").fadeOut(1000)
        .promise().done(function(){
        $(this).parents().closest("tr").remove();
    });
    $.ajax(
        {
            url: "/admin/domain/delete/"+id,
            type: 'POST',
            data: {
                "id": id,
                "_token": token,
            },
            success: function (){
                table.row( $button.parents('tr') ).remove().draw();
            }
        });
});


///show image theme order by service type

$(".serviceType").ready(function(){
    var ServiceTypeId = $(".serviceType").val()
    let _services = $('#service_id').val()
    if(ServiceTypeId == 2){
        $(".service_idSingle").hide();
        $(".service_idMultiple").show();
        $(".second_service_section").hide();
    }else if(ServiceTypeId == 1){
        $('.themeImageSection').show();
        $(".service_idSingle").show();
        $(".second_service_section").show();
        $(".service_idMultiple").hide();
    }else if(ServiceTypeId == 3){
        $('.themeImageSection').show();
        $(".second_service_section").hide();
        $(".service_idSingle").hide();
        $(".exit_pop").show();
        $(".service_idMultiple").show();
    }else{
        $(".second_service_section").hide();
    }
    if(ServiceTypeId != '') {
        $.ajax({
            url: ListImageThemes,
            method: "POST",
            data: {
                "ServiceTypeId": ServiceTypeId,
                "services": _services,
                "_token": token
            },
            success: function (the_result) {
                var lengthArray = the_result.length;
                let theme_selected = $('.selected-theme').val()
                if (lengthArray >= 1) {
                    $(".SelectThemeDomain").show();

                    $('.themeImageSection .row').html('');
                    for (i = 0; i < the_result.length; i++) {
                        if (the_result[i]['id'] == theme_selected) {
                            $('.themeImageSection .row').prepend('<div class="col-sm-12 col-md-3 col-lg-1 mr-4"><label><input checked type="radio" name="theme_id" value=' + the_result[i]['id'] + '><img id="theImg" src="'+ the_result[i]["theme_img"] + '"/></label></div>')
                        }else{
                            $('.themeImageSection .row').prepend('<div class="col-sm-12 col-md-3 col-lg-1 mr-4"><label><input type="radio" name="theme_id" value=' + the_result[i]['id'] + '><img id="theImg" src="'+ the_result[i]["theme_img"] + '"/></label></div>')
                        }
                    }
                } else {
                    $('.themeImageSection .row').html('<h4>you don\'t have any themes</h4>');
                }
            }
        });
    }else
    {
        $(".service_idSingle").show();
        $(".service_idMultiple").hide();
        $(".SelectThemeDomain").hide();
        $('.themeImageSection .row').html("");
    }
})

$(document).on("change", ".serviceType" , function() {
    var ServiceTypeId = this.value;
    let _services = $('#service_id').val()
    $(".exit_pop").show();
    if(ServiceTypeId == 2)
    {
        $('.themeImageSection').show();
        $(".second_service_section").hide();
        $(".service_idSingle").hide();
        $(".exit_pop").hide();
        $(".service_idMultiple").show();
    }else if(ServiceTypeId == 1)
    {
        $("#service_id #default").prop("selected", true);
        $('.themeImageSection').hide();
        $(".second_service_section").show();
        $(".service_idSingle").show();
        $(".exit_pop").show();
        $(".service_idMultiple").hide();
    }
    else if(ServiceTypeId == 3)
    {
        $('.themeImageSection').show();
        $(".second_service_section").hide();
        $(".service_idSingle").hide();
        $(".exit_pop").show();
        $(".service_idMultiple").show();
    }
    if(ServiceTypeId != '') {
        if (ServiceTypeId != 1) {
            $.ajax({
                url: ListImageThemes,
                method: "POST",
                data: {
                    "ServiceTypeId": ServiceTypeId,
                    "services": _services,
                    "_token": token
                },
                success: function (the_result) {
                    console.log(the_result)
                    $('.themeImageSection').show();
                    var lengthArray = the_result.length;
                    let theme_selected = $('.selected-theme').val()
                    if (lengthArray >= 1) {
                        $(".SelectThemeDomain").show();

                        $('.themeImageSection .row').html('');
                        for (i = 0; i < the_result.length; i++) {
                            if (the_result[i]['id'] == theme_selected) {
                                $('.themeImageSection .row').prepend('<div class="col-sm-12 col-md-3 col-lg-1 mr-4"><label><input checked type="radio" name="theme_id" value=' + the_result[i]['id'] + '><img id="theImg" src="' + the_result[i]["theme_img"] + '"/></label></div>')
                            } else {
                                $('.themeImageSection .row').prepend('<div class="col-sm-12 col-md-3 col-lg-1 mr-4"><label><input type="radio" name="theme_id" value=' + the_result[i]['id'] + '><img id="theImg" src="' + the_result[i]["theme_img"] + '"/></label></div>')
                            }
                        }
                    } else {
                        $('.themeImageSection .row').html('<h4>you don\'t have any themes</h4>');
                    }
                }
            });
        }
    }else
    {
        $(".service_idSingle").show();
        $(".service_idMultiple").hide();
        $(".SelectThemeDomain").hide();
        $('.themeImageSection .row').html("");
    }
});

//Change Lead Status on buyer dashboard
function changeLeadStatusForBuyer(id){
    var status = $('#LeadBuyersStatusChange-'+id).val();

    $.ajax({
        url: buyerLeadChangeStatus,
        method: "POST",
        data: {
            id: id,
            status: status,
            _token: token
        },
        success: function (the_result) { }
    });
}


///Add GTM Field
$(document).on("click", "#addWithTs" , function() {
    $.ajax({
        url: getAllTrafficSorce,
        method: "POST",
        data: {
            "_token": token
        },
        success: function (the_result) {
            console.log(the_result.length)
            var lengthArray = the_result.length;
            if (lengthArray >= 1) {
                $('.PixelsSection .row .FirstSection').append('<div class="parentDiv"> <div class="form-group row">' +
                    '<label for="GoogleTagManager" class="col-sm-4 col-form-label">Ts Name:</label>' +
                    '<div class="col-sm-6">\n' +
                    '<select id="service_id" class="select form-control" name="TsName[]" data-placeholder="Choose Ts...">' +
                    '<optgroup label="Ts Name">'+
                    '</optgroup>\n'+
                    '</select>\n'+
                    '</div>\n'+
                    '<div class="col-sm-2">'+
                    '<span id="clear"> <i id="DeleteGTM" class="fa fa-times" aria-hidden="true"></i> </span>' +
                    '</div>'+
                    '</div><div class="form-group row">'+
                    '<label for="GoogleTagManager" class="col-sm-4 col-form-label">Google Tag Manager ID:</label>' +
                    '<div class="col-sm-6">'+
                    '<input type="text" class="form-control" id="GPixels" name="GPixelsId[]" value="">' +
                    '</div>'+
                    '</div></div>');

                for (i = 0; i < the_result.length; i++){
                    $('.PixelsSection .row .FirstSection #service_id:last').append('<option value='+ the_result[i]['id'] +'>'+ the_result[i]['name'] +'</option>');
                }
            }
        }
    });
});
let _allTraffic = []
let _allTrafficHTML = ""
$(document).ready(function() {
    let domain_id = $(".domain-id").val()
    $.ajax({
        url: trafficSourceAjax,
        method: "get",
        data: {
            "_token": token,
            "domain_id": domain_id
        },
        success: function (response) {
            var lengthArray = response.traffic.length;
            _allTraffic = response.allTraffic
            if (lengthArray >= 1) {
                for (let i = 0; i < response.traffic.length; i++) {
                    _allTrafficHTML = ""
                    for (let j = 0; j < response.allTraffic.length; j++){
                        if (response.allTraffic[j].id == response.traffic[i].ts_name) {
                            _allTrafficHTML += ('<option selected value=' + response.allTraffic[j]['id'] + '>' + response.allTraffic[j]['name'] + '</option>')
                        }else{
                            _allTrafficHTML += ('<option value=' + response.allTraffic[j]['id'] + '>' + response.allTraffic[j]['name'] + '</option>')
                        }
                    }
                    $('.PixelsSection .row .FirstSection').append('<div class="parentDiv"> <div class="form-group row">' +
                        '<label for="GoogleTagManager" class="col-sm-4 col-form-label">Ts Name:</label>' +
                        '<div class="col-sm-6">\n' +
                        '<select id="service_id_'+i+'" class="select form-control" name="TsName[]" data-placeholder="Choose Ts...">' +
                        '<optgroup label="Ts Name">' +
                        _allTrafficHTML+
                        '</optgroup>\n' +
                        '</select>\n' +
                        '</div>\n' +
                        '<div class="col-sm-2">' +
                        '<span id="clear"> <i id="DeleteGTM" class="fa fa-times" aria-hidden="true"></i> </span>' +
                        '</div>' +
                        '</div><div class="form-group row">' +
                        '<label for="GoogleTagManager" class="col-sm-4 col-form-label">Google Tag Manager ID:</label>' +
                        '<div class="col-sm-6">' +
                        '<input type="text" class="form-control" id="GPixels" name="GPixelsId[]" value="'+response.traffic[i]['pixels_name']+'">' +
                        '</div>' +
                        '</div></div>');
                }
            }
        }
    });
});


$(document).on("click", "#DeleteGTM" , function() {
    event.preventDefault();
    $(this).parents('.parentDiv').remove();
});

//For input-number-increment & input-number-decrement
$(document).ready(function() {
    $('#saveDashboardSalesSettings').click(function (){
        var sales_target = $('#sales_target').val();
        var daly_sales_max_transfer = $('#daly_sales_max_transfer').val();
        var sdr_target = $('#sdr_target').val();
        var daly_sdr_max_transfer = $('#daly_sdr_max_transfer').val();
        var callCenter_target = $('#callCenter_target').val();
        var daly_callCenter_max_transfer = $('#daly_callCenter_max_transfer').val();
        var type = $('#saveDashboardType').val();
        var this_buton = $(this);

        this_buton.text("Saving…");
        this_buton.prop("disabled", true);
        $.ajax({
            url: AdminSalesStoreSetting,
            method: "POST",
            data: {
                sales_target: sales_target,
                daly_sales_max_transfer: daly_sales_max_transfer,
                sdr_target: sdr_target,
                daly_sdr_max_transfer: daly_sdr_max_transfer,
                callCenter_target: callCenter_target,
                daly_callCenter_max_transfer: daly_callCenter_max_transfer,
                type: type,
                _token: token
            },
            success: function (the_result) {
                this_buton.text("Save");
                this_buton.prop("disabled", false);
            }
        });
    });

    $('.input-number-decrement').click(function (){
        var parent = $(this).closest('div');
        var parentClass = parent[0]['classList'][0];
        var current_input = $('.'+parentClass +' '+'.input-number');

        var i = $('.'+parentClass +' '+'.input-number').val();
        var input_number_val = parseInt(i)-1;
        if( input_number_val < current_input.attr("min") ){
            input_number_val = 0;
        }

        current_input.val(input_number_val);
        var admin_id = current_input.data("id");

        saveTransfersSalesDashboard(input_number_val, admin_id);
    });

    $('.input-number-increment').click(function (){
        var parent = $(this).closest('div');
        var parentClass = parent[0]['classList'][0];
        var current_input = $('.'+parentClass +' '+'.input-number');


        var i = $('.'+parentClass +' '+'.input-number').val();
        var input_number_val = parseInt(i)+1;
        if( input_number_val > current_input.prop("max") ){
            input_number_val = 100;
        }

        current_input.val(input_number_val);
        var admin_id = current_input.data("id");

        saveTransfersSalesDashboard(input_number_val, admin_id);
    });

    $('.input-number').change(function (){
        var admin_id = $(this).data("id");
        var input_number_val = $(this).val();

        saveTransfersSalesDashboard(input_number_val, admin_id);
    });

    // Buyer Sections
    // Add Buyer Lead Note
    $(".buyer_lead_model_add").click(function (){
        let lead_id = $("#lead_model_id").val();
        let lead_note = $("#lead_model_note").val();
        $.ajax({
            url: buyer_lead_note_update,
            method: "POST",
            data: {
                lead_id: lead_id,
                lead_note: lead_note,
                _token: token
            },
            success: function (the_result) {
                alert("Lead Note Has Been Updated!");
                $('#show_script_text_data-'+lead_id).val(lead_note);
            }
        });
    });
    // Delete Buyer Lead Note
    $(".buyer_lead_model_delete").click(function (){
        let lead_id = $("#lead_model_id").val();
        if (confirm('Are You Sure You Want To Delete The Note?')) {
            $.ajax({
                url: buyer_lead_note_update,
                method: "POST",
                data: {
                    lead_id: lead_id,
                    lead_note: "",
                    _token: token
                },
                success: function (the_result) {
                    alert("The Lead Note Has Been Deleted Successfully!");
                    $("#lead_model_note").val("");
                    $('#show_script_text_data-'+lead_id).val("");
                }
            });
        }
    });
});

function saveTransfersSalesDashboard(input_number_val, admin_id){
    $.ajax({
        url: AdminSalesStoreTransfers,
        method: "POST",
        data: {
            input_number_val: input_number_val,
            admin_id: admin_id,
            _token: token
        },
        success: function (the_result) {

        }
    });
}

//change QA Lead Status
function QA_LeadStatus_table_Ajax_changing(lead_id) {
    var status = $('#QA_LeadStatus_table_Ajax_changing-'+lead_id).val();
    $.ajax({
        url: QAChangeStatusLead,
        method: "POST",
        data: {
            status: status,
            lead_id: lead_id,
            _token: token
        },
        success: function (the_result) {
            if (the_result != 1) {
                alert('Fail To Update Lead Status');
            }
        }
    });
}

//Delete ACH Payments
function DeletePayments(transaction_id, transactions_value, user_id, row_id) {
    if (confirm('Are you sure you want to delete this payment?')) {
        $.ajax({
            url: Delete_ACH_Credit,
            method: "POST",
            data: {
                transaction_id: transaction_id,
                transactions_value: transactions_value,
                user_id :user_id,
                _token: token
            },
            success: function (the_result) {
                if(the_result == 1){
                    var i = row_id.parentNode.parentNode.rowIndex;
                    document.getElementById("pagination-table").deleteRow(i);
                } else {
                    alert("OOPS, The process was not completed!");
                }
            }
        });
    }
}


