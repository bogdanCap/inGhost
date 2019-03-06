<?php

namespace App\Http\Controllers\Data;

use App\Events\UserActivity;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataController extends Controller
{
    public function getData(\Illuminate\Http\Request $request)
    {
        return \Illuminate\Support\Facades\Response::json([
            'hello' => ['ok'],
            'user' => $request->user()
        ], 200); // Status code here
    }
}
