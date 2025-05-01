//Number Of Windows Shown
$(document).ready(function(){
    $('#service_idCampainAddAdmnin').change(function(){
        var value = $('#service_idCampainAddAdmnin').val();

        $('.questions_div_section_select_campaign').prop('required',false);
        $('.questions_div_section_campaign').hide();

        $('.div_service'+value+'_section_camp .questions_div_section_select_campaign').prop('required',true);
        $('.div_service'+value+'_section_camp').show();
        //To check the utility providers
        if( value == 2 ){
            if($("#is_utility_providers").is(":checked")){
                $('.campaign_utility_providers .questions_div_section_select_campaign').prop('required',true);
            } else {
                $('.campaign_utility_providers .questions_div_section_select_campaign').prop('required',false);
            }
        }

        //To check the painting services if checked or not
        $('.painting_service').each(function () {
            var painting_service_id = $(this).val();
            if(this.checked){
                $('#painting_service'+painting_service_id+' .questions_div_section_select_campaign').prop('required',true);
            } else {
                $('#painting_service'+painting_service_id+' .questions_div_section_select_campaign').prop('required',false);
            }
        });

        $('.paving_service').each(function () {
            var paving_service_id = $(this).val();
            if(this.checked){
                $('#ps'+paving_service_id+' .questions_div_section_select_campaign').prop('required',true);
            } else {
                $('#ps'+paving_service_id+' .questions_div_section_select_campaign').prop('required',false);
            }
        });

    });

    $('#service_idCampainAddAdmnin').change();

    $("#is_utility_providers").change(function () {
        if($(this).is(":checked")){
            $(".campaign_utility_providers").show();
            $('.campaign_utility_providers .questions_div_section_select_campaign').prop('required',true);
        } else {
            $(".campaign_utility_providers").hide();
            $('.campaign_utility_providers .questions_div_section_select_campaign').prop('required',false);
        }
    });
});

function multiServicesCheckboxPaving(i) {
    if( i == 'ps1' || i == 'ps3' ){
        var x = document.getElementById(i);
        if (x.style.display === "none") {
            x.style.display = "table";
            if( i == 'ps1' ){
        //        alert("ps1");
                $('#paving_asphalt_type').prop('required',true);
            } else {

                $('#paving_loose_fill_type').prop('required',true);
            }
        } else {
            x.style.display = "none";
            if( i == 'ps1' ){
            //    alert("ps1-1");
                $('#paving_asphalt_type').prop('required',false);
            } else {
                $('#paving_loose_fill_type').prop('required',false);
            }
        }
    }
}

function multiServicesCheckboxPainting(id) {
   // alert("multiServicesCheckboxPainting");
    var x = document.getElementById(id);
    if (x.style.display === "none") {
        x.style.display = "table";
        $('#'+id+' .questions_div_section_select_campaign').prop('required',true);
    } else {
        x.style.display = "none";
        $('#'+id+' .questions_div_section_select_campaign').prop('required',false);
    }
}


$(document).ready(function() {
    $('#submitFormCam').on('click', function (event) {
        if (($("#special_source_price").val() > 0) && ($("#special_budget_bid_exclusive").val() > 0)){
            alert("You can only choose special state or special source!");
            return false;
        }
        if((($("#special_source").val().length > 0) && $("#special_state").val() != null )){
            alert("You can only choose special state or special source!");
            return false;
        }

        if ($('#service_idCampainAddAdmnin').val() == 23) {
            if ($('div.checkbox-group.required :checkbox:checked').length <= 0) {
                return false;
            }
        }else if($('#service_idCampainAddAdmnin').val() == 22){
            if ($('div.checkbox-group.required :checkbox:checked').length <= 0) {
                return false;
            }
        }
    });

    $('.UpdateCamp').on('click', function (event) {
        if (($("#special_source_price").val() > 0) && ($("#special_budget_bid_exclusive").val() > 0)){
            alert("You can only choose special state or special source!");
            return false;
        }

        if((($("#special_source").val().length > 0) && $("#special_state").val() != null )){
            alert("You can only choose special state or special source!");
            return false;
        }


        var n = $('#service_idCampainAddAdmnin').val() ;
        if ($('#service_idCampainAddAdmnin').val() == 23) {
            if ($('.div_service23_section_camp div.checkbox-group.required :checkbox:checked').length <= 0) {
                return false;
            }
        }else if($('#service_idCampainAddAdmnin').val() == 22){
            if ($('.div_service22_section_camp div.checkbox-group.required :checkbox:checked').length <= 0) {
                return false;
            }
        }
    });
});