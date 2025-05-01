var states = [];
var stateslength = 0;

var counties = [];
var countieslength = 0;

var cities = [];
var citieslength = 0;

var zipcodes = [];
var zipcodeslength = 0;
$(document).ready(function () {

    $.ajax({
        url: urlstatesAll,
        method: "POST",
        data: {
            _token: token
        },
        success: function(the_result){
            states = the_result;
            stateslength = states.length;
            (function () {
                // initialize select2 dropdown
                $('.statesListCamp').select2({
                    data: dropdownDataState(),
                    placeholder: 'search',
                    multiple: true,
                    // creating query with pagination functionality.
                    query: function (data) {
                        var pageSize,
                            dataset,
                            that = this;
                        pageSize = 20; // Number of the option loads at a time
                        results = [];
                        if (data.term && data.term !== '') {
                            // HEADS UP; for the _.filter function I use underscore (actually lo-dash) here
                            results = _.filter(that.data, function (e) {
                                return e.text.toUpperCase().indexOf(data.term.toUpperCase()) >= 0;
                            });
                        } else if (data.term === '') {
                            results = that.data;
                        }
                        data.callback({
                            results: results.slice((data.page - 1) * pageSize, data.page * pageSize),
                            more: results.length >= data.page * pageSize,
                        });
                    },
                });
            })();

            // For the testing purpose we are making a huge array of demo data (20 000 items)
            function dropdownDataState() {
                return _.map(_.range(1, stateslength), function (i) {
                    return {
                        id: states[i]['state_id'],
                        text: states[i]['state_name'],
                    };
                });

            }
        }
    });
    //========================================================================================================

    $.ajax({
        url: urlCountiesALL,
        method: "POST",
        data: {
            _token: token
        },
        success: function(the_result){
            counties = the_result;
            countieslength = counties.length;
            (function () {
                // initialize select2 dropdown
                $('.countiesListCamp').select2({
                    data: dropdownDataCounty(),
                    placeholder: 'search',
                    multiple: true,
                    // creating query with pagination functionality.
                    query: function (data) {
                        var pageSize,
                            dataset,
                            that = this;
                        pageSize = 20; // Number of the option loads at a time
                        results = [];
                        if (data.term && data.term !== '') {
                            // HEADS UP; for the _.filter function I use underscore (actually lo-dash) here
                            results = _.filter(that.data, function (e) {
                                return e.text.toUpperCase().indexOf(data.term.toUpperCase()) >= 0;
                            });
                        } else if (data.term === '') {
                            results = that.data;
                        }
                        data.callback({
                            results: results.slice((data.page - 1) * pageSize, data.page * pageSize),
                            more: results.length >= data.page * pageSize,
                        });
                    },
                });
            })();

            // For the testing purpose we are making a huge array of demo data (20 000 items)
            function dropdownDataCounty() {
                return _.map(_.range(1, countieslength), function (i) {
                    return {
                        id: counties[i]['county_id'],
                        text: counties[i]['county_name'],
                    };
                });

            }
        }
    });

    //========================================================================================================


    $.ajax({
        url: urlcitiesALL,
        method: "POST",
        data: {
            _token: token
        },
        success: function(the_result){
            cities = the_result;
            citieslength = cities.length;
            (function () {
                // initialize select2 dropdown
                $('.citiesListCamp').select2({
                    data: dropdownDataCiy(),
                    placeholder: 'search',
                    multiple: true,
                    // creating query with pagination functionality.
                    query: function (data) {
                        var pageSize,
                            dataset,
                            that = this;
                        pageSize = 20; // Number of the option loads at a time
                        results = [];
                        if (data.term && data.term !== '') {
                            // HEADS UP; for the _.filter function I use underscore (actually lo-dash) here
                            results = _.filter(that.data, function (e) {
                                return e.text.toUpperCase().indexOf(data.term.toUpperCase()) >= 0;
                            });
                        } else if (data.term === '') {
                            results = that.data;
                        }
                        data.callback({
                            results: results.slice((data.page - 1) * pageSize, data.page * pageSize),
                            more: results.length >= data.page * pageSize,
                        });
                    },
                });
            })();

            // For the testing purpose we are making a huge array of demo data (20 000 items)
            function dropdownDataCiy() {
                console.log(citieslength);
                return _.map(_.range(1, citieslength), function (i) {
                    return {
                        id: cities[i]['city_id'],
                        text: cities[i]['city_name'],
                    };
                });

            }
        }
    });


    //========================================================================================================


    $.ajax({
        url: urlzipCodesAll,
        method: "POST",
        data: {
            _token: token
        },
        success: function(the_result){
            zipcodes = the_result;
            zipcodeslength = zipcodes.length;
            (function () {
                // initialize select2 dropdown
                $('.zipcodeListCamp').select2({
                    data: dropdownDataZipCode(),
                    placeholder: 'search',
                    multiple: true,
                    // creating query with pagination functionality.
                    query: function (data) {
                        var pageSize,
                            dataset,
                            that = this;
                        pageSize = 20; // Number of the option loads at a time
                        results = [];
                        if (data.term && data.term !== '') {
                            // HEADS UP; for the _.filter function I use underscore (actually lo-dash) here
                            results = _.filter(that.data, function (e) {
                                return e.text.toUpperCase().indexOf(data.term.toUpperCase()) >= 0;
                            });
                        } else if (data.term === '') {
                            results = that.data;
                        }
                        data.callback({
                            results: results.slice((data.page - 1) * pageSize, data.page * pageSize),
                            more: results.length >= data.page * pageSize,
                        });
                    },
                });
            })();

            // For the testing purpose we are making a huge array of demo data (20 000 items)
            function dropdownDataZipCode() {
                return _.map(_.range(1, zipcodeslength), function (i) {
                    return {
                        id: zipcodes[i]['zip_code_list_id'],
                        text: zipcodes[i]['zip_code_list'],
                    };
                });

            }
        }
    });

});