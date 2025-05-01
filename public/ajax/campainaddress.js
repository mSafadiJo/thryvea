$(document).ready(function () {
    $('#stateCamp').change(function(){
        var stateVal = $(this).val();

        var countyCamp = $('#countyCamp').val();
        var cityCamp = $('#cityCamp').val();
        var zipcodeCamp = $('#zipcodeCamp').val();

        var defaultcountyCamp = $('#defaultcountyCamp').val();
        var defaultcityCamp = $('#defaultcityCamp').val();
        // var defaultzipcodeCamp = $('#defaultzipcodeCamp').val();
        var defaultzipcodeCamp = "";
       //Counties
        $.ajax({
            url: getCountieswithFillter,
            method: "POST",
            data: {
                stateVal: stateVal,
                defaultcountyCamp: defaultcountyCamp,
                countyCamp: countyCamp,
                _token: token
            },
            success: function(the_result){
                 $('#countyCamp').html(the_result.select);
            }
        });
        //Cities
        $.ajax({
            url: getAllWhithFillter,
            method: "POST",
            data: {
                stateVal: stateVal,
                defaultcityCamp: defaultcityCamp,
                cityCamp: cityCamp,
                _token: token
            },
            success: function(the_result){
                $('#cityCamp').html(the_result.select);
            }
        });
        //zipcode
        $.ajax({
            url: getAllWhithFillterZipCode,
            method: "POST",
            data: {
                stateVal: stateVal,
                defaultzipcodeCamp: defaultzipcodeCamp,
                zipcodeCamp: zipcodeCamp,
                _token: token
            },
            success: function(the_result){
                $('#zipcodeCamp').html(the_result.select);
            }
        });
    });

    $('#stateCamp').change(function(){
        var stateexpectVal = $(this).val();

        var countyexpectCamp = $('#county_expectCamp').val();
        var cityexpectCamp = $('#city_expectCamp').val();
        var zipcodeexpectCamp = $('#zipcode_expectCamp').val();

        var defaultcounty_expectCamp = $('#defaultcounty_expectCamp').val();
        var defaultcity_expectCamp = $('#defaultcity_expectCamp').val();
        var defaultzipcode_expectCamp = $('#defaultzipcode_expectCamp').val();
        //Counties
        $.ajax({
            url: getCountieswithFillter,
            method: "POST",
            data: {
                stateVal: stateexpectVal,
                defaultcountyCamp: defaultcounty_expectCamp,
                countyCamp: countyexpectCamp,
                _token: token
            },
            success: function(the_result){
                $('#county_expectCamp').html(the_result.select);
            }
        });
        //Cities
        $.ajax({
            url: getAllWhithFillter,
            method: "POST",
            data: {
                stateVal: stateexpectVal,
                defaultcityCamp: defaultcity_expectCamp,
                cityCamp: cityexpectCamp,
                _token: token
            },
            success: function(the_result){
                $('#city_expectCamp').html(the_result.select);
            }
        });
        //zipcode
        $.ajax({
            url: getAllWhithFillterZipCode,
            method: "POST",
            data: {
                stateVal: stateexpectVal,
                defaultzipcodeCamp: defaultzipcode_expectCamp,
                zipcodeCamp: zipcodeexpectCamp,
                _token: token
            },
            success: function(the_result){
                $('#zipcode_expectCamp').html(the_result.select);
            }
        });
    });

    $('#stateCamp').change();
    // $('#state_expectCamp').change();
});
