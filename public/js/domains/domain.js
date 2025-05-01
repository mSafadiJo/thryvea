$(document).ready(function(){
    let _session_recodeing_1 = false
    let _session_recording_TS_1 = false
    let _session_recording_TS = false
    let second_service_checkbox_val = false
    function change_session_recodrding() {
        let val = $("#session_recording").prop('checked');
        if (val){
            $('.session_recoding_condition').removeClass('in-active')
        } else {
            $('.session_recoding_condition').addClass('in-active')
        }
    }
    function change_session_recording_value(){
        let session_re_value = ($("input[type='radio'][name='all']:checked").val())
        if (session_re_value == 'according traffic source') {
            if (!_session_recording_TS) {
                _session_recording_TS = true
                $('.session_recoding_TS').removeClass('in-active')
            } else {
                _session_recording_TS = false
                $('.session_recoding_TS').addClass('in-active')
            }
        }else{
            _session_recording_TS = false
            $('.session_recoding_TS').addClass('in-active')
        }
    }
    function second_service_checkbox(){
        let val = $("#second_service_checkbox").prop('checked');
        if (val){
            $(".second_service_select_1").removeClass('in-active')
        }else{
            $(".second_service_select_1").addClass('in-active')
        }
    }
    let ServiceTypeId = $("#serviceType").val()
    $(".service_idSingle #service_id").change(function (){
        let _services = $(this).val()
        let ServiceTypeId =  ($("#serviceType").val())
        if(ServiceTypeId != '' && ServiceTypeId == 1) {
            $.ajax({
                url: ListImageThemes,
                method: "POST",
                data: {
                    "ServiceTypeId": 1,
                    "services": _services,
                    "_token": token
                },
                success: function (the_result) {
                    var lengthArray = the_result.length;
                    let theme_selected = $('.selected-theme').val()
                    $('.themeImageSection .row').html('');
                    if (lengthArray >= 1) {
                        $('.themeImageSection').show();
                        $(".SelectThemeDomain").show();

                        for (i = 0; i < the_result.length; i++) {
                            if (the_result[i]['id'] == theme_selected) {
                                $('.themeImageSection .row').prepend('<div class="col-sm-12 col-md-3 col-lg-1 mr-4"><label><input checked type="radio" name="theme_id" value=' + the_result[i]['id'] + '><img id="theImg" src="' + the_result[i]["theme_img"] + '"/></label></div>')
                            } else {
                                $('.themeImageSection .row').prepend('<div class="col-sm-12 col-md-3 col-lg-1 mr-4"><label><input type="radio" name="theme_id" value=' + the_result[i]['id'] + '><img id="theImg" src="' + the_result[i]["theme_img"] + '"/></label></div>')
                            }
                        }
                    } else {
                        $(".themeImageSection").show();
                        $('.themeImageSection .row').html('<h4>you don\'t have any themes</h4>');
                    }
                }
            });
        }
    })

    $(".session_recording").on('change', function () {
        change_session_recodrding()
    })
    $('.session_recording_chech').on('change', function() {
        change_session_recording_value()
    })
    $('#second_service_checkbox').on('change', function() {
        second_service_checkbox()
    })
    change_session_recodrding()
    change_session_recording_value()
    second_service_checkbox()
})
