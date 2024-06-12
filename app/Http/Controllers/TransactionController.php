<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;

class TransactionController extends Controller
{
    public function getTransactionsByGame()
    {
        $transactions = Transaction::selectRaw('products.name, SUM(transactions.price) as price_total, COUNT(transactions.user_id) as user_total')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil mendapatkan transaksi game total',
            'data' => $transactions
        ]);
    }

    public function getUserTransactionsByProduct($product_id)
    {
        $transactions = Transaction::where('product_id', $product_id)
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.id', 'users.username', 'transactions.price')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil mendapatkan transaksi di dalam game user',
            'data' => $transactions
        ]);
    }
}

