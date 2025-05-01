$(document).ready(function () {
    $('#offday-Sun').change(function () {
        if ($('#offday-Sun').is(':checked')) {
            $('#starttime-Sun').prop('required',false);
            $('#starttime-Sun').prop('disabled',true);

            $('#endtime-Sun').prop('required',false);
            $('#endtime-Sun').prop('disabled',true);
        } else {
            $('#starttime-Sun').prop('required',true);
            $('#starttime-Sun').prop('disabled',false);

            $('#endtime-Sun').prop('required',true);
            $('#endtime-Sun').prop('disabled',false);
        }
    });

    $('#offday-Mon').change(function () {
        if ($('#offday-Mon').is(':checked')) {
            $('#starttime-Mon').prop('required',false);
            $('#starttime-Mon').prop('disabled',true);

            $('#endtime-Mon').prop('required',false);
            $('#endtime-Mon').prop('disabled',true);
        } else {
            $('#starttime-Mon').prop('required',true);
            $('#starttime-Mon').prop('disabled',false);

            $('#endtime-Mon').prop('required',true);
            $('#endtime-Mon').prop('disabled',false);
        }
    });

    $('#offday-Tus').change(function () {
        if ($('#offday-Tus').is(':checked')) {
            $('#starttime-Tus').prop('required',false);
            $('#starttime-Tus').prop('disabled',true);

            $('#endtime-Tus').prop('required',false);
            $('#endtime-Tus').prop('disabled',true);
        } else {
            $('#starttime-Tus').prop('required',true);
            $('#starttime-Tus').prop('disabled',false);

            $('#endtime-Tus').prop('required',true);
            $('#endtime-Tus').prop('disabled',false);
        }
    });

    $('#offday-Wen').change(function () {
        if ($('#offday-Wen').is(':checked')) {
            $('#starttime-Wen').prop('required',false);
            $('#starttime-Wen').prop('disabled',true);

            $('#endtime-Wen').prop('required',false);
            $('#endtime-Wen').prop('disabled',true);
        } else {
            $('#starttime-Wen').prop('required',true);
            $('#starttime-Wen').prop('disabled',false);

            $('#endtime-Wen').prop('required',true);
            $('#endtime-Wen').prop('disabled',false);
        }
    });

    $('#offday-Thr').change(function () {
        if ($('#offday-Thr').is(':checked')) {
            $('#starttime-Thr').prop('required',false);
            $('#starttime-Thr').prop('disabled',true);

            $('#endtime-Thr').prop('required',false);
            $('#endtime-Thr').prop('disabled',true);
        } else {
            $('#starttime-Thr').prop('required',true);
            $('#starttime-Thr').prop('disabled',false);

            $('#endtime-Thr').prop('required',true);
            $('#endtime-Thr').prop('disabled',false);
        }
    });

    $('#offday-fri').change(function () {
        if ($('#offday-fri').is(':checked')) {
            $('#starttime-fri').prop('required',false);
            $('#starttime-fri').prop('disabled',true);

            $('#endtime-fri').prop('required',false);
            $('#endtime-fri').prop('disabled',true);
        } else {
            $('#starttime-fri').prop('required',true);
            $('#starttime-fri').prop('disabled',false);

            $('#endtime-fri').prop('required',true);
            $('#endtime-fri').prop('disabled',false);
        }
    });

    $('#offday-sat').change(function () {
        if ($('#offday-sat').is(':checked')) {
            $('#starttime-sat').prop('required',false);
            $('#starttime-sat').prop('disabled',true);

            $('#endtime-sat').prop('required',false);
            $('#endtime-sat').prop('disabled',true);
        } else {
            $('#starttime-sat').prop('required',true);
            $('#starttime-sat').prop('disabled',false);

            $('#endtime-sat').prop('required',true);
            $('#endtime-sat').prop('disabled',false);
        }
    });

    $('#24hourin7days').change(function () {
        if ($('#24hourin7days').is(':checked')) {
            $('#starttime-Sun').prop('required',false);
            $('#starttime-Sun').prop('disabled',true);

            $('#endtime-Sun').prop('required',false);
            $('#endtime-Sun').prop('disabled',true);

            $('#offday-Sun').prop('disabled',true);

            $('#starttime-Mon').prop('required',false);
            $('#starttime-Mon').prop('disabled',true);

            $('#endtime-Mon').prop('required',false);
            $('#endtime-Mon').prop('disabled',true);

            $('#offday-Mon').prop('disabled',true);

            $('#starttime-Tus').prop('required',false);
            $('#starttime-Tus').prop('disabled',true);

            $('#endtime-Tus').prop('required',false);
            $('#endtime-Tus').prop('disabled',true);

            $('#offday-Tus').prop('disabled',true);

            $('#starttime-Wen').prop('required',false);
            $('#starttime-Wen').prop('disabled',true);

            $('#endtime-Wen').prop('required',false);
            $('#endtime-Wen').prop('disabled',true);

            $('#offday-Wen').prop('disabled',true);

            $('#starttime-Thr').prop('required',false);
            $('#starttime-Thr').prop('disabled',true);

            $('#endtime-Thr').prop('required',false);
            $('#endtime-Thr').prop('disabled',true);

            $('#offday-Thr').prop('disabled',true);

            $('#starttime-fri').prop('required',false);
            $('#starttime-fri').prop('disabled',true);

            $('#endtime-fri').prop('required',false);
            $('#endtime-fri').prop('disabled',true);

            $('#offday-fri').prop('disabled',true);

            $('#starttime-sat').prop('required',false);
            $('#starttime-sat').prop('disabled',true);

            $('#endtime-sat').prop('required',false);
            $('#endtime-sat').prop('disabled',true);

            $('#offday-sat').prop('disabled',true);

            $('#timezone').prop('required',false);
            $('#timezone').prop('disabled',true);
        } else {
            $('#starttime-Sun').prop('required',true);
            $('#starttime-Sun').prop('disabled',false);

            $('#endtime-Sun').prop('required',true);
            $('#endtime-Sun').prop('disabled',false);

            $('#offday-Sun').prop('disabled',false);
            $('#offday-Sun').change();

            $('#starttime-Mon').prop('required',true);
            $('#starttime-Mon').prop('disabled',false);

            $('#endtime-Mon').prop('required',true);
            $('#endtime-Mon').prop('disabled',false);

            $('#offday-Mon').prop('disabled',false);
            $('#offday-Mon').change();

            $('#starttime-Tus').prop('required',true);
            $('#starttime-Tus').prop('disabled',false);

            $('#endtime-Tus').prop('required',true);
            $('#endtime-Tus').prop('disabled',false);

            $('#offday-Tus').prop('disabled',false);
            $('#offday-Tus').change();

            $('#starttime-Wen').prop('required',true);
            $('#starttime-Wen').prop('disabled',false);

            $('#endtime-Wen').prop('required',true);
            $('#endtime-Wen').prop('disabled',false);

            $('#offday-Wen').prop('disabled',false);
            $('#offday-Wen').change();

            $('#starttime-Thr').prop('required',true);
            $('#starttime-Thr').prop('disabled',false);

            $('#endtime-Thr').prop('required',true);
            $('#endtime-Thr').prop('disabled',false);

            $('#offday-Thr').prop('disabled',false);
            $('#offday-Thr').change();

            $('#starttime-fri').prop('required',true);
            $('#starttime-fri').prop('disabled',false);

            $('#endtime-fri').prop('required',true);
            $('#endtime-fri').prop('disabled',false);

            $('#offday-fri').prop('disabled',false);
            $('#offday-fri').change();

            $('#starttime-sat').prop('required',true);
            $('#starttime-sat').prop('disabled',false);

            $('#endtime-sat').prop('required',true);
            $('#endtime-sat').prop('disabled',false);

            $('#offday-sat').prop('disabled',false);
            $('#offday-sat').change();

            $('#timezone').prop('required',true);
            $('#timezone').prop('disabled',false);
        }
    });
    $('#24hourin7days').change();

    $('#fordisabledData').change(function () {
        if ($('#fordisabledData').is(':checked')) {
            $('#starttime-Sun').prop('disabled', true);
            $('#endtime-Sun').prop('disabled', true);
            $('#offday-Sun').prop('disabled', true);

            $('#starttime-Mon').prop('disabled', true);
            $('#endtime-Mon').prop('disabled', true);
            $('#offday-Mon').prop('disabled', true);

            $('#starttime-Tus').prop('disabled', true);
            $('#endtime-Tus').prop('disabled', true);
            $('#offday-Tus').prop('disabled', true);

            $('#starttime-Wen').prop('disabled', true);
            $('#endtime-Wen').prop('disabled', true);
            $('#offday-Wen').prop('disabled', true);

            $('#starttime-Thr').prop('disabled', true);
            $('#endtime-Thr').prop('disabled', true);
            $('#offday-Thr').prop('disabled', true);

            $('#starttime-fri').prop('disabled', true);
            $('#endtime-fri').prop('disabled', true);
            $('#offday-fri').prop('disabled', true);

            $('#starttime-sat').prop('disabled', true);
            $('#endtime-sat').prop('disabled', true);
            $('#offday-sat').prop('disabled', true);

            $('#timezone').prop('disabled', true);
        }
    });
    $('#fordisabledData').change();
});