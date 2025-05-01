<?php

namespace App\Http\Controllers\Themes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service_Campaign;
use App\AccessLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Themes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ThemesController extends Controller
{
    public function ListThemes(){
        $themess = collect();
        $theme_services = collect(collect(DB::table('themes_services')
            ->join('service__campaigns', 'themes_services.service_id', '=' ,'service__campaigns.service_campaign_id')
            ->select(['themes_services.theme_id', 'service__campaigns.service_campaign_name', 'service__campaigns.service_campaign_id as id'])
            ->get())->values())->groupBy('theme_id');
        //return collect($theme_services->groupBy('theme_id'));

//        return $theme_services;
        $themes = DB::table('themes')
            ->select(['id', 'theme_name', 'service_type', 'theme_img', 'status', 'created_at'])
            ->get();
        return view('SuperAdmin.Themes.ListThemes', compact(['themes', 'theme_services']));
    }

    public function Edit_Theme($id){
        $themes = DB::table('themes')->where('id', $id)->first();
        $allService = new Service_Campaign();
        $fetchAllService= $allService->featchAllService();

        $ThemesService = Themes::with('service')->find($id);
        $getThemesService = $ThemesService->service;

        return view('SuperAdmin.Themes.EditTheme')
            ->with('Themes', $themes)
            ->with('fetchAllService',$fetchAllService)
            ->with('getThemesService',$getThemesService);
    }

    public function updateThemeUserAdmin(Request $request){
        /**
         * if the service id is one (1) it will be single service.
         *  2 is multi services.
         *  3. is ignostic services.
         *  4. image uploaded on public/images/themes.
         */
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required|string',
            'service_type' => 'required',
        ]);
        if(empty($request->file('Theme_img'))){
            $image_real_name = $request['oldrealImage'];
            $theme_img = $request['oldImage'];
        }else{
            $Image = $request->file('Theme_img');
            $extension = $Image->getClientOriginalExtension();
            $image_real_name = $Image->getClientOriginalName();
            $theme_img = "/images/themes/".$Image->getFilename().'.'.$extension;
            $request->Theme_img->move(public_path('images/themes'), $theme_img);
        }
        $updateTheme = DB::table('themes')
            ->where('id', $request['id'])
            ->update([
                'theme_name' => $request['name'],
                'service_type' => $request['service_type'],
                'image_real_name' => $image_real_name,
                'theme_img' => $theme_img
            ]);

        $themes = Themes::find($request['id']);
        if (!$themes)
            return abort('404');
        $themes->service()->sync($request['service_id']);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request['id'],
            'section_name' => $request['name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'ThemeTemplates',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('AllThemes');
    }

    public function updateStatusThemesUserAdmin(Request $request){
        $updateTheme = DB::table('themes')
            ->where('id', $request['id'])
            ->update([
                'status' => $request['status']
            ]);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $request['id'],
            'section_name' => $request['name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'ThemeTemplates',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return 1;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *  4. image uploaded on public/images/themes.
     */
    public function AddForm(){
        ///// to fetch all service from service model///////
        $allService = new Service_Campaign();
        $fetchAllService= $allService->featchAllService();
        return view('SuperAdmin.Themes.AddTheme')->with('fetchAllService',$fetchAllService);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Validation\ValidationException
     * 4. image uploaded on public/images/themes.
     */
    public function Store(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'service_type' => 'required',
        ]);
        $Image = $request->file('Theme_img');
        $extension = $Image->getClientOriginalExtension();
        $theme_img = '/images/themes/'.$Image->getFilename().'.'.$extension;
        $request->Theme_img->move(public_path('images/themes'), $theme_img);

        if(empty($request['status']))
        {
            $status = 0;
        }else
        {
            $status = $request['status'];
        }
        //Save Theme
        $Save_Theme = new Themes();
        $Save_Theme->theme_name = $request['name'];
        $Save_Theme->theme_img = $theme_img;
        $Save_Theme->status = $status ;
        $Save_Theme->image_real_name = $Image->getClientOriginalName();
        $Save_Theme->service_type = $request['service_type'];
        $Save_Theme->created_at = date("Y-m-d H:i:s") ;
        $Save_Theme->updated_at = date("Y-m-d H:i:s") ;
        $Save_Theme->save();

        // to get last id insert in theme table
        $id = DB::getPdo()->lastInsertId();

        // to save theme data and service data in table themes_services
        $themes = Themes::find($id);
        if (!$themes)
            return abort('404');
        $themes->service()->syncWithoutDetaching($request['service_id']);
        // to save any action in AccessLog table
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $request['name'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'ThemeTemplates',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('AllThemes');
    }

    public function DeleteTheme($id){
        $theme_name = DB::table('themes')
            ->where('id', $id)
            ->first(['theme_name']);

        DB::table('themes')->where('id', $id)->delete();

        // to save any action in AccessLog table
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $theme_name->theme_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'ThemeTemplates',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => ""
        ]);
    }

    public function ListImageThemes(Request $request){
        if ($request->ServiceTypeId == 1){
            return DB::table('themes')
                ->join('themes_services', 'themes.id', '=', 'themes_services.theme_id')
                ->where('themes.status' , 1)
                ->where('themes_services.service_id' , $request->services)
                ->select(['themes.*'])
                ->get();
        }elseif ($request->ServiceTypeId == 2 || $request->ServiceTypeId == 3){
            $GetAllThemeImage = DB::table('themes')
                ->select('themes.id','themes.theme_img')
                ->where('service_type', $request->ServiceTypeId)
                ->where('themes.status' , 1)
                ->get();
            return $GetAllThemeImage;
        }
    }
}
