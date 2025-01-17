<?php

namespace App\Auth\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomTokenGuard implements Guard
{
    protected $request;
    protected $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // This method returns the currently authenticated user
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if (!$token) {
            return null;
        }

        // Check the token across multiple databases
        $this->user = $this->validateTokenAcrossDatabases($token);

        return $this->user;
    }

    // This method is used to get the user ID
    public function id()
    {
        $user = $this->user();
        return $user ? $user->id : null;
    }

    // This method validates the token by checking multiple databases
    protected function validateTokenAcrossDatabases($token)
    {
        $databases = ['shared', 'standard']; // Add your database connections here

        foreach ($databases as $database) {
            $user = $this->findUserByToken($token, $database);
            if ($user) {
                return $user;
            }
        }

        return null;
    }

    // This method is used to find a user by token in a specific database
    protected function findUserByToken($token, $database)
{
    // Query the database connection directly without affecting global config
    $connection = DB::connection($database);

    $tokenPart = explode('|', $token)[1];

    // Hash the token part to match the stored value
    $hashedToken = hash('sha256', $tokenPart);

    // Retrieve the full record from the personal_access_tokens table
    $tokenRecord = $connection->table('personal_access_tokens')
        ->where('token', $hashedToken) // Compare the hashed token
        ->where('tokenable_type', 'App\Models\User') // Ensure it's a user token
        ->first();

   

    // If token found, retrieve the corresponding user
    if ($tokenRecord) {
        $user = User::find($tokenRecord->tokenable_id); // Use the tokenable_id to get the user
        return $user;
    }

    return null;
}


    // This method checks if the user is authenticated
    public function check()
    {
        return $this->user() !== null;
    }

    // This method checks if the user is a guest (not authenticated)
    public function guest()
    {
        return !$this->check();
    }

    // This method validates the user (e.g., for login)
    public function validate(array $credentials = [])
    {
        // Implement your token validation logic here
        return true; // Adjust this as per your logic
    }

    // This method checks if a user is authenticated
    public function hasUser()
    {
        return $this->user() !== null;
    }

    // This method is used to set the authenticated user
    public function setUser($user)
    {
        $this->user = $user;
    }
}
