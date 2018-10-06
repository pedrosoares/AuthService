<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller {


    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Validate if the User has permission to run a request
     * @param Request $request
     * @return JsonResponse
     */
    public function can(Request $request) {
        if($request->has("permissions")) {
            /**
             * @var string
             */
            $uri = $request->get("permissions");
            /**
             * @var User
             */
            $user = $request->user();

            $permissions = $user->Permissions()->whereIn('name', $uri)
                ->get()->count();

            if ($permissions == count($uri)) {
                return new JsonResponse([
                    "message" => "Authorized"
                ], 200);
            }
        }

        return new JsonResponse([
            "message" => "Unauthorized"
        ], 403);
    }

}