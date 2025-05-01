<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Services\ApiMain;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '25-0';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function index(){
        return view('Admin.Setting.Payments');
    }

    public function store(Request $request){
        $type = $request->type;
        $type_auto = $request->type_auto;
        $main_api_file = new ApiMain();
        $main_api_file->overWriteEnvFile("PAYMENT_METHODS", $type);
        $main_api_file->overWriteEnvFile("AUTO_PAYMENT_METHODS", $type_auto);
        \Session::put("success", "You've changed the merchant account to");
        return Back();
    }


}
