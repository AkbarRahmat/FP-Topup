<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use App\Models\Game;
use App\Models\User;
use App\Models\UserGame;
use App\Services\TripayService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;




class TransactionController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

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
            'processed_proof' => 'required_if:status,success|url', // Mengubah validasi untuk menerima URL
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

        // Fix Bug Later...
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

    public function createUserTransaction(Request $request)
    {
        $input = $request->validate([
            'global_id' => 'required|string|numeric',
            'server' => 'nullable|string',
            'phone' => 'required|string|numeric',
            'product_id' => 'required|string',
            'vendor' => 'required|string'
        ]);
        $input['server'] = $input['server'] ?? null;

        // Generate password
        $hashedPassword = Hash::make('user_' . $input['global_id'] . '_userpw');

        // Create
        $user = User::Create([
            'phone' => $input['phone'],
            'username' => 'user_' . $input['global_id'],
            'password' => $hashedPassword,
            'role' => 'buyer',
            'status' => 'limited',
            'last_login' => now()
        ]);

        $userGame = UserGame::Create([
            'globalid' => $input['global_id'],
            'server' => $input['server']
        ]);

        // Get Product
        $product = Product::with(['game'])->find($input['product_id']);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_product_notfound'
            ], 404);
        }

        // Cost
        $additional = [
            'seller_cost' => 1000
        ];

        $tripayResponse = $this->tripayService->createTransaction($product, $product->game, $user, $additional);

        if (!$tripayResponse['success']) {
            return response()->json([
                'success' => false,
                'message' => 'fail_create_transaction',
                'debug' => $tripayResponse
            ], 500);
        }
        $tripayData = $tripayResponse['data'];

        // Calculate Payment
        $paymentData = [
            'status' => 'pending',
            'vendor' => 'Tripay ' . $tripayData['payment_name'],
            'reference' => $tripayData['reference'],
            'product_price' => $product['price'],
            'seller_cost' => $additional['seller_cost'],
            'service_cost' => $tripayData['total_fee'],
            'total_cost' => 0,
            'paid_price' => 0,
            'refund_cost' => 0,
            'debt_cost' => 0,
            'expired_at' => Carbon::createFromTimestamp($tripayData['expired_time'])->format('Y-m-d H:i:s')
        ];
        calculateTransactionTotalCost($paymentData, false);

        // Create Payment
        $payment = Payment::create($paymentData);

        // Create Transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'usergame_id' => $userGame->id,
            'payment_id' => $payment->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'success_create_transaction',
            'data' => [
                'payment_url' => $tripayData['checkout_url'],
            ]
        ], 201);
    }
}
