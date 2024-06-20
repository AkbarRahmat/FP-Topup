<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Game;
use App\Models\User;
use App\Models\UserGame;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;



class TransactionController extends Controller
{

    public function getAllTransactionsGameTotal($status)
    {
        // Query
        $transactions = DB::table('transactions');
        $transactions->join('users', 'transactions.user_id', '=', 'users.id');
        $transactions->join('products', 'transactions.product_id', '=', 'products.id');
        $transactions->join('games', 'products.game_id', '=', 'games.id');
        $transactions->join('payments', 'transactions.payment_id', '=', 'payments.id');
        $transactions->select(
            'transactions.status',
            'products.game_id as game_id',
            'games.name as game_name',
            DB::raw('CAST(SUM(payments.paid_price) AS UNSIGNED) as paid_total'),
            DB::raw('CAST(COUNT(transactions.user_id) AS UNSIGNED) as user_total')
        );
        $transactions->where('products.category', 'game');

        // Filter Status
        if ($status && $status != 'all') {
            $transactions->where('transactions.status', $status);
        }

        $transactions->groupBy('transactions.status', 'products.game_id', 'games.name');

        // Data Result
        $result = $transactions->get();

        if ($result->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_transaction_empty',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'success_get_transaction',
            'data' => $result
        ]);
    }

    public function getUserTransactionsByGame($status, $game_target)
    {
        // Query
        $transactions = DB::table('transactions');
        $transactions->join('users', 'transactions.user_id', '=', 'users.id');
        $transactions->join('products', 'transactions.product_id', '=', 'products.id');
        $transactions->join('games', 'products.game_id', '=', 'games.id');
        $transactions->join('usergames', 'transactions.usergame_id', '=', 'usergames.id');
        $transactions->join('payments', 'transactions.payment_id', '=', 'payments.id');
        $transactions->select('transactions.id as transaction_id', 'transactions.status as transaction_status', 'products.name as product_name', 'games.name as game_name', 'products.price as lasted_price', 'payments.product_price as product_price', 'payments.paid_price as paid_price', 'usergames.username as usergame_username', 'usergames.globalid as usergame_globalid');
        $transactions->where('products.category', 'game');

        // Check UUID
        if (isUUID($game_target)) {
            $transactions->where('products.game_id', $game_target);
        } else {
            $transactions->where('games.name', $game_target);
        }

        // Filter Status
        if ($status && $status != 'all') {
            $transactions->where('transactions.status', $status);
        }

        // Data Result
        $result = $transactions->get();

        if ($result->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_transaction_empty',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'success_get_transaction',
            'data' => $result
        ]);
    }
    public function updateTransactionStatus(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:processed,success',
            'processed_by' => 'nullable|exists:users,id',
            'processed_proof' => 'required_if:status,success|image|max:2048', // Menambahkan validasi untuk processed_proof
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                "message" => "fail_validation",
                "data" => $validator->errors()
            ], 400);
        }

        // Cari transaksi berdasarkan ID
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_transaction_notfound',
            ], 404);
        }

        // Update status transaksi
        $transaction->status = $request->input('status');

        // Update processed_by jika disertakan
        if ($request->has('processed_by')) {
            $transaction->processed_by = $request->input('processed_by');
        }

        // Mengelola processed_proof jika disertakan
        if ($request->hasFile('processed_proof')) {
            $file = $request->file('processed_proof');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('proofs', $fileName, 'public');
            $transaction->processed_proof = $filePath;
        }

        // Simpan transaksi yang telah diperbarui
        $transaction->save();

        return response()->json([
            'success' => true,
            'message' => 'success_update_transaction',
            'data' => $transaction,
        ]);
    }
   public function getTransactionDetail($transaction_id): JsonResponse
    {
        // Get transaction details
        $transaction = Transaction::with(['user', 'usergame', 'product', 'payment'])->find($transaction_id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_transaction_notfound'
            ], 404);
        }

        // Get Table Data
        $user = optional($transaction->user);
        $product = optional($transaction->product);
        $game = optional($product->game);
        $usergame = optional($transaction->usergame);
        $payment = optional($transaction->payment);

        // Check Game
        if (!$game->id || !$usergame->id) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_transaction_notfound'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'success_get_transaction',
            'data' => [
                'transaction' => [
                    'status' => $transaction->status,
                    'processed_by' => $transaction->processed_by,
                    'processed_proof' => $transaction->processed_proof,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                ],
                'game' => [
                    'id' => $game->id,
                    'name' => $game->name,
                ],
                'user' => [
                    'username' => $user->username,
                    'phone' => $user->phone,
                ],
                'usergame' => [
                    'globalid' => $usergame->globalid,
                    'server' => $usergame->server,
                    'username' => $usergame->username,
                ],
                'product' => [
                    'name' => $product->name,
                    'price' => $product->price,
                ],
                'payment' => [
                    'status' => $payment->status,
                    'product_price' => $payment->product_price,
                    'seller_cost' => $payment->seller_cost,
                    'service_cost' => $payment->service_cost,
                    'total_cost' => $payment->total_cost,
                    'paid_price' => $payment->paid_price,
                    'refund_cost' => $payment->refund_cost,
                    'debt_cost' => $payment->debt_cost
                ],
            ]
        ]);
    }
}
