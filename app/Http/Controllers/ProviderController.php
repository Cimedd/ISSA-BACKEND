<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function getProvider(){
        $provider = Provider::all();
        return response()->json(['status' => 'success', 'message' =>'Data successfull fetched', "providers" => $provider]);
    }

    public function getProviderByID($id){
        $provider = Provider::where('category_id', $id)->get();
        if (!$provider) return response()->json(['status' => 'error', 'message' => 'Provider not found'], 404);
        return response()->json(['status' => 'success', 'message' =>'Data successfull fetched', "providers" => $provider]);
    }
}
