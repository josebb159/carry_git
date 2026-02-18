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
        Fleet::create($request->validated());

        return redirect()->route('fleet.index')->with('success', 'Vehicle added successfully.');
    }
}
