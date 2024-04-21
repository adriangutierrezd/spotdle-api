<?php

use App\Http\Controllers\ProjectController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::post('auth', function(Request $request){
    $credentials = [
        'email' => $request->email,
        'password' => $request->password
    ];

    if(Auth::attempt($credentials)){
        $user = Auth::user();

        $basicToken = $user->createToken('basic-token');

        return [
            'token' => $basicToken->plainTextToken,
            'user' => $user
        ];

    }
});

Route::post('sign-up', function(Request $request){
    $user = new User([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);

    $user->save();
});

Route::group([
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => 'auth:sanctum'
], function(){
    Route::apiResource('projects', ProjectController::class);
});
