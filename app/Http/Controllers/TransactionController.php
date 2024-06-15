<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function getAllTransactionsGameTotal()
    {
        $transactions = DB::table('transactions');
        $transactions->join('users', 'transactions.user_id', '=', 'users.id');
        $transactions->join('products', 'transactions.product_id', '=', 'products.id');
        $transactions->join('games', 'products.game_id', '=', 'games.id');
        $transactions->join('payments', 'transactions.payment_id', '=', 'payments.id');
        $transactions->select(
            'games.name as game_name',
            DB::raw('SUM(payments.total_price) as price_total'),
            DB::raw('COUNT(transactions.user_id) as user_total')
        );
        $transactions->groupBy('games.name');
        $result = $transactions->get();

        return response()->json([
            'success' => true,
            'message' => 'success_get_transaction_game_total',
            'data' => $result
        ]);

    }

    public function getUserTransactionsByGame($game_target)
    {
        $transactions = DB::table('transactions');
        $transactions->join('users', 'transactions.user_id', '=', 'users.id');
        $transactions->join('products', 'transactions.product_id', '=', 'products.id');
        $transactions->join('games', 'products.game_id', '=', 'games.id');
        $transactions->join('payments', 'transactions.payment_id', '=', 'payments.id');
        $transactions->select('users.username', 'products.name as product_name', 'games.name as game_name', 'products.price as product_price', 'payments.total_price as payment_price','transactions.username_game','transactions.user_id_game','transactions.user_server_game');
        
        if (isUUID($game_target)) {
            $transactions->where('products.game_id', $game_target);
        } else {
            $transactions->where('games.name', $game_target);
        }
        $result = $transactions->get();

        if ($result->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'failed_get_transaction_empty',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'succes_get_trasaction_ingame_user',
            'data' => $result
        ]);
        
        // 'success' => true,
        // 'message' => 'Pesan berhasil mendapatkan transaksi di dalam game user',
        // 'data' => $game_id
    }
 
    
}