<?php

namespace App\Http\Controllers;

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
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|max:15'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number
        ]);

        $user->sendEmailVerificationNotification();

        event(new EventsRegistered($user));

        return response()->json(['success' => 'User Successfully Registered'], 201);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['error' => 'Email not verified. Please check your inbox.'], 403);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function resendVerification(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email already verified.'], 400);
    }

    $user->sendEmailVerificationNotification();

    return response()->json(['message' => 'Verification email sent.']);
}

public function verifyEmail($id, $hash)
{
    $user = User::findOrFail($id);

    if (hash_equals($hash, sha1($user->getEmailForVerification()))) {
        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified.']);
    }

    return response()->json(['message' => 'Invalid verification link.'], 400);
}
}
