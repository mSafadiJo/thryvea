<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '25-0';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $sources = Cache::get('sourcesBotUnsoldLead', []);
            $sourcesString = !empty($sources) ? implode(' , ', $sources) : '';

            return view('Admin.Setting.index', compact('sourcesString'));

        } else {

            $sources = array_values(
                array_filter(
                    array_map('trim', explode(',', $request->SourcesList))
                )
            );

            Cache::forever('sourcesBotUnsoldLead', $sources);

            $sourcesString = implode(' , ', $sources);

            return view('Admin.Setting.index', compact('sourcesString'))
                ->with('success', 'Sources updated!');
        }
    }


}
