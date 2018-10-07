<?php

namespace App\Http\Controllers;
use App\Permission;
use App\User;
use App\UserPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller;

class AuthController extends Controller {

    private $auth;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->auth = app("auth");
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $credentials = $request->only(['email', 'password']);

        if (!$token = $this->auth->attempt($credentials)) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me() {
        return new JsonResponse($this->auth->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        $this->auth->logout();

        return new JsonResponse(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->respondWithToken($this->auth->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token) {
        return new JsonResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60
        ]);
    }

    /**
     * Create a new User
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users'
        ]);
        try {
            $user = null;
            //Transaction is used if the Permission insert fail we remove the user
            app('db')->transaction(function() use (&$user, $request) {
                // Create user
                $user = User::create([
                    'name' => $request->get("name"),
                    'email' => $request->get("email"),
                    'password' => app("hash")
                        ->make($request->get("password")),
                ]);
                // Add user Permission to validate Login
                UserPermission::create([
                    "user_id" => $user->id,
                    "permission_id" => Permission::AUTH
                ]);
            });
            return new JsonResponse([
                "message" => "User registered successfully",
                "user" => $user
            ], 200);
        }catch (\Exception $exception) {
            Log::error($exception);
            return new JsonResponse([
                "message" => "Something went wrong!",
                "user" => null
            ], 500);
        }
    }

}