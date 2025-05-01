$(document).ready(function() {
    $("#submit_lead_virified_forms_buttons").click(function () {
        $("#submit_lead_virified_forms_buttons").text("Sending…");
        $("#submit_lead_virified_forms_buttons").prop("disabled", true);
        $("#submit_lead_virified_forms").submit();
    });

    $("#statenamelead").change(function () {
        var value = $(this).val();
        $("#utility_provider option").hide();
        $("#utility_provider option[class='utility_provider_stateId']").show();
        $("#utility_provider option[class='utility_provider_stateId"+value+"']").show();
    });
    $("#statenamelead").change();

    $('#service_id_forms').change(function () {
        var value = $(this).val();
        if( value == 1 ){
            $('.c-windows-service').show();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').show();
            $('.c-main-OwnRented-service').show();
        } else if( value == 2 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').show();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').show();
            $('.c-main-priority-service').hide();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').hide();
        } else if( value == 3 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').show();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').show();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').hide();
        } else if( value == 4 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').show();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 5 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').show();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 6 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').show();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $(".c-roofing-service.c-Sunrooms-service").show();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').hide();
        } else if( value == 7 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').show();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 8 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').show();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 9 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').show();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 10 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').show();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 11 || value == 12 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').show();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').show();
            $('.c-main-OwnRented-service').show();
        } else if( value == 13 || value == 14 ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').show();
            $('.c-main-OwnRented-service').show();
        } else if( value == 15  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').show();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 16  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 17  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').show();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $(".c-roofing-service.c-Sunrooms-service").show();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').hide();
        } else if( value == 18  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').show();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();
        } else if( value == 19  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').show();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').show();
            $('.c-main-OwnRented-service').show();
        } else if( value == 20  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').show();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').show();
            $('.c-main-OwnRented-service').show();
        } else if( value == 21  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').show();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').show();
            $('.c-main-OwnRented-service').show();
        } else if( value == 22  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').show();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();

            $("#paving_service").change();
        } else if( value == 23  ){
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-homeSiding-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').show();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').show();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').show();

            $("#painting_service").change();
        } else {
            $('.c-windows-service').hide();
            $('.c-solar-service').hide();
            $('.c-homesecurity-service').hide();
            $('.c-flooring-service').hide();
            $('.c-walkintubs-service').hide();
            $('.c-roofing-service').hide();
            $('.c-kitchen-service').hide();
            $('.c-Bathroom-service').hide();
            $('.c-Stairlifts-service').hide();
            $('.c-Furnace-service').hide();
            $('.c-Boiler-service').hide();
            $('.c-CentralAC-service').hide();
            $('.c-Cabinet-service').hide();
            $('.c-Plumbing-service').hide();
            $('.c-Bathtubs-service').hide();
            $('.c-Sunrooms-service').hide();
            $('.c-Handyman-service').hide();
            $('.c-Countertops-service').hide();
            $('.c-Doors-service').hide();
            $('.c-Gutter-service').hide();
            $('.c-Paving-service').hide();
            $('.c-Painting-service').hide();

            $('.c-main-property_type_c-service').hide();
            $('.c-main-priority-service').hide();
            $('.c-main-projectnature-service').hide();
            $('.c-main-OwnRented-service').hide();
        }
    });
    $('#service_id_forms').change();

    $("#paving_service").change(function () {
        var value = $(this).val();
        if( value == 1 ){
            $(".c-Paving1-service").show();
            $(".c-Paving2-service").hide();
            $(".c-Paving3-service").hide();
            $(".c-Paving4-service").hide();
        } else if( value == 2 ){
            $(".c-Paving1-service").hide();
            $(".c-Paving2-service").show();
            $(".c-Paving3-service").hide();
            $(".c-Paving4-service").hide();
        } else if( value == 3 ){
            $(".c-Paving1-service").hide();
            $(".c-Paving2-service").hide();
            $(".c-Paving3-service").show();
            $(".c-Paving4-service").hide();
        } else if( value == 4 ){
            $(".c-Paving1-service").hide();
            $(".c-Paving2-service").hide();
            $(".c-Paving3-service").hide();
            $(".c-Paving4-service").show();
        } else {
            $(".c-Paving1-service").hide();
            $(".c-Paving2-service").hide();
            $(".c-Paving3-service").hide();
            $(".c-Paving4-service").hide();
        }
    });

    $("#painting_service").change(function () {
        var value = $(this).val();
        if( value == 1 ){
            $(".c-Painting1-service").show();
            $(".c-Painting2-service").hide();
            $(".c-Painting3-service").hide();
            $(".c-Painting4-service").hide();
            $(".c-Painting5-service").hide();

            $(".c-Painting1-service.c-Painting2-service.c-Painting3-service.c-Painting4-service").show();
            $(".c-Painting1-service.c-Painting4-service").show();
        } else if( value == 2 ){
            $(".c-Painting1-service").hide();
            $(".c-Painting2-service").show();
            $(".c-Painting3-service").hide();
            $(".c-Painting4-service").hide();
            $(".c-Painting5-service").hide();

            $(".c-Painting1-service.c-Painting2-service.c-Painting3-service.c-Painting4-service").show();
        } else if( value == 3 ){
            $(".c-Painting1-service").hide();
            $(".c-Painting2-service").hide();
            $(".c-Painting3-service").show();
            $(".c-Painting4-service").hide();
            $(".c-Painting5-service").hide();

            $(".c-Painting1-service.c-Painting2-service.c-Painting3-service.c-Painting4-service").show();
        } else if( value == 4 ){
            $(".c-Painting1-service").hide();
            $(".c-Painting2-service").hide();
            $(".c-Painting3-service").hide();
            $(".c-Painting4-service").show();
            $(".c-Painting5-service").hide();
            $(".c-Painting1-service.c-Painting2-service.c-Painting3-service.c-Painting4-service").show();
            $(".c-Painting1-service.c-Painting4-service").show();
        } else if( value == 5 ){
            $(".c-Painting1-service").hide();
            $(".c-Painting2-service").hide();
            $(".c-Painting3-service").hide();
            $(".c-Painting4-service").hide();
            $(".c-Painting5-service").show();
        } else {
            $(".c-Painting1-service").hide();
            $(".c-Painting2-service").hide();
            $(".c-Painting3-service").hide();
            $(".c-Painting4-service").hide();
            $(".c-Painting5-service").hide();
        }
    });
});