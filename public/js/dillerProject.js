$(document).ready(function(){
   $('#filterForSolarUserData').click(function () {
       var from_date = $('#datepicker1').val();
       var to_date = $('#datepicker2').val();

       if( from_date != '' && to_date != '' ) {
           $.ajax({
               url: Solar_System_Form_User_search,
               method: "POST",
               data: {
                   from_date: from_date,
                   to_date: to_date,
                   _token: token
               },
               success: function (the_result) {
                   $('#dataAjaxTableReport').html(the_result);
                   $('#fromdate_excel').val(from_date);
                   $('#todate_excel').val(to_date);
                   $('#datatable').DataTable();
                   $("select.form-control.form-control-sm").css("height", "35px");
               }
           });
       } else {
           alert('Please fill Start/End Date..');
       }
   });
    $('#filterForSolarUserData').click();


    $('#SuperAdminSearchSolarSystemDialer').click(function () {
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();
        var SoldData = $('#SoldData').val();

        if( from_date != '' && to_date != '' ) {
            $.ajax({
                url: Admin_DialerSystem_Solar_Search,
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    SoldData: SoldData,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    $('#SuperAdminSearchSolarSystemDialer').click();

    $('#SuperAdminSearchSolarSystemDialer_Comp').click(function () {
        var from_date = $('#datepicker1').val();
        var to_date = $('#datepicker2').val();
        var SoldData = $('#SoldData').val();

        if( from_date != '' && to_date != '' ) {
            $.ajax({
                url: Admin_DialerSystem_Solar_Search2,
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    SoldData: SoldData,
                    _token: token
                },
                success: function (the_result) {
                    $('#dataAjaxTableReport').html(the_result);

                    var table = $('#datatable-buttons').DataTable({
                        lengthChange: false,
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    });

                    table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
                }
            });
        } else {
            alert('Please fill Start/End Date..');
        }
    });
    $('#SuperAdminSearchSolarSystemDialer_Comp').click();
});

function senddataleadforCrm(id) {
    $('#sendleadToCRMCustomer-'+id).prop("disabled", true);
    $.ajax({
        url: Solar_System_Form_User_send,
        method: "POST",
        data: {
            id: id,
            _token: token
        },
        success: function (the_result) {
            $('#sendleadToCRMCustomer-'+id).prop("disabled", false);
            if( the_result == 1 || the_result == 0 ){
                $('#sendleadToCRMCustomer-'+id).hide();
                $('#Solar_System_Form_User_Edit'+id).hide();
                $('#DeleteForm-'+id).hide();
                $('#DeleteCampaignspan-'+id).hide();
                $('#Solar_System_Form_User_Details'+id).show();
                if( the_result == 1 ){
                    alert('Sold Lead');
                } else {
                    alert('UnSold Lead');
                }
            } else {
                $('#errorapiCrm').html(the_result);
                alert('something was wrong');
            }
        }
    });
}