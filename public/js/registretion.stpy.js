$(document).ready(function() {
    $('#submitFormCam').click(function() {
        var propertytype = $('#propertytype').val();
        var Installings = $('#Installings').val();
        var customPaid = $('#customPaid').val();
        var homeowned = $('#homeowned').val();
        if($("#default-wizard-head-3").hasClass('stepy-active')) {
            if (!propertytype || !Installings || !customPaid || !homeowned
                || budget_bid_shared == '' || budget_bid_exclusive == '') {
                $("#propertytype").next().css("border", "1px solid #dadada");
                $("#propertytype").next().css("box-shadow", "none");
                $("#Installings").next().css("border", "1px solid #dadada");
                $("#Installings").next().css("box-shadow", "none");
                $("#customPaid").next().css("border", "1px solid #dadada");
                $("#customPaid").next().css("box-shadow", "none");
                $("#homeowned").next().css("border", "1px solid #dadada");
                $("#homeowned").next().css("box-shadow", "none");
                if (!propertytype) {
                    $("#propertytype").next().css("border", "1px solid red");
                    $("#propertytype").next().css("box-shadow", "0 0 5px 0 red");
                }
                if (!Installings) {
                    $("#Installings").next().css("border", "1px solid red");
                    $("#Installings").next().css("box-shadow", "0 0 5px 0 red");
                }
                if (!customPaid) {
                    $("#customPaid").next().css("border", "1px solid red");
                    $("#customPaid").next().css("box-shadow", "0 0 5px 0 red");
                }
                if (!homeowned) {
                    $("#homeowned").next().css("border", "1px solid red");
                    $("#homeowned").next().css("box-shadow", "0 0 5px 0 red");
                }
                $('#submitForm').click();
            } else {
                $("#propertytype").next().css("border", "1px solid #dadada");
                $("#propertytype").next().css("box-shadow", "none");
                $("#Installings").next().css("border", "1px solid #dadada");
                $("#Installings").next().css("box-shadow", "none");
                $("#customPaid").next().css("border", "1px solid #dadada");
                $("#customPaid").next().css("box-shadow", "none");
                $("#homeowned").next().css("border", "1px solid #dadada");
                $("#homeowned").next().css("box-shadow", "none");
                methods.step.call(self, (index + 1) + 1);
            }
        } else {
            $("#propertytype").next().css("border", "1px solid #dadada");
            $("#propertytype").next().css("box-shadow", "none");
            $("#Installings").next().css("border", "1px solid #dadada");
            $("#Installings").next().css("box-shadow", "none");
            $("#customPaid").next().css("border", "1px solid #dadada");
            $("#customPaid").next().css("box-shadow", "none");
            $("#homeowned").next().css("border", "1px solid #dadada");
            $("#homeowned").next().css("box-shadow", "none");
            methods.step.call(self, (index + 1) + 1);
        }
    });

    $('#submitForm').click(function() {
        var stateList = $('#state_name').val();
        var cityList = $('#cityList').val();
        var zipcodeList = $('#zipcodeList').val();
        var streetname = $('#streetname').val();
        if($("#default-wizard-head-2").hasClass('stepy-active')) {
            if (stateList == '' || cityList == '' || zipcodeList == '' || streetname == '') {
                $("#stateList").next().css("border", "1px solid #dadada");
                $("#stateList").next().css("box-shadow", "none");
                $("#cityList").next().css("border", "1px solid #dadada");
                $("#cityList").next().css("box-shadow", "none");
                $("#zipcodeList").next().css("border", "1px solid #dadada");
                $("#zipcodeList").next().css("box-shadow", "none");
                $("#streetname").css("border", "1px solid #dadada");
                $("#streetname").css("box-shadow", "none");
                if (stateList == '') {
                    $("#stateList").next().css("border", "1px solid red");
                    $("#stateList").next().css("box-shadow", "0 0 5px 0 red");
                }
                if (cityList == '') {
                    $("#cityList").next().css("border", "1px solid red");
                    $("#cityList").next().css("box-shadow", "0 0 5px 0 red");
                }
                if (zipcodeList == '') {
                    $("#zipcodeList").next().css("border", "1px solid red");
                    $("#zipcodeList").next().css("box-shadow", "0 0 5px 0 red");
                }
                if (streetname == '') {
                    $("#streetname").next().css("border", "1px solid red");
                    $("#streetname").next().css("box-shadow", "0 0 5px 0 red");
                }
                $('#submitForm').click();
            } else {
                $("#stateList").next().css("border", "1px solid #dadada");
                $("#stateList").next().css("box-shadow", "none");
                $("#cityList").next().css("border", "1px solid #dadada");
                $("#cityList").next().css("box-shadow", "none");
                $("#zipcodeList").next().css("border", "1px solid #dadada");
                $("#zipcodeList").next().css("box-shadow", "none");
                $("#streetname").css("border", "1px solid #dadada");
                $("#streetname").css("box-shadow", "none");
                methods.step.call(self, (index + 1) + 1);
            }
        } else {
            $("#stateList").next().css("border", "1px solid #dadada");
            $("#stateList").next().css("box-shadow", "none");
            $("#cityList").next().css("border", "1px solid #dadada");
            $("#cityList").next().css("box-shadow", "none");
            $("#zipcodeList").next().css("border", "1px solid #dadada");
            $("#zipcodeList").next().css("box-shadow", "none");
            $("#streetname").css("border", "1px solid #dadada");
            $("#streetname").css("box-shadow", "none");
        }
    });
});