<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    // POST /api/register
    public function register(Request $request) {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'color'    => 'nullable|string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'color'    => $request->color ?? '#6366f1',
        ]);

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'user'  => $user->load('household.members'),
            'token' => $token
        ]);
    }

    // POST /api/login
    public function login(Request $request) {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Wrong email or password'], 401);
        }

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'user'  => $user->load('household.members'),
            'token' => $token
        ]);
    }

    // POST /api/logout
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    // GET /api/me
    public function me(Request $request) {
        return response()->json(
            $request->user()->load('household.members')
        );
    }
}