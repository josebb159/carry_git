<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domains\Carriers\Models\Carrier;
use App\Domains\Carriers\Http\Requests\StoreCarrierRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CarrierController extends Controller
{
    public function index(Request $request): View
    {
        $query = Carrier::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('vat_number', 'like', "%{$search}%");
        }

        $carriers = $query->latest()->paginate(15);

        return view('carriers.index', compact('carriers'));
    }

    public function create(): View
    {
        return view('carriers.create');
    }

    public function store(StoreCarrierRequest $request): RedirectResponse
    {
        Carrier::create($request->validated());

        return redirect()->route('carriers.index')->with('success', 'Carrier created successfully.');
    }
}
