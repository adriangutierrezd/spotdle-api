<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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
            'data' => [
                'token' => $basicToken->plainTextToken,
                'user' => $user
            ],
            'status' => 200,
            'message' => 'Credenciales correctas'
        ];
    }

    return [
        'data' => [],
        'status' => 401,
        'message' => 'Las credenciales no son correctas'
    ];
});

Route::post('sign-up', function(Request $request){

    try{
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    
        $user->save();

        return [
            'data' => $user,
            'status' => 201,
            'message' => 'Recurso creado con éxito'
        ];
    }catch(Exception $e){
        return [
            'data' => [],
            'status' => 500,
            'message' => 'Ha ocurrido un error'
        ];
    }
});

Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => 'auth:sanctum'
], function(){
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);

});
