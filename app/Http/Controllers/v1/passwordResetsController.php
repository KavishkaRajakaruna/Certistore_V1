<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Notifications\v1\Password\ResetRequest;
use App\passwordReset;
use App\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Psy\Util\Str;

class passwordResetsController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!user){
            return response() ->json([
                'message'=> 'User account not found'
            ], 404);
        }
        $passwordReset= passwordReset::updateOrCreate(
            ['email' => $user->email],
            ['email'=> $user->email,
             'token' => Str::random(60),
                'name' => $user->name,
            ]
        );
        if ($user && $passwordReset)
            $user->notify(new ResetRequest($passwordReset));
        return response() ->json([
            'message' => "Password reset link emailed"
        ],200);
    }

    public function find($token){
        $passwordRest = passwordReset::where('token', $token)->first();
        if (!$passwordRest){
            return view('passwordReset.form', ['error'=>true]);
        }
        if(Carbon::parse($passwordRest->updated_at)->addMinutes(720)->isPast()){
            $passwordRest->delete();
            return view('passwordReset.form', ['error'=> true]);
        }
        //add a view
        return response()->json([$passwordRest]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password'=> 'required|string',
            'token' => 'required'
        ]);
        $passwordReset = passwordReset::where([
            ['token'=> $request->token],
            ['email' => $request->email]
        ])->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        $user = User::where('email', $passwordReset->email)->first();

        $user ->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($user));
        return response()->json([
            'message' => 'Password reset successful'
        ],200);
    }


}
