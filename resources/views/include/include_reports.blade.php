<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.min.css" />
<link href="{{ URL::asset('css/formsStyle.css') }}" rel="stylesheet" type="text/css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.15.0/lodash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.min.js"></script>

<script>
    //URL Data
    var Report_GetAdmins = "{{ route('Report.GetAdmins.data') }}";
    var Report_GetServices = "{{ route('Report.GetServices.data') }}";
    var Report_GetStates = "{{ route('Report.GetStates.data') }}";
    var Report_GetCounties = "{{ route('Report.GetCounties.data') }}";
    var Report_GetCities = "{{ route('Report.GetCities.data') }}";
    var Report_GetZipcodes = "{{ route('Report.GetZipcodes.data') }}";
    var Report_getBuyers = "{{ route('Report.getBuyers.data') }}";
    var Report_getEnvironments = "{{ route('Report.getEnvironments.data') }}";
    var Report_getTraffic_source = "{{ route('Report.getTraffic_source.data') }}";
    var Report_getSellers = "{{ route('Report.getSellers.data') }}";

    //For Traffic_source Filter
    if( $("#sellers-reports").length ) {
        // use this if you are using id to check
        var sellers = [];
        var sellerslength = 0;
        $.ajax({
            url: Report_getSellers,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                sellers = the_result;
                sellerslength = sellers.length;

                (function () {
                    // initialize select2 dropdown
                    $('#sellers-reports').select2({
                        data: sellers_reports(),
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
                function sellers_reports() {
                    return _.map(_.range(0, sellerslength), function (i) {
                        return {
                            id: sellers[i]['id'],
                            text: sellers[i]['user_business_name'],
                        };
                    });
                }
            }
        });
    }
    //==============================================================================================================

    //For Traffic_source Filter
    if( $("#trafficSource-reports").length ) {
        // use this if you are using id to check
        var traffic_source = [];
        var traffic_sourcelength = 0;
        $.ajax({
            url: Report_getTraffic_source,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                traffic_source = the_result;
                traffic_sourcelength = traffic_source.length;

                (function () {
                    // initialize select2 dropdown
                    $('#trafficSource-reports').select2({
                        data: trafficSource_reports(),
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
                function trafficSource_reports() {
                    return _.map(_.range(0, traffic_sourcelength), function (i) {
                        return {
                            id: traffic_source[i]['name'],
                            text: traffic_source[i]['name'],
                        };
                    });
                }
            }
        });
    }
    //==============================================================================================================

    //For Buyers Filter
    if( $("#buyers-reports").length ) {
        // use this if you are using id to check
        var buyers = [];
        var buyerslength = 0;
        $.ajax({
            url: Report_getBuyers,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                buyers = the_result;
                buyerslength = buyers.length;

                (function () {
                    // initialize select2 dropdown
                    $('#buyers-reports').select2({
                        data: buyers_reports(),
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
                function buyers_reports() {
                    return _.map(_.range(0, buyerslength), function (i) {
                        return {
                            id: buyers[i]['id'],
                            text: buyers[i]['user_business_name'],
                        };
                    });
                }
            }
        });
    }
    //==============================================================================================================

    //For environments Filter
    if( $("#environments-reports").length ) {
        // use this if you are using id to check
        var environments = [];
        var environmentslength = 0;
        $.ajax({
            url: Report_getEnvironments,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                environments = the_result;
                environmentslength = environments.length;

                (function () {
                    // initialize select2 dropdown
                    $('#environments-reports').select2({
                        data: environments_reports(),
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
                function environments_reports() {
                    return _.map(_.range(0, environmentslength), function (i) {
                        return {
                            id: environments[i]['id'],
                            text: environments[i]['name'],
                        };
                    });
                }
            }
        });
    }
    //==============================================================================================================

    //For Services Filter
    if( $("#services-reports").length ) {
        // use this if you are using id to check
        var services = [];
        var serviceslength = 0;
        $.ajax({
            url: Report_GetServices,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                services = the_result;
                serviceslength = services.length;

                (function () {
                    // initialize select2 dropdown
                    $('#services-reports').select2({
                        data: services_reports(),
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
                function services_reports() {
                    return _.map(_.range(0, serviceslength), function (i) {
                        return {
                            id: services[i]['service_campaign_id'],
                            text: services[i]['service_campaign_name'],
                        };
                    });
                }
            }
        });
    }
    //==============================================================================================================
    //For Admin Filter
    if( $("#admins-reports").length ) {
        var admins = [];
        var adminslength = 0;
        $.ajax({
            url: Report_GetAdmins,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                admins = the_result;
                adminslength = admins.length;

                (function () {
                    // initialize select2 dropdown
                    $('#admins-reports').select2({
                        data: admins_reports(),
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
                function admins_reports() {
                    return _.map(_.range(0, adminslength), function (i) {
                        return {
                            id: admins[i]['id'],
                            text: admins[i]['username'],
                        };
                    });
                }
            }
        });
    }
    //==============================================================================================================
    //For States Filter
    if( $("#states-reports").length ) {
        var states = [];
        var statelength = 0;
        $.ajax({
            url: Report_GetStates,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                states = the_result;
                stateslength = states.length;

                (function () {
                    // initialize select2 dropdown
                    $('#states-reports').select2({
                        data: states_reports(),
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
                function states_reports() {
                    return _.map(_.range(0, stateslength), function (i) {
                        return {
                            id: states[i]['state_id'],
                            text: states[i]['state_code'],
                        };
                    });
                }
            }
        });
    }
    //==============================================================================================================
    //For Counties Filter
    if( $("#counties-reports").length ) {
        var counties = [];
        var countieslength = 0;
        $.ajax({
            url: Report_GetCounties,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                counties = the_result;
                countieslength = counties.length;

                (function () {
                    // initialize select2 dropdown
                    $('#counties-reports').select2({
                        data: counties_reports(),
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
                function counties_reports() {
                    return _.map(_.range(0, countieslength), function (i) {
                        return {
                            id: counties[i]['county_id'],
                            text: counties[i]['county_name'],
                        };
                    });
                }
            }
        });
    }

    //==============================================================================================================
    //For Cities Filter
    if( $("#cities-reports").length ) {
        var cities = [];
        var citieslength = 0;
        $.ajax({
            url: Report_GetCities,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                cities = the_result;
                citieslength = cities.length;

                (function () {
                    // initialize select2 dropdown
                    $('#cities-reports').select2({
                        data: cities_reports(),
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
                function cities_reports() {
                    return _.map(_.range(0, citieslength), function (i) {
                        return {
                            id: cities[i]['city_id'],
                            text: cities[i]['city_name'],
                        };
                    });
                }
            }
        });
    }

    //==============================================================================================================

    //For Zipcodes Filter
    if( $("#zipcodes-reports").length ) {
        var zipcodes = [];
        var zipcodeslength = 0;
        $.ajax({
            url: Report_GetZipcodes,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                zipcodes = the_result;
                zipcodeslength = zipcodes.length;

                (function () {
                    // initialize select2 dropdown
                    $('#zipcodes-reports').select2({
                        data: zipcodes_reports(),
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
                function zipcodes_reports() {
                    return _.map(_.range(0, zipcodeslength), function (i) {
                        return {
                            id: zipcodes[i]['zip_code_list_id'],
                            text: zipcodes[i]['zip_code_list'],
                        };
                    });
                }
            },
            complete: function (hxr, status) {
                $('.unloader').show();
                $('.loader').hide();
            }
        });
    }
    //==============================================================================================================

    if( $("#zipcodes_reports_Filter").length ) {

        var zipcodes = [];
        var zipcodeslength = 0;
        $.ajax({
            url: Report_GetZipcodes,
            method: "POST",
            data: {
                _token: token
            },
            success: function (the_result) {
                zipcodes = the_result;
                zipcodeslength = zipcodes.length;

                (function () {
                    // initialize select2 dropdown
                    $('#zipcodes_reports_Filter').select2({
                        data: zipcodes_reports_Filter(),
                        placeholder: 'search',
                        multiple: false,
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
                function zipcodes_reports_Filter() {
                    return _.map(_.range(0, zipcodeslength), function (i) {
                        return {
                            id: zipcodes[i]['zip_code_list_id'],
                            text: zipcodes[i]['zip_code_list'],
                        };
                    });
                }
            },
            complete: function (hxr, status) {
                $('.unloader').show();
                $('.loader').hide();
            }
        });
    }

</script>
