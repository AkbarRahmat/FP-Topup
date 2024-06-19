<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
                'message' => 'failed_get_transaction_empty',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'success_get_transaction_game_total',
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
        $transactions->select('transactions.id as transaction_id', 'transactions.status as transaction_status', 'products.name as product_name', 'games.name as game_name', 'products.price as lasted_price', 'payments.product_price as product_price', 'payments.paid_price as paid_price', 'usergames.username as usergame_name');
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
                'message' => 'failed_get_transaction_empty',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'succes_get_trasaction_ingame_user',
            'data' => $result
        ]);
    }


}
