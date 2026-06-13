<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request data
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,client,trainer,receptionist,manager',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validatedData->errors(),
            ], 422);
        }
        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User registration failed',
            ], 500);
        }

        // Generate a JWT token for the user
        $token = JWTAuth::fromUser($user);

        // Return the token in the response
        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => $user,
            'autorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    public function login(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validatedData->errors(),
            ], 422);
        }
        // Validate the request data
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password',
            ], 401);
        }

        // Return the token in the response
        $user = JWTAuth::user();
        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 200);
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);

            return response()->json([
                'status' => 'success',
                'message' => 'Se cerro la sección satisfactoriamente',
            ], 200);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo cerrar sesión, token no proporcionado o inválido.'
            ], 400);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json([
                'status' => 'success',
                'user' => JWTAuth::user(),
                'authorization' => [
                    'token' => $newToken,
                    'type' => 'bearer',
                ]
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to refresh token, token invalid or expired.',
                $e->getMessage()
            ], 400);
        }
    }

    public function show(Request $request)
    {

        $user = Auth::user();

        if (! $user instanceof User) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no autenticado',
            ], 401);
        }

        switch ($user->role) {
            case 'client':
                $user = User::with('client')->find($user->id);
                return response()->json([
                    'status' => 'success',
                    'role' => $user->role,
                    'user' => $user->load('client'),
                ]);

            case 'trainer':
                $user = User::with('trainer')->find($user->id);
                return response()->json([
                    'status' => 'success',
                    'role' => $user->role,
                    'user' => $user->load('trainer'),
                ]);

            case 'admin':
            case 'receptionist':
            case 'manager':
                return response()->json([
                    'status' => 'success',
                    'role' => $user->role,
                    'user' => $user,
                ]);

            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rol no asignado o inválido'
                ], 403);
        }
    }
}
