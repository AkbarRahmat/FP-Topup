<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Game;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductsByGame($game_id)
    {
        $products = Product::where('game_id', $game_id)->get(['id', 'name', 'price']);

        if ($products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_product_notfound',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'messege' => 'success_get_product',
            'data' => $products
        ]);
    }
    public function getGames()
    {
        $games = Game::all(['id', 'name']);

        if ($games->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_product_notfound',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'messege' => 'success_get_product',
            'data' => $games
        ]);
    }
    public function updatePrice(Request $request, $product_id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'price' => 'required|integer|min:0',
        ]);

        // Temukan produk berdasarkan UUID
        $product = Product::find($product_id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Update harga produk
        $product->price = $validatedData['price'];
        $product->save();

        return response()->json(['message' => 'Product price updated successfully'], 200);
    }
}
