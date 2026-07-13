<?php

namespace App\Http\Controllers;

use App\Models\OrganizationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationSettingController extends Controller
{
    public function edit()
    {
        $setting = OrganizationSetting::first();
        if (!$setting) {
            $setting = OrganizationSetting::create([
                'name' => 'Flugzeit Aviation',
                'theme_color' => '#cb0c9f'
            ]);
        }
        $cities = $setting->city ? \App\cities::where('name', $setting->city)->get() : collect([]);
        return view('organization_settings.edit', compact('setting', 'cities'));
    }

    public function update(Request $request)
    {
        $setting = OrganizationSetting::first();
        if (!$setting) {
            $setting = new OrganizationSetting();
        }

        $setting->name = $request->input('name');
        $setting->theme_color = $request->input('theme_color');
        $setting->website = $request->input('website');
        $setting->email = $request->input('email');
        $setting->phone = $request->input('phone');
        $setting->address = $request->input('address');
        $setting->city = $request->input('city');

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/settings'), $logoName);
            $setting->logo = 'uploads/settings/' . $logoName;
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = time() . '_favicon.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads/settings'), $faviconName);
            $setting->favicon = 'uploads/settings/' . $faviconName;
        }

        if ($request->hasFile('login_bg')) {
            $bg = $request->file('login_bg');
            $bgName = time() . '_bg.' . $bg->getClientOriginalExtension();
            $bg->move(public_path('uploads/settings'), $bgName);
            $setting->login_bg = 'uploads/settings/' . $bgName;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Organization settings updated successfully!');
    }
}
