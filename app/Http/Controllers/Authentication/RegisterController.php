<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Model\Client;
use App\Model\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'type' => 'required|string',
        ]);

        $inputValues = $request->only(['email', 'password', 'type', 'first_name', 'last_name']);

        // Check if email is unique

        $uniqueEmail = ($inputValues['type'] == 'client') ? Client::where('email', $inputValues['email'])->get()->count() : Company::where('email', $inputValues['email'])->get()->count();

        if ($uniqueEmail != 0) {
            return response([
                'success' => false,
                "error" => "Email address already in use",
            ], Response::HTTP_FORBIDDEN);
        }

        $account = ($inputValues['type'] == 'client') ? new Client : new Company;
        $account->first_name = $inputValues['first_name'];
        $account->last_name = $inputValues['last_name'];
        $account->email = strtolower($inputValues['email']);
        $account->password = password_hash($inputValues['password'], PASSWORD_BCRYPT);
        $account->save();

        $autoLogin = $this->autoLogin([
            'email' => $inputValues['email'],
            'password' => $inputValues['password'],
        ], $request);

        if (!$autoLogin['success']) {
            return response()->json([
                'success' => false,
                'error' => 'Email or Password doesn\'t exist',
            ], 401);
        }

        unset($autoLogin['success']);
        return response([
            'success' => true,
            'data' => [
                'msg' => "Welcome to TowMe ".$inputValues['first_name'],
                'session' => $autoLogin,
            ],
        ], Response::HTTP_CREATED);

    }

    private function autoLogin($credentials, $request)
    {
        if (!Auth::attempt($credentials)) {
            return ['success' => false];
        }

        $user = $request->user();

        // Create token

        $tokenResult = $user->createToken('TowMe Token');

        $token = $tokenResult->token;

        // Remember me option

        $token->expires_at = Carbon::now()->addWeeks(1);

        $user_details = auth()->user();
        $token->save();

        return [
            'success' => true,
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
                'picture' => "",
                'verified' => 0,
                'registered' => $user_details->created_at,
            ],
        ];
    }
}
