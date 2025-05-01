$(document).ready(function (){
    $('.linkShow').click(function (){
        var type = $('#password').attr('type');
        if(type === 'password'){
            $('#password').attr('type','text');
        }else{
            $('#password').attr('type','password');
        }
    })
    $('.linkShowP2').click(function (){
        var type = $('#password1').attr('type');
        if(type === 'password'){
            $('#password1').attr('type','text');
        }else{
            $('#password1').attr('type','password');
        }
    })
});
