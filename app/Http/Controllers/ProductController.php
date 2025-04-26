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
        $product = Product::where('provider_id',$id)->get();
        if (!$product) return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        return response()->json(['status' => 'success', 'message' =>'Data successfull fetched', "products" => $product]);
    }

    public function insert(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json(['status' => 'success', 'message' =>'Data successfull fetched', "products" => $product]);
    }

}
