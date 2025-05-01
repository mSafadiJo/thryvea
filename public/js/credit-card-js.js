// to separate the visa number every 4 digits
// document.getElementById('visanumber').addEventListener('input', function (e) {
//     e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{4})/g, '$1 ').trim();
// });

var visanumbersattus = 0;
$(document).ready(function() {
    $('#visanumber').on('keyup', function() {
        var foo = $(this).val().split(" ").join("");
        if (foo.length > 0) {
            foo = foo.match(new RegExp('.{1,4}', 'g')).join(" ");
        }
        $(this).val(foo);
    });

    $('#visanumber').keyup(function(){
        $('#visanumber').validateCreditCard(function (result) {
            if (result.card_type != null && result.valid != false && result.length_valid != false && result.luhn_valid != false) {
                // $("#visanumber").css("border-color", "green");
                $('#visatype').val(result.card_type.name);
                $('#visatypeLabel').html(result.card_type.name);
                $("#visanumber").css("border-color", 'green');
                visanumbersattus = 1;
            } else {
                $('#visatypeLabel').html('');
                $("#visanumber").css("border-color", '#dadada');
                visanumbersattus = 0;
            }
        });
    });
});
function CheckFunction(){
    if (visanumbersattus == 0) {
        $('#pErrorsShown').html('invalid visa card number');
    } else {
        $("#paymentSubmitForm").click();
    }
}//to separate the visa number every 4 digits