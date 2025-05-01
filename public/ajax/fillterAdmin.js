$(document).ready(function () {
    $('#FilterCampaignAdmainAjax').click(function () {
        var buyer_id = $('#buyer_id').val();
        var service_id = $('#service_id').val();

        $("#FilterCampaignAdmainAjax").text("Searching…");
        $("#FilterCampaignAdmainAjax").prop("disabled", true);
        $.ajax({
            url: AdminCampaignStorFilter,
            method: "POST",
            data: {
                buyer_id: buyer_id,
                service_id: service_id,
                _token: token
            },
            success: function (the_result) {
                $('#dataAjaxTableCampaign').html(the_result);

                var table = $('#datatable').DataTable({
                    "responsive": true,
                    "order": [[ 0, "desc" ]]
                });
            },
            complete: function () {
                $("#FilterCampaignAdmainAjax").text("Search");
                $("#FilterCampaignAdmainAjax").prop("disabled", false);
            }
        });
    });

    $('#FilterSellerCampaignAdmainAjax').click(function () {
        var seller_id = $('#seller_id').val();
        var service_id = $('#service_id').val();

        $("#FilterSellerCampaignAdmainAjax").text("Searching…");
        $("#FilterSellerCampaignAdmainAjax").prop("disabled", true);
        $.ajax({
            url: AdminSellerCampaignsfilter,
            method: "POST",
            data: {
                seller_id: seller_id,
                service_id: service_id,
                _token: token
            },
            success: function (the_result) {
                $('#dataAjaxTableCampaign').html(the_result);

                var table = $('#datatable-buttons3').DataTable({
                    "order": [[ 0, "desc" ]],
                    lengthChange: false,
                    "responsive": true,
                    buttons: ['copy', 'excel', 'pdf', 'colvis']
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons3_wrapper .col-md-6:eq(0)');
            },
            complete: function () {
                $("#FilterSellerCampaignAdmainAjax").text("Search");
                $("#FilterSellerCampaignAdmainAjax").prop("disabled", false);
            }
        });
    });

    $('#filterLeadSMSEmail').click(function () {
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#filterLeadSMSEmail").text("Searching…");
            $("#filterLeadSMSEmail").prop("disabled", true);

            $.ajax({
                url: list_of_leads_SMS_Email_ajax,
                method: "POST",
                data: {
                    service_id: service_id,
                    states: states,
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableCampaign').html(the_result);
                    $('#datatable4').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#filterLeadSMSEmail").text("Search");
                    $("#filterLeadSMSEmail").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    $('#filterLeadSMSEmail').click();

    $('#filterLeadtest').click(function () {
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#filterLeadtest").text("Searching…");
            $("#filterLeadtest").prop("disabled", true);
            $.ajax({
                url: list_of_leads_TestAjax,
                method: "POST",
                data: {
                    service_id: service_id,
                    states: states,
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableCampaign').html(the_result);
                    $('#datatable4').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#filterLeadtest").text("Search");
                    $("#filterLeadtest").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    $('#filterLeadtest').click();


    //Access Log ==================================================================================
    $('#accessLogFilterDateGeneral').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();
        var section = $('#section_accesslog').val();

        if( start_date != '' && end_date != '' ) {
            $("#accessLogFilterDateGeneral").text("Searching…");
            $("#accessLogFilterDateGeneral").prop("disabled", true);
            $.ajax({
                url: accessLogSearchGeneral,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    section: section,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accessLogFilterDateGeneral").text("Search");
                    $("#accessLogFilterDateGeneral").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdatePromocode').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdatePromocode").text("Searching…");
            $("#accesslogfilterdatePromocode").prop("disabled", true);
            $.ajax({
                url: AccessLogpromoCodessearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdatePromocode").text("Search");
                    $("#accesslogfilterdatePromocode").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateAdminuser').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateAdminuser").text("Searching…");
            $("#accesslogfilterdateAdminuser").prop("disabled", true);
            $.ajax({
                url: AccessLogAdminUsersearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateAdminuser").text("Search");
                    $("#accesslogfilterdateAdminuser").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateBuyerUser').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateBuyerUser").text("Searching…");
            $("#accesslogfilterdateBuyerUser").prop("disabled", true);
            $.ajax({
                url: AccessLogBuyersUsersearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateBuyerUser").text("Search");
                    $("#accesslogfilterdateBuyerUser").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateCampaign').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateCampaign").text("Searching…");
            $("#accesslogfilterdateCampaign").prop("disabled", true);
            $.ajax({
                url: AccessLogCampainsearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateCampaign").text("Search");
                    $("#accesslogfilterdateCampaign").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateAuthUser').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateAuthUser").text("Searching…");
            $("#accesslogfilterdateAuthUser").prop("disabled", true);
            $.ajax({
                url: AccessLogAuthenticationsearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateAuthUser").text("Search");
                    $("#accesslogfilterdateAuthUser").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateTicket').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateTicket").text("Searching…");
            $("#accesslogfilterdateTicket").prop("disabled", true);
            $.ajax({
                url: AccessLogTicketsearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateTicket").text("Search");
                    $("#accesslogfilterdateTicket").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateDialerSystemSolar').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateDialerSystemSolar").text("Searching…");
            $("#accesslogfilterdateDialerSystemSolar").prop("disabled", true);
            $.ajax({
                url: AccessLogDialerSystemSolarsearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateDialerSystemSolar").text("Search");
                    $("#accesslogfilterdateDialerSystemSolar").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateUserPaymentssearch').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateUserPaymentssearch").text("Searching…");
            $("#accesslogfilterdateUserPaymentssearch").prop("disabled", true);
            $.ajax({
                url: AccessLogUserPaymentssearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateUserPaymentssearch").text("Search");
                    $("#accesslogfilterdateUserPaymentssearch").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateLead').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateLead").text("Searching…");
            $("#accesslogfilterdateLead").prop("disabled", true);
            $.ajax({
                url: AccessLogLeadsearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateLead").text("Search");
                    $("#accesslogfilterdateLead").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdatePlatformsMarketing').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdatePlatformsMarketing").text("Searching…");
            $("#accesslogfilterdatePlatformsMarketing").prop("disabled", true);
            $.ajax({
                url: AccessLogMarketingPlatformsearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdatePlatformsMarketing").text("Search");
                    $("#accesslogfilterdatePlatformsMarketing").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateMarketingTrafficSources').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateMarketingTrafficSources").text("Searching…");
            $("#accesslogfilterdateMarketingTrafficSources").prop("disabled", true);
            $.ajax({
                url: AccessLogAccessLogMarketingTrafficSourcesearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateMarketingTrafficSources").text("Search");
                    $("#accesslogfilterdateMarketingTrafficSources").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#accesslogfilterdateSellerCampaigns').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#accesslogfilterdateSellerCampaigns").text("Searching…");
            $("#accesslogfilterdateSellerCampaigns").prop("disabled", true);
            $.ajax({
                url: AccessLogSellerCampaignsearch,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#accessLogTables').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#accesslogfilterdateSellerCampaigns").text("Search");
                    $("#accesslogfilterdateSellerCampaigns").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#filterLeadBuyers').click(function () {
        var service_id = $('#service_id').val();
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();
        if( start_date != '' && end_date != '' ) {
            $.ajax({
                url: list_of_leads_BuyersAjax,
                method: "POST",
                data: {
                    service_id: service_id,
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                beforeSend: function() {
                    $("#filterLeadBuyers").text("Searching…");
                    $("#filterLeadBuyers").prop("disabled", true);
                },
                success: function (the_result) {
                    $('#dataAjaxTableLeads').html(the_result);
                    $('#datatableBuyersLead').DataTable({
                        "order": [[ 0, "desc" ]],
                        responsive: true
                    });
                    $("#filterLeadBuyers").text("Search");
                    $("#filterLeadBuyers").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    // $('#filterLeadBuyers').click();

    $('#filterLeadReturnBuyers').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();
        if( start_date != '' && end_date != '' ) {
            $.ajax({
                url: ReturnLeadBuyerAjax,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                beforeSend: function() {
                    $("#filterLeadReturnBuyers").text("Searching…");
                    $("#filterLeadReturnBuyers").prop("disabled", true);
                },
                success: function (the_result) {
                    $('#dataAjaxTableLeads').html(the_result);
                    $('#datatableBuyersReturnLead').DataTable({
                        "order": [[ 0, "desc" ]],
                        responsive: true
                    });
                    $("#filterLeadReturnBuyers").text("Search");
                    $("#filterLeadReturnBuyers").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#filterListOfClickLeadsBuyer').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();
        if( start_date != '' && end_date != '' ) {
            $.ajax({
                url: BuyersListOfClickLeadsAjax,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                beforeSend: function() {
                    $("#filterListOfClickLeadsBuyer").text("Searching…");
                    $("#filterListOfClickLeadsBuyer").prop("disabled", true);
                },
                success: function (the_result) {
                    $('#dataAjaxTableLeads').html(the_result);
                    $('#datatableBuyersLead').DataTable({
                        "order": [[ 0, "desc" ]],
                        responsive: true
                    });
                    $("#filterListOfClickLeadsBuyer").text("Search");
                    $("#filterListOfClickLeadsBuyer").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#filterLeadSeller').click(function () {
        var service_id = $('#service_id').val();
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();
        if( start_date != '' && end_date != '' ) {
            $.ajax({
                url: list_of_leads_SellerAjax,
                method: "POST",
                data: {
                    service_id: service_id,
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                beforeSend: function() {
                    $("#filterLeadSeller").text("Searching…");
                    $("#filterLeadSeller").prop("disabled", true);
                },
                success: function (the_result) {
                    $('#dataAjaxTableLeads').html(the_result);
                    $('#datatable3').DataTable({
                        "order": [[ 0, "desc" ]],
                        responsive: true
                    });
                    $("#filterLeadSeller").text("Search");
                    $("#filterLeadSeller").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $('#FilterLeadLostReport').click(function () {
        var campaign = $('#campaign_id').val();
        var start_date = $('.StartDate').val();
        var end_date = $('.EndDate').val();

        if( start_date != '' && end_date != '' && campaign != null ) {
            $("#FilterLeadLostReport").text("Searching…");
            $("#FilterLeadLostReport").prop("disabled", true);
            $.ajax({
                url: FilterLostLeadReportAjax,
                method: "POST",
                data: {
                    campaign: campaign,
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableCampaign').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });

                    var table = $('#datatable-buttons').DataTable({
                        "order": [[ 0, "desc" ]],
                        lengthChange: false,
                        "responsive": true,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function () {
                    $("#FilterLeadLostReport").text("Search");
                    $("#FilterLeadLostReport").prop("disabled", false);
                }
            });
        } else {
            alert('Please choose campaign, Start/End Date..');
        }
    });
    //$('#FilterLeadLostReport').click();

    $('#ShowReturnTicketAjax').click(function () {
        var start_date = $('.StartDate').val();
        var end_date = $('.EndDate').val();
        var ticket_status_id = $('#ticket_status_id').val();

        if( start_date != '' && end_date != '' ) {
            $("#ShowReturnTicketAjax").text("Searching…");
            $("#ShowReturnTicketAjax").prop("disabled", true);
            $.ajax({
                url: ShowReturnTicketAjax,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    ticket_status_id: ticket_status_id,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTablecrm').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });

                    var table = $('#datatable-buttons').DataTable({
                        "order": [[ 0, "desc" ]],
                        lengthChange: false,
                        "responsive": true,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function () {
                    $("#ShowReturnTicketAjax").text("Search");
                    $("#ShowReturnTicketAjax").prop("disabled", false);
                }
            });
        } else {
            alert('Please choose Start/End Date..');
        }
    });
    $("#ShowReturnTicketAjax").click();

    $('#ShowIssueTicketAjax').click(function () {
        var start_date = $('.StartDate').val();
        var end_date = $('.EndDate').val();
        var ticket_status_id = $('#ticket_status_id').val();

        if( start_date != '' && end_date != '' ) {
            $("#ShowIssueTicketAjax").text("Searching…");
            $("#ShowIssueTicketAjax").prop("disabled", true);
            $.ajax({
                url: ShowIssueTicketAjax,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    ticket_status_id: ticket_status_id,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTablecrm').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });

                    var table = $('#datatable-buttons').DataTable({
                        "order": [[ 0, "desc" ]],
                        lengthChange: false,
                        "responsive": true,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function () {
                    $("#ShowIssueTicketAjax").text("Search");
                    $("#ShowIssueTicketAjax").prop("disabled", false);
                }
            });
        } else {
            alert('Please choose Start/End Date..');
        }
    });
    $("#ShowIssueTicketAjax").click();

    //Search Prospect users
    $('#filterProspectUsers').click(function () {
        var service_id = $('#service_id').val();
        var state_id = $('#state_id').val();
        var claimer_prospect = $('#claimer_prospect').val();
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' )  {
            $("#filterProspectUsers").text("Searching…");
            $("#filterProspectUsers").prop("disabled", true);
            $.ajax({
                url: AdminProspectsSearch,
                method: "POST",
                data: {
                    service_id: service_id,
                    state_id: state_id,
                    claimer_prospect: claimer_prospect,
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTablecrm').html(the_result);
                    $('#datatable4').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#filterProspectUsers").text("Search");
                    $("#filterProspectUsers").prop("disabled", false);
                }
            });
        } else {
            alert('Please choose Start/End Date..');
        }
    });
    $('#filterProspectUsers').click();

    //Search Sales Commission Report
    $('#FilterSalesCommissionReport').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        $("#FilterSalesCommissionReport").text("Searching…");
        $("#FilterSalesCommissionReport").prop("disabled", true);
        $.ajax({
            url: ReportsSalesCommissionSearch,
            method: "POST",
            data: {
                start_date: start_date,
                end_date: end_date,
                _token: token
            },
            success: function (the_result) {
                $('#dataAjaxTableReport').html(the_result);
                $('#datatable').DataTable({
                    "responsive": true,
                    "order": [[ 0, "desc" ]]
                });

                var table = $('#datatable-buttons').DataTable({
                    "order": [[ 0, "desc" ]],
                    lengthChange: false,
                    "responsive": true,
                    buttons: ['copy', 'excel', 'pdf', 'colvis']
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            },
            complete: function () {
                $("#FilterSalesCommissionReport").text("Search");
                $("#FilterSalesCommissionReport").prop("disabled", false);
            }
        });
    });
    $('#FilterSalesCommissionReport').click();

    $('#filterLeadForm').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();

        if( start_date != '' && end_date != '' ) {
            $("#filterLeadForm").text("Searching…");
            $("#filterLeadForm").prop("disabled", true);

            $.ajax({
                url: list_of_leads_FormAjax,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableForm').html(the_result);
                    $('#datatable3').DataTable({
                        "responsive": true,
                        "order": [[ 0, "desc" ]]
                    });
                },
                complete: function () {
                    $("#filterLeadForm").text("Search");
                    $("#filterLeadForm").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    $('#filterLeadForm').click();



    $(window).load(function() {
        $(".pagination").addClass('pull-right');
    });

    function fetch_data(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id) {
        $.ajax({
            url: url + page + "&query=" + query,
            data : {
                _token: token,
                buyer_id: buyer_id,
                service_id: service_id,
                states: states,
                start_date: start_date,
                end_date: end_date,
                environments: environments,
                traffic_source: traffic_source,
                google_g: google_g,
                google_c: google_c,
                google_k: google_k,
                county_id: county_id,
                city_id: city_id,
                zipcode_id: zipcode_id,
                seller_id : seller_id
            },

            success: function (data) {
                //console.log(data);
                $("#filterLeadTables").text("Search");
                $("#filterLeadTables").prop("disabled", false);
                $('#table_data').html('');
                $('#table_data').html(data);
                $(".pagination").addClass('pull-right');
            }
        })
    }

    $(document).on('keyup', '#search', function () {
        var query = $('#search').val();
        var page = $('#hidden_page').val();
        var url = $('#pagination-table').data('action');
        var seller_id = $('#seller_id').val();
        var buyer_id = $('#buyer_id').val();
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('.start_date_pagination').val();
        var end_date = $('.end_date_pagination').val();
        var environments = $('#environments').val();
        var traffic_source = $('#traffic_source').val();
        var google_g = $('#google_g').val();
        var google_c = $('#google_c').val();
        var google_k = $('#google_k').val();

        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();

        fetch_data(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id);
    });

    $(document).on('click', '.table-responsive .pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var query = $('#search').val();
        var url = $('#pagination-table').data('action');
        var seller_id = $('#seller_id').val();
        var buyer_id = $('#buyer_id').val();
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('.start_date_pagination').val();
        var end_date = $('.end_date_pagination').val();
        var environments = $('#environments').val();
        var traffic_source = $('#traffic_source').val();
        var google_g = $('#google_g').val();
        var google_c = $('#google_c').val();
        var google_k = $('#google_k').val();

        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();

        fetch_data(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id);
    });

    $('#filterLeadTables').click(function () {
        $('#search').val("");
        var page = $('#hidden_page').val();
        var query = "";
        var url = $('#pagination-table').data('action');

        var seller_id = $('#seller_id').val();
        var buyer_id = $('#buyer_id').val();
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('.start_date_pagination').val();
        var end_date = $('.end_date_pagination').val();
        var environments = $('#environments').val();
        var traffic_source = $('#traffic_source').val();
        var google_g = $('#google_g').val();
        var google_c = $('#google_c').val();
        var google_k = $('#google_k').val();

        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();


        if (start_date != '' && end_date != '') {
            $("#filterLeadTables").text("Searching…");
            $("#filterLeadTables").prop("disabled", true);
            fetch_data(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id);
        } else {
            alert('Please fill Start/End Date..');
        }
    });



    //new pagination table with sort
    function fetch_dataNew(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id, sort_type, sort_by) {
        $.ajax({
            url: url + page +"&sortby="+sort_by+"&sorttype="+sort_type+ "&query=" + query,
            data : {
                _token: token,
                buyer_id: buyer_id,
                service_id: service_id,
                states: states,
                start_date: start_date,
                end_date: end_date,
                environments: environments,
                traffic_source: traffic_source,
                google_g: google_g,
                google_c: google_c,
                google_k: google_k,
                county_id: county_id,
                city_id: city_id,
                zipcode_id: zipcode_id,
                seller_id : seller_id
            },

            success: function (data) {
                //console.log(data);
                $("#filterLeadTablesNew").text("Search");
                $("#filterLeadTablesNew").prop("disabled", false);
                $('#table_dataNew tbody').html('');
                $('#table_dataNew tbody').html(data);
                $(".pagination").addClass('pull-right');
            }
        })
    }

    $(document).on('keyup', '#searchNew', function () {
        var query = $('#searchNew').val();
        var page = $('#hidden_page').val();
        var url = $('#pagination-table').data('action');
        var seller_id = $('#seller_id').val();
        var buyer_id = $('#buyer_id').val();
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('.start_date_pagination').val();
        var end_date = $('.end_date_pagination').val();
        var environments = $('#environments').val();
        var traffic_source = $('#traffic_source').val();
        var google_g = $('#google_g').val();
        var google_c = $('#google_c').val();
        var google_k = $('#google_k').val();

        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();

        var column_name = $('#hidden_column_name').val();
        var sort_type = $('#hidden_sort_type').val();

        fetch_dataNew(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id, sort_type, column_name);
    });

    $(document).on('click', '.sorting', function(){
        var query = $('#searchNew').val();
        var page = 1;
        var url = $('#pagination-table').data('action');
        var seller_id = $('#seller_id').val();
        var buyer_id = $('#buyer_id').val();
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('.start_date_pagination').val();
        var end_date = $('.end_date_pagination').val();
        var environments = $('#environments').val();
        var traffic_source = $('#traffic_source').val();
        var google_g = $('#google_g').val();
        var google_c = $('#google_c').val();
        var google_k = $('#google_k').val();

        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();

        var column_name = $(this).data('column_name');
        var order_type = $(this).data('sorting');
        var reverse_order = '';
        if(order_type == 'ASC')
        {
            $(this).data('sorting', 'DESC');
            reverse_order = 'DESC';
            // clear_icon();
            $('#'+column_name+'_icon').html('<i class="fa fa-arrow-down" aria-hidden="true"></i>');
        }
        if(order_type == 'DESC')
        {
            $(this).data('sorting', 'ASC');
            reverse_order = 'ASC';
            //clear_icon
            $('#'+column_name+'_icon').html('<i class="fa fa-arrow-up" aria-hidden="true"></i>');
        }
        $('#hidden_column_name').val(column_name);
        $('#hidden_sort_type').val(reverse_order);

        fetch_dataNew(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id, reverse_order, column_name);
    });

    $(document).on('click', '.table-responsiveNew .pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var query = $('#searchNew').val();
        var url = $('#pagination-table').data('action');
        var seller_id = $('#seller_id').val();
        var buyer_id = $('#buyer_id').val();
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('.start_date_pagination').val();
        var end_date = $('.end_date_pagination').val();
        var environments = $('#environments').val();
        var traffic_source = $('#traffic_source').val();
        var google_g = $('#google_g').val();
        var google_c = $('#google_c').val();
        var google_k = $('#google_k').val();

        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();

        var column_name = $('#hidden_column_name').val();
        var sort_type = $('#hidden_sort_type').val();

        fetch_dataNew(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id, sort_type, column_name);
    });

    $('#filterLeadTablesNew').click(function () {
        $('#search').val("");
        var page = 1;
        var query = "";
        var url = $('#pagination-table').data('action');

        var seller_id = $('#seller_id').val();
        var buyer_id = $('#buyer_id').val();
        var states = $('#statenamelead').val();
        var service_id = $('#service_id').val();
        var start_date = $('.start_date_pagination').val();
        var end_date = $('.end_date_pagination').val();
        var environments = $('#environments').val();
        var traffic_source = $('#traffic_source').val();
        var google_g = $('#google_g').val();
        var google_c = $('#google_c').val();
        var google_k = $('#google_k').val();

        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();

        var column_name = $('#hidden_column_name').val();
        var sort_type = $('#hidden_sort_type').val();

        if (start_date != '' && end_date != '') {
            $("#filterLeadTablesNew").text("Searching…");
            $("#filterLeadTablesNew").prop("disabled", true);
            fetch_dataNew(url, page, query, buyer_id, states, service_id, start_date, end_date, environments, traffic_source, google_g, google_c, google_k, county_id, city_id, zipcode_id,seller_id, sort_type, column_name);
        } else {
            alert('Please fill Start/End Date..');
        }
    });

    $(window).load(function() {
        $('#filterLeadTablesNew').click();
    });

    //RevShareSeller Ajax
    $('#filterLeadRevShare').click(function (e) {
        e.preventDefault();
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();
        if(start_date != '' && end_date != '') {
            $.ajax({
                url: HomeRevShareSellerAjax,
                method: "POST",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    _token: token
                },
                beforeSend: function() {
                    $("#filterLeadRevShare").text("Searching…");
                    $("#filterLeadRevShare").prop("disabled", true);
                },
                success: function (the_result) {
                    $('#leads_count').html(the_result['LeadsCount']);
                    $('#total_bid_lead').html(the_result['total_bid_lead']);
                    $('#percentage').html(the_result['percentage']);
                    $('#profit_percentage').html(the_result['profit_percentage']);
                    $('#last_transaction').html(the_result['last_transaction']);
                },
                complete: function () {
                    $("#filterLeadRevShare").text("Search");
                    $("#filterLeadRevShare").prop("disabled", false);
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    $('#filterLeadRevShare').click();

});
