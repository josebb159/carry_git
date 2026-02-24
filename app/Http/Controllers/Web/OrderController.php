<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Domains\Orders\Models\Order;
use App\Domains\Events\Models\Event;
use App\Shared\Enums\OrderStatus;
use App\Shared\Enums\EventType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Domains\Orders\DTOs\OrderDTO;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isClient = $user->hasRole('user') || $user->hasRole('merchant');

        $query = Order::with(['client', 'carrier']);

        if ($isClient) {
            $query->where('user_id', $user->id);
        }

        // filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                    $q->where('legal_name', 'like', "%{$search}%");
                }
                );
            });
        }

        $orders = $query->latest()->paginate(10);
        $statuses = OrderStatus::cases();

        return view('orders.index', compact('orders', 'statuses'));
    }

    public function create(): View
    {
        $user = auth()->user();
        $isClient = $user->hasRole('user') || $user->hasRole('merchant');

        $myClient = null;
        if ($isClient) {
            $myClient = \App\Domains\Clients\Models\Client::where('user_id', $user->id)->first();
        }

        $clients = \App\Domains\Clients\Models\Client::all();
        $carriers = \App\Domains\Carriers\Models\Carrier::all();

        return view('orders.create', compact('clients', 'carriers', 'isClient', 'myClient'));
    }

    public function store(\App\Domains\Orders\Http\Requests\CreateOrderRequest $request): \Illuminate\Http\RedirectResponse
    {
        $dto = \App\Domains\Orders\DTOs\OrderDTO::fromRequest($request, $request->user()->id);

        $service = app(\App\Domains\Orders\Services\OrderService::class);
        $order = $service->createOrder($dto);

        return redirect()->route('dashboard')->with('success', 'Tu solicitud ha sido creada con éxito.');
    }

    public function show(string $uuid): View
    {
        $user = auth()->user();
        $isClient = $user->hasRole('user') || $user->hasRole('merchant');

        $query = Order::where('uuid', $uuid)
            ->with(['client', 'carrier', 'locations', 'events.user', 'invoices']);

        if ($isClient) {
            $query->where('user_id', $user->id);
        }

        $order = $query->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
