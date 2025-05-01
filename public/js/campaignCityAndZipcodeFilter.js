function search_select_func(element, multiple, post_url, token, maximumSelectionLength, minimumInputLength){
    $(element).select2({
        placeholder: 'Search ..',
        minimumInputLength:minimumInputLength,
        multiple:multiple,
        maximumSelectionLength: maximumSelectionLength,
        ajax: {
            type: 'POST',
            url: post_url,
            delay:250,
            data: function (params) {
                return {
                    text: params.term,
                    _token: token
                }
            },
            processResults: function(data, params) {
                params.page = params.page || 1;

                return {
                    results: data.results,
                };
            }
        }
    });
}

$(window).load( function() {
    search_select_func('#City_Name', true, citysearch, token, 10000, 1);
    search_select_func('#Zip_Name', true, zipsearch, token, 10000, 1);
    search_select_func('#City_Name_expect', true, citysearch, token, 10000, 1);
    search_select_func('#Zip_Name_expect', true, zipsearch, token, 10000, 1);
    search_select_func('.countyCam', true, countysearch, token, 10000, 1);
    search_select_func('#county_expect', true, countysearch, token, 10000, 1);
});
