<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{

    public function getAllWhithNoFillter(Request $request){
        $states = DB::table('states')->get(['state_id', 'state_name']);

        return response()->json($states, 200);
    }

    public function getAll(){
        $states = State::All();

        $options = '';
        if( count($states) > 0 ){
            foreach( $states as $state ){
                $options .= "<option value='" . $state->state_id ."' >" . $state->state_code ."</option>";
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        foreach( $states as $state ){
            $options .= "<option value='" . $state->state_id ."' >" . $state->state_code ."</option>";
        }
        $response = array(
            'select' => $options
        );

        return response()->json($response, 200);
    }

    public function getAllWithSelect(Request $request){
        $stateid = $request->stateid;
        $states = DB::table('states')->All();

        $options = '';
        if( count($states) > 0 ) {
            foreach ($states as $state) {
                if ($state->id == $stateid) {
                    $options .= "<option value='" . $state->state_id . "' selected >" . $state->state_code . "</option>";
                } else {
                    $options .= "<option value='" . $state->state_id . "' >" . $state->state_code . "</option>";
                }
            }
        } else {
            $options .= "<option value='' >NA</option>";
        }
        $response = array(
            'select' => $options
        );
        return response()->json($response, 200);
    }
}
