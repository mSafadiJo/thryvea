$(document).ready(function(){
    $('#service_type').change(function (){
        let service_id = $('#service_type').val()
        if (service_id == 1) {
            $('.ServiceName').css('visibility', 'visible')
        }else if (service_id == 2) {
            $('.ServiceName').css('visibility', 'hidden')
        }else if(service_id == 3){
            $('.ServiceName').css('visibility', 'hidden')
        }
    })
})

