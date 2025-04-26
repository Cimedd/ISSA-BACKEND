<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function contacts()
    {
        $user = auth()->user();
    
        $contacts = $user->contacts()->with('contacts')->get(); 
    
        return response()->json($contacts);
    }

    public function search(string $phone){
        $search = $phone; 
        $results = User::where('id', '!=', auth()->id())
            ->where('phone_number', 'LIKE', "%$search%")
            ->limit(10)
            ->get();

    return response()->json($results);
    }
}
