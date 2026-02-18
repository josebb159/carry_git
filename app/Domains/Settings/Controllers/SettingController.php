<?php

namespace App\Domains\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Settings\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $settings = [
            'Maps_API_KEY' => Setting::get('Maps_API_KEY')
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'Maps_API_KEY' => 'nullable|string'
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('status', 'Configuraciones actualizadas correctamente.');
    }
}
