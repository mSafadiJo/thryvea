$(document).ready(function () {

    // ------------------------------------------------------- //
    // Custom Scrollbar
    // ------------------------------------------------------ //

    // if ($(window).outerWidth() > 992) {
    //     $("nav.side-navbar").mCustomScrollbar({
    //         scrollInertia: 200
    //     });
    // }

    // Main Template Color
    var brandPrimary = '#33b35a';

    // ------------------------------------------------------- //
    // Side Navbar Functionality
    // ------------------------------------------------------ //
    $('#toggle-btn').on('click', function (e) {
        e.preventDefault();

        if ($(window).outerWidth() > 1194) {
            $('nav.side-navbar').toggleClass('shrink');
            //$('.page').toggleClass('active');
            $('.topbar2').toggleClass('topbar3');
            $('.content-page').toggleClass('content-page2');

        } else {
            $('nav.side-navbar').toggleClass('show-sm');
            //$('.page').toggleClass('active-sm');
        }
    });

    // ------------------------------------------------------- //
    // Tooltips init
    // ------------------------------------------------------ //

    $('[data-toggle="tooltip"]').tooltip()

    // ------------------------------------------------------- //
    // Universal Form Validation
    // ------------------------------------------------------ //

    $('.form-validate').each(function() {
        $(this).validate({
            errorElement: "div",
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            ignore: ':hidden:not(.summernote),.note-editable.card-block',
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                //console.log(element);
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.siblings("label"));
                }
                else {
                    error.insertAfter(element);
                }
            }
        });
    });
    // ------------------------------------------------------- //
    // Material Inputs
    // ------------------------------------------------------ //

    var materialInputs = $('input.input-material');

    // activate labels for prefilled values
    materialInputs.filter(function () {
        return $(this).val() !== "";
    }).siblings('.label-material').addClass('active');

    // move label on focus
    materialInputs.on('focus', function () {
        $(this).siblings('.label-material').addClass('active');
    });

    // remove/keep label on blur
    materialInputs.on('blur', function () {
        $(this).siblings('.label-material').removeClass('active');

        if ($(this).val() !== '') {
            $(this).siblings('.label-material').addClass('active');
        } else {
            $(this).siblings('.label-material').removeClass('active');
        }
    });

    // ------------------------------------------------------- //
    // Jquery Progress Circle
    // ------------------------------------------------------ //
    // var progress_circle = $("#progress-circle").gmpc({
    //     color: brandPrimary,
    //     line_width: 5,
    //     percent: 80
    // });
    // progress_circle.gmpc('animate', 80, 3000);

    // ------------------------------------------------------- //
    // External links to new window
    // ------------------------------------------------------ //

    $('.external').on('click', function (e) {

        e.preventDefault();
        window.open($(this).attr("href"));
    });

    // ------------------------------------------------------ //
    // For demo purposes, can be deleted
    // ------------------------------------------------------ //

    var stylesheet = $('link#theme-stylesheet');
    $("<link id='new-stylesheet' rel='stylesheet'>").insertAfter(stylesheet);
    var alternateColour = $('link#new-stylesheet');

    // if ($.cookie("theme_csspath")) {
    //     alternateColour.attr("href", $.cookie("theme_csspath"));
    // }

    $("#colour").change(function () {

        if ($(this).val() !== '') {

            var theme_csspath = 'css/style.' + $(this).val() + '.css';

            alternateColour.attr("href", theme_csspath);

            $.cookie("theme_csspath", theme_csspath, {
                expires: 365,
                path: document.URL.substr(0, document.URL.lastIndexOf('/'))
            });

        }

        return false;
    });

});


$(document).ready( function() {
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
    });

    $('.btn-file :file').on('fileselect', function(event, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function(){
        readURL(this);
    });
});

    $(document).ready(function() {
        $(".fancybox").fancybox();
    });

$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});


$("#service_type").change(function(){
   var value = $(this).val();
   if(value==2)
   {
       $(".ServiceName").css('display','none');
   }else{
       $(".ServiceName").css('display','block');
   }
});



///***sewitch input on add pixels on domain ***///
$(document).ready(function(){
    $(".switch input").on("change", function(e) {
        const isOn = e.currentTarget.checked;

        if (isOn) {
            alert("yes");
            $("#GPixels").prop('disabled', true);
        } else {
            alert("no");
            $("#GPixels").prop('disabled', false);
        }
    });
});
