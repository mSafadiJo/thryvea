$(document).ready(function() {
    $('#map').usmap({
        //  mouseout: myCallback,
        //stateStyles: {fill: '#237ff1'},
        stateHoverStyles: false,
        mouseover: function(event, data) {
            myCallback();
            $('#clicked-state span').html(data.name);
        }
    });
});

function myCallback(event, data)
{
    var array_filiter_state_1 = $.parseJSON($("#state_map_1").val());
    var array_filiter_state_2 = $.parseJSON($("#state_map_2").val());
    var array_filiter_state_3 = $.parseJSON($("#state_map_3").val());
    var array_filiter_state_4 = $.parseJSON($("#state_map_4").val());
    var array_filiter_state_5 = $.parseJSON($("#state_map_5").val());
    var array_filiter_state_6 = $.parseJSON($("#state_map_6").val());
    var array_filiter_state_7 = $.parseJSON($("#state_map_7").val());
    if (array_filiter_state_1 != "")
    {
        for(var i=0 ; i< array_filiter_state_1.length ; i++)
        {
            $('#'+array_filiter_state_1[i]).css('fill','#ff0f0f');
        }
    }

    if (array_filiter_state_2 != "")
    {
        for(var i=0 ; i< array_filiter_state_2.length ; i++)
        {
            $('#'+array_filiter_state_2[i]).css('fill','#fe4b0f');
        }
    }

    if (array_filiter_state_3 != "")
    {
        for(var i=0 ; i< array_filiter_state_3.length ; i++)
        {
            $('#'+array_filiter_state_3[i]).css('fill','#ff8000');
        }
    }

    if (array_filiter_state_4 != "")
    {
        for(var i=0 ; i< array_filiter_state_4.length ; i++)
        {
            $('#'+array_filiter_state_4[i]).css('fill','#fec30f');
        }
    }

    if (array_filiter_state_5 != "")
    {
        for(var i=0 ; i< array_filiter_state_5.length ; i++)
        {
            $('#'+array_filiter_state_5[i]).css('fill','#ffff46');
        }
    }

    if (array_filiter_state_6 != "")
    {
        for(var i=0 ; i< array_filiter_state_6.length ; i++)
        {
            $('#'+array_filiter_state_6[i]).css('fill','#237ff1');
        }
    }
    if (array_filiter_state_7 != "")
    {
        for(var i=0 ; i< array_filiter_state_7.length ; i++)
        {
            $('#'+array_filiter_state_7[i]).css('fill','#03a9f4');
        }
    }




}


$(document).ready(function() {
    $('#mapLead').usmap({
        //  mouseout: myCallback,
        //stateStyles: {fill: '#237ff1'},
        stateHoverStyles: false,
        mouseover: function(event, data) {
            myCallbackLead();
            $('#clicked-state span').html(data.name);
        }
    });
});


function myCallbackLead(event, data)
{
    var array_filiter_state_1 = $.parseJSON($("#state_map_1").val());
    var array_filiter_state_2 = $.parseJSON($("#state_map_2").val());
    var array_filiter_state_3 = $.parseJSON($("#state_map_3").val());
    var array_filiter_state_4 = $.parseJSON($("#state_map_4").val());
    var array_filiter_state_5 = $.parseJSON($("#state_map_5").val());
    var array_filiter_state_6 = $.parseJSON($("#state_map_6").val());
    if (array_filiter_state_1 != "")
    {
        for(var i=0 ; i< array_filiter_state_1.length ; i++)
        {
            $('#'+array_filiter_state_1[i]).css('fill','#ff0f0f');
        }
    }

    if (array_filiter_state_2 != "")
    {
        for(var i=0 ; i< array_filiter_state_2.length ; i++)
        {
            $('#'+array_filiter_state_2[i]).css('fill','#fe4b0f');
        }
    }

    if (array_filiter_state_3 != "")
    {
        for(var i=0 ; i< array_filiter_state_3.length ; i++)
        {
            $('#'+array_filiter_state_3[i]).css('fill','#ff8000');
        }
    }

    if (array_filiter_state_4 != "")
    {
        for(var i=0 ; i< array_filiter_state_4.length ; i++)
        {
            $('#'+array_filiter_state_4[i]).css('fill','#fec30f');
        }
    }

    if (array_filiter_state_5 != "")
    {
        for(var i=0 ; i< array_filiter_state_5.length ; i++)
        {
            $('#'+array_filiter_state_5[i]).css('fill','#ffff46');
        }
    }

    if (array_filiter_state_6 != "")
    {
        for(var i=0 ; i< array_filiter_state_6.length ; i++)
        {
            $('#'+array_filiter_state_6[i]).css('fill','#237ff1');
        }
    }
}
