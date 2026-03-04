<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domains\Fleet\Models\Fleet;
use App\Domains\Carriers\Models\Carrier;
use App\Domains\Fleet\Http\Requests\StoreFleetRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FleetController extends Controller
{
    public function index(): View
    {
        $fleets = Fleet::with('carrier')->latest()->paginate(15);
        return view('fleet.index', compact('fleets'));
    }

    public function create(): View
    {
        $carriers = Carrier::select('id', 'name')->orderBy('name')->get();
        return view('fleet.create', compact('carriers'));
    }

    public function store(StoreFleetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle booleans from checkboxes
        $validated['adr_enabled'] = $request->boolean('adr_enabled');
        $validated['pallet_exchange'] = $request->boolean('pallet_exchange');
        $validated['gps_tracking'] = $request->boolean('gps_tracking');
        $validated['subcontractors_trucks'] = $request->boolean('subcontractors_trucks');
        $validated['double_driver'] = $request->boolean('double_driver');
        $validated['multimodal_solutions'] = $request->boolean('multimodal_solutions');
        $validated['partial_loads'] = $request->boolean('partial_loads');

        Fleet::create($validated);

        return redirect()->route('fleet.index')->with('success', 'Flota registrada correctamente.');
    }
}
