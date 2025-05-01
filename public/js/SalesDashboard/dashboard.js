setInterval(function() {
    $.ajax({
        url: SalesDashboardReload,
        method: "POST",
        data: {
            _token: token
        },
        success: function (the_result) {
            $('#sales_transfers_dashboard_div_1').html(the_result.sales_transfers_dashboard_div_1);
            $('#sales_transfers_dashboard_body_1').html(the_result.sales_transfers_dashboard_body_1);
            $('#sales_transfers_dashboard_div_2').html(the_result.sales_transfers_dashboard_div_2);
            $('#sales_transfers_dashboard_body_2').html(the_result.sales_transfers_dashboard_body_2);
        }
    });
}, 3000);

