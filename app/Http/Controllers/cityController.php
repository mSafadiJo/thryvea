<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cityController extends Controller
{
    public function getAllWhithNoFillter(Request $request){
        $cities = DB::table('cities')->get(['city_id', 'city_name']);

        return response()->json($cities, 200);
    }

    public function getAllWhithFillter(Request $request){
        $stateVal = $request->stateVal;

        $defaultcityCamp = $request->defaultcityCamp;
        $cityCamp = $request->cityCamp;
        if( !empty($cityCamp) ){
            $cityCamp = implode(',',$cityCamp);
        }
        $defCities = $cityCamp . ',' . $defaultcityCamp;
        if( !empty($defCities) || $defCities != '' ) {
            $defCities = explode(',', $defCities);
        }

        $cities = DB::table('cities')->whereIn('state_id', $stateVal)->get(['city_id', 'city_name']);

        $options = '';
        $options = '<optgroup label="Cities">';
        if( count($cities) > 0 ) {
            foreach ($cities as $city) {
                if( !empty($defCities) ){
                    if( in_array($city->city_id, $defCities) ){
                        $options .= "<option value='" . $city->city_id . "' selected>" . $city->city_name . "</option>";
                    } else {
                        $options .= "<option value='" . $city->city_id . "' >" . $city->city_name . "</option>";
                    }
                } else {
                    $options .= "<option value='" . $city->city_id . "' >" . $city->city_name . "</option>";
                }

            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= "</optgroup>";
        $response = array(
            'select' => $options
        );
        return response()->json($response, 200);
    }

    public function getAllWhithNoFillterCounties(Request $request){
        $counties = DB::table('counties')->get(['county_id', 'county_name']);
        return response()->json($counties, 200);
    }

    public function getAllWhithFillterCounties(Request $request){
        $stateVal = $request->stateVal;
        $defaultcountyCamp = $request->defaultcountyCamp;
        $countyCamp = $request->countyCamp;
        if( !empty($countyCamp) ){
            $countyCamp = implode(',',$countyCamp);
        }
        $defCounties = $countyCamp . ',' . $defaultcountyCamp;
        if( !empty($defCounties) || $defCounties != '' ) {
            $defCounties = explode(',', $defCounties);
        }
        $counties = DB::table('counties')->whereIn('state_id', $stateVal)->get(['county_id', 'county_name']);

        $options = '';
        $options = '<optgroup label="Counties">';
        if( count($counties) > 0 ) {
            foreach ($counties as $county) {
                if( !empty($defCounties) ){
                    if( in_array($county->county_id, $defCounties) ){
                        $options .= "<option value='" . $county->county_id . "' selected>" . $county->county_name . "</option>";
                    } else {
                        $options .= "<option value='" . $county->county_id . "' >" . $county->county_name . "</option>";
                    }
                } else {
                    $options .= "<option value='" . $county->county_id . "' >" . $county->county_name . "</option>";
                }
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= "</optgroup>";
        $response = array(
            'select' => $options
        );
        return response()->json($response, 200);
    }

    public function getAll(Request $request){
        $stateid = $request->stateid;
        $cities = DB::table('cities')->where('state_id', $stateid)->get();

        $options = '';
        $options = '<optgroup label="Cities">';
        if( count($cities) > 0 ) {
            foreach ($cities as $city) {
                $options .= "<option value='" . $city->city_id . "' >" . $city->city_name . "</option>";
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= "</optgroup>";
        $response = array(
            'select' => $options
        );
        return response()->json($response, 200);
    }

    public function getAllWithSelect(Request $request){
        $stateid = $request->stateid;
        $cityID = $request->cityId;
        $cities = DB::table('cities')->where('state_id', $stateid)->get();

        $options = '';
        $options = '<optgroup label="Cities">';
        if( count($cities) > 0 ) {
            foreach ($cities as $city) {
                if ($city->city_id == $cityID) {
                    $options .= "<option value='" . $city->city_id . "' selected >" . $city->city_name . "</option>";
                } else {
                    $options .= "<option value='" . $city->city_id . "' >" . $city->city_name . "</option>";
                }
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= " </optgroup>";
        $response = array(
            'select' => $options,
            'state_id' => $stateid
        );
        return response()->json($response, 200);
    }

    //////////// for lead review /////////////////

    public function getAllCounties(Request $request){
        $stateVal = $request->stateVal;
        $countyID = $request->countyID;

        $counties = DB::table('counties')->where('state_id', $stateVal)->get(['county_id', 'county_name']);

        $options = '';
        $options = '<optgroup label="Counties">';
        if( count($counties) > 0 ) {

            foreach ($counties as $county) {
                $selected='';
                $county_name=$county->county_id;
                if($county_name == $countyID){
                    $selected = 'selected' ;
                }
                $options .= "<option value='" . $county->county_id . "' $selected  .>" . $county->county_name . "</option>";
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= "</optgroup>";
        $response = array(
            'select' => $options
        );

        return response()->json($response, 200);
    }

    public function getCityReview(Request $request){
        $stateVal = $request->stateid;
        $cityID = $request->cityID;

        $cities = DB::table('cities')->where('state_id', $stateVal)->get(['city_id', 'city_name']);

        $options = '';
        $options = '<optgroup label="Cities">';
        if( count($cities) > 0 ) {
            foreach ($cities as $cities) {
                $selected='';
                $cities_name= $cities->city_id;
                if($cities_name==$cityID){
                    $selected = 'selected' ;
                }
                $options .= "<option value='" . $cities->city_id . "' $selected  .>" . $cities->city_name . "</option>";
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= "</optgroup>";
        $response = array(
            'select' => $options
        );
        return response()->json($response, 200);
    }

    public function citySearchResult(Request $request){
        $text = $request->text;
        $cityResult = DB::table('cities')->where('city_name', 'Like', "%".$text."%")->limit(100)->get(['city_name AS text', 'city_id AS id']);

        $data = array(
            "results" => $cityResult,
            "total" => count($cityResult)
        );

        return $data;

    }

    public function countiesSearchResult(Request $request){
        $text = $request->text;
        $countyResult = DB::table('counties')->where('county_name', 'Like', "%".$text."%")->limit(100)->get(['county_name AS text', 'county_id AS id']);

        $data = array(
            "results" => $countyResult,
            "total" => count($countyResult)
        );

        return $data;

    }

}
