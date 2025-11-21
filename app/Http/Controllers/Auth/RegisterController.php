<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = User::create($request->getData());

            auth()->login($user);
            $request->session()->regenerate();

            //todo properly validate register user data.
            return response()->json([
                'user' => $user,
                'message' => 'Registration successful'
            ]);
        });
    }
}
