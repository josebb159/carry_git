<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Merchant\Models\SupportTicket;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantSupportController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v10/support/index
     */
    public function index(Request $request): JsonResponse
    {
        $tickets = SupportTicket::where('user_id', $request->user()->id)->latest()->paginate(20);
        return $this->successResponse($tickets, 'Support tickets retrieved.');
    }

    /**
     * POST /api/v10/support/store
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'open';

        $ticket = SupportTicket::create($validated);

        return $this->successResponse($ticket, 'Support ticket submitted.', 201);
    }

    /**
     * DELETE /api/v10/support/delete/{id}
     */
    public function destroy(int $id, Request $request): JsonResponse
    {
        $ticket = SupportTicket::where('user_id', $request->user()->id)->findOrFail($id);
        $ticket->delete();

        return $this->successResponse(null, 'Support ticket deleted.');
    }
}
