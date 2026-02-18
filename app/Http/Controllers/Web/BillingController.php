<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domains\Billing\Models\Invoice;
use App\Shared\Enums\InvoiceStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(Request $request): View
    {
        // Simple authorization check (usually handled by middleware/policy)
        /* if (!auth()->user()->can('view-billing')) {
            abort(403);
        } */

        $query = Invoice::with(['client', 'order']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(15);
        $statuses = InvoiceStatus::cases();

        return view('billing.index', compact('invoices', 'statuses'));
    }
}
