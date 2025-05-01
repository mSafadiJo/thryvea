$(document).ready(function () {

    //FilterLeadVolume Reports
    $('#FilterLeadVolume').click(function () {
        var service_id = $('#services-reports').val();
        var state_id = $('#states-reports').val();
        var admin_id = $('#admins-reports').val();
        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();
        var distance_area = $('#distance_area').val();
        var trafficSource = $('#trafficSource-reports').val();

        if( from_date != '' && to_date != '' ){
            // $('.unloader').hide();
            // $('.loader').show();
            $("#FilterLeadVolume").text("Searching…");
            $("#FilterLeadVolume").prop("disabled", true);
            $.ajax({
                url: AdminReportlead_volumedata,
                method: "POST",
                data: {
                    service_id: service_id,
                    state_id: state_id,
                    admin_id: admin_id,
                    from_date: from_date,
                    to_date: to_date,
                    county_id: county_id,
                    city_id: city_id,
                    zipcode_id: zipcode_id,
                    distance_area: distance_area,
                    trafficSource: trafficSource,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    // $('.unloader').show();
                    // $('.loader').hide();
                    $("#FilterLeadVolume").text("Search");
                    $("#FilterLeadVolume").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    //$('#FilterLeadVolume').click();

    //FilterLeadVolume Reports
    $('#FilterSellerLeadVolume').click(function () {
        var service_id = $('#services-reports').val();
        var state_id = $('#states-reports').val();
        var seller_id = $('#seller_id-reports').val();
        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();
        var distance_area = $('#distance_area').val();

        if( from_date != '' && to_date != ''  ){
            // $('.unloader').hide();
            // $('.loader').show();
            $("#FilterSellerLeadVolume").text("Searching…");
            $("#FilterSellerLeadVolume").prop("disabled", true);
            $.ajax({
                url: AdminReportsSeller_Lead_VolumeSearch,
                method: "POST",
                data: {
                    service_id: service_id,
                    state_id: state_id,
                    seller_id: seller_id,
                    from_date: from_date,
                    to_date: to_date,
                    county_id: county_id,
                    city_id: city_id,
                    zipcode_id: zipcode_id,
                    distance_area: distance_area,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    // $('.unloader').show();
                    // $('.loader').hide();
                    $("#FilterSellerLeadVolume").text("Search");
                    $("#FilterSellerLeadVolume").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Seller And Start/End Date..');
        }
    });
    //$('#FilterSellerLeadVolume').click();

    //FilterPerformanceReport Reports
    $('#FilterPerformanceReport').click(function () {
        var service_id = $('#service_id').val();
        var state_id = $('#state').val();
        var admin_id = $('#adminfilter').val();
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();

        if( from_date != '' && to_date != '' ){
            // $('.unloader').hide();
            // $('.loader').show();
            $("#FilterPerformanceReport").text("Searching…");
            $("#FilterPerformanceReport").prop("disabled", true);
            $.ajax({
                url: AdminReportPerformance_Reportsdata,
                method: "POST",
                data: {
                    service_id: service_id,
                    state_id: state_id,
                    admin_id: admin_id,
                    from_date: from_date,
                    to_date: to_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    // $('.unloader').show();
                    // $('.loader').hide();
                    $("#FilterPerformanceReport").text("Search");
                    $("#FilterPerformanceReport").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    //$('#FilterPerformanceReport').click();

    //FilterLead-websites  Reports
    $('#FilterLeadWebsites').click(function () {
        var service_id = $('#services-reports').val();
        var state_id = $('#states-reports').val();
        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();
        var trafficSource = $('#trafficSource-reports').val();
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();
        var sold_status = $('#sold').val();
        if( from_date != '' && to_date != ''){
            // $('.unloader').hide();
            // $('.loader').show();
            $("#FilterLeadWebsites").text("Searching…");
            $("#FilterLeadWebsites").prop("disabled", true);
            $.ajax({
                url: AdminReportlead_from_websitesdata,
                method: "POST",
                data: {
                    service_id: service_id,
                    state_id: state_id,
                    sold_status:sold_status,
                    county_id:county_id,
                    zipcode_id:zipcode_id,
                    city_id:city_id,
                    from_date: from_date,
                    to_date: to_date,
                    trafficSource: trafficSource,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    // $('.unloader').show();
                    // $('.loader').hide();
                    $("#FilterLeadWebsites").text("Search");
                    $("#FilterLeadWebsites").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    //$('#FilterLeadWebsites').click();

    // lead report
    $('#FilterLeadReport').click(function () {
        // $('.unloader').hide();
        // $('.loader').show();
        $("#FilterLeadReport").text("Searching…");
        $("#FilterLeadReport").prop("disabled", true);
        var service_id = $('#services-reports').val();
        var state_id = $('#states-reports').val();
        var county_id = $('#counties-reports').val();
        var city_id = $('#cities-reports').val();
        var zipcode_id = $('#zipcodes-reports').val();
        var buyers = $('#buyers-reports').val();
        var trafficSource = $('#trafficSource-reports').val();
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();
        var sold_status = $('#sold').val();
        if( from_date != '' && to_date != ''){
            $.ajax({
                url: AdminReportlead_reportdata,
                method: "POST",
                data: {
                    service_id: service_id,
                    state_id: state_id,
                    sold_status:sold_status,
                    county_id:county_id,
                    zipcode_id:zipcode_id,
                    city_id:city_id,
                    from_date: from_date,
                    to_date: to_date,
                    buyers: buyers,
                    trafficSource: trafficSource,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    // $('.unloader').show();
                    // $('.loader').hide();
                    $("#FilterLeadReport").text("Search");
                    $("#FilterLeadReport").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    //$('#FilterLeadReport').click();

    //Filter Sales Reports Reports
    $('#FilterSalesReport').click(function () {
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();

        if( from_date != '' && to_date != '' ){
            // $('.unloader').hide();
            // $('.loader').show();
            $("#FilterSalesReport").text("Searching…");
            $("#FilterSalesReport").prop("disabled", true);
            $.ajax({
                url: AdminReportSalesReportdata,
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    // $('.unloader').show();
                    // $('.loader').hide();
                    $("#FilterSalesReport").text("Search");
                    $("#FilterSalesReport").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    //$('#FilterSalesReport').click();

    //Filter SDR Reports Reports
    $('#FilterSDRReport').click(function () {
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();

        if( from_date != '' && to_date != '' ){
            // $('.unloader').hide();
            // $('.loader').show();
            $("#FilterSDRReport").text("Searching…");
            $("#FilterSDRReport").prop("disabled", true);
            $.ajax({
                url: AdminReportSDRReportdata,
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    // $('.unloader').show();
                    // $('.loader').hide();
                    $("#FilterSDRReport").text("Search");
                    $("#FilterSDRReport").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    //$('#FilterSDRReport').click();

    //Filter Account Manager Reports Reports
    $('#FilterAccountManagerReport').click(function () {
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();

        if( from_date != '' && to_date != '' ){
            // $('.unloader').hide();
            // $('.loader').show();
            $("#FilterAccountManagerReport").text("Searching…");
            $("#FilterAccountManagerReport").prop("disabled", true);
            $.ajax({
                url: AdminReportAccountManagerReportdata,
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    // $('.unloader').show();
                    // $('.loader').hide();
                    $("#FilterAccountManagerReport").text("Search");
                    $("#FilterAccountManagerReport").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    //$('#FilterAccountManagerReport').click();

    $('#FilterAffiliateReport').click(function () {
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();

        if( from_date != '' && to_date != '')  {
            $("#FilterAffiliateReport").text("Searching…");
            $("#FilterAffiliateReport").prop("disabled", true);
            $.ajax({
                url: AffiliateReportAjax,
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReportAff').html(the_result);
                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function () {
                    $("#FilterAffiliateReport").text("Search");
                    $("#FilterAffiliateReport").prop("disabled", false);
                }
            });
        } else {
            alert('Please choose campaign, Start/End Date..');
        }
    });
    //$('#FilterAffiliateReport').click();

    $('#FilterAgentsReport').click(function () {
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();

        if( from_date != '' && to_date != '')  {
            $("#FilterAgentsReport").text("Searching…");
            $("#FilterAgentsReport").prop("disabled", true);
            $.ajax({
                url: AgentsCallCenterAjax,
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReportAGE').html(the_result);
                    $('#datatable').DataTable();

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function () {
                    $("#FilterAgentsReport").text("Search");
                    $("#FilterAgentsReport").prop("disabled", false);
                }
            });
        } else {
            alert('Please choose campaign, Start/End Date..');
        }
    });
    //$('#FilterAgentsReport').click();

    //Search call center profit report
    $('#FilterCallCenterLeadsProfit').click(function () {
        var start_date = $('#datepicker1').val();
        var end_date = $('#datepicker2').val();
        if( start_date != '' && end_date != '')  {
            $("#FilterCallCenterLeadsProfit").text("Searching…");
            $("#FilterCallCenterLeadsProfit").prop("disabled", true);
            $.ajax({
                url: ReportscallCenterProfitSearch,
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
                        "order": [[ 0, "ASC" ]]
                    });

                    var table = $('#datatable-buttons').DataTable({
                        "order": [[ 0, "ASC" ]],
                        lengthChange: false,
                        "responsive": true,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function () {
                    $("#FilterCallCenterLeadsProfit").text("Search");
                    $("#FilterCallCenterLeadsProfit").prop("disabled", false);
                }
            });
        } else {
            alert('Please choose campaign, Start/End Date..');
        }
    });
    //$('#FilterCallCenterLeadsProfit').click();

    //FilterMarketingCost Reports
    $('#FilterMarketingCost').click(function () {
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();

        if( from_date != '' && to_date != '' ){
            $("#FilterMarketingCost").text("Searching…");
            $("#FilterMarketingCost").prop("disabled", true);
            $.ajax({
                url: MarketingCostReport,
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);
                    $('#datatable').DataTable({
                        "responsive": true,
                        "order": [[ 0, "ASC" ]]
                    });

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                },
                complete: function(hxr, status) {
                    $("#FilterMarketingCost").text("Search");
                    $("#FilterMarketingCost").prop("disabled", false);
                },
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
});
