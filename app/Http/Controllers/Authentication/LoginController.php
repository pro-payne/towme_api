<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Token\Parser;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        //  Validate

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'type' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!Auth::guard(strtolower($request->type) . '-web')->attempt($credentials)) {
            return response([
                'success' => false,
                'error' => 'Email or Password doesn\'t exist',
            ], 401);
        }

        $user = $request->user();

        // Create token

        $tokenResult = $user->createToken('TowMe Token');

        $token = $tokenResult->token;

        // Remember me option

        // $token->expires_at = Carbon::now()->addWeeks(1);

        $user_details = auth()->user();
        $token->save();

        return response([
            'success' => true,
            'data' => [
                'accessToken' => $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'user' => [
                    'identity' => $user_details->id,
                    'type' => strtolower($request->type),
                    'first_name' => $user_details->first_name,
                    'last_name' => $user_details->last_name,
                    'email' => $user_details->email,
                    'picture' => ($user_details->picture != null) ? $user_details->picture : "",
                    'verified' => $user_details->verified,
                    'registered' => $user_details->created_at,
                ],
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $value = $request->bearerToken();        
        $id = (new Parser())->parse($value)->getHeader('jti');

        DB::table('oauth_access_tokens')
            ->where('id', $id)
            ->update([
                'revoked' => true,
            ]);
        return response()->json([
            'message' => 'logged_out',
        ]);
    }
}
