<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\activeUser;


class userLogIn extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        if(Auth::attempt(['email'=> request('email'), 'password' => request('password')])){
            $user= Auth::user();
            $accessToken = Auth::User()->createToken('authToken')->accessToken;
            $this->activeUsers($user->id);
            return response() ->json([
               'user'=>$user,
               'access_token'=> $accessToken,
                'message' => 'LogIn Successful'
            ], 200);

        }
        return response() -> json(['message' => 'Invalid Credentials'] , 401);
    }

    private function activeUsers($id)
    {
        $activeUser = new activeUser;
        $activeUser->uid = $id;
        $activeUser->active = true;
        $activeUser->save();

    }


}
