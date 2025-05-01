
$(document).ready(function(){
    $('#BuyerIdShopLead').on('change', function() {
        $('#CampaignsShopLead').empty();
        $('.inputShopLead').empty();
        $("#LeadSourceShopLead").val('');
        $('#LeadSourceShopLead').prop('disabled', true);
        var type = $(this).data("type");

        var BuyerId = $(".select2 option:selected").val();
        $.ajax({
            url: getCampaignsByBuyer,
            method: "POST",
            data: {
                BuyerId: BuyerId,
                type: type,
                _token: token
            },
            success: function(the_result){
                $("#CampaignsShopLead").append('<option value="">Choose ...</option>');
                for(var i=0 ; i<the_result.length;i++) {
                    $("#CampaignsShopLead").append('<option value="' + the_result[i]['campaign_id'] + '">' + the_result[i]['campaign_name'] + '</option>');
                }
                $('#CampaignsShopLead').prop('disabled', false);
            }
        });
    });

// For Get All Campaigns From Buyer Name on Exclude & includes section
    $('#BuyerIdShopLeadEx').on('change', function() {
        $('#CampaignsShopLead').empty();
        $('.inputShopLead').empty();
        $("#LeadSourceShopLead").val('');
        $('#LeadSourceShopLead').prop('disabled', true);
        var type = $(this).data("type");

        var BuyerId = $(".select2 option:selected").val();
        $.ajax({
            url: getCampaignsByBuyerEx,
            method: "POST",
            data: {
                BuyerId: BuyerId,
                type: type,
                _token: token
            },
            success: function(the_result){
                $("#CampaignsShopLead").append('<option value="">Choose ...</option>');
                for(var i=0 ; i<the_result.length;i++) {
                    $("#CampaignsShopLead").append('<option value="' + the_result[i]['campaign_id'] + '">' + the_result[i]['campaign_name'] + '</option>');
                }
                $('#CampaignsShopLead').prop('disabled', false);
            }
        });
    });

    $('#CampaignsShopLead').on('change', function() {
        // $('#LeadSourceShopLead').prop('disabled', false);
        $('#seller_idShopLead').prop('disabled', false);
        $('.inputShopLead').empty();
        $("#LeadSourceShopLead").empty();

        var CampaignId = $("#CampaignsShopLead option:selected").val();
        $.ajax({
            url: getAllSourceByCampaign,
            method: "POST",
            data: {
                CampaignId: CampaignId,
                _token: token
            },
            success: function(the_result){
                //console.log(the_result); return false;
                $("#LeadSourceShopLead").append('<option value="">Choose ...</option>');
                for(var i=0 ; i<the_result.length;i++) {
                    $("#LeadSourceShopLead").append('<option value="' + the_result[i]['id'] + '">' + the_result[i]['name'] + '</option>');
                }
                $('#LeadSourceShopLead').prop('disabled', false);
            }
        });


    });

    $('#LeadSourceShopLead').on('change', function() {

        var LeadSourceId = this.value;
        var selectedText = $(this).find("option:selected").text();
        var inputName= 'percentage_'+LeadSourceId ;
        var foundiD =  $(".inputShopLead").find("input[name="+inputName+"]");

        if(foundiD['length'] == 0){
            $(".inputShopLead").append('<div class="col-sm-3"><div class="form-group">' +
                '<label for="county" style="display: block;">'+ selectedText+'</label>' +
                '<input style="width: 90%;'+
                'display: inline-block;" class="form-control percentageValue"  type="text" name="percentage_'+LeadSourceId+ '"/>' +
                '<input style="visibility: hidden;" type="checkbox" checked name="percentage[]" value="percentage_'+LeadSourceId+ '"/>' +
                ' <i style="cursor: pointer;" class="fa fa-times clearShopLead"></i>' +
                '</div>' +
                '</div>');
        }
        $('#submitShopLead').prop('disabled', false);
    });

    $(document).on('click', '.clearShopLead', function(){
        $(this).parent('div').parent('div').remove();
    });


    $(document).on('click', '#submitShopLead', function(e){
        e.preventDefault();

        var sum = 0;
        $(".percentageValue").each(function(){
            this.value = this.value.replace(/\s/g,'');

            if(!this.value){
                return false;
            }else{
                sum += +this.value;
            }
        });
        if($(".inputShopLead").html()==""){
            alert("make sure you enter all chose");
        } else {
            if(sum <= 100) {
                $("#ShopLeadForm").submit();
            } else {
                alert("Make sure not to enter values that their sum exceeds 100 OR empty!");

            }
        }
        // alert(sum);


    });

    $(document).on('click', '#submitShopLeadUpdae', function(e){
        e.preventDefault();

        var sum = 0;
        var counter = 0;
        var emp = 0;
        $(".percentageValueUpdate").each(function(){
            this.value = this.value.replace(/\s/g,'');
            counter += +1;
            if(!this.value)
            {
                emp += +1;
            }
            sum += +this.value;
        });

        if(counter != emp)
        {

            if($(".ShopLeadFormUpdate").html()==""){
                alert("make sure you enter all chose");
            } else {
                if(sum <= 100) {
                    $("#ShopLeadFormUpdate").submit();
                } else {
                    alert("Make sure not to enter values that their sum exceeds 100 OR empty!");
                }
            }
        }else{
            alert("make sure you enter all chose");
        }

    });

    $(document).on('keypress', '.percentageValue', function(e){
        if(e.which === 32)
            return false;
    });

    $(document).on('keypress', '.percentageValueUpdate', function(e){
        if(e.which === 32)
            return false;
    });

    $(document).on('click', '#submitShopLeadEx', function(e){
        e.preventDefault();

        if(!$('#seller_idShopLead').val()){
            alert("Seller Can't Be Empty");
        } else {
            $("#ShopLeadForm").submit();
        }
    });


    $(document).on('click', '#submitShopLeadUpdateEx', function(e){
        e.preventDefault();

        if(!$('#seller_idEX').val()){
            alert("Seller Can't Be Empty");
        } else {
            $("#ShopLeadFormUpdateEx").submit();
        }
    });

    $(document).on('click', '#submitShopLeadBuyer', function(e){
        e.preventDefault();

        if(!$('#ExcludeBuyers1').val() || !$('#ExcludeBuyers2').val() ){
            alert(" Buyer Can't Be Empty");
        } else {
            $("#ShopLeadFormBuyer").submit();
        }
    });

});

