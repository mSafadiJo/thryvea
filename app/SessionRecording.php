<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SessionRecording extends Model
{
    protected $connection = 'mysql2';

    public $table = "recording";

    public function getEvent(){
        $Event = DB::connection('mysql2')->table('recording')->select("event")->where('jornaya_id', 'DD407ABC-C214-BCE2-1773-B549766905EA')->get();

    }

//    public function featchAllThemes(){
//        $themes = DB::table('themes')->get()->All();
//        return $themes ;
//    }
//
//    //many to many relationship with table service
//    public function service(){
//        return $this -> belongsToMany('App\Service_Campaign','themes_services','theme_id','service_id','id','service_campaign_id');
//    }

    protected $fillable = [
        'id','jornaya_id','visitor_id','ts_name','domain_name','event','created_at','updated_at'
    ];


}
