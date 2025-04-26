<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        return response()->json(Product::all());
    }

    public function getProductsByID($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);
        return response()->json($product);
    }

    public function insert(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

}
