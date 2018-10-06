<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class AuthController extends Controller {

    private $auth;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
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

}