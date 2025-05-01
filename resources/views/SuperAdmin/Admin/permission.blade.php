<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <h3>User Privileges</h3>
                    </div>
                </div>
            </div>
            <hr>
        @php
            $permission_users = array();
            if( !empty($user_data->permission_users) ){
                $permission_users = json_decode($user_data->permission_users, true);
            }
        @endphp
        <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Services</b>
                            <input type="checkbox" class="user_privileges_type" data-type="service" name="user_privileges[]" value="1-0"
                                   @if( in_array('1-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_service_div"
                     @if( in_array('1-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="1-1"
                                   @if( in_array('1-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_service_div"
                     @if( in_array('1-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="1-2"
                                   @if( in_array('1-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_service_div"
                     @if( in_array('1-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="1-4"
                                   @if( in_array('1-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Themes</b>
                            <input type="checkbox" class="user_privileges_type" data-type="themes" name="user_privileges[]" value="19-0"
                                   @if( in_array('19-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_themes_div"
                     @if( in_array('19-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="19-1"
                                   @if( in_array('19-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_themes_div"
                     @if( in_array('19-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="19-2"
                                   @if( in_array('19-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_themes_div"
                     @if( in_array('19-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="19-3"
                                   @if( in_array('19-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_themes_div"
                     @if( in_array('19-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="19-4"
                                   @if( in_array('19-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Domains</b>
                            <input type="checkbox" class="user_privileges_type" data-type="domains" name="user_privileges[]" value="20-0"
                                   @if( in_array('20-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_domains_div"
                     @if( in_array('20-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="20-1"
                                   @if( in_array('20-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_domains_div"
                     @if( in_array('20-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="20-2"
                                   @if( in_array('20-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_domains_div"
                     @if( in_array('20-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="20-3"
                                   @if( in_array('20-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_domains_div"
                     @if( in_array('20-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="20-4"
                                   @if( in_array('20-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Pixels</b>
                            <input type="checkbox" class="user_privileges_type" data-type="pixels" name="user_privileges[]" value="21-0"
                                   @if( in_array('21-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_pixels_div"
                     @if( in_array('21-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="21-4"
                                   @if( in_array('21-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Our Partners</b>
                            <input type="checkbox" class="user_privileges_type" data-type="OurPartners" name="user_privileges[]" value="24-0"
                                   @if( in_array('24-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_OurPartners_div"
                     @if( in_array('24-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="24-1"
                                   @if( in_array('24-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_OurPartners_div"
                     @if( in_array('24-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="24-2"
                                   @if( in_array('24-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_OurPartners_div"
                     @if( in_array('24-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="24-3"
                                   @if( in_array('24-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_OurPartners_div"
                     @if( in_array('24-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="24-4"
                                   @if( in_array('24-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Promotional Codes</b>
                            <input type="checkbox" class="user_privileges_type" data-type="PromotionalCodes" name="user_privileges[]" value="2-0"
                                   @if( in_array('2-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_PromotionalCodes_div"
                     @if( in_array('2-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="2-1"
                                   @if( in_array('2-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_PromotionalCodes_div"
                     @if( in_array('2-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="2-2"
                                   @if( in_array('2-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_PromotionalCodes_div"
                     @if( in_array('2-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="2-3"
                                   @if( in_array('2-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_PromotionalCodes_div"
                     @if( in_array('2-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="2-4"
                                   @if( in_array('2-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Reports</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Reports" name="user_privileges[]" value="3-0"
                                   @if( in_array('3-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Lead Report
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-5"
                                   @if( in_array('3-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Lead Volume
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-6"
                                   @if( in_array('3-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Seller Lead Volume
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-9"
                                   @if( in_array('3-9', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Performance Reports
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-7"
                                   @if( in_array('3-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Marketing Reports
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-8"
                                   @if( in_array('3-8', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">SDR Reports
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-10"
                                   @if( in_array('3-10', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Sales Reports
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-11"
                                   @if( in_array('3-11', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Account Manager Reports
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-12"
                                   @if( in_array('3-12', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Campaigns By Zipcode
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-13"
                                   @if( in_array('3-13', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Active ZipCodes
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-14"
                                   @if( in_array('3-14', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Lost Leads Report
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-15"
                                   @if( in_array('3-15', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">CRM Responses
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-16"
                                   @if( in_array('3-16', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Lead Map
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-17"
                                   @if( in_array('3-17', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Buyers Map
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-18"
                                   @if( in_array('3-18', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Sales Commission
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-19"
                                   @if( in_array('3-19', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Sales/Transfers Dashboard
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-20"
                                   @if( in_array('3-20', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Users Payments
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-21"
                                   @if( in_array('3-21', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Affiliate Report
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-22"
                                   @if( in_array('3-22', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Agents CallCenter Report
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-23"
                                   @if( in_array('3-23', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">CallCenter Target Dashboard
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-24"
                                   @if( in_array('3-24', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Call Center Leads Profit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-25"
                                   @if( in_array('3-25', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">PayPerClick Reports
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-26"
                                   @if( in_array('3-26', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Buyers Location Report
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-27"
                                   @if( in_array('3-27', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Marketing Cost
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-28"
                                   @if( in_array('3-28', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Reports_div"
                     @if( in_array('3-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="3-4"
                                   @if( in_array('3-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Admins</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Admins" name="user_privileges[]" value="4-0"
                                   @if( in_array('4-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Admins_div"
                     @if( in_array('4-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="4-1"
                                   @if( in_array('4-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Admins_div"
                     @if( in_array('4-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="4-2"
                                   @if( in_array('4-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Admins_div"
                     @if( in_array('4-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="4-3"
                                   @if( in_array('4-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Admins_div"
                     @if( in_array('4-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Unlimit Return
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="4-5"
                                   @if( in_array('4-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Prospects</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Prospects" name="user_privileges[]" value="14-0"
                                   @if( in_array('14-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Prospects_div"
                     @if( in_array('14-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="14-1"
                                   @if( in_array('14-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Prospects_div"
                     @if( in_array('14-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="14-2"
                                   @if( in_array('14-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Prospects_div"
                     @if( in_array('14-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="14-3"
                                   @if( in_array('14-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Prospects_div"
                     @if( in_array('14-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Transactions
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="14-4"
                                   @if( in_array('14-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Prospects_div"
                     @if( in_array('14-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add Transaction
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="14-5"
                                   @if( in_array('14-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Prospects_div"
                     @if( in_array('14-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Prospects_div"
                     @if( in_array('14-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Convert To Buyers
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="14-6"
                                   @if( in_array('14-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Prospects_div"
                     @if( in_array('14-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="14-7"
                                   @if( in_array('14-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Buyers</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Buyers" name="user_privileges[]" value="5-0"
                                   @if( in_array('5-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-1"
                                   @if( in_array('5-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-2"
                                   @if( in_array('5-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-3"
                                   @if( in_array('5-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Campaigns
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-5"
                                   @if( in_array('5-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Wallet
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-6"
                                   @if( in_array('5-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Payments
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-7"
                                   @if( in_array('5-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Transactions
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-8"
                                   @if( in_array('5-8', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>

                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Tickets
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-9"
                                   @if( in_array('5-9', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Claim
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-10"
                                   @if( in_array('5-10', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>

                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Payment Term
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-11"
                                   @if( in_array('5-11', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Return Lead Tickets
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-12"
                                   @if( in_array('5-12', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Return Lead Amount
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-13"
                                   @if( in_array('5-13', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Rev_Share
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-15"
                                   @if( in_array('5-15', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Buyers_div"
                     @if( in_array('5-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="5-4"
                                   @if( in_array('5-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Sellers</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Sellers" name="user_privileges[]" value="23-0"
                                   @if( in_array('23-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Sellers_div"
                     @if( in_array('23-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Campaigns
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="23-5"
                                   @if( in_array('23-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Sellers_div"
                     @if( in_array('23-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Payments
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="23-7"
                                   @if( in_array('23-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Sellers_div"
                     @if( in_array('23-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Transactions
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="23-8"
                                   @if( in_array('23-8', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Sellers_div"
                     @if( in_array('23-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Return Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="23-12"
                                   @if( in_array('23-12', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Account ownership</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Accountownership" name="user_privileges[]" value="6-0"
                                   @if( in_array('6-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Accountownership_div"
                     @if( in_array('6-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Claim
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="6-5"
                                   @if( in_array('6-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Accountownership_div"
                     @if( in_array('6-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Payment Term
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="6-6"
                                   @if( in_array('6-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Buyers Campaigns</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Campaigns" name="user_privileges[]" value="7-0"
                                   @if( in_array('7-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_div"
                     @if( in_array('7-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="7-1"
                                   @if( in_array('7-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_div"
                     @if( in_array('7-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="7-2"
                                   @if( in_array('7-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_div"
                     @if( in_array('7-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="7-3"
                                   @if( in_array('7-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_div"
                     @if( in_array('7-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="7-4"
                                   @if( in_array('7-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_div"
                     @if( in_array('7-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Approved
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="7-5"
                                   @if( in_array('7-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_div"
                     @if( in_array('7-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_div"
                     @if( in_array('7-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Send Test Lead
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="7-6"
                                   @if( in_array('7-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_div"
                     @if( in_array('7-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Details
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="7-7"
                                   @if( in_array('7-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Sellers Campaigns</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Campaigns_sellers" name="user_privileges[]" value="12-0"
                                   @if( in_array('12-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_sellers_div"
                     @if( in_array('12-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="12-1"
                                   @if( in_array('12-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_sellers_div"
                     @if( in_array('12-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="12-2"
                                   @if( in_array('12-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_sellers_div"
                     @if( in_array('12-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="12-3"
                                   @if( in_array('12-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_sellers_div"
                     @if( in_array('12-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="12-4"
                                   @if( in_array('12-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_sellers_div"
                     @if( in_array('12-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Approved
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="12-5"
                                   @if( in_array('12-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_sellers_div"
                     @if( in_array('12-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_Campaigns_sellers_div"
                     @if( in_array('12-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Details
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="12-7"
                                   @if( in_array('12-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Leads Management</b>
                            <input type="checkbox" class="user_privileges_type" data-type="LeadsManagement" name="user_privileges[]" value="8-0"
                                   @if( in_array('8-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of All Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-12"
                                   @if( in_array('8-12', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Sold Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-5"
                                   @if( in_array('8-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of UnSold Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-6"
                                   @if( in_array('8-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of PING Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-14"
                                   @if( in_array('8-14', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Affiliate Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-16"
                                   @if( in_array('8-16', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Return Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-7"
                                   @if( in_array('8-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Artchive Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-9"
                                   @if( in_array('8-9', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Leads Review
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-13"
                                   @if( in_array('8-13', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Leads (SMS/Email)
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-15"
                                   @if( in_array('8-15', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Leads Form
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-17"
                                   @if( in_array('8-17', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Leads Call Center
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-18"
                                   @if( in_array('8-18', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Return Leads Call Center
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-19"
                                   @if( in_array('8-19', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">List Of Call Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-20"
                                   @if( in_array('8-20', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Details
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-10"
                                   @if( in_array('8-10', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-2"
                                   @if( in_array('8-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-3"
                                   @if( in_array('8-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Push Lead
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-11"
                                   @if( in_array('8-11', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-4"
                                   @if( in_array('8-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Info Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-23"
                                   @if( in_array('8-23', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">CRM Responses
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-21"
                                   @if( in_array('8-21', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_LeadsManagement_div"
                     @if( in_array('8-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">QA status
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="8-22"
                                   @if( in_array('8-22', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>TS Marketing</b>
                            <input type="checkbox" class="user_privileges_type" data-type="ts_marketing" name="user_privileges[]" value="13-0"
                                   @if( in_array('13-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Platforms
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="13-5"
                                   @if( in_array('13-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Traffic Sources
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="13-6"
                                   @if( in_array('13-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Call Center Sources
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="13-7"
                                   @if( in_array('13-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Lead Cost By TS
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="13-8"
                                   @if( in_array('13-8', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="13-1"
                                   @if( in_array('13-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="13-2"
                                   @if( in_array('13-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="13-3"
                                   @if( in_array('13-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_ts_marketing_div"
                     @if( in_array('13-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="13-4"
                                   @if( in_array('13-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Block Leads Info</b>
                            <input type="checkbox" class="user_privileges_type" data-type="BlockLeadsInfo" name="user_privileges[]" value="15-0"
                                   @if( in_array('15-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Send SMS</b>
                            <input type="checkbox" class="user_privileges_type" data-type="SendSMS" name="user_privileges[]" value="16-0"
                                   @if( in_array('16-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_SendSMS_div"
                     @if( in_array('16-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">General SMS
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="16-1"
                                   @if( in_array('16-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_SendSMS_div"
                     @if( in_array('16-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Professional SMS
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="16-2"
                                   @if( in_array('16-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_SendSMS_div"
                     @if( in_array('16-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Generate Bitly URL
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="16-3"
                                   @if( in_array('16-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Contractors</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Contractors" name="user_privileges[]" value="17-0"
                                   @if( in_array('17-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Contractors_div"
                     @if( in_array('17-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="17-4"
                                   @if( in_array('17-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Task Management</b>
                            <input type="checkbox" class="user_privileges_type" data-type="TaskManagement" name="user_privileges[]" value="9-0"
                                   @if( in_array('9-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_TaskManagement_div"
                     @if( in_array('9-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="9-1"
                                   @if( in_array('9-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_TaskManagement_div"
                     @if( in_array('9-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="9-2"
                                   @if( in_array('9-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_TaskManagement_div"
                     @if( in_array('9-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="9-3"
                                   @if( in_array('9-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_TaskManagement_div"
                     @if( in_array('9-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="9-4"
                                   @if( in_array('9-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Tickets</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Tickets" name="user_privileges[]" value="22-0"
                                   @if( in_array('22-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Tickets_div"
                     @if( in_array('22-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Issue Tickets
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="22-1"
                                   @if( in_array('22-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Tickets_div"
                     @if( in_array('22-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Return Tickets
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="22-2"
                                   @if( in_array('22-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Tickets_div"
                     @if( in_array('22-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Export
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="22-4"
                                   @if( in_array('22-4', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Session Recording</b>
                            <input type="checkbox" class="user_privileges_type" data-type="SessionRecording" name="user_privileges[]" value="18-0"
                                   @if( in_array('18-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Access Log</b>
                            <input type="checkbox" class="user_privileges_type" data-type="AccessLog" name="user_privileges[]" value="10-0"
                                   @if( in_array('10-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Services
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-5"
                                   @if( in_array('10-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Themes
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-20"
                                   @if( in_array('10-20', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Domains
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-21"
                                   @if( in_array('10-21', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Promo Codes
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-6"
                                   @if( in_array('10-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Admin User
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-7"
                                   @if( in_array('10-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Prospect User
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-17"
                                   @if( in_array('10-17', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Buyers User
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-8"
                                   @if( in_array('10-8', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Buyer Campaigns
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-9"
                                   @if( in_array('10-9', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Seller Campaigns
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-14"
                                   @if( in_array('10-14', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Lead Management
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-10"
                                   @if( in_array('10-10', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Authentication
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-11"
                                   @if( in_array('10-11', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Ticket
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-12"
                                   @if( in_array('10-12', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">User Payments
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-13"
                                   @if( in_array('10-13', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Marketing Platforms
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-15"
                                   @if( in_array('10-15', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Marketing TS
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-16"
                                   @if( in_array('10-16', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Block Leads Info
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-18"
                                   @if( in_array('10-18', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Send SMS
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-19"
                                   @if( in_array('10-19', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Shop Leads
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-22"
                                   @if( in_array('10-22', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Call Center Sources
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-23"
                                   @if( in_array('10-23', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>

                <div class="col-sm-2 user_privileges_AccessLog_div"
                     @if( in_array('10-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Lead Cost By TS
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="10-24"
                                   @if( in_array('10-24', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Shop Leads Info</b>
                            <input type="checkbox" class="user_privileges_type" data-type="shopLeadInfo" name="user_privileges[]" value="11-0"
                                   @if( in_array('11-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Sources Percentage
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-5"
                                   @if( in_array('11-5', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Include/Exclude Sellers
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-6"
                                   @if( in_array('11-6', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Exclude Buyers
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-7"
                                   @if( in_array('11-7', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Exclude Sources
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-8"
                                   @if( in_array('11-8', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Exclude Url
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-9"
                                   @if( in_array('11-9', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group"></div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Exclude Seller Sources
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-10"
                                   @if( in_array('11-10', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Add
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-1"
                                   @if( in_array('11-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Edit
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-2"
                                   @if( in_array('11-2', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_shopLeadInfo_div"
                     @if( in_array('11-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Delete
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="11-3"
                                   @if( in_array('11-3', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- =============================================================================== -->
            <hr>
            <!-- =============================================================================== -->
            <div class="row ">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="container-services"><b>Settings</b>
                            <input type="checkbox" class="user_privileges_type" data-type="Settings" name="user_privileges[]" value="25-0"
                                   @if( in_array('25-0', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Settings_div"
                     @if( in_array('25-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group">
                        <label class="container-services">Payment Methods
                            <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="25-1"
                                   @if( in_array('25-1', $permission_users)) checked @endif>
                            <span class="checkmark-services"></span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-2 user_privileges_Settings_div"
                @if( in_array('25-0', $permission_users)) style="display: block;" @else style="display: none;" @endif>
               <div class="form-group">
                   <label class="container-services">Export up to 30D
                       <input type="checkbox" class="user_privileges_service" name="user_privileges[]" value="25-2"
                              @if( in_array('25-2', $permission_users)) checked @endif>
                       <span class="checkmark-services"></span>
                   </label>
               </div>
           </div>

            </div>
            <!-- =============================================================================== -->
            <hr>
        </div>
    </div>
</div>
