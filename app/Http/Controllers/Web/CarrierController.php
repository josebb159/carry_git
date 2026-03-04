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
        $validated = $request->validated();

        // Handle booleans not present in checkboxes
        $validated['gps_tracking'] = $request->boolean('gps_tracking');
        $validated['adr_enabled'] = $request->boolean('adr_enabled');
        $validated['pallet_exchange'] = $request->boolean('pallet_exchange');
        $validated['xl_certification'] = $request->boolean('xl_certification');
        $validated['accept_e_invoicing'] = $request->boolean('accept_e_invoicing');

        // Handle file uploads
        $documentFields = [
            'doc_company_registration',
            'doc_cmr_insurance',
            'doc_transport_license',
            'doc_bank_certificate',
            'doc_tax_residence'
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store('carriers/documents', 'public');
                $validated[$field] = $path;
            }
        }

        Carrier::create($validated);

        return redirect()->route('carriers.index')->with('success', 'Carrier created successfully.');
    }
}
