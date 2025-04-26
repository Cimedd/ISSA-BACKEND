<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function getProvider(){
        $provider = Provider::all();
        return response()->json(data: ["data" => $provider]);
    }

    public function getProviderByID($id){
        $provider = Provider::find($id);
        if (!$provider) return response()->json(['message' => 'Provider not found'], 404);
        return response()->json($provider);
    }
}
