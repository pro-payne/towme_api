<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\Account;
use Lcobucci\JWT\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Account $account, Request $request)
    {
        $user = auth()->user();
        checkUser($user, $account);

        dd($user);
    }
}
