<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UssdContributionController extends Controller
{
    public function storeContribution(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'         => 'required|string',
            'amount'        => 'required|numeric|gt:0',
            'type'          => 'required|in:contribution,loan,withdrawal',
            'momo_tx_id'    => 'required|string',
            'session_id'    => 'required|string',
            'status'        => 'nullable|in:PENDING,SUCCESS',
            'blockchain_tx' => 'nullable|string',
            'blockchain_url' => 'nullable|string',
            'metadata'      => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ], 422);
        }

        // Find user by phone number
        $phone = $request->phone;
        $user = User::where('mobile', $phone)
                    ->orWhere('mobile', 'LIKE', '%' . substr($phone, -9))
                    ->first();

        if (!$user) {
            return response()->json([
                'remark'  => 'user_not_found',
                'status'  => 'error',
                'message' => ['error' => ['User not found with this phone number']],
            ], 404);
        }

        $amount = $request->amount;
        $type = $request->type;
        $paymentStatus = $request->status ?? 'SUCCESS';

        // Create deposit record for contribution
        if ($type === 'contribution') {
            $deposit = new Deposit();
            $deposit->user_id = $user->id;
            $deposit->branch_id = $user->branch_id;
            $deposit->method_code = 0; // USSD/Mobile Money
            $deposit->amount = $amount;
            $deposit->method_currency = 'RWF';
            $deposit->charge = 0;
            $deposit->rate = 1;
            $deposit->final_amount = $amount;
            $deposit->detail = json_encode([
                'momo_tx_id' => $request->momo_tx_id,
                'blockchain_tx' => $request->blockchain_tx,
                'blockchain_url' => $request->blockchain_url,
                'metadata' => $request->metadata,
                'timestamp' => now()->toISOString(),
            ]);
            $deposit->trx = $request->session_id;
            $deposit->status = $paymentStatus == 'PENDING' ? Status::PAYMENT_PENDING : Status::PAYMENT_SUCCESS;
            $deposit->save();

            // Update user balance only if payment is successful
            if ($paymentStatus != 'PENDING') {
                $user->balance += $amount;
                $user->save();
            }

            // Create transaction record only for successful payments
            if ($paymentStatus != 'PENDING') {
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $amount;
                $transaction->post_balance = $user->balance;
                $transaction->charge = 0;
                $transaction->trx_type = '+';
                $transaction->details = 'USSD Contribution';
                $transaction->trx = $request->session_id;
                $transaction->remark = 'ussd_contribution';
                
                // Store blockchain info in details if available
                if ($request->blockchain_tx) {
                    $transaction->details = 'USSD Contribution - Blockchain TX: ' . $request->blockchain_tx;
                }
                
                $transaction->save();
            }

            return response()->json([
                'remark'  => 'contribution_saved',
                'status'  => 'success',
                'message' => ['success' => ['Contribution saved successfully']],
                'data'    => [
                    'deposit_id' => $deposit->id,
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'new_balance' => $user->balance,
                    'transaction_id' => isset($transaction) ? $transaction->id : null,
                    'trx' => $request->session_id,
                ],
            ]);
        }

        return response()->json([
            'remark'  => 'type_not_implemented',
            'status'  => 'error',
            'message' => ['error' => ['This transaction type is not yet implemented']],
        ], 400);
    }

    public function getContributions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ], 422);
        }

        $phone = $request->phone;
        $user = User::where('mobile', $phone)
                    ->orWhere('mobile', 'LIKE', '%' . substr($phone, -9))
                    ->first();

        if (!$user) {
            return response()->json([
                'remark'  => 'user_not_found',
                'status'  => 'error',
                'message' => ['error' => ['User not found']],
            ], 404);
        }

        // Get both pending and successful deposits (USSD contributions)
        $deposits = Deposit::where('user_id', $user->id)
                          ->where('method_code', 0) // USSD/Mobile Money only
                          ->whereIn('status', [Status::PAYMENT_PENDING, Status::PAYMENT_SUCCESS])
                          ->orderBy('created_at', 'desc')
                          ->limit(20)
                          ->get()
                          ->map(function($deposit) {
                              // Parse detail JSON if available
                              $detail = [];
                              if ($deposit->detail && is_string($deposit->detail)) {
                                  $decoded = json_decode($deposit->detail, true);
                                  if (json_last_error() === JSON_ERROR_NONE) {
                                      $detail = $decoded;
                                  }
                              }
                              
                              return [
                                  'id' => $deposit->id,
                                  'amount' => $deposit->amount,
                                  'status' => $deposit->status == Status::PAYMENT_PENDING ? 'PENDING' : 'SUCCESS',
                                  'momo_tx_id' => $detail['momo_tx_id'] ?? $deposit->trx,
                                  'blockchain_tx' => $detail['blockchain_tx'] ?? null,
                                  'blockchain_url' => $detail['blockchain_url'] ?? null,
                                  'created_at' => $deposit->created_at,
                                  'updated_at' => $deposit->updated_at,
                              ];
                          });

        return response()->json([
            'remark'  => 'contributions',
            'status'  => 'success',
            'message' => ['success' => ['Contributions retrieved']],
            'data'    => [
                'user_id' => $user->id,
                'balance' => $user->balance,
                'contributions' => $deposits,
            ],
        ]);
    }
}
