<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function demoTest()
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Welcome to the Demo route. Access is limited.'
        ], 200);
    }

    public function standardTest()
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Welcome to the Standard route. You have full access.'
        ], 200);
    }

    public function sharedTest()
    {
        return response()->json([
            'Auth' => Auth::user(),
            'Db' => DB::getDefaultConnection(),
            'status' => 'success',
            'code' => 200,
            'message' => 'This is a shared route accessible by both demo and standard users.'
        ], 200);
    }
}
