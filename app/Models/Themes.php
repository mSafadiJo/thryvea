<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Themes extends Model
{
    public $table = "themes";


    public function CountAllTheme(){
        $Themes = DB::table('themes')->get();
        $ThemesCount = count($Themes);
        return $ThemesCount ;
    }

    public function featchAllThemes(){
        $themes = DB::table('themes')->get()->All();
        return $themes ;
    }

    //many to many relationship with table service
    public function service(){
        return $this -> belongsToMany('App\Service_Campaign','themes_services','theme_id','service_id','id','service_campaign_id');
    }

    protected $fillable = [
        'id','theme_name','theme_img','status','created_at','updated_at','image_real_name','service_type'
    ];


}
