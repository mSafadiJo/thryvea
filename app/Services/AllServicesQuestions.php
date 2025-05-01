<?php

namespace App\Services;

class AllServicesQuestions
{
    public function saveQuesAnswersInDb($dbObject, $questions, $service){
        switch ($service){
            case 1:
                //Windows service
                $dbObject->lead_numberOfItem = $questions['data_arr']['LeaddataIDs']['number_of_window'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_installing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 2:
                //Solar service
                $dbObject->property_type_campaign_id = $questions['data_arr']['LeaddataIDs']['property_type'];
                $dbObject->lead_solor_solution_list_id = $questions['data_arr']['LeaddataIDs']['power_solution'];
                $dbObject->lead_solor_sun_expouser_list_id = $questions['data_arr']['LeaddataIDs']['roof_shade'];
                $dbObject->lead_current_utility_provider_id = $questions['data_arr']['LeaddataIDs']['utility_provider'];
                $dbObject->lead_avg_money_electicity_list_id = $questions['data_arr']['LeaddataIDs']['monthly_electric_bill'];
                break;
            case 3:
                //Home Security service
                $dbObject->property_type_campaign_id = $questions['data_arr']['LeaddataIDs']['property_type'];
                $dbObject->lead_installation_preferences_id = $questions['data_arr']['LeaddataIDs']['Installation_Preferences'];
                $dbObject->lead_have_item_before_it = $questions['data_arr']['LeaddataIDs']['lead_have_item_before_it'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 4:
                //Flooring service
                $dbObject->lead_type_of_flooring_id = $questions['data_arr']['LeaddataIDs']['flooring_type'];
                $dbObject->lead_nature_flooring_project_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 5:
                //Walk-in-tops service
                $dbObject->lead_walk_in_tub_id = $questions['data_arr']['LeaddataIDs']['reason'];
                $dbObject->lead_desired_featuers_id = $questions['data_arr']['LeaddataIDs']['features'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 6:
                //Roofing service
                $dbObject->property_type_campaign_id = $questions['data_arr']['LeaddataIDs']['property_type_roofing'];
                $dbObject->lead_type_of_roofing_id = $questions['data_arr']['LeaddataIDs']['roof_type'];
                $dbObject->lead_nature_of_roofing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                $dbObject->lead_property_type_roofing_id = $questions['data_arr']['LeaddataIDs']['property_type_roofing'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 7:
                //Home Siding service
                $dbObject->type_of_siding_lead_id = $questions['data_arr']['LeaddataIDs']['type_of_siding'];
                $dbObject->nature_of_siding_lead_id = $questions['data_arr']['LeaddataIDs']['project_nature_siding'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 8:
                //Kitchen service
                $dbObject->service_kitchen_lead_id = $questions['data_arr']['LeaddataIDs']['services'];
                $dbObject->campaign_kitchen_r_a_walls_status = $questions['data_arr']['LeaddataIDs']['demolishing_walls'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 9:
                //Bathroom service
                $dbObject->campaign_bathroomtype_id = $questions['data_arr']['LeaddataIDs']['services'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 10:
                // stairs Service
                $dbObject->stairs_type_lead_id = $questions['data_arr']['LeaddataIDs']['stairs_type'];
                $dbObject->stairs_reason_lead_id = $questions['data_arr']['LeaddataIDs']['reason'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 11:
                //Furnace Service
                $dbObject->furnance_type_lead_id = $questions['data_arr']['LeaddataIDs']['type_of_heating'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_installing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                break;
            case 12:
                //Boiler Service
                $dbObject->furnance_type_lead_id = $questions['data_arr']['LeaddataIDs']['type_of_heating'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_installing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                break;
            case 13:
                //Central A/C Service
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_installing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                break;
            case 14:
                //Cabinet Service
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_installing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                break;
            case 15:
                //Plumbing Service
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->plumbing_service_list_id = $questions['data_arr']['LeaddataIDs']['services'];
                break;
            case 16:
                //Bathtubs Service
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                break;
            case 17:
                //SunRooms Service
                $dbObject->sunroom_service_lead_id = $questions['data_arr']['LeaddataIDs']['services'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_property_type_roofing_id = $questions['data_arr']['LeaddataIDs']['property_type_roofing'];
                $dbObject->property_type_campaign_id = $questions['data_arr']['LeaddataIDs']['property_type_roofing'];
                break;
            case 18:
                //Handyman service
                $dbObject->handyman_ammount_work_id = $questions['data_arr']['LeaddataIDs']['services'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                break;
            case 19:
                //CounterTops Service
                $dbObject->countertops_service_lead_id = $questions['data_arr']['LeaddataIDs']['service'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_installing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                break;
            case 20:
                //Doors Service
                $dbObject->door_typeproject_lead_id = $questions['data_arr']['LeaddataIDs']['door_type'];
                $dbObject->number_of_door_lead_id = $questions['data_arr']['LeaddataIDs']['number_of_door'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_installing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                break;
            case 21:
                //Gutter Service
                $dbObject->gutters_meterial_lead_id = $questions['data_arr']['LeaddataIDs']['service'];
                $dbObject->lead_priority_id = $questions['data_arr']['LeaddataIDs']['start_time'];
                $dbObject->lead_ownership = $questions['data_arr']['LeaddataIDs']['homeOwn'];
                $dbObject->lead_installing_id = $questions['data_arr']['LeaddataIDs']['project_nature'];
                break;
            case 24:
                //Auto Insurance
                $dbObject->VehicleYear = $questions['data_arr']['LeaddataIDs']['VehicleYear'];
                $dbObject->VehicleMake = $questions['data_arr']['LeaddataIDs']['VehicleMake'];
                $dbObject->car_model = $questions['data_arr']['LeaddataIDs']['car_model'];
                $dbObject->more_than_one_vehicle = $questions['data_arr']['LeaddataIDs']['more_than_one_vehicle'];
                $dbObject->driversNum = $questions['data_arr']['LeaddataIDs']['driversNum'];
                $dbObject->birthday = $questions['data_arr']['LeaddataIDs']['birthday'];
                $dbObject->genders = $questions['data_arr']['LeaddataIDs']['genders'];
                $dbObject->married = $questions['data_arr']['LeaddataIDs']['married'];
                $dbObject->license = $questions['data_arr']['LeaddataIDs']['license'];
                $dbObject->InsuranceCarrier = $questions['data_arr']['LeaddataIDs']['InsuranceCarrier'];
                $dbObject->driver_experience = $questions['data_arr']['LeaddataIDs']['driver_experience'];
                $dbObject->number_of_tickets = $questions['data_arr']['LeaddataIDs']['number_of_tickets'];
                $dbObject->DUI_charges = $questions['data_arr']['LeaddataIDs']['DUI_charges'];
                $dbObject->SR_22_need = $questions['data_arr']['LeaddataIDs']['SR_22_need'];
                $dbObject->ticket_date = $questions['data_arr']['LeaddataIDs']['ticket_date'];
                $dbObject->violation_date = $questions['data_arr']['LeaddataIDs']['violation_date'];
                $dbObject->claim_date = $questions['data_arr']['LeaddataIDs']['claim_date'];
                $dbObject->submodel = $questions['data_arr']['LeaddataIDs']['submodel'];
                $dbObject->expiration_date = $questions['data_arr']['LeaddataIDs']['expiration_date'];
                $dbObject->coverage_type = $questions['data_arr']['LeaddataIDs']['coverage_type'];
                $dbObject->license_status = $questions['data_arr']['LeaddataIDs']['license_status'];
                $dbObject->license_state = $questions['data_arr']['LeaddataIDs']['license_state'];
                $dbObject->accident_date = $questions['data_arr']['LeaddataIDs']['accident_date'];
                break;
        }
        return $dbObject;
    }

    public function campaignsMatchingWithQuestions($campaigns, $service, $projectnatureArrayData, $ownershipArrayData, $property_typeArrayData, $LeaddataIDs){
        switch ($service) {
            case 1:
                //Windows Service
                $numberofwindowsReq = $LeaddataIDs['number_of_window'];

                $campaigns->join('campaigns_questions', function($join) use($numberofwindowsReq,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.number_of_window', "$numberofwindowsReq");

                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 2:
                //Solar Service
                $solaravgMony = $LeaddataIDs['monthly_electric_bill'];
                $solor_solution = $LeaddataIDs['power_solution'];
                $solor_sun = $LeaddataIDs['roof_shade'];
                $utility_provider = strtolower($LeaddataIDs['utility_provider']);

                $campaigns->join('campaigns_questions', function($join) use($solaravgMony,$solor_solution,$solor_sun,$utility_provider,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.solar_bill', "$solaravgMony")
                        ->whereJsonContains('campaigns_questions.solar_power_solution', "$solor_solution")
                        ->whereJsonContains('campaigns_questions.roof_shade', "$solor_sun");

                    if(!empty($utility_provider)){
                        $join->where(function ($query) use ($utility_provider) {
                            $query->whereJsonContains('campaigns_questions.utility_providers', "$utility_provider");
                            $query->orWhere('campaigns.is_utility_solar_filter', "0");
                        });
                    }

                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 3:
                //Home Security Service
                $installation_preferences_security = $LeaddataIDs['Installation_Preferences'];
                $lead_have_item_before_it = $LeaddataIDs['lead_have_item_before_it'];

                $campaigns->join('campaigns_questions', function($join) use($installation_preferences_security,$lead_have_item_before_it,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.security_installing', "$installation_preferences_security")
                        ->whereJsonContains('campaigns_questions.existing_monitoring_system', "$lead_have_item_before_it");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 4:
                //Flooring Service
                $type_of_flooring_flooring = $LeaddataIDs['flooring_type'];

                $campaigns->join('campaigns_questions', function($join) use($type_of_flooring_flooring,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.flooring_type', "$type_of_flooring_flooring");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 5:
                //Walk-in-tops Service
                $walk_in_tub = $LeaddataIDs['reason'];

                $campaigns->join('campaigns_questions', function($join) use($walk_in_tub,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.walk_in_tup_filter', "$walk_in_tub");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 6:
                //Roofing Service
                $type_of_roofing = $LeaddataIDs['roof_type'];

                $campaigns->join('campaigns_questions', function($join) use($type_of_roofing,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.roof_type', "$type_of_roofing");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 7:
                //Home Siding Service
                $type_of_siding = $LeaddataIDs['type_of_siding'];

                $campaigns->join('campaigns_questions', function($join) use($type_of_siding,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.type_of_siding', "$type_of_siding");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 8:
                //Kitchen Service
                $service_kitchen = $LeaddataIDs['services'];
                $removing_adding_walls = $LeaddataIDs['demolishing_walls'];

                $campaigns->join('campaigns_questions', function($join) use($service_kitchen,$removing_adding_walls,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.kitchen_service', "$service_kitchen")
                        ->whereJsonContains('campaigns_questions.kitchen_walls', "$removing_adding_walls");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 9:
                //Bathroom Service
                $bathroom_type = $LeaddataIDs['services'];

                $campaigns->join('campaigns_questions', function($join) use($bathroom_type,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.bathroom_type', "$bathroom_type");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 10:
                //StairLifts Service
                $stairs_type = $LeaddataIDs['stairs_type'];
                $stairs_reason = $LeaddataIDs['reason'];

                $campaigns->join('campaigns_questions', function($join) use($stairs_type,$stairs_reason,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.stairs_type', "$stairs_type")
                        ->whereJsonContains('campaigns_questions.stairs_reason', "$stairs_reason");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 11:
                //Furnace Services
                $furnance_type = $LeaddataIDs['type_of_heating'];

                $campaigns->join('campaigns_questions', function($join) use($furnance_type,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.furnace_type', "$furnance_type");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 12:
                //Boiler Services
                $boiler_type = $LeaddataIDs['type_of_heating'];

                $campaigns->join('campaigns_questions', function($join) use($boiler_type,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.type_of_heating', "$boiler_type");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 13:
                //Central A/C Services
                $campaigns->join('campaigns_questions', function($join) use($ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id');
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 14:
                //Cabinet Services
                $campaigns->join('campaigns_questions', function($join) use($ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id');
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 15:
                //plumbing Service
                $plumbing_service = $LeaddataIDs['services'];

                $campaigns->join('campaigns_questions', function($join) use($plumbing_service,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.plumbing_service', "$plumbing_service");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 16:
                //Bathtubs Services
                $campaigns->join('campaigns_questions', function($join) use($ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id');
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 17:
                //SunRooms Service
                $sunroom_service = $LeaddataIDs['services'];

                $campaigns->join('campaigns_questions', function($join) use($sunroom_service,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.sunroom_service', "$sunroom_service");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 18:
                //Handyman Service
                $handyman_ammount = $LeaddataIDs['services'];

                $campaigns->join('campaigns_questions', function($join) use($handyman_ammount,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.handyman_amount_work', "$handyman_ammount");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 19:
                //CounterTops Service
                $countertops_service = $LeaddataIDs['service'];

                $campaigns->join('campaigns_questions', function($join) use($countertops_service,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.counter_tops_service', "$countertops_service");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 20:
                //Doors Service
                $door_typeproject = $LeaddataIDs['door_type'];
                $number_of_door = $LeaddataIDs['number_of_door'];

                $campaigns->join('campaigns_questions', function($join) use($door_typeproject,$number_of_door,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.door_type', "$door_typeproject")
                        ->whereJsonContains('campaigns_questions.number_of_door', "$number_of_door");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 21:
                //Gutter Service
                $gutters_meterial = $LeaddataIDs['service'];

                $campaigns->join('campaigns_questions', function($join) use($gutters_meterial,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.gutters_material', "$gutters_meterial");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 22:
                //Paving Service
                $paving_service = $LeaddataIDs['service'];
                $paving_best_describes_priject = $LeaddataIDs['project_type'];

                $campaigns->join('campaigns_questions', function($join) use($paving_service,$LeaddataIDs,$paving_best_describes_priject,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.paving_service', "$paving_service");

                    switch ($paving_service){
                        case 1:
                            $paving_asphalt_type = $LeaddataIDs['asphalt_needing'];
                            $join->whereJsonContains('campaigns_questions.paving_asphalt', "$paving_asphalt_type");
                            break;
                        case 3:
                            $paving_loose_fill_type = $LeaddataIDs['material_loose'];
                            $join->whereJsonContains('campaigns_questions.paving_loose_fill', "$paving_loose_fill_type");
                            break;
                    }

                    $join->whereJsonContains('campaigns_questions.paving_best_desc', "$paving_best_describes_priject");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 23:
                //Painting Service
                $painting_service = $LeaddataIDs['service'];

                $campaigns->join('campaigns_questions', function($join) use($painting_service,$LeaddataIDs, $ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.painting_service', "$painting_service");

                    switch ($painting_service) {
                        case 1:
                            $join->whereJsonContains('campaigns_questions.painting_project_type', $LeaddataIDs['project_type'])
                                ->whereJsonContains('campaigns_questions.painting_stories_number', $LeaddataIDs['stories_number'])
                                ->whereJsonContains('campaigns_questions.painting_kinds_of_surfaces', $LeaddataIDs['surfaces_kinds'])
                                ->whereJsonContains('campaigns_questions.painting_historical_structure', $LeaddataIDs['historical_structure']);
                            break;
                        case 2:
                            $join->whereJsonContains('campaigns_questions.painting_type_of_paint', $LeaddataIDs['painted_needs'])
                                ->whereJsonContains('campaigns_questions.painting_rooms_number', $LeaddataIDs['rooms_number'])
                                ->whereJsonContains('campaigns_questions.painting_historical_structure', $LeaddataIDs['historical_structure']);
                            break;
                        case 3:
                            $join->whereJsonContains('campaigns_questions.painting_historical_structure', $LeaddataIDs['historical_structure']);
                            $join->where(function ($query) use ($LeaddataIDs) {
                                foreach($LeaddataIDs['painted_feature'] as $each_feature) {
                                    $query->OrWhereJsonContains('campaigns_questions.painting_each_feature', "$each_feature");
                                }
                            });
                            break;
                        case 4:
                            $join->whereJsonContains('campaigns_questions.painting_historical_structure', $LeaddataIDs['historical_structure'])
                                ->whereJsonContains('campaigns_questions.painting_stories_number', $LeaddataIDs['stories_number']);
                            $join->where(function ($query) use ($LeaddataIDs) {
                                foreach($LeaddataIDs['existing_roof'] as $existing_roof) {
                                    $query->OrWhereJsonContains('campaigns_questions.painting_existing_roof', "$existing_roof");
                                }
                            });
                            break;
                        case 5:
                            $join->whereJsonContains('campaigns_questions.painting_surfaces_textured', $LeaddataIDs['surfaces_textured']);
                            $join->where(function ($query) use ($LeaddataIDs) {
                                foreach($LeaddataIDs['surfaces_kinds'] as $kindof_texturing) {
                                    $query->OrWhereJsonContains('campaigns_questions.painting_kind_of_texturing', "$kindof_texturing");
                                }
                            });
                            break;
                    }

                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 24:
                //Auto Insurance
                $license = $LeaddataIDs['license'];
                $driver_experience = $LeaddataIDs['driver_experience'];
                $submodel = $LeaddataIDs['submodel'];
                $coverage_type = $LeaddataIDs['coverage_type'];
                $license_status = $LeaddataIDs['license_status'];
                $license_state = $LeaddataIDs['license_state'];

                $campaigns->join('campaigns_questions', function($join) use($license,$driver_experience,$submodel,$coverage_type,$license_status,$license_state,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.auto_insurance_license', "$license")
                        ->whereJsonContains('campaigns_questions.driver_experience', "$driver_experience")
                        ->whereJsonContains('campaigns_questions.submodel', "$submodel")
                        ->whereJsonContains('campaigns_questions.coverage_type', "$coverage_type")
                        ->whereJsonContains('campaigns_questions.license_status', "$license_status")
                        ->whereJsonContains('campaigns_questions.license_state', "$license_state");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            default:
                $campaigns->join('campaigns_questions', function($join) use($ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id');
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;

        }
        return $campaigns;
    }

    public function insertFromCampaigns($dbQuery, $request, $campaign_id){

        $dbQuery->insert([
            'campaign_id' => $campaign_id,
            'home_owned' => json_encode(array_values(!empty($request['homeowned']) ? $request['homeowned'] : array())),
            'property_type' => json_encode(array_values(!empty($request['propertytype']) ? $request['propertytype'] : array())),
            'installing' => json_encode(array_values(!empty($request['Installings']) ? $request['Installings'] : array())),

            // solar
            'solar_bill' => json_encode(!empty($request['solorBill']) ? $request['solorBill'] : array()),
            'solar_power_solution' => json_encode(!empty($request['Solarpowersolution']) ? $request['Solarpowersolution'] : array()),
            'roof_shade' => json_encode(!empty($request['RoofShade']) ? $request['RoofShade'] : array()),
            'utility_providers' => strtolower(json_encode(!empty($request['Utility_Providers']) ? $request['Utility_Providers'] : array())),

            // window
            'number_of_window' => json_encode(!empty($request['number_of_windows_c']) ? $request['number_of_windows_c'] : array()),

            // security
            'security_installing' => json_encode(!empty($request['securityInstalling']) ? $request['securityInstalling'] : array()),
            'existing_monitoring_system' => json_encode(!empty($request['ExistingMonitoringSystem']) ? $request['ExistingMonitoringSystem'] : array()),

            // flooring
            'flooring_type' => json_encode(!empty($request['flooringtype']) ? $request['flooringtype'] : array()),

            //walk_in_tup
            'walk_in_tup_filter' => json_encode(!empty($request['lead_walk_in_tub']) ? $request['lead_walk_in_tub'] : array()),

            //roof
            'roof_type' => json_encode(!empty($request['roofingtype']) ? $request['roofingtype'] : array()),

            //siding
            'type_of_siding' => json_encode(!empty($request['sidingtype']) ? $request['sidingtype'] : array()),

            // kitchen
            'kitchen_service' => json_encode(!empty($request['kitchen_service']) ? $request['kitchen_service'] : array()),
            'kitchen_walls' => json_encode(!empty($request['removing_adding_walls']) ? $request['removing_adding_walls'] : array()),

            //siding
            'bathroom_type' => json_encode(!empty($request['bathroom_service']) ? $request['bathroom_service'] : array()),

            //siding
            'stairs_reason' => json_encode(!empty($request['stairlift_reason']) ? $request['stairlift_reason'] : array()),
            'stairs_type' => json_encode(!empty($request['stairlift_type']) ? $request['stairlift_type'] : array()),

            //furnace
            'furnace_type' => json_encode(!empty($request['furnance_type']) ? $request['furnance_type'] : array()),

            //Boiler
            'type_of_heating' => json_encode(!empty($request['furnance_type']) ? $request['furnance_type'] : array()),


            //plumbing
            'plumbing_service' => json_encode(!empty($request['plumbing_service']) ? $request['plumbing_service'] : array()),

            //sunroom
            'sunroom_service' => json_encode(!empty($request['sunroom_service']) ? $request['sunroom_service'] : array()),

            //handyman
            'handyman_amount_work' => json_encode(!empty($request['handyman_ammount']) ? $request['handyman_ammount'] : array()),

            //counter
            'counter_tops_service' => json_encode(!empty($request['countertops_service']) ? $request['countertops_service'] : array()),

            //doors
            'door_type' => json_encode(!empty($request['door_typeproject']) ? $request['door_typeproject'] : array()),
            'number_of_door' => json_encode(!empty($request['number_of_door']) ? $request['number_of_door'] : array()),

            //gutters
            'gutters_material' => json_encode(!empty($request['gutters_meterial']) ? $request['gutters_meterial'] : array()),

            //paving
            'paving_service' => json_encode(!empty($request['paving_service']) ? $request['paving_service'] : array()),
            'paving_asphalt' => json_encode(!empty($request['paving_asphalt_type']) ? $request['paving_asphalt_type'] : array()),
            'paving_loose_fill' => json_encode(!empty($request['paving_loose_fill_type']) ? $request['paving_loose_fill_type'] : array()),
            'paving_best_desc' => json_encode(!empty($request['paving_best_describes_priject']) ? $request['paving_best_describes_priject'] : array()),

            //painting
            'painting_service' => json_encode(!empty($request['painting_service']) ? $request['painting_service'] : array()),
            'painting_project_type' => json_encode(!empty($request['painting1_typeof_project']) ? $request['painting1_typeof_project'] : array()),
            'painting_stories_number' => json_encode(!empty($request['painting1_stories_number']) ? $request['painting1_stories_number'] : array()),
            'painting_kinds_of_surfaces' => json_encode(!empty($request['painting1_kindsof_surfaces']) ? $request['painting1_kindsof_surfaces'] : array()),
            'painting_historical_structure' => json_encode(!empty($request['historical_structure']) ? $request['historical_structure'] : array()),
            'painting_rooms_number' => json_encode(!empty($request['painting2_rooms_number']) ? $request['painting2_rooms_number'] : array()),
            'painting_type_of_paint' => json_encode(!empty($request['painting2_typeof_paint']) ? $request['painting2_typeof_paint'] : array()),
            'painting_each_feature' => json_encode(!empty($request['painting3_each_feature']) ? $request['painting3_each_feature'] : array()),
            'painting_existing_roof' => json_encode(!empty($request['painting4_existing_roof']) ? $request['painting4_existing_roof'] : array()),
//            'painting_asphalt' => json_encode(array_values(array_unique($painting_asphalt))),
            'painting_surfaces_textured' => json_encode(!empty($request['painting5_surfaces_textured']) ? $request['painting5_surfaces_textured'] : array()),
//            'painting_best_desc' => json_encode(array_values(array_unique($painting_best_desc))),
            'painting_kind_of_texturing' => json_encode(!empty($request['painting5_kindof_texturing']) ? $request['painting5_kindof_texturing'] : array()),

            //Auto Insurance
            'driver_experience' => json_encode(!empty($request['driver_experience']) ? $request['driver_experience'] : array()),
            'auto_insurance_license' => json_encode(!empty($request['auto_insurance_license']) ? $request['auto_insurance_license'] : array()),
            'license_state' => json_encode(!empty($request['license_state']) ? $request['license_state'] : array()),
            'license_status' => json_encode(!empty($request['license_status']) ? $request['license_status'] : array()),
            'coverage_type' => json_encode(!empty($request['coverage_type']) ? $request['coverage_type'] : array()),
            'submodel' => json_encode(!empty($request['submodel']) ? $request['submodel'] : array()),
        ]);
    }

    public function insertFromCloneCampaigns($dbQuery, $campaign_Data, $new_campaign_id){
        $dbQuery->insert([
            'campaign_id' => $new_campaign_id,
            'home_owned' => $campaign_Data->home_owned,
            'property_type' => $campaign_Data->property_type,
            'installing' => $campaign_Data->installing,

            // solar
            'solar_bill' => $campaign_Data->solar_bill,
            'solar_power_solution' => $campaign_Data->solar_power_solution,
            'roof_shade' => $campaign_Data->roof_shade,
            'utility_providers' => $campaign_Data->utility_providers,

            // window
            'number_of_window' => $campaign_Data->number_of_window,

            // security
            'security_installing' => $campaign_Data->security_installing,
            'existing_monitoring_system' =>$campaign_Data->existing_monitoring_system,

            // flooring
            'flooring_type' => $campaign_Data->flooring_type,

            //walk_in_tup
            'walk_in_tup_filter' => $campaign_Data->walk_in_tup_filter,

            //roof
            'roof_type' => $campaign_Data->roof_type,

            //siding
            'type_of_siding' => $campaign_Data->type_of_siding,

            // kitchen
            'kitchen_service' => $campaign_Data->kitchen_service,
            'kitchen_walls' => $campaign_Data->kitchen_walls,

            //bathroom
            'bathroom_type' => $campaign_Data->bathroom_type,

            //stairs
            'stairs_reason' => $campaign_Data->stairs_reason,
            'stairs_type' => $campaign_Data->stairs_type,

            //furnace
            'furnace_type' => $campaign_Data->furnace_type,

            //Boiler
            'type_of_heating' => $campaign_Data->type_of_heating,

            //plumbing
            'plumbing_service' => $campaign_Data->plumbing_service,

            //sunroom
            'sunroom_service' => $campaign_Data->sunroom_service,

            //handyman
            'handyman_amount_work' => $campaign_Data->handyman_amount_work,

            //counter
            'counter_tops_service' => $campaign_Data->counter_tops_service,

            //doors
            'door_type' => $campaign_Data->door_type,
            'number_of_door' => $campaign_Data->number_of_door,

            //gutters
            'gutters_material' => $campaign_Data->gutters_material,

            //paving
            'paving_service' => $campaign_Data->paving_service,
            'paving_asphalt' => $campaign_Data->paving_asphalt,
            'paving_loose_fill' => $campaign_Data->paving_loose_fill,
            'paving_best_desc' => $campaign_Data->paving_best_desc,

            //painting
            'painting_service' => $campaign_Data->painting_service,
            'painting_project_type' => $campaign_Data->painting_project_type,
            'painting_stories_number' => $campaign_Data->painting_stories_number,
            'painting_kinds_of_surfaces' => $campaign_Data->painting_kinds_of_surfaces,
            'painting_historical_structure' => $campaign_Data->painting_historical_structure,
            'painting_rooms_number' => $campaign_Data->painting_rooms_number,
            'painting_type_of_paint' => $campaign_Data->painting_type_of_paint,
            'painting_each_feature' => $campaign_Data->painting_each_feature,
            'painting_existing_roof' => $campaign_Data->painting_existing_roof,
//          'painting_asphalt' => json_encode(array_values(array_unique($painting_asphalt))),
            'painting_surfaces_textured' => $campaign_Data->painting_surfaces_textured,
//          'painting_best_desc' => json_encode(array_values(array_unique($painting_best_desc))),
            'painting_kind_of_texturing' => $campaign_Data->painting_kind_of_texturing,

            //Auto Insurance
            'driver_experience' => $campaign_Data->driver_experience,
            'auto_insurance_license' => $campaign_Data->auto_insurance_license,
            'license_state' => $campaign_Data->license_state,
            'license_status' => $campaign_Data->license_status,
            'coverage_type' => $campaign_Data->coverage_type,
            'submodel' => $campaign_Data->submodel,
        ]);
    }

    public function updateFromCampaigns($dbQuery, $request){
        $dbQuery->update([
            'home_owned' => json_encode(array_values(!empty($request['homeowned']) ? $request['homeowned'] : array())),
            'property_type' => json_encode(array_values(!empty($request['propertytype']) ? $request['propertytype'] : array())),
            'installing' => json_encode(array_values(!empty($request['Installings']) ? $request['Installings'] : array())),

            // solar
            'solar_bill' => json_encode(!empty($request['solorBill']) ? $request['solorBill'] : array()),
            'solar_power_solution' => json_encode(!empty($request['Solarpowersolution']) ? $request['Solarpowersolution'] : array()),
            'roof_shade' => json_encode(!empty($request['RoofShade']) ? $request['RoofShade'] : array()),
            'utility_providers' => strtolower(json_encode(!empty($request['Utility_Providers']) ? $request['Utility_Providers'] : array())),

            // window
            'number_of_window' => json_encode(!empty($request['number_of_windows_c']) ? $request['number_of_windows_c'] : array()),

            // security
            'security_installing' => json_encode(!empty($request['securityInstalling']) ? $request['securityInstalling'] : array()),
            'existing_monitoring_system' => json_encode(!empty($request['ExistingMonitoringSystem']) ? $request['ExistingMonitoringSystem'] : array()),

            // flooring
            'flooring_type' => json_encode(!empty($request['flooringtype']) ? $request['flooringtype'] : array()),

            //walk_in_tup
            'walk_in_tup_filter' => json_encode(!empty($request['lead_walk_in_tub']) ? $request['lead_walk_in_tub'] : array()),

            //roof
            'roof_type' => json_encode(!empty($request['roofingtype']) ? $request['roofingtype'] : array()),

            //siding
            'type_of_siding' => json_encode(!empty($request['sidingtype']) ? $request['sidingtype'] : array()),

            // kitchen
            'kitchen_service' => json_encode(!empty($request['kitchen_service']) ? $request['kitchen_service'] : array()),
            'kitchen_walls' => json_encode(!empty($request['removing_adding_walls']) ? $request['removing_adding_walls'] : array()),

            //siding
            'bathroom_type' => json_encode(!empty($request['bathroom_service']) ? $request['bathroom_service'] : array()),

            //siding
            'stairs_reason' => json_encode(!empty($request['stairlift_reason']) ? $request['stairlift_reason'] : array()),
            'stairs_type' => json_encode(!empty($request['stairlift_type']) ? $request['stairlift_type'] : array()),

            //furnace
            'furnace_type' => json_encode(!empty($request['furnance_type']) ? $request['furnance_type'] : array()),

            //Boiler
            'type_of_heating' => json_encode(!empty($request['furnance_type']) ? $request['furnance_type'] : array()),


            //plumbing
            'plumbing_service' => json_encode(!empty($request['plumbing_service']) ? $request['plumbing_service'] : array()),

            //sunroom
            'sunroom_service' => json_encode(!empty($request['sunroom_service']) ? $request['sunroom_service'] : array()),

            //handyman
            'handyman_amount_work' => json_encode(!empty($request['handyman_ammount']) ? $request['handyman_ammount'] : array()),

            //counter
            'counter_tops_service' => json_encode(!empty($request['countertops_service']) ? $request['countertops_service'] : array()),

            //doors
            'door_type' => json_encode(!empty($request['door_typeproject']) ? $request['door_typeproject'] : array()),
            'number_of_door' => json_encode(!empty($request['number_of_door']) ? $request['number_of_door'] : array()),

            //gutters
            'gutters_material' => json_encode(!empty($request['gutters_meterial']) ? $request['gutters_meterial'] : array()),

            //paving
            'paving_service' => json_encode(!empty($request['paving_service']) ? $request['paving_service'] : array()),
            'paving_asphalt' => json_encode(!empty($request['paving_asphalt_type']) ? $request['paving_asphalt_type'] : array()),
            'paving_loose_fill' => json_encode(!empty($request['paving_loose_fill_type']) ? $request['paving_loose_fill_type'] : array()),
            'paving_best_desc' => json_encode(!empty($request['paving_best_describes_priject']) ? $request['paving_best_describes_priject'] : array()),

            //painting
            'painting_service' => json_encode(!empty($request['painting_service']) ? $request['painting_service'] : array()),
            'painting_project_type' => json_encode(!empty($request['painting1_typeof_project']) ? $request['painting1_typeof_project'] : array()),
            'painting_stories_number' => json_encode(!empty($request['painting1_stories_number']) ? $request['painting1_stories_number'] : array()),
            'painting_kinds_of_surfaces' => json_encode(!empty($request['painting1_kindsof_surfaces']) ? $request['painting1_kindsof_surfaces'] : array()),
            'painting_historical_structure' => json_encode(!empty($request['historical_structure']) ? $request['historical_structure'] : array()),
            'painting_rooms_number' => json_encode(!empty($request['painting2_rooms_number']) ? $request['painting2_rooms_number'] : array()),
            'painting_type_of_paint' => json_encode(!empty($request['painting2_typeof_paint']) ? $request['painting2_typeof_paint'] : array()),
            'painting_each_feature' => json_encode(!empty($request['painting3_each_feature']) ? $request['painting3_each_feature'] : array()),
            'painting_existing_roof' => json_encode(!empty($request['painting4_existing_roof']) ? $request['painting4_existing_roof'] : array()),
//            'painting_asphalt' => json_encode(array_values(array_unique($painting_asphalt))),
            'painting_surfaces_textured' => json_encode(!empty($request['painting5_surfaces_textured']) ? $request['painting5_surfaces_textured'] : array()),
//            'painting_best_desc' => json_encode(array_values(array_unique($painting_best_desc))),
            'painting_kind_of_texturing' => json_encode(!empty($request['painting5_kindof_texturing']) ? $request['painting5_kindof_texturing'] : array()),

            //Auto Insurance
            'driver_experience' => json_encode(!empty($request['driver_experience']) ? $request['driver_experience'] : array()),
            'auto_insurance_license' => json_encode(!empty($request['auto_insurance_license']) ? $request['auto_insurance_license'] : array()),
            'license_state' => json_encode(!empty($request['license_state']) ? $request['license_state'] : array()),
            'license_status' => json_encode(!empty($request['license_status']) ? $request['license_status'] : array()),
            'coverage_type' => json_encode(!empty($request['coverage_type']) ? $request['coverage_type'] : array()),
            'submodel' => json_encode(!empty($request['submodel']) ? $request['submodel'] : array()),
        ]);
    }

    public function leadCustomerLeadUpdate($dbQuery, $request, $dataMassageForDB){
        $dbQuery->update([
            "lead_fname" => $request['fname'],
            "lead_lname" => $request['lname'],
            "lead_address" => $request['Street'],
            "lead_email" => $request['email'],
            "lead_phone_number" => $request['phone'],
            "lead_numberOfItem" => $request['numberofwindows'],
            "lead_ownership" => $request['ownership'],

            "lead_installing_id" => $request['projectnature'],
            "lead_priority_id" => $request['priority'],
            "lead_state_id" => $request['state'],
            "lead_city_id" => $request['city'],
            "lead_zipcode_id" => $request['zipcode'],
            "lead_county_id" => $request['County'],
            "lead_solor_solution_list_id" => $request['solor_solution'],
            "lead_solor_sun_expouser_list_id" => $request['solor_sun'],
            "lead_current_utility_provider_id" => $request['utility_provider'],
            "lead_avg_money_electicity_list_id" => $request['avg_money'],
            "property_type_campaign_id" => $request['property_type_c'],
            "lead_installation_preferences_id" => $request['installation_preferences'],
            "lead_have_item_before_it" => $request['lead_have_item_before_it'],
            "lead_type_of_flooring_id" => $request['type_of_flooring'],
            "lead_nature_flooring_project_id" => $request['nature_flooring_project'],
            "lead_walk_in_tub_id" => $request['walk_in_tub'],
            "lead_desired_featuers_id" => $request['desired_featuers'],
            "lead_type_of_roofing_id" => $request['type_of_roofing'],
            "lead_nature_of_roofing_id" => $request['nature_of_roofing'],
            "lead_property_type_roofing_id" => $request['property_type_roofing'],
            "type_of_siding_lead_id" => $request['type_of_siding'],
            "nature_of_siding_lead_id" => $request['nature_of_siding'],
            "service_kitchen_lead_id" => $request['service_kitchen'],
            "campaign_kitchen_r_a_walls_status" => $request['removing_adding_walls'],
            "campaign_bathroomtype_id" => $request['bathroom_type'],
            "stairs_type_lead_id" => $request['stairs_type'],
            "stairs_reason_lead_id" => $request['stairs_reason'],
            "furnance_type_lead_id" => $request['furnance_type'],
            "plumbing_service_list_id" => $request['plumbing_service'],
            "sunroom_service_lead_id" => $request['sunroom_service'],
            "handyman_ammount_work_id" => $request['handyman_ammount'],
            "countertops_service_lead_id" => $request['countertops_service'],
            "door_typeproject_lead_id" => $request['door_typeproject'],
            "number_of_door_lead_id" => $request['number_of_door'],

            "gutters_meterial_lead_id" => $request['gutters_meterial'],
            "paving_service_lead_id" => $request['paving_service'],
            "paving_asphalt_type_id" => $request['paving_asphalt_type'],
            "paving_loose_fill_type_id" => $request['paving_loose_fill_type'],
            "paving_best_describes_priject_id" => $request['paving_best_describes_priject'],

            "painting_service_lead_id" => $request['painting_service'],
            "painting1_typeof_project_id" => $request['painting1_typeof_project'],
            "painting1_stories_number_id" => $request['painting1_stories'],
            "painting1_kindsof_surfaces_id" => $request['painting1_kindsof_surfaces'],
            "painting2_rooms_number_id" => $request['painting2_rooms_number'],
            "painting2_typeof_paint_id" => $request['painting2_typeof_paint'],
            "painting3_each_feature_id" => $request['painting3_each_feature'],
            "painting4_existing_roof_id" => $request['painting4_existing_roof'],
            "painting5_kindof_texturing_id" => $request['painting5_kindof_texturing'],
            "painting5_surfaces_textured_id" => $request['painting5_surfaces_textured'],
            "historical_structure" => $request['interior_historical'],

            //Shared fields Insurance
            "birthday" => ( !empty($request['birthday']) ? date('Y-m-d', strtotime($request['birthday'])) : null ),
            "genders" => $request['genders'],
            "married" => $request['married'],

            //Auto Insurance
            "VehicleYear" => $request['VehicleYear'],
            "VehicleMake" => $request['VehicleMake'],
            "car_model" => $request['car_model'],
            "more_than_one_vehicle" => $request['more_than_one_vehicle'],
            "driversNum" => $request['driversNum'],
            "license" => $request['license'],
            "InsuranceCarrier" => $request['InsuranceCarrier'],
            "driver_experience" => $request['driver_experience'],
            "number_of_tickets" => $request['number_of_tickets'],
            "DUI_charges" => $request['DUI_charges'],
            "SR_22_need" => $request['SR_22_need'],
            "submodel" => $request['submodel'],
            "coverage_type" => $request['coverage_type'],
            "license_status" => $request['license_status'],
            "license_state" => $request['license_state'],
            "ticket_date" => $request['ticket_date'],
            "violation_date" => $request['violation_date'],
            "accident_date" => $request['accident_date'],
            "claim_date" => $request['claim_date'],
            "expiration_date" => $request['expiration_date'],

            //home insurance
            'house_type' => $request['house_type'],
            'Year_Built' => $request['Year_Built'],
            'primary_residence' => $request['primary_residence'],
            'new_purchase' => $request['new_purchase'],
            'previous_insurance_within_last30' => $request['previous_insurance_within_last30'],
            'previous_insurance_claims_last3yrs' => $request['previous_insurance_claims_last3yrs'],
            'credit_rating' => $request['credit_rating'],

            //Life Insurance & Disability insurance
            'Height' => $request['Height'],
            'weight' => $request['weight'],
            'amount_coverage' => $request['amount_coverage'],
            'military_personnel_status' => $request['military_personnel_status'],
            'military_status' => $request['military_status'],
            'service_branch' => $request['service_branch'],

            //Business insurance
            'CommercialCoverage' => $request['CommercialCoverage'],
            'company_benefits_quote' => $request['company_benefits_quote'],
            'business_start_date' => $request['business_start_date'],
            'estimated_annual_payroll' => $request['estimated_annual_payroll'],
            'number_of_employees' => $request['number_of_employees'],
            'coverage_start_month' => $request['coverage_start_month'],
            'business_name' => $request['business_name'],

            //Health Insurance & long term insurance
            'pregnancy' => $request['pregnancy'],
            'tobacco_usage' => $request['tobacco_usage'],
            'health_conditions' => $request['health_conditions'],
            'number_of_people_in_household' => $request['number_of_people_in_household'],
            'addPeople' => $request['addPeople'],
            'annual_income' => $request['annual_income'],

            //debt relief
            "debt_amount"  => $request['debt_amount'],
            "debt_type"  => json_encode($request['debt_type']),

            "lead_details_text" => $dataMassageForDB
        ]);
    }

    public function leadReviewLeadUpdate($dbQuery, $request, $dataMassageForDB){
        $dbQuery->update([
            "lead_fname" => $request['fname'],
            "lead_lname" => $request['lname'],
            "lead_address" => $request['Street'],
            "lead_email" => $request['email'],
            "lead_phone_number" => $request['phone'],
            "lead_state_id" => $request['state'],
            "lead_city_id" => $request['city'],
            "lead_zipcode_id" => $request['zipcode'],
            "lead_county_id" => $request['County'],

            "lead_numberOfItem" => $request['numberofwindows'],
            "lead_ownership" => $request['ownership'],
            "lead_installing_id" => $request['projectnature'],
            "lead_priority_id" => $request['priority'],

            "lead_solor_solution_list_id" => $request['solor_solution'],
            "lead_solor_sun_expouser_list_id" => $request['solor_sun'],
            "lead_current_utility_provider_id" => $request['utility_provider'],
            "lead_avg_money_electicity_list_id" => $request['avg_money'],
            "property_type_campaign_id" => $request['property_type_c'],

            "lead_installation_preferences_id" => $request['installation_preferences'],
            "lead_have_item_before_it" => $request['lead_have_item_before_it'],

            "lead_type_of_flooring_id" => $request['type_of_flooring'],
            "lead_nature_flooring_project_id" => $request['nature_flooring_project'],

            "lead_walk_in_tub_id" => $request['walk_in_tub'],
            "lead_desired_featuers_id" => json_encode($request['desired_featuers']),

            "lead_type_of_roofing_id" => $request['type_of_roofing'],
            "lead_nature_of_roofing_id" => $request['nature_of_roofing'],
            "lead_property_type_roofing_id" => $request['property_type_roofing'],

            "type_of_siding_lead_id" => $request['type_of_siding'],
            "nature_of_siding_lead_id" => $request['nature_of_siding'],

            "service_kitchen_lead_id" => $request['service_kitchen'],
            "campaign_kitchen_r_a_walls_status" => $request['removing_adding_walls'],

            "campaign_bathroomtype_id" => $request['bathroom_type'],

            "stairs_type_lead_id" => $request['stairs_type'],
            "stairs_reason_lead_id" => $request['stairs_reason'],

            "furnance_type_lead_id" => $request['furnance_type'],

            "plumbing_service_list_id" => $request['plumbing_service'],

            "sunroom_service_lead_id" => $request['sunroom_service'],

            "handyman_ammount_work_id" => $request['handyman_ammount'],

            "countertops_service_lead_id" => $request['countertops_service'],

            "door_typeproject_lead_id" => $request['door_typeproject'],
            "number_of_door_lead_id" => $request['number_of_door'],

            "gutters_meterial_lead_id" => $request['gutters_meterial'],

            "paving_service_lead_id" => $request['paving_service'],
            "paving_asphalt_type_id" => $request['paving_asphalt_type'],
            "paving_loose_fill_type_id" => $request['paving_loose_fill_type'],
            "paving_best_describes_priject_id" => $request['paving_best_describes_priject'],

            "painting_service_lead_id" => $request['painting_service'],
            "painting1_typeof_project_id" => $request['painting1_typeof_project'],
            "painting1_stories_number_id" => $request['painting1_stories'],
            "painting1_kindsof_surfaces_id" => $request['painting1_kindsof_surfaces'],
            "painting2_rooms_number_id" => $request['painting2_rooms_number'],
            "painting2_typeof_paint_id" => $request['painting2_typeof_paint'],
            "painting3_each_feature_id" => json_encode($request['painting3_each_feature']),
            "painting4_existing_roof_id" => json_encode($request['painting4_existing_roof']),
            "painting5_kindof_texturing_id" => json_encode($request['painting5_kindof_texturing']),
            "painting5_surfaces_textured_id" => $request['painting5_surfaces_textured'],
            "historical_structure" => $request['interior_historical'],

            //Shared fields Insurance
            "birthday" => ( !empty($request['birthday']) ? date('Y-m-d', strtotime($request['birthday'])) : null ),
            "genders" => $request['genders'],
            "married" => $request['married'],

            //Auto Insurance
            "VehicleYear" => $request['VehicleYear'],
            "VehicleMake" => $request['VehicleMake'],
            "car_model" => $request['car_model'],
            "more_than_one_vehicle" => $request['more_than_one_vehicle'],
            "driversNum" => $request['driversNum'],
            "license" => $request['license'],
            "InsuranceCarrier" => $request['InsuranceCarrier'],
            "driver_experience" => $request['driver_experience'],
            "number_of_tickets" => $request['number_of_tickets'],
            "DUI_charges" => $request['DUI_charges'],
            "SR_22_need" => $request['SR_22_need'],
            "submodel" => $request['submodel'],
            "coverage_type" => $request['coverage_type'],
            "license_status" => $request['license_status'],
            "license_state" => $request['license_state'],
            "ticket_date" => $request['ticket_date'],
            "violation_date" => $request['violation_date'],
            "accident_date" => $request['accident_date'],
            "claim_date" => $request['claim_date'],
            "expiration_date" => $request['expiration_date'],

            //home insurance
            'house_type' => $request['house_type'],
            'Year_Built' => $request['Year_Built'],
            'primary_residence' => $request['primary_residence'],
            'new_purchase' => $request['new_purchase'],
            'previous_insurance_within_last30' => $request['previous_insurance_within_last30'],
            'previous_insurance_claims_last3yrs' => $request['previous_insurance_claims_last3yrs'],
            'credit_rating' => $request['credit_rating'],

            //Life Insurance & Disability insurance
            'Height' => $request['Height'],
            'weight' => $request['weight'],
            'amount_coverage' => $request['amount_coverage'],
            'military_personnel_status' => $request['military_personnel_status'],
            'military_status' => $request['military_status'],
            'service_branch' => $request['service_branch'],

            //Business insurance
            'CommercialCoverage' => $request['CommercialCoverage'],
            'company_benefits_quote' => $request['company_benefits_quote'],
            'business_start_date' => $request['business_start_date'],
            'estimated_annual_payroll' => $request['estimated_annual_payroll'],
            'number_of_employees' => $request['number_of_employees'],
            'coverage_start_month' => $request['coverage_start_month'],
            'business_name' => $request['business_name'],

            //Health Insurance & long term insurance
            'pregnancy' => $request['pregnancy'],
            'tobacco_usage' => $request['tobacco_usage'],
            'health_conditions' => $request['health_conditions'],
            'number_of_people_in_household' => $request['number_of_people_in_household'],
            'addPeople' => $request['addPeople'],
            'annual_income' => $request['annual_income'],

            "is_completed" => $request['completed'],
            "lead_details_text" => $dataMassageForDB
        ]);
    }

    public function leadReviewCompleteLeadCustomerSave($leadsCustomer, $request, $dataMassageForDB){
        $leadsCustomer->lead_fname = $request['fname'];
        $leadsCustomer->lead_lname = $request['lname'];
        $leadsCustomer->lead_address = $request['Street'];
        $leadsCustomer->lead_email = $request['email'];
        $leadsCustomer->lead_phone_number = $request['phone'];
        $leadsCustomer->lead_numberOfItem = $request['numberofwindows'];
        $leadsCustomer->lead_ownership = $request['ownership'];
        $leadsCustomer->lead_type_service_id = $request['service_id'];
        $leadsCustomer->lead_installing_id = $request['projectnature'];
        $leadsCustomer->lead_priority_id = $request['priority'];
        $leadsCustomer->lead_state_id = $request['state'];
        $leadsCustomer->lead_city_id = $request['city'];
        $leadsCustomer->lead_zipcode_id = $request['zipcode'];
        $leadsCustomer->lead_county_id = $request['County'];
        $leadsCustomer->lead_serverDomain = $request['lead_serverDomain'];
        $leadsCustomer->lead_timeInBrowseData = $request['lead_timeInBrowseData'];
        $leadsCustomer->lead_ipaddress = $request['lead_ipaddress'];
        $leadsCustomer->lead_FullUrl = $request['lead_FullUrl'];
        $leadsCustomer->lead_browser_name = $request['lead_browser_name'];
        $leadsCustomer->lead_aboutUserBrowser = $request['lead_aboutUserBrowser'];
        $leadsCustomer->lead_website = $request['website'];
        $leadsCustomer->traffic_source = $request['traffic_source'];
        $leadsCustomer->lead_solor_solution_list_id = $request['solor_solution'];
        $leadsCustomer->lead_solor_sun_expouser_list_id = $request['solor_sun'];
        $leadsCustomer->lead_current_utility_provider_id = $request['utility_provider'];
        $leadsCustomer->lead_avg_money_electicity_list_id = $request['avg_money'];
        $leadsCustomer->property_type_campaign_id = $request['property_type_c'];
        $leadsCustomer->lead_installation_preferences_id = $request['installation_preferences'];
        $leadsCustomer->lead_have_item_before_it = $request['lead_have_item_before_it'];
        $leadsCustomer->lead_type_of_flooring_id = $request['type_of_flooring'];
        $leadsCustomer->lead_nature_flooring_project_id = $request['nature_flooring_project'];
        $leadsCustomer->lead_walk_in_tub_id = $request['walk_in_tub'];
        $leadsCustomer->lead_desired_featuers_id = json_encode($request['desired_featuers']);
        $leadsCustomer->lead_type_of_roofing_id = $request['type_of_roofing'];
        $leadsCustomer->lead_nature_of_roofing_id = $request['nature_of_roofing'];
        $leadsCustomer->lead_property_type_roofing_id = $request['property_type_roofing'];
        $leadsCustomer->type_of_siding_lead_id = $request['type_of_siding'];
        $leadsCustomer->nature_of_siding_lead_id = $request['nature_of_siding'];
        $leadsCustomer->service_kitchen_lead_id = $request['service_kitchen'];
        $leadsCustomer->campaign_kitchen_r_a_walls_status = $request['removing_adding_walls'];
        $leadsCustomer->campaign_bathroomtype_id = $request['bathroom_type'];
        $leadsCustomer->stairs_type_lead_id = $request['stairs_type'];
        $leadsCustomer->stairs_reason_lead_id = $request['stairs_reason'];
        $leadsCustomer->furnance_type_lead_id = $request['furnance_type'];
        $leadsCustomer->plumbing_service_list_id = $request['plumbing_service'];
        $leadsCustomer->sunroom_service_lead_id = $request['sunroom_service'];
        $leadsCustomer->handyman_ammount_work_id = $request['handyman_ammount'];
        $leadsCustomer->countertops_service_lead_id = $request['countertops_service'];
        $leadsCustomer->door_typeproject_lead_id = $request['door_typeproject'];
        $leadsCustomer->number_of_door_lead_id = $request['number_of_door'];
        $leadsCustomer->gutters_meterial_lead_id = $request['gutters_meterial'];
        $leadsCustomer->paving_service_lead_id = $request['paving_service'];
        $leadsCustomer->paving_asphalt_type_id = $request['paving_asphalt_type'];
        $leadsCustomer->paving_loose_fill_type_id = $request['paving_loose_fill_type'];
        $leadsCustomer->paving_best_describes_priject_id = $request['paving_best_describes_priject'];
        $leadsCustomer->painting_service_lead_id = $request['painting_service'];
        $leadsCustomer->painting1_typeof_project_id = $request['painting1_typeof_project'];
        $leadsCustomer->painting1_stories_number_id = $request['painting1_stories'];
        $leadsCustomer->painting1_kindsof_surfaces_id = $request['painting1_kindsof_surfaces'];
        $leadsCustomer->painting2_rooms_number_id = $request['painting2_rooms_number'];
        $leadsCustomer->painting2_typeof_paint_id = $request['painting2_typeof_paint'];
        $leadsCustomer->painting3_each_feature_id = json_encode($request['painting3_each_feature']);
        $leadsCustomer->painting4_existing_roof_id = json_encode($request['painting4_existing_roof']);
        $leadsCustomer->painting5_kindof_texturing_id = json_encode($request['painting5_kindof_texturing']);
        $leadsCustomer->painting5_surfaces_textured_id = $request['painting5_surfaces_textured'];
        $leadsCustomer->historical_structure = $request['interior_historical'];
        $leadsCustomer->created_at = date('Y-m-d H:i:s');
        $leadsCustomer->lead_source = $request['lead_source'];
        $leadsCustomer->lead_source_text = $request['lead_source_text'];
        $leadsCustomer->trusted_form = $request['trusted_form'];
        $leadsCustomer->universal_leadid = $request['universal_leadid'];
        $leadsCustomer->google_ts = $request['google_ts'];
        $leadsCustomer->google_c = $request['google_c'];
        $leadsCustomer->google_g = $request['google_g'];
        $leadsCustomer->google_k = $request['google_k'];
        $leadsCustomer->token = $request['token'];
        $leadsCustomer->visitor_id = $request['visitor_id'];
        $leadsCustomer->converted = "1";
        $leadsCustomer->lead_details_text = $dataMassageForDB;

        //Shared fields Insurance
        $leadsCustomer->birthday = ( !empty($request['birthday']) ? date('Y-m-d', strtotime($request['birthday'])) : null );
        $leadsCustomer->genders = $request['genders'];
        $leadsCustomer->married = $request['married'];

        //Auto Insurance
        $leadsCustomer->VehicleYear = $request['VehicleYear'];
        $leadsCustomer->VehicleMake = $request['VehicleMake'];
        $leadsCustomer->car_model = $request['car_model'];
        $leadsCustomer->more_than_one_vehicle = $request['more_than_one_vehicle'];
        $leadsCustomer->driversNum = $request['driversNum'];
        $leadsCustomer->license = $request['license'];
        $leadsCustomer->InsuranceCarrier = $request['InsuranceCarrier'];
        $leadsCustomer->driver_experience = $request['driver_experience'];
        $leadsCustomer->number_of_tickets = $request['number_of_tickets'];
        $leadsCustomer->DUI_charges = $request['DUI_charges'];
        $leadsCustomer->SR_22_need = $request['SR_22_need'];
        $leadsCustomer->submodel = $request['submodel'];
        $leadsCustomer->coverage_type = $request['coverage_type'];
        $leadsCustomer->license_status = $request['license_status'];
        $leadsCustomer->license_state = $request['license_state'];
        $leadsCustomer->ticket_date = $request['ticket_date'];
        $leadsCustomer->violation_date = $request['violation_date'];
        $leadsCustomer->accident_date = $request['accident_date'];
        $leadsCustomer->claim_date = $request['claim_date'];
        $leadsCustomer->expiration_date = $request['expiration_date'];

        //home insurance
        $leadsCustomer->house_type = $request['house_type'];
        $leadsCustomer->Year_Built = $request['Year_Built'];
        $leadsCustomer->primary_residence = $request['primary_residence'];
        $leadsCustomer->new_purchase = $request['new_purchase'];
        $leadsCustomer->previous_insurance_within_last30 = $request['previous_insurance_within_last30'];
        $leadsCustomer->credit_rating = $request['credit_rating'];
        $leadsCustomer->previous_insurance_claims_last3yrs = $request['previous_insurance_claims_last3yrs'];

        //Life Insurance & Disability insurance
        $leadsCustomer->Height = $request['Height'];
        $leadsCustomer->weight = $request['weight'];
        $leadsCustomer->amount_coverage = $request['amount_coverage'];
        $leadsCustomer->military_personnel_status = $request['military_personnel_status'];
        $leadsCustomer->military_status = $request['military_status'];
        $leadsCustomer->service_branch = $request['service_branch'];

        //Business insurance
        $leadsCustomer->CommercialCoverage = $request['CommercialCoverage'];
        $leadsCustomer->company_benefits_quote = $request['company_benefits_quote'];
        $leadsCustomer->business_start_date = $request['business_start_date'];
        $leadsCustomer->estimated_annual_payroll = $request['estimated_annual_payroll'];
        $leadsCustomer->number_of_employees = $request['number_of_employees'];
        $leadsCustomer->coverage_start_month = $request['coverage_start_month'];
        $leadsCustomer->business_name = $request['business_name'];

        //Health Insurance & long term insurance
        $leadsCustomer->pregnancy = $request['pregnancy'];
        $leadsCustomer->tobacco_usage = $request['tobacco_usage'];
        $leadsCustomer->health_conditions = $request['health_conditions'];
        $leadsCustomer->number_of_people_in_household = $request['number_of_people_in_household'];
        $leadsCustomer->addPeople = $request['addPeople'];
        $leadsCustomer->annual_income = $request['annual_income'];

        return $leadsCustomer;
    }

    public function websitesAPIControllerAddLeadCustomer($leadCustomerStore, $request, $lead_source_id, $lead_source2, $dataMassageForDB, $tcpa_compliant, $tcpa_consent_text, $is_blocked_lead_info){
        $leadCustomerStore->visitor_leads_id = $request['visitor_leads_id'];
        $leadCustomerStore->lead_fname = $request['fname'];
        $leadCustomerStore->lead_lname = $request['lname'];
        $leadCustomerStore->lead_address = $request['street_name'];
        $leadCustomerStore->lead_email = $request['email'];
        $leadCustomerStore->lead_phone_number = $request['phone_number'];
        $leadCustomerStore->lead_numberOfItem = $request['numberofwindows'];
        $leadCustomerStore->lead_ownership = $request['ownership'];
        $leadCustomerStore->lead_type_service_id = $request['service_id'];
        $leadCustomerStore->lead_installing_id = $request['projectnature'];
        $leadCustomerStore->lead_priority_id = $request['priority'];
        $leadCustomerStore->lead_state_id = $request['state_id'];
        $leadCustomerStore->lead_city_id = $request['city_id'];
        $leadCustomerStore->lead_zipcode_id = $request['zipcode_id'];
        $leadCustomerStore->lead_county_id = $request['county_id'];
        $leadCustomerStore->lead_serverDomain = $request['serverDomain'];
        $leadCustomerStore->lead_timeInBrowseData = $request['timeInBrowseData'];
        $leadCustomerStore->lead_ipaddress = $request['ipaddress'];
        $leadCustomerStore->lead_FullUrl = $request['FullUrl'];
        $leadCustomerStore->lead_browser_name = $request['browser_name'];
        $leadCustomerStore->lead_aboutUserBrowser = $request['aboutUserBrowser'];
        $leadCustomerStore->lead_website = $request['lead_website'];
        $leadCustomerStore->traffic_source = $request['traffic_source'];
        $leadCustomerStore->lead_solor_solution_list_id = $request['solor_solution'];
        $leadCustomerStore->lead_solor_sun_expouser_list_id = $request['solor_sun'];
        $leadCustomerStore->lead_current_utility_provider_id = $request['utility_provider'];
        $leadCustomerStore->lead_avg_money_electicity_list_id = $request['avg_money'];
        $leadCustomerStore->property_type_campaign_id = $request['property_type_c'];
        $leadCustomerStore->lead_installation_preferences_id = $request['installation_preferences'];
        $leadCustomerStore->lead_have_item_before_it = $request['lead_have_item_before_it'];
        $leadCustomerStore->lead_type_of_flooring_id = $request['type_of_flooring'];
        $leadCustomerStore->lead_nature_flooring_project_id = $request['nature_flooring_project'];
        $leadCustomerStore->lead_walk_in_tub_id = $request['walk_in_tub'];
        $leadCustomerStore->lead_desired_featuers_id = $request['desired_featuers'];
        $leadCustomerStore->lead_type_of_roofing_id = $request['type_of_roofing'];
        $leadCustomerStore->lead_nature_of_roofing_id = $request['nature_of_roofing'];
        $leadCustomerStore->lead_property_type_roofing_id = $request['property_type_roofing'];
        $leadCustomerStore->type_of_siding_lead_id = $request['type_of_siding'];
        $leadCustomerStore->nature_of_siding_lead_id = $request['nature_of_siding'];
        $leadCustomerStore->service_kitchen_lead_id = $request['service_kitchen'];
        $leadCustomerStore->campaign_kitchen_r_a_walls_status = $request['removing_adding_walls'];
        $leadCustomerStore->campaign_bathroomtype_id = $request['bathroom_type'];
        $leadCustomerStore->stairs_type_lead_id = $request['stairs_type'];
        $leadCustomerStore->stairs_reason_lead_id = $request['stairs_reason'];
        $leadCustomerStore->furnance_type_lead_id = $request['furnance_type'];
        $leadCustomerStore->plumbing_service_list_id = $request['plumbing_service'];
        $leadCustomerStore->sunroom_service_lead_id = $request['sunroom_service'];
        $leadCustomerStore->handyman_ammount_work_id = $request['handyman_ammount'];
        $leadCustomerStore->countertops_service_lead_id = $request['countertops_service'];
        $leadCustomerStore->door_typeproject_lead_id = $request['door_typeproject'];
        $leadCustomerStore->number_of_door_lead_id = $request['number_of_door'];
        $leadCustomerStore->gutters_meterial_lead_id = $request['gutters_meterial'];
        $leadCustomerStore->paving_service_lead_id = $request['paving_service'];
        $leadCustomerStore->paving_asphalt_type_id = $request['paving_asphalt_type'];
        $leadCustomerStore->paving_loose_fill_type_id = $request['paving_loose_fill_type'];
        $leadCustomerStore->paving_best_describes_priject_id = $request['paving_best_describes_priject'];
        $leadCustomerStore->painting_service_lead_id = $request['painting_service'];
        $leadCustomerStore->painting1_typeof_project_id = $request['painting1_typeof_project'];
        $leadCustomerStore->painting1_stories_number_id = $request['painting1_stories'];
        $leadCustomerStore->painting1_kindsof_surfaces_id = $request['painting1_kindsof_surfaces'];
        $leadCustomerStore->painting2_rooms_number_id = $request['painting2_rooms_number'];
        $leadCustomerStore->painting2_typeof_paint_id = $request['painting2_typeof_paint'];
        $leadCustomerStore->painting3_each_feature_id =$request['painting3_each_feature'];
        $leadCustomerStore->painting4_existing_roof_id = $request['painting4_existing_roof'];
        $leadCustomerStore->painting5_kindof_texturing_id = $request['painting5_kindof_texturing'];
        $leadCustomerStore->painting5_surfaces_textured_id = $request['painting5_surfaces_textured'];
        $leadCustomerStore->historical_structure = $request['interior_historical'];
        $leadCustomerStore->created_at = date('Y-m-d H:i:s');
        $leadCustomerStore->lead_source = $lead_source_id;
        $leadCustomerStore->lead_source_text = $lead_source2;
        $leadCustomerStore->lead_details_text = $dataMassageForDB;
        $leadCustomerStore->trusted_form = $request['trusted_form'];
        $leadCustomerStore->universal_leadid = $request['universal_leadid'];
        $leadCustomerStore->google_ts = $request['tc'];
        $leadCustomerStore->google_c = $request['c'];
        $leadCustomerStore->google_g = $request['g'];
        $leadCustomerStore->google_k = $request['k'];
        $leadCustomerStore->token = $request['token'];
        $leadCustomerStore->visitor_id = $request['visitor_id'];
        $leadCustomerStore->flag = $request['fl'];
        $leadCustomerStore->pushnami_s1 = $request['s1'];
        $leadCustomerStore->pushnami_s2 = $request['s2'];
        $leadCustomerStore->pushnami_s3 = $request['s3'];
        $leadCustomerStore->google_gclid = $request['gclid'];
        $leadCustomerStore->is_multi_service = (!empty($request['is_multi_service']) ?  $request['is_multi_service'] : 0);
        $leadCustomerStore->is_sec_service = (!empty($request['is_sec_service']) ?  $request['is_sec_service'] : 0);
        $leadCustomerStore->tcpa_compliant = $tcpa_compliant;
        $leadCustomerStore->tcpa_consent_text = $tcpa_consent_text;

        if( strtolower($request['tc']) != 'raf1' && strtolower($request['tc']) != 'raf2' ) {
            if (!empty($is_sold_duplicate) || !empty($is_unsold_duplicate)) {
                $leadCustomerStore->is_duplicate_lead = 1;
            }
        }

        if( strtolower($request['tc']) == 'raf1' || strtolower($request['tc']) == 'raf2' ){
            $leadCustomerStore->status = 3;//ReAffiliate OR VerifiedLR
        } else if(!empty($request['fl']) && in_array(strtolower($request['fl']), ['1', '2'])){
            $leadCustomerStore->status = 4;//Flag
        } else if( $is_blocked_lead_info == 1 ) {
            $leadCustomerStore->status = 2;//Blocked
        }

        //Auto Insurance
        $leadCustomerStore->VehicleYear = $request['VehicleYear'];
        $leadCustomerStore->VehicleMake = $request['VehicleMake'];
        $leadCustomerStore->car_model = $request['car_model'];
        $leadCustomerStore->more_than_one_vehicle = $request['more_than_one_vehicle'];
        $leadCustomerStore->driversNum = $request['driversNum'];
        $leadCustomerStore->birthday = ( !empty($request['birthday']) ? date('Y-m-d', strtotime($request['birthday'])) : null );
        $leadCustomerStore->genders = $request['genders'];
        $leadCustomerStore->married = $request['married'];
        $leadCustomerStore->license = $request['license'];
        $leadCustomerStore->InsuranceCarrier = $request['InsuranceCarrier'];
        $leadCustomerStore->driver_experience = $request['driver_experience'];
        $leadCustomerStore->number_of_tickets = $request['number_of_tickets'];
        $leadCustomerStore->DUI_charges = $request['DUI_charges'];
        $leadCustomerStore->SR_22_need = $request['SR_22_need'];
        $leadCustomerStore->submodel = $request['submodel'];
        $leadCustomerStore->coverage_type = $request['coverage_type'];
        $leadCustomerStore->license_status = $request['license_status'];
        $leadCustomerStore->license_state = $request['license_state'];
        $leadCustomerStore->ticket_date = $request['ticket_date'];
        $leadCustomerStore->violation_date = $request['violation_date'];
        $leadCustomerStore->accident_date = $request['accident_date'];
        $leadCustomerStore->claim_date = $request['claim_date'];
        $leadCustomerStore->expiration_date = $request['expiration_date'];

        //home insurance
        $leadCustomerStore->house_type = $request['house_type'];
        $leadCustomerStore->Year_Built = $request['Year_Built'];
        $leadCustomerStore->primary_residence = $request['primary_residence'];
        $leadCustomerStore->new_purchase = $request['new_purchase'];
        $leadCustomerStore->previous_insurance_within_last30 = $request['previous_insurance_within_last30'];
        $leadCustomerStore->credit_rating = $request['credit_rating'];
        $leadCustomerStore->previous_insurance_claims_last3yrs = $request['previous_insurance_claims_last3yrs'];

        //Life Insurance & Disability insurance
        $leadCustomerStore->Height = $request['Height'];
        $leadCustomerStore->weight = $request['weight'];
        $leadCustomerStore->amount_coverage = $request['amount_coverage'];
        $leadCustomerStore->military_personnel_status = $request['military_personnel_status'];
        $leadCustomerStore->military_status = $request['military_status'];
        $leadCustomerStore->service_branch = $request['service_branch'];

        //Business insurance
        $leadCustomerStore->CommercialCoverage = $request['CommercialCoverage'];
        $leadCustomerStore->company_benefits_quote = $request['company_benefits_quote'];
        $leadCustomerStore->business_start_date = $request['business_start_date'];
        $leadCustomerStore->estimated_annual_payroll = $request['estimated_annual_payroll'];
        $leadCustomerStore->number_of_employees = $request['number_of_employees'];
        $leadCustomerStore->coverage_start_month = $request['coverage_start_month'];
        $leadCustomerStore->business_name = $request['business_name'];

        //Health Insurance & long term insurance
        $leadCustomerStore->pregnancy = $request['pregnancy'];
        $leadCustomerStore->tobacco_usage = $request['tobacco_usage'];
        $leadCustomerStore->health_conditions = $request['health_conditions'];
        $leadCustomerStore->number_of_people_in_household = $request['number_of_people_in_household'];
        $leadCustomerStore->addPeople = $request['addPeople'];
        $leadCustomerStore->annual_income = $request['annual_income'];

        //debt relief
        $leadCustomerStore->debt_amount = $request['debt_amount'];
        $leadCustomerStore->debt_type = json_encode($request['debt_type']);

        return $leadCustomerStore;
    }

    public function callToolsLeadsCustomerSave($leadsCustomer, $request, $service_id, $address, $traffic_source, $dataMassageForDB, $appointment_type){
        $leadsCustomer->lead_fname = $request['fname'];
        $leadsCustomer->lead_lname = $request['lname'];
        $leadsCustomer->lead_address = $request['street'];
        $leadsCustomer->lead_email = $request['email'];
        $leadsCustomer->lead_phone_number = $request['phone_number'];
        $leadsCustomer->lead_type_service_id = $service_id;
        $leadsCustomer->lead_state_id =  $address['state_id'];
        $leadsCustomer->lead_city_id = $address['city_id'];
        $leadsCustomer->lead_zipcode_id = $address['zipcode_id'];
        $leadsCustomer->lead_county_id = $address['county_id'];
        $leadsCustomer->lead_website = (!empty($request['website']) ? $request['website'] : 'CallTools');
        $leadsCustomer->lead_serverDomain = (!empty($request['website']) ? $request['website'] : 'CallTools');
        $leadsCustomer->lead_FullUrl = (!empty($request['website']) ? $request['website'] : 'CallTools');
        $leadsCustomer->traffic_source = $traffic_source;
        $leadsCustomer->universal_leadid = $request['universal_leadid'];
        $leadsCustomer->trusted_form = $request['trusted_form'];
        $leadsCustomer->lead_details_text = $dataMassageForDB;
        $leadsCustomer->created_at = date('Y-m-d H:i:s');

        if( $request['type'] == 1 ){
            $leadsCustomer->lead_source = 10;
            $leadsCustomer->lead_source_text = "CallTools Verified";
        }
        else if( $request['type'] == 2 ){
            $appointment_date = (empty($request['appointment_date']) ? "" : date('Y-m-d H:i:s', strtotime($request['appointment_date'])));
            $appointment_type = $request['appointment_type'];

            $leadsCustomer->lead_source = 11;
            $leadsCustomer->appointment_date = $appointment_date;
            $leadsCustomer->lead_source_text = "CallTools Appointment";
        }
        else {
            $leadsCustomer->lead_source = 12;
            $leadsCustomer->lead_source_text = "CallTools Transfer";
        }

        //Windows
        $leadsCustomer->lead_numberOfItem = $request['numberofwindows'];
        $leadsCustomer->lead_ownership = $request['ownership'];
        $leadsCustomer->lead_installing_id = $request['projectnature'];
        $leadsCustomer->lead_priority_id = $request['priority'];
        //Solar
        $leadsCustomer->lead_solor_solution_list_id = $request['solor_solution'];
        $leadsCustomer->lead_solor_sun_expouser_list_id = $request['solor_sun'];
        $leadsCustomer->lead_current_utility_provider_id = $request['utility_provider'];
        $leadsCustomer->lead_avg_money_electicity_list_id = $request['avg_money'];
        $leadsCustomer->property_type_campaign_id = $request['property_type_c'];
        //Home Security
        $leadsCustomer->lead_installation_preferences_id = $request['installation_preferences'];
        $leadsCustomer->lead_have_item_before_it = $request['lead_have_item_before_it'];
        //Flooring
        $leadsCustomer->lead_type_of_flooring_id = $request['type_of_flooring'];
        $leadsCustomer->lead_nature_flooring_project_id = $request['nature_flooring_project'];
        //Walk in tubs
        $leadsCustomer->lead_walk_in_tub_id = $request['walk_in_tub'];
        $leadsCustomer->lead_desired_featuers_id = $request['desired_featuers'];
        //Roofing
        $leadsCustomer->lead_type_of_roofing_id = $request['type_of_roofing'];
        $leadsCustomer->lead_nature_of_roofing_id = $request['nature_of_roofing'];
        $leadsCustomer->lead_property_type_roofing_id = $request['property_type_roofing'];
        //Siding
        $leadsCustomer->type_of_siding_lead_id = $request['type_of_siding'];
        $leadsCustomer->nature_of_siding_lead_id = $request['nature_of_siding'];
        //Kitchen
        $leadsCustomer->service_kitchen_lead_id = $request['service_kitchen'];
        $leadsCustomer->campaign_kitchen_r_a_walls_status = $request['removing_adding_walls'];
        //Bathroom
        $leadsCustomer->campaign_bathroomtype_id = $request['bathroom_type'];
        //stairs
        $leadsCustomer->stairs_type_lead_id = $request['stairs_type'];
        $leadsCustomer->stairs_reason_lead_id = $request['stairs_reason'];
        //Furnace & Boiler
        $leadsCustomer->furnance_type_lead_id = $request['furnance_type'];
        //Plumbing
        $leadsCustomer->plumbing_service_list_id = $request['plumbing_service'];
        //SunRoom
        $leadsCustomer->sunroom_service_lead_id = $request['sunroom_service'];
        //HandyMan
        $leadsCustomer->handyman_ammount_work_id = $request['handyman_ammount'];
        //Counter Tops
        $leadsCustomer->countertops_service_lead_id = $request['countertops_service'];
        //Doors
        $leadsCustomer->door_typeproject_lead_id = $request['door_typeproject'];
        $leadsCustomer->number_of_door_lead_id = $request['number_of_door'];
        //Gutters
        $leadsCustomer->gutters_meterial_lead_id = $request['gutters_meterial'];
        //Paving
        $leadsCustomer->paving_service_lead_id = $request['paving_service'];
        $leadsCustomer->paving_asphalt_type_id = $request['paving_asphalt_type'];
        $leadsCustomer->paving_loose_fill_type_id = $request['paving_loose_fill_type'];
        $leadsCustomer->paving_best_describes_priject_id = $request['paving_best_describes_priject'];
        //Painting
        $leadsCustomer->painting_service_lead_id = $request['painting_service'];
        $leadsCustomer->painting1_typeof_project_id = $request['painting1_typeof_project'];
        $leadsCustomer->painting1_stories_number_id = $request['painting1_stories'];
        $leadsCustomer->painting1_kindsof_surfaces_id = $request['painting1_kindsof_surfaces'];
        $leadsCustomer->painting2_rooms_number_id = $request['painting2_rooms_number'];
        $leadsCustomer->painting2_typeof_paint_id = $request['painting2_typeof_paint'];
        $leadsCustomer->painting3_each_feature_id =$request['painting3_each_feature'];
        $leadsCustomer->painting4_existing_roof_id = $request['painting4_existing_roof'];
        $leadsCustomer->painting5_kindof_texturing_id = $request['painting5_kindof_texturing'];
        $leadsCustomer->painting5_surfaces_textured_id = $request['painting5_surfaces_textured'];
        $leadsCustomer->historical_structure = $request['interior_historical'];
        //Auto Insurance
        $leadsCustomer->VehicleYear = $request['VehicleYear'];
        $leadsCustomer->VehicleMake = $request['VehicleMake'];
        $leadsCustomer->car_model = $request['car_model'];
        $leadsCustomer->more_than_one_vehicle = $request['more_than_one_vehicle'];
        $leadsCustomer->driversNum = $request['driversNum'];
        $leadsCustomer->birthday = ( !empty($request['birthday']) ? date('Y-m-d', strtotime($request['birthday'])) : null );
        $leadsCustomer->genders = $request['genders'];
        $leadsCustomer->married = $request['married'];
        $leadsCustomer->license = $request['license'];
        $leadsCustomer->InsuranceCarrier = $request['InsuranceCarrier'];
        $leadsCustomer->driver_experience = $request['driver_experience'];
        $leadsCustomer->number_of_tickets = $request['number_of_tickets'];
        $leadsCustomer->DUI_charges = $request['DUI_charges'];
        $leadsCustomer->SR_22_need = $request['SR_22_need'];
        $leadsCustomer->submodel = $request['submodel'];
        $leadsCustomer->coverage_type = $request['coverage_type'];
        $leadsCustomer->license_status = $request['license_status'];
        $leadsCustomer->license_state = $request['license_state'];
        $leadsCustomer->ticket_date = $request['ticket_date'];
        $leadsCustomer->violation_date = $request['violation_date'];
        $leadsCustomer->accident_date = $request['accident_date'];
        $leadsCustomer->claim_date = $request['claim_date'];
        $leadsCustomer->expiration_date = $request['expiration_date'];
        //home insurance
        $leadsCustomer->house_type = $request['house_type'];
        $leadsCustomer->Year_Built = $request['Year_Built'];
        $leadsCustomer->primary_residence = $request['primary_residence'];
        $leadsCustomer->new_purchase = $request['new_purchase'];
        $leadsCustomer->previous_insurance_within_last30 = $request['previous_insurance_within_last30'];
        $leadsCustomer->credit_rating = $request['credit_rating'];
        $leadsCustomer->previous_insurance_claims_last3yrs = $request['previous_insurance_claims_last3yrs'];
        //Life Insurance & Disability insurance
        $leadsCustomer->Height = $request['Height'];
        $leadsCustomer->weight = $request['weight'];
        $leadsCustomer->amount_coverage = $request['amount_coverage'];
        $leadsCustomer->military_personnel_status = $request['military_personnel_status'];
        $leadsCustomer->military_status = $request['military_status'];
        $leadsCustomer->service_branch = $request['service_branch'];
        //Business insurance
        $leadsCustomer->CommercialCoverage = $request['CommercialCoverage'];
        $leadsCustomer->company_benefits_quote = $request['company_benefits_quote'];
        $leadsCustomer->business_start_date = $request['business_start_date'];
        $leadsCustomer->estimated_annual_payroll = $request['estimated_annual_payroll'];
        $leadsCustomer->number_of_employees = $request['number_of_employees'];
        $leadsCustomer->coverage_start_month = $request['coverage_start_month'];
        $leadsCustomer->business_name = $request['business_name'];
        //Health Insurance & long term insurance
        $leadsCustomer->pregnancy = $request['pregnancy'];
        $leadsCustomer->tobacco_usage = $request['tobacco_usage'];
        $leadsCustomer->health_conditions = $request['health_conditions'];
        $leadsCustomer->number_of_people_in_household = $request['number_of_people_in_household'];
        $leadsCustomer->addPeople = $request['addPeople'];
        $leadsCustomer->annual_income = $request['annual_income'];

        $result = array(
            'leadsCustomer' => $leadsCustomer,
            'appointmentType' => $appointment_type
        );

        return $result;

    }

    public function saveNameLeadReview($leadReviewSave, $old_data, $request, $dataMassageForDB, $is_multi_service){
        $leadReviewSave->update([
            "lead_fname" => (!empty($old_data->lead_fname) ?  $old_data->lead_fname . ", " . $request['fname'] : $request['fname']),
            "lead_lname" => (!empty($old_data->lead_fname) ?  $old_data->lead_lname . ", " . $request['lname'] : $request['lname']),
            "lead_numberOfItem" => $request['numberofwindows'],
            "lead_ownership" => $request['ownership'],
            "lead_type_service_id" => $request['service_id'],
            "lead_installing_id" => $request['projectnature'],
            "lead_priority_id" => $request['priority'],
            "lead_solor_solution_list_id" => $request['solor_solution'],
            "lead_solor_sun_expouser_list_id" => $request['solor_sun'],
            "lead_current_utility_provider_id" => $request['utility_provider'],
            "lead_avg_money_electicity_list_id" => $request['avg_money'],
            "property_type_campaign_id" => $request['property_type_c'],
            "lead_installation_preferences_id" => $request['installation_preferences'],
            "lead_have_item_before_it" => $request['lead_have_item_before_it'],
            "lead_type_of_flooring_id" => $request['type_of_flooring'],
            "lead_nature_flooring_project_id" => $request['nature_flooring_project'],
            "lead_walk_in_tub_id" => $request['walk_in_tub'],
            "lead_desired_featuers_id" => $request['desired_featuers'],
            "lead_type_of_roofing_id" => $request['type_of_roofing'],
            "lead_nature_of_roofing_id" => $request['nature_of_roofing'],
            "lead_property_type_roofing_id" => $request['property_type_roofing'],
            "type_of_siding_lead_id" => $request['type_of_siding'],
            "nature_of_siding_lead_id" => $request['nature_of_siding'],
            "service_kitchen_lead_id" => $request['service_kitchen'],
            "campaign_kitchen_r_a_walls_status" => $request['removing_adding_walls'],
            "campaign_bathroomtype_id" => $request['bathroom_type'],
            "stairs_type_lead_id" => $request['stairs_type'],
            "stairs_reason_lead_id" => $request['stairs_reason'],
            "furnance_type_lead_id" => $request['furnance_type'],
            "furnance_type_b" => $request['furnance_type_b'],
            "furnance_type_f" => $request['furnance_type_f'],
            "plumbing_service_list_id" => $request['plumbing_service'],
            "sunroom_service_lead_id" => $request['sunroom_service'],
            "handyman_ammount_work_id" => $request['handyman_ammount'],
            "countertops_service_lead_id" => $request['countertops_service'],
            "door_typeproject_lead_id" => $request['door_typeproject'],
            "number_of_door_lead_id" => $request['number_of_door'],

            "gutters_meterial_lead_id" => $request['gutters_meterial'],
            "paving_service_lead_id" => $request['paving_service'],
            "paving_asphalt_type_id" => $request['paving_asphalt_type'],
            "paving_loose_fill_type_id" => $request['paving_loose_fill_type'],
            "paving_best_describes_priject_id" => $request['paving_best_describes_priject'],

            "painting_service_lead_id" => $request['painting_service'],
            "painting1_typeof_project_id" => $request['painting1_typeof_project'],
            "painting1_stories_number_id" => $request['painting1_stories'],
            "painting1_kindsof_surfaces_id" => $request['painting1_kindsof_surfaces'],
            "painting2_rooms_number_id" => $request['painting2_rooms_number'],
            "painting2_typeof_paint_id" => $request['painting2_typeof_paint'],
            "painting3_each_feature_id" => $request['painting3_each_feature'],
            "painting4_existing_roof_id" => $request['painting4_existing_roof'],
            "painting5_kindof_texturing_id" => $request['painting5_kindof_texturing'],
            "painting5_surfaces_textured_id" => $request['painting5_surfaces_textured'],
            "historical_structure" => $request['interior_historical'],

            //Shared fields Insurance
            "birthday" => ( !empty($request['birthday']) ? date('Y-m-d', strtotime($request['birthday'])) : null ),
            "genders" => $request['genders'],
            "married" => $request['married'],

            //Auto Insurance
            "VehicleYear" => $request['VehicleYear'],
            "VehicleMake" => $request['VehicleMake'],
            "car_model" => $request['car_model'],
            "more_than_one_vehicle" => $request['more_than_one_vehicle'],
            "driversNum" => $request['driversNum'],
            "license" => $request['license'],
            "InsuranceCarrier" => $request['InsuranceCarrier'],
            "driver_experience" => $request['driver_experience'],
            "number_of_tickets" => $request['number_of_tickets'],
            "DUI_charges" => $request['DUI_charges'],
            "SR_22_need" => $request['SR_22_need'],
            "submodel" => $request['submodel'],
            "coverage_type" => $request['coverage_type'],
            "license_status" => $request['license_status'],
            "license_state" => $request['license_state'],
            "ticket_date" => $request['ticket_date'],
            "violation_date" => $request['violation_date'],
            "accident_date" => $request['accident_date'],
            "claim_date" => $request['claim_date'],
            "expiration_date" => $request['expiration_date'],

            //home insurance
            'house_type' => $request['house_type'],
            'Year_Built' => $request['Year_Built'],
            'primary_residence' => $request['primary_residence'],
            'new_purchase' => $request['new_purchase'],
            'previous_insurance_within_last30' => $request['previous_insurance_within_last30'],
            'previous_insurance_claims_last3yrs' => $request['previous_insurance_claims_last3yrs'],
            'credit_rating' => $request['credit_rating'],

            //Life Insurance & Disability insurance
            'Height' => $request['Height'],
            'weight' => $request['weight'],
            'amount_coverage' => $request['amount_coverage'],
            'military_personnel_status' => $request['military_personnel_status'],
            'military_status' => $request['military_status'],
            'service_branch' => $request['service_branch'],

            //Business insurance
            'CommercialCoverage' => $request['CommercialCoverage'],
            'company_benefits_quote' => $request['company_benefits_quote'],
            'business_start_date' => $request['business_start_date'],
            'estimated_annual_payroll' => $request['estimated_annual_payroll'],
            'number_of_employees' => $request['number_of_employees'],
            'coverage_start_month' => $request['coverage_start_month'],
            'business_name' => $request['business_name'],

            //Health Insurance & long term insurance
            'pregnancy' => $request['pregnancy'],
            'tobacco_usage' => $request['tobacco_usage'],
            'health_conditions' => $request['health_conditions'],
            'number_of_people_in_household' => $request['number_of_people_in_household'],
            'addPeople' => $request['addPeople'],
            'annual_income' => $request['annual_income'],

            "trusted_form" => $request['xxTrustedFormCertUrl'],
            "created_at" => date('Y-m-d H:i:s'),
            "lead_details_text" => $dataMassageForDB,
            "lead_timeInBrowseData" => $request['timeInBrowseData'],
            "is_multi_service" => $is_multi_service
        ]);
    }

    public function pushLead($campaigns, $leadsCustomer, $ownershipArrayData, $property_typeArrayData, $projectnatureArrayData){
        switch ($leadsCustomer->lead_type_service_id) {
            case 1:
                //Windows Service
                $numberofwindowsReq = $leadsCustomer->lead_numberOfItem;

                $campaigns->join('campaigns_questions', function($join) use($numberofwindowsReq,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.number_of_window', "$numberofwindowsReq");

                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 2:
                //Solar Service
                $solaravgMony = $leadsCustomer->lead_avg_money_electicity_list_id;
                $solor_solution = $leadsCustomer->lead_solor_solution_list_id;
                $solor_sun = $leadsCustomer->lead_solor_sun_expouser_list_id;
                $utility_provider = strtolower($leadsCustomer->lead_current_utility_provider_id);

                $campaigns->join('campaigns_questions', function($join) use($solaravgMony,$solor_solution,$solor_sun,$utility_provider,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.solar_bill', "$solaravgMony")
                        ->whereJsonContains('campaigns_questions.solar_power_solution', "$solor_solution")
                        ->whereJsonContains('campaigns_questions.roof_shade', "$solor_sun");

                    if(!empty($utility_provider)){
                        $join->where(function ($query) use ($utility_provider) {
                            $query->whereJsonContains('campaigns_questions.utility_providers', "$utility_provider");
                            $query->orWhere('campaigns.is_utility_solar_filter', "0");
                        });
                    }

                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 3:
                //Home Security Service
                $installation_preferences_security = $leadsCustomer->lead_installation_preferences_id;
                $lead_have_item_before_it = $leadsCustomer->lead_have_item_before_it;

                $campaigns->join('campaigns_questions', function($join) use($installation_preferences_security,$lead_have_item_before_it,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.security_installing', "$installation_preferences_security")
                        ->whereJsonContains('campaigns_questions.existing_monitoring_system', "$lead_have_item_before_it");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 4:
                //Flooring Service
                $type_of_flooring_flooring = $leadsCustomer->lead_type_of_flooring_id;

                $campaigns->join('campaigns_questions', function($join) use($type_of_flooring_flooring,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.flooring_type', "$type_of_flooring_flooring");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 5:
                //Walk-in-tops Service
                $walk_in_tub = $leadsCustomer->lead_walk_in_tub_id;

                $campaigns->join('campaigns_questions', function($join) use($walk_in_tub,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.walk_in_tup_filter', "$walk_in_tub");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 6:
                //Roofing Service
                $type_of_roofing = $leadsCustomer->lead_type_of_roofing_id;

                $campaigns->join('campaigns_questions', function($join) use($type_of_roofing,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.roof_type', "$type_of_roofing");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 7:
                //Home Siding Service
                $type_of_siding = $leadsCustomer->type_of_siding_lead_id;

                $campaigns->join('campaigns_questions', function($join) use($type_of_siding,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.type_of_siding', "$type_of_siding");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 8:
                //Kitchen Service
                $service_kitchen = $leadsCustomer->service_kitchen_lead_id;
                $removing_adding_walls = $leadsCustomer->campaign_kitchen_r_a_walls_status;

                $campaigns->join('campaigns_questions', function($join) use($service_kitchen,$removing_adding_walls,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.kitchen_service', "$service_kitchen")
                        ->whereJsonContains('campaigns_questions.kitchen_walls', "$removing_adding_walls");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 9:
                //Bathroom Service
                $bathroom_type = $leadsCustomer->campaign_bathroomtype_id;

                $campaigns->join('campaigns_questions', function($join) use($bathroom_type,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.bathroom_type', "$bathroom_type");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 10:
                //StairLifts Service
                $stairs_type = $leadsCustomer->stairs_type_lead_id;
                $stairs_reason = $leadsCustomer->stairs_reason_lead_id;

                $campaigns->join('campaigns_questions', function($join) use($stairs_type,$stairs_reason,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.stairs_type', "$stairs_type")
                        ->whereJsonContains('campaigns_questions.stairs_reason', "$stairs_reason");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 11:
                //Furnace Services
                $furnance_type = $leadsCustomer->furnance_type_lead_id;

                $campaigns->join('campaigns_questions', function($join) use($furnance_type,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.furnace_type', "$furnance_type");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 12:
                //Boiler Services
                $furnance_type = $leadsCustomer->furnance_type_lead_id;

                $campaigns->join('campaigns_questions', function($join) use($furnance_type,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.type_of_heating', "$furnance_type");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 13:
                //Central A/C Services
                $campaigns->join('campaigns_questions', function($join) use($ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id');
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 14:
                //Cabinet Services
                $campaigns->join('campaigns_questions', function($join) use($ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id');
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 15:
                //plumbing Service
                $plumbing_service = $leadsCustomer->plumbing_service_list_id;

                $campaigns->join('campaigns_questions', function($join) use($plumbing_service,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.plumbing_service', "$plumbing_service");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 16:
                //Bathtubs Services
                $campaigns->join('campaigns_questions', function($join) use($ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id');
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 17:
                //SunRooms Service
                $sunroom_service = $leadsCustomer->sunroom_service_lead_id;

                $campaigns->join('campaigns_questions', function($join) use($sunroom_service,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.sunroom_service', "$sunroom_service");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 18:
                //Handyman Service
                $handyman_ammount = $leadsCustomer->handyman_ammount_work_id;

                $campaigns->join('campaigns_questions', function($join) use($handyman_ammount,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.handyman_amount_work', "$handyman_ammount");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 19:
                //CounterTops Service
                $countertops_service = $leadsCustomer->countertops_service_lead_id;

                $campaigns->join('campaigns_questions', function($join) use($countertops_service,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.counter_tops_service', "$countertops_service");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 20:
                //Doors Service
                $door_typeproject = $leadsCustomer->door_typeproject_lead_id;
                $number_of_door = $leadsCustomer->number_of_door_lead_id;

                $campaigns->join('campaigns_questions', function($join) use($door_typeproject,$number_of_door,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.door_type', "$door_typeproject")
                        ->whereJsonContains('campaigns_questions.number_of_door', "$number_of_door");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 21:
                //Gutter Service
                $gutters_meterial = $leadsCustomer->gutters_meterial_lead_id;

                $campaigns->join('campaigns_questions', function($join) use($gutters_meterial,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.gutters_material', "$gutters_meterial");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 22:
                //Paving Service
                $paving_service = $leadsCustomer->paving_service_lead_id;
                $paving_asphalt_type = $leadsCustomer->paving_asphalt_type_id;
                $paving_loose_fill_type = $leadsCustomer->paving_loose_fill_type_id;
                $paving_best_describes_priject = $leadsCustomer->paving_best_describes_priject_id;

                $campaigns->join('campaigns_questions', function($join) use($paving_service,$paving_asphalt_type,$paving_loose_fill_type,$paving_best_describes_priject,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.paving_service', "$paving_service");

                    switch ($paving_service){
                        case 1:
                            $join->whereJsonContains('campaigns_questions.paving_asphalt', "$paving_asphalt_type");
                            break;
                        case 3:
                            $join->whereJsonContains('campaigns_questions.paving_loose_fill', "$paving_loose_fill_type");
                            break;
                    }

                    $join->whereJsonContains('campaigns_questions.paving_best_desc', "$paving_best_describes_priject");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 23:
                //Painting Service
                $painting_service = $leadsCustomer->painting_service_lead_id;
                $painting1_typeof_project = $leadsCustomer->painting1_typeof_project_id;
                $painting1_stories = $leadsCustomer->painting1_stories_number_id;
                $painting1_kindsof_surfaces = $leadsCustomer->painting1_kindsof_surfaces_id;
                $interior_historical = $leadsCustomer->historical_structure;
                $painting2_rooms_number = $leadsCustomer->painting2_rooms_number_id;
                $painting2_typeof_paint = $leadsCustomer->painting2_typeof_paint_id;
                $painting3_each_feature = json_decode($leadsCustomer->painting3_each_feature_id, true);
                $painting4_existing_roof = json_decode($leadsCustomer->painting4_existing_roof_id, true);
                $painting5_surfaces_textured = $leadsCustomer->painting5_surfaces_textured_id;
                $painting5_kindof_texturing = json_decode($leadsCustomer->painting5_kindof_texturing_id, true);

                $campaigns->join('campaigns_questions', function($join) use($painting_service,$painting1_typeof_project,$painting1_stories,$painting1_kindsof_surfaces,$interior_historical,$painting2_rooms_number,$painting2_typeof_paint,$painting3_each_feature,$painting4_existing_roof,$painting5_surfaces_textured,$painting5_kindof_texturing, $ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.painting_service', "$painting_service");

                    switch ($painting_service) {
                        case 1:
                            $join->whereJsonContains('campaigns_questions.painting_project_type', "$painting1_typeof_project")
                                ->whereJsonContains('campaigns_questions.painting_stories_number', "$painting1_stories")
                                ->whereJsonContains('campaigns_questions.painting_kinds_of_surfaces', "$painting1_kindsof_surfaces")
                                ->whereJsonContains('campaigns_questions.painting_historical_structure', "$interior_historical");
                            break;
                        case 2:
                            $join->whereJsonContains('campaigns_questions.painting_type_of_paint', "$painting2_typeof_paint")
                                ->whereJsonContains('campaigns_questions.painting_rooms_number', "$painting2_rooms_number")
                                ->whereJsonContains('campaigns_questions.painting_historical_structure', "$interior_historical");
                            break;
                        case 3:
                            $join->whereJsonContains('campaigns_questions.painting_historical_structure', "$interior_historical");
                            $join->where(function ($query) use ($painting3_each_feature) {
                                foreach($painting3_each_feature as $each_feature) {
                                    $query->OrWhereJsonContains('campaigns_questions.painting_each_feature', "$each_feature");
                                }
                            });
                            break;
                        case 4:
                            $join->whereJsonContains('campaigns_questions.painting_historical_structure', "$interior_historical")
                                ->whereJsonContains('campaigns_questions.painting_stories_number', "$painting1_stories");
                            $join->where(function ($query) use ($painting4_existing_roof) {
                                foreach($painting4_existing_roof as $existing_roof) {
                                    $query->OrWhereJsonContains('campaigns_questions.painting_existing_roof', "$existing_roof");
                                }
                            });
                            break;
                        case 5:
                            $join->whereJsonContains('campaigns_questions.painting_surfaces_textured', "$painting5_surfaces_textured");
                            $join->where(function ($query) use ($painting5_kindof_texturing) {
                                foreach($painting5_kindof_texturing as $kindof_texturing) {
                                    $query->OrWhereJsonContains('campaigns_questions.painting_kind_of_texturing', "$kindof_texturing");
                                }
                            });
                            break;
                    }

                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            case 24:
                //Auto Insurance
                $license = $leadsCustomer->license;
                $driver_experience = $leadsCustomer->driver_experience;
                $submodel = $leadsCustomer->submodel;
                $coverage_type = $leadsCustomer->coverage_type;
                $license_status = $leadsCustomer->license_status;
                $license_state = $leadsCustomer->license_state;

                $campaigns->join('campaigns_questions', function($join) use($license,$driver_experience,$submodel,$coverage_type,$license_status,$license_state,$ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id')
                        ->whereJsonContains('campaigns_questions.auto_insurance_license', "$license")
                        ->whereJsonContains('campaigns_questions.driver_experience', "$driver_experience")
                        ->whereJsonContains('campaigns_questions.submodel', "$submodel")
                        ->whereJsonContains('campaigns_questions.coverage_type', "$coverage_type")
                        ->whereJsonContains('campaigns_questions.license_status', "$license_status")
                        ->whereJsonContains('campaigns_questions.license_state', "$license_state");
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
            default:
                $campaigns->join('campaigns_questions', function($join) use($ownershipArrayData,$property_typeArrayData,$projectnatureArrayData)
                {
                    $join->on('campaigns_questions.campaign_id', '=', 'campaigns.campaign_id');
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($projectnatureArrayData as $project_nature_data) {
                            $query->OrWhereJsonContains('campaigns_questions.installing', "$project_nature_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($property_typeArrayData as $property_type_data) {
                            $query->OrWhereJsonContains('campaigns_questions.property_type', "$property_type_data");
                        }
                    });
                    $join->where(function ($query) use ($projectnatureArrayData,$property_typeArrayData,$ownershipArrayData) {
                        foreach($ownershipArrayData as $owner_ship_data) {
                            $query->OrWhereJsonContains('campaigns_questions.home_owned', "$owner_ship_data");
                        }
                    });
                });
                break;
        }

        return $campaigns;
    }

}
