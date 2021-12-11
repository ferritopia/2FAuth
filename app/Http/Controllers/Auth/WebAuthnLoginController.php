<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DarkGhostHunter\Larapass\Http\AuthenticatesWebAuthn;

class WebAuthnLoginController extends Controller
{
    // use AuthenticatesWebAuthn;
    use AuthenticatesWebAuthn {
		options as traitOptions;
		login as traitLogin;
	}

    /*
    |--------------------------------------------------------------------------
    | WebAuthn Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller allows the WebAuthn user device to request a login and
    | return the correctly signed challenge. Most of the hard work is done
    | by your Authentication Guard once the user is attempting to login.
    |
    */

    public function __construct()
    {
        // $this->middleware(['guest', 'throttle:10,1']);
    }


	public function options(Request $request)
	{
        // $user = User::first();

        // if (!$user) {
        //     return response()->json([
        //         'message' => 'no registered user'//__('errors.cannot_decipher_secret')
        //     ], 400);
        // }
        // else $request->merge(['email' => $user->email]);

		return $this->traitOptions($request);
	}


    /**
     * Log the user in.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if ($request->has('response')) {
            $response = $request->response;

            if(!$response['userHandle']) {
                $user = User::getFromCredentialId($request->id);
                $response['userHandle'] = base64_encode($user->userHandle());
                $request->merge(['response' => $response]);
            }
        }

        return $this->traitLogin($request);
    }
}