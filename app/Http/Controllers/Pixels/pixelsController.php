<?php

namespace App\Http\Controllers\Pixels;

use App\Models\Themes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class pixelsController extends Controller
{
    public function index(){
        $pixels = DB::table('pixels')
            ->join('domains', 'pixels.domain_id', '=', 'domains.id')
            ->select(['pixels.*', 'domains.domain_name'])
            ->get();
        return view('SuperAdmin.Pixels.pixels', compact(['pixels']));
    }
}
