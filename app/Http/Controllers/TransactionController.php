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
            DB::raw('SUM(payments.total_price) as price_total'),
            DB::raw('COUNT(transactions.user_id) as user_total')
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
        $transactions->join('payments', 'transactions.payment_id', '=', 'payments.id');
        $transactions->select('products.name as product_name', 'games.name as game_name', 'products.price as product_price', 'payments.total_price as payment_price','transactions.usergame_name','transactions.usergame_id','transactions.usergame_server');
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
