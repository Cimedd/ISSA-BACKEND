<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Auth\Events\Registered as EventsRegistered;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|max:15|unique:users,phone_number'
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "message" => $validator->errors()->first()], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' =>  $request->password,
            'phone_number' => $request->phone_number
        ]);

        $user->sendEmailVerificationNotification();

        event(new EventsRegistered($user));

        return response()->json(['status'=> 'success','message' => 'User Successfully Registered'], 201);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone_number'  => 'required|string',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "message" => $validator->errors()->first()], 422);
        }

        $credentials = $request->only('phone_number', 'password');
        $user = User::where('phone_number', $credentials['phone_number'])->first();

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['status'=> 'error', 'message' => 'Email not verified. Please check your inbox.'], 403);
        }

        if ($credentials['password'] !== $user->password) {
            return response()->json(['status'=> 'error', 'message' => 'Invalid credentials'], 401);
        }

        $token = JWTAuth::fromUser($user);
    
        if (!$token) {
            return response()->json(['status'=> 'error','message' => 'Could not create token'], 500);
        }
        
        return response()->json(['status'=> 'success','message' => 'Login Success','token' => $token, "userId" => $user->id, "name" => $user->name]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['status'=> 'success','message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function resendVerification(Request $request)
{
    $request->validate([
        'phone' => 'required|exists:users,phone_number',
    ]);

    $user = User::where('phone_number', $request->phone)->first();

    if ($user->hasVerifiedEmail()) {
        return response()->json(['status'=> 'error', 'message' => 'Email already verified.'], 400);
    }

    $user->sendEmailVerificationNotification();

    return response()->json(['status'=> 'success','message' => 'Verification email sent.']);
}

public function verifyEmail($id, $hash)
{
    $user = User::findOrFail($id);

    if (hash_equals($hash, sha1($user->getEmailForVerification()))) {
        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['status'=> 'success', 'message' => 'Email successfully verified.']);
    }

    return response()->json(['status'=> 'success','message' => 'Invalid verification link.'], 400);
}
}
