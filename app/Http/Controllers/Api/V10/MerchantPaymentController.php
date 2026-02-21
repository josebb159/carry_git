<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Domains\Merchant\Models\PaymentAccount;
use App\Domains\Merchant\Models\PaymentRequest;
use App\Domains\Users\Models\User;
use App\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantPaymentController extends Controller
{
    use ApiResponse;

    // ─── Payment Accounts ──────────────────────────────────────────────────────

    /**
     * GET /api/v10/payment-accounts/index
     */
    public function accountIndex(Request $request): JsonResponse
    {
        $accounts = PaymentAccount::where('user_id', $request->user()->id)->latest()->get();
        return $this->successResponse($accounts, 'Payment accounts retrieved.');
    }

    /**
     * POST /api/v10/payment-account/store
     */
    public function accountStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'account_type' => 'required|string|max:100',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = $request->user()->id;
        $account = PaymentAccount::create($validated);

        return $this->successResponse($account, 'Payment account added.', 201);
    }

    // ─── Payment Requests ───────────────────────────────────────────────────────

    /**
     * GET /api/v10/payment-request/index
     */
    public function requestIndex(Request $request): JsonResponse
    {
        $requests = PaymentRequest::where('user_id', $request->user()->id)
            ->with('paymentAccount')
            ->latest()
            ->paginate(20);

        return $this->successResponse($requests, 'Payment requests retrieved.');
    }

    /**
     * POST /api/v10/payment-request/store
     */
    public function requestStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payment_account_id' => 'required|exists:payment_accounts,id',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string',
        ]);

        // Verify the account belongs to the user
        PaymentAccount::where('user_id', $request->user()->id)
            ->where('id', $validated['payment_account_id'])
            ->firstOrFail();

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'pending';

        $paymentRequest = PaymentRequest::create($validated);

        return $this->successResponse($paymentRequest, 'Payment request submitted.', 201);
    }

    // ─── Statements ────────────────────────────────────────────────────────────

    /**
     * GET /api/v10/statements/index
     */
    public function statements(Request $request): JsonResponse
    {
        $requests = PaymentRequest::where('user_id', $request->user()->id)
            ->with('paymentAccount')
            ->latest()
            ->paginate(20);

        return $this->successResponse($requests, 'Statements retrieved.');
    }

    /**
     * GET /api/v10/statement-reports
     */
    public function statementReports(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $approved = PaymentRequest::where('user_id', $userId)->where('status', 'approved')->sum('amount');
        $pending = PaymentRequest::where('user_id', $userId)->where('status', 'pending')->sum('amount');
        $rejected = PaymentRequest::where('user_id', $userId)->where('status', 'rejected')->sum('amount');

        return $this->successResponse([
            'total_approved' => $approved,
            'total_pending' => $pending,
            'total_rejected' => $rejected,
        ], 'Statement reports retrieved.');
    }

    // ─── Transactions ───────────────────────────────────────────────────────────

    /**
     * GET /api/v10/account-transaction/index
     */
    public function transactionIndex(Request $request): JsonResponse
    {
        // For now, use payment requests as transaction history
        $transactions = PaymentRequest::where('user_id', $request->user()->id)
            ->with('paymentAccount')
            ->latest()
            ->paginate(20);

        return $this->successResponse($transactions, 'Transactions retrieved.');
    }

    /**
     * GET /api/v10/account-transaction/filter
     * Query params: type (credit|debit), account (payment_account_id)
     */
    public function transactionFilter(Request $request): JsonResponse
    {
        $query = PaymentRequest::where('user_id', $request->user()->id);

        if ($request->filled('account')) {
            $query->where('payment_account_id', $request->account);
        }

        $transactions = $query->with('paymentAccount')->latest()->paginate(20);

        return $this->successResponse($transactions, 'Filtered transactions retrieved.');
    }

    // ─── Wallet ─────────────────────────────────────────────────────────────────

    /**
     * GET /api/v10/merchant/wallet
     */
    public function wallet(Request $request): JsonResponse
    {
        return $this->successResponse([
            'balance' => $request->user()->wallet_balance ?? 0,
            'currency' => \App\Domains\Settings\Models\Setting::get('currency', 'USD'),
        ], 'Wallet retrieved.');
    }

    /**
     * POST /api/v10/merchant/wallet/recharge-add
     */
    public function walletRecharge(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // In a real app, you'd integrate a payment gateway here.
        // For now, simulate a top-up.
        $user = $request->user();
        $user->increment('wallet_balance', $request->amount);

        return $this->successResponse([
            'balance' => $user->fresh()->wallet_balance,
        ], 'Wallet recharged successfully.');
    }
}
