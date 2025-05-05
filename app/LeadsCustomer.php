<?php

namespace App;

use App\Services\APIValidations;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class LeadsCustomer extends Model
{
    protected $table = 'leads_customers'; // Optional if using default naming
    protected $primaryKey = 'lead_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public function state(): BelongsTo {
        return $this->belongsTo(State::class, 'lead_state_id', 'state_id');
    }

    public function county(): BelongsTo {
        return $this->belongsTo(County::class, 'lead_county_id', 'county_id');
    }

    public function city(): BelongsTo {
        return $this->belongsTo(City::class, 'lead_city_id', 'city_id');
    }

    public function service(): BelongsTo {
        return $this->belongsTo(Service_Campaign::class, 'lead_type_service_id', 'service_campaign_id');
    }

    public function zipcode(): BelongsTo {
        return $this->belongsTo(ZipCodesList::class, 'lead_zipcode_id', 'zip_code_list_id');
    }

    public function CountAllLead(){
        $AllLead = DB::table('leads_customers')->get();
        $countAllLead = count($AllLead);
        return $countAllLead ;
    }


    public function fullName() {
        return "{$this->lead_fname} {$this->lead_fname}";
    }

    public function addressHumanized() {
        return "Address: {$this->lead_address}, City: {$this->city->city_name[0]}, State: {$this->state->name}, Zipcode: {$this->zipcode->zip_code_list}";
    }

    public function addressMapping() {
        return array(
            "zipcode_id" => $this->lead_zipcode_id,
            "city_id" => $this->lead_city_id,
            "county_id" => $this->lead_county_id,
            "state_id" => $this->lead_state_id,
        );
    }

    public function meta_data($value = "") {
        $api_validations = new APIValidations();

        try {
            $meta_data = $api_validations->check_questions_ids_push_array($this);
            return $meta_data[$value];
        } catch (\Exception $e) {
            return $api_validations->check_questions_ids_push_array($this);
        }
    }


    public function dataAsUnifiedJson() {
        $marketing_platform = DB::table('marketing_platforms')->select('id','lead_source', 'name')
            ->where('name', '')->first();
        $source_api = $marketing_platform->lead_source ?? 'ADMS20';
        $source = $marketing_platform->name ?? 'SEO';
        $source_id = $marketing_platform->id ?? 1;
        $meta_data = $this->meta_data();

        $based_json = array(
            'leadCustomer_id' => $this->lead_id,
            'name' => '', // To be assigned later.
            'leadName' => $this->fullName(),
            'LeadEmail' => $this->lead_email,
            'LeadPhone' => $this->lead_phone_number,
            'Address' => $this->addressHumanized(),
            'LeadService' => $this->service->service_campaign_name,
            'data' => $meta_data['Leaddatadetails'],
            'trusted_form' => $this->trusted_form,
            'service_id' => $this->service->service_campaign_id,
            'street' => $this->lead_address,
            'City' => $this->city->city_name[1] ?? '',
            'State' => $this->state->state_name,
            'state_code' => $this->state->state_code,
            'Zipcode' => $this->zipcode->zip_code_list,
            'county' => $this->county->county_name[0] ?? '',
            'first_name' => $this->lead_fname,
            'last_name' => $this->lead_lname,
            'UserAgent' => $this->lead_aboutUserBrowser,
            'OriginalURL' => $this->lead_serverDomain,
            'OriginalURL2' => "https://www.{$this->lead_serverDomain}",
            'SessionLength' => $this->lead_timeInBrowseData,
            'IPAddress' => $this->lead_ipaddress,
            'LeadId' => $this->universal_leadid,
            'browser_name' => $this->lead_browser_name,
            'tcpa_compliant' => $this->tcpa_compliant,
            'TCPAText' => $this->tcpa_consent_text,
            'lead_source' => $source_api,
            'lead_source_name' => $source,
            'lead_source_id' => $source_id,
            'traffic_source' => $this->traffic_source,
            'google_ts' => $this->google_ts,
            'is_multi_service' => $this->is_multi_service,
            'is_sec_service' => $this->is_sec_service,
            'appointment_date' => date('Y-m-d H:i:s'),
            'appointment_type' => '',
            "is_lead_review" => 0,
            "created_at" => $this->created_at,
        );

        return array_merge($based_json, $meta_data, $meta_data['LeaddataIDs']);
    }

    protected $fillable = [
        'lead_fname', 'lead_lname', 'lead_address', 'lead_email', 'lead_phone_number',
        'lead_numberOfItem', 'lead_ownership', 'lead_type_service_id', 'lead_installing_id',
        'lead_priority_id', 'lead_state_id', 'lead_county_id', 'lead_city_id', 'lead_zipcode_id', 'lead_website',
        'lead_solor_solution_list_id', 'lead_solor_sun_expouser_list_id',
        'lead_current_utility_provider_id', 'lead_avg_money_electicity_list_id', 'property_type_campaign_id',
        'lead_installation_preferences_id', 'lead_have_item_before_it',
        'lead_type_of_flooring_id', 'lead_nature_flooring_project_id', 'lead_walk_in_tub_id',
        'lead_desired_featuers_id', 'lead_type_of_roofing_id', 'lead_nature_of_roofing_id',
        'lead_property_type_roofing_id', 'type_of_siding_lead_id', 'nature_of_siding_lead_id',
        'is_duplicate_lead', 'lead_source_text', 'lead_details_text', 'status', 'vendor_id', 'lead_ping_id',
        'google_ts','google_c','google_g','google_k', 'is_verified_phone', 'is_seen_lead', 'lead_id', 'response_data',
        'hash_legs_sold', 'pushnami_s1', 'pushnami_s2', 'pushnami_s3', 'google_gclid',
        'band_width_order_id', 'band_width_new_number', 'band_width_connect',
        'VehicleYear', 'VehicleMake', 'car_model', 'more_than_one_vehicle', 'driversNum', 'birthday', 'genders',
        'married', 'license', 'InsuranceCarrier', 'driver_experience', 'number_of_tickets', 'DUI_charges', 'SR_22_need',
        'ping_price', 'ping_original_price', 'ping_response_data', 'ping_campaign_id_arr', 'ping_lead_bid_type',
        'debt_amount', 'debt_type'
    ];
}
