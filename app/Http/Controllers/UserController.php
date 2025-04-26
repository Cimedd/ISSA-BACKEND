<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function contacts()
    {
        $user = auth()->user();
    
        //$contacts = $user->contacts()->with('contacts')->get(); 
        $contacts = $user->contacts()->select('id', 'name', 'phone_number')->get();

        return response()->json(["status" => "success", "message" => "Contact fetched", "contacts" => $contacts]);
    }

    public function user()
    {
        $user = auth()->user();
    
        return response()->json($user);
    }

    public function update(Request $request){
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(), // Allow the current user's email to remain unchanged
        ]);
    
        // Get the currently authenticated user
        $user = auth()->user();
    
        // Update the user's details
        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->email = $validated['email'];
    
        // Save the changes to the database
        $user->save();
    
        // Return a success response
        return response()->json([
            'status'  => 'success',
            'message' => 'User details updated successfully',
            'data'    => $user,
        ]);
    }

    public function search(string $phone){
        $search = $phone; 
        $results = User::where('id', '!=', auth()->id())->select('id', 'name', 'phone_number')
            ->where('phone_number', 'LIKE', "%$search%")
            ->limit(10)
            ->get();

    return response()->json(["status" => "success", "message" => "Contact fetched", "contacts" => $results]);
    }
}
