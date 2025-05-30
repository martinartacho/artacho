<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = [
            'logo' => \App\Models\Setting::get('logo', 'logos/default.png'),
            'language' => \App\Models\Setting::get('language', 'en'),
        ];
        return view('settings.edit', compact('settings'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:png,jpg,JPG,jpeg,svg', 'max:2048'],
        ]);

        $logoPath = $request->file('logo')->store('logos', 'public');

        Setting::set('logo', $logoPath);

        return redirect()->route('settings.edit')->with('success', 'Logo actualizado correctamente.');
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => ['required', 'in:en,es,ca'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'language'],
            ['value' => $request->language]
        );
        
        cache()->forget('global_language');
        
        return redirect()->route('settings.edit')->with('success', __('Idioma actualizado correctamente.'));
    }

}
