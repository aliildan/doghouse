<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Objects\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Login user and create token
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Wrong email or password! Please try again.',
            ], 403);
        }

        $user = Auth::user();
        $response = new ApiResponse(
            [
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
            ], 'Successfully logged in!');
        return response()->json($response->jsonSerialize());
    }

    /**
     * Register user.
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $response = new ApiResponse(['user' => $user], 'User created successfully!');
        return response()->json($response->jsonSerialize());
    }

    /**
     * Logout user (Invalidate the token)
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        $response = new ApiResponse(null, 'Successfully logged out!');
        return response()->json($response->jsonSerialize());
    }

    /**
     * Refresh token
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $response = new ApiResponse(
            [
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ],
            ], 'Successfully refreshed token!');
        return response()->json($response->jsonSerialize());

    }
}
