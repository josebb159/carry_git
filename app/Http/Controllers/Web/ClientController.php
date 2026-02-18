<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domains\Clients\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::withCount('orders');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('legal_name', 'like', "%{$search}%")
                ->orWhere('vat_number', 'like', "%{$search}%");
        }

        $clients = $query->latest()->paginate(15);

        return view('clients.index', compact('clients'));
    }
    public function create(): View
    {
        return view('clients.create');
    }

    public function store(\App\Domains\Clients\Http\Requests\CreateClientRequest $request): \Illuminate\Http\RedirectResponse
    {
        Client::create($request->validated());

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }
}
