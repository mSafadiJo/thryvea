<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Domains extends Model
{
    public $table = "domains";


    public function CountAllDomains(){
        $domains = DB::table('domains')->get();
        $domainsCount = count($domains);
        return $domainsCount ;
    }

    public function featchAllDomains(){
        $domains = DB::table('domains')->get()->All();
        return $domains ;
    }

    protected $fillable = [
        'id','domain_name','service_id','Service_type','theme_id','status','created_at','updated_at'
    ];
}
