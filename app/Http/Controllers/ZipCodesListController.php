<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZipCodesListController extends Controller
{

    public function getAllWhithNoFillterZipCode(Request $request){
        $zip_codes_lists = DB::table('zip_codes_lists')->get(['zip_code_list_id', 'zip_code_list']);

        return response()->json($zip_codes_lists, 200);
    }

    public function getAllWhithFillterZipCode(Request $request){
        $stateVal = $request->stateVal;

        $defaultzipcodeCamp = $request->defaultzipcodeCamp;
        $zipcodeCamp = $request->zipcodeCamp;
        if( !empty($zipcodeCamp) ){
            $zipcodeCamp = implode(',',$zipcodeCamp);
        }
        $defzipcode = $zipcodeCamp . ',' . $defaultzipcodeCamp;
        if( !empty($defzipcode) || $defzipcode != '' ) {
            $defzipcode = explode(',', $defzipcode);
        }

        $zip_codes_lists = DB::table('zip_codes_lists')->whereIn('state_id', $stateVal)->get(['zip_code_list_id', 'zip_code_list']);

        $options = '';
        $options = '<optgroup label="ZipCodes">';
        if( count($zip_codes_lists) > 0 ) {
            foreach ($zip_codes_lists as $zip_code) {
                if( !empty($defzipcode) ){
                    if( in_array($zip_code->zip_code_list_id, $defzipcode) ){
                        $options .= "<option value='" . $zip_code->zip_code_list_id . "' selected>" . $zip_code->zip_code_list . "</option>";
                    } else {
                        $options .= "<option value='" . $zip_code->zip_code_list_id . "' >" . $zip_code->zip_code_list . "</option>";
                    }
                } else {
                    $options .= "<option value='" . $zip_code->zip_code_list_id . "' >" . $zip_code->zip_code_list . "</option>";
                }
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= " </optgroup>";
        $response = array(
            'select' => $options
        );

        return response()->json($response, 200);
    }

    public function getAll(Request $request){
        $cityid = $request->cityid;
        $zip_codes = DB::table('zip_codes_lists')->where('city_id', $cityid)->get();

        $options = '';
        $options = '<optgroup label="ZipCodes">';
        if( count($zip_codes) > 0 ) {
            foreach ($zip_codes as $zip_code) {
                $options .= "<option value='" . $zip_code->zip_code_list_id . "' >" . $zip_code->zip_code_list . "</option>";
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= " </optgroup>";
        $response = array(
            'select' => $options
        );
        return response()->json($response, 200);
    }

    public function getAllWithSelect(Request $request){
        $stateid = $request->stateid;
        $cityID = $request->cityId;
        $zipcodeid = $request->zipcodeid;
        $zip_codes = DB::table('zip_codes_lists')->where('city_id', $cityID)->get();

        $options = '';
        $options = '<optgroup label="ZipCodes">';
        if( count($zip_codes) > 0 ) {
            foreach ($zip_codes as $zip_code) {
                if ($zip_code->zip_code_list_id == $zipcodeid) {
                    $options .= "<option value='" . $zip_code->zip_code_list_id . "' selected >" . $zip_code->zip_code_list . "</option>";
                } else {
                    $options .= "<option value='" . $zip_code->zip_code_list_id . "' >" . $zip_code->zip_code_list . "</option>";
                }
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= " </optgroup>";
        $response = array(
            'select' => $options,
            'zipcodeid' => $zipcodeid,
            'state_id' => $stateid
        );
        return response()->json($response, 200);
    }




    /////////// for lead review ////////////
    public function getAllZipCodeReview(Request $request){
        $cityid = $request->cityid;
        $zipcodeid = $request->zipcodeid;


        $zip_codes = DB::table('zip_codes_lists')->where('city_id',$cityid )->get(['zip_code_list_id', 'zip_code_list']);




        $options = '';
        $options = '<optgroup label="ZipCodes">';
        if( count($zip_codes) > 0 ) {
            foreach ($zip_codes as $zip_code) {
                $selected='';
                $zipCode_name= $zip_code->zip_code_list;
                if($zipCode_name == $zipcodeid ){
                    $selected = 'selected' ;
                }
                $options .= "<option value='" . $zip_code->zip_code_list_id . "' . $selected .>" . $zip_code->zip_code_list . "</option>";
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $options .= " </optgroup>";
        $response = array(
            'select' => $options
        );
        return response()->json($response, 200);
    }


    public function zipSearchResult(Request $request){
        $text = $request->text;
        $zipResult = DB::table('zip_codes_lists')->where('zip_code_list', 'Like', "%".$text."%")->limit(10)->get(['zip_code_list AS text', 'zip_code_list_id AS id']);
        $data = array(
            "results" => $zipResult,
            "total" => count($zipResult)
        );

        return $data;

    }

}
