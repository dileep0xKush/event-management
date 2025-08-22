<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;
use Illuminate\Support\Str;
use App\Events\UserForceLogout;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        if ($user->active_token_id) {
            $oldToken = Token::find($user->active_token_id);
            if ($oldToken) {
                $oldToken->revoke();

                // Broadcast session_id (token ID)
                broadcast(new UserForceLogout($user->id, $oldToken->id));
            }
        }

        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->accessToken;
        $tokenId = $tokenResult->token->id;

        $user->active_token_id = $tokenId;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'session_id' => $tokenId, // Include this in response
            'user'    => $user
        ]);
    }
    public function logout(Request $request)
    {
        if ($request->user()) {

            $user = $request->user();

            if ($user) {
                $user->token()->revoke();
                $user->active_token_id = null;
                $user->save();
            }

            $request->user()->token()->revoke();
        }

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
