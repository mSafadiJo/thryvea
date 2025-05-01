setInterval(function() {
    $.ajax({
        url: CallCenterDashboardReload,
        method: "POST",
        data: {
            _token: token
        },
        success: function (the_result) {
            $('#callCenter_transfers_dashboard_div').html(the_result.callCenter_transfers_dashboard_div);
            $('#callCenter_transfers_dashboard_body').html(the_result.callCenter_transfers_dashboard_body);
        }
    });
}, 3000);

