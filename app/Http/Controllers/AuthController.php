<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use Artisan;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        DB::setDefaultConnection('shared');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'subscription_id' => '1', // Set default subscription level
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    


    public function login(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Try to find the user in the 'standard' database
        \Config::set('database.default', 'standard');

        $user = User::where('email', $validated['email'])->first();

        // If user exists in 'standard' database
        if (!empty($user)) {
            $response = $this->standardLogin($validated, $user); // Pass $validated and $user
            if (!empty($response)) { // Corrected syntax here
                return $this->tryLogin($response, $validated);
            }
        }

        // If user doesn't exist in 'standard' database, try 'shared' database
        if (empty($response)) {
            DB::setDefaultConnection('shared');
            $response = $this->sharedLogin($validated); // Pass $validated
            return $this->tryLogin($response, $validated);
        }

        // If no user found, return error response
        return response()->json('Something Went Wrong');
    }

    protected function standardLogin($validated, $user)
    {
        if ($user && $user->subscription->level == 'standard') {
            // We already validated the user before, so re-fetch from the same database connection
            return User::where('email', $validated['email'])->first();
        } else {
            return null;
        }
    }

    protected function sharedLogin($validated)
    {
        $user = User::where('email', $validated['email'])->first();
        if ($user && $user->subscription->level == 'demo') {
            // We already validated the user before, so re-fetch from the same database connection
            return User::where('email', $validated['email'])->first();
        } else {
            return null;
        }
    }

    protected function tryLogin($response, $validated)
    {
        // If no user found, or password doesn't match, return error response
        if (!$response || !\Hash::check($validated['password'], $response->password)) {
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Unauthorized'
            ], 401);
        }

        // User found, now authenticate the user and return response
        Auth::login($response);

        // Get the active database connection
        $connection = DB::getDefaultConnection();
        $token = $response->createToken('API Token', ['connection' => $connection])->plainTextToken;


        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Login successful',
            'data' => [
                'user' => $response,
                'connection' => $connection,
                'token' => $token
            ]
        ], 200);
    }




}


