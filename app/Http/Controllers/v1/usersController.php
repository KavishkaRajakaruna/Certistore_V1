<?php

namespace App\Http\Controllers\v1;

use App\activeUser;
use App\Events\NewUserCreated;

use App\Events\NewUserCreatedEvent;
use App\Http\Controllers\Controller;
use App\User;
use App\activateUser;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Notifications\verifyEmail;
use Illuminate\Support\Str;



class usersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function __construct()
    {
        $this->middleware('auth:api')->except('store', 'signupActivate');
        $this->middleware('adminAuth')->only('index');
        $this->middleware('accountActivate')->except('store', 'signupActivate');
    }

    public function index(Request $request)
    {

        $users = User::paginate(20);
        return response() ->json([
           $users,
            'message' => 'success',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required',
            'contactNumber' => 'required|min:10|numeric',
            'nic' => 'required',
            'email' => 'required|email|unique:users',
            'user_type' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);
        if ($validator->fails()){
            return response()->json(['error' =>$validator->errors()],401);
        }

        $input = $request->all();
        $input['userId'] = $this->userSelect($input['user_type']);
        $input['password'] = Hash::make($input['password']);
        $input['nic'] = strtolower($input['nic']);
        $user =  User::create($input);
        $userId = $this->userSelect($input['user_type']).'0'.$user->id ;
        $user->update(['userId' => $userId]);
        $accesstoken = $user->createToken('authToken')->accessToken;
        event(new NewUserCreatedEvent($user));

//User Activation email verify________
        $activation = new activateUser([
            'uid' => $user->id,
            'activation_token' => Str::random(60),
        ]);
        $activation ->save();

        $user->notify(new verifyEmail($activation));

//------------------------------
        return response()->json([
            'user' => $user,
            'message' => 'User created ',
            'access_token'=> $accesstoken,
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        if(Auth::id() == $user->id){
            return response()->json([
                'user' => $user,
            ], 200);
        } else{
            return response()->json([
                'message' => "User Unauthorized",

            ],401);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|',
            'email' => 'required|email|unique:users',

        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
                $request->request,
            ], 401);
        }


            if(Auth::id()!= $user->id){
                return response()->json([
                    'message' => 'user unauthorized',
                ], 401);
            }else {
                try {

                    $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                    ]);
                    return response()->json([
                        'message' => 'user ' . $user->name . ' updated'

                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        'error' => $e
                    ], 500);
                }
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if(Auth::id()!=$user->id){
            return response()->json([
                'message' => 'not authorized',
            ],401);
        }else{
            $user->delete();
            return response()->json([
                'message' => 'Deletion Success',
                'duration' => '7',
            ],200);
        }

    }

//    public function updateState()
//    {
//
//    }

//    private function deactiveUsers(activeUser $activeUser)
//    {
//        $activeUser->delete();
//    }

    private function userSelect($type)
    {
        if($type == 0)
            return 'U';
        elseif ($type == 1)
            return 'R';
        else
            return 'I';
    }

    public function signupActivate($token){
        $token = activateUser::where('activation_token', $token)->first();
        activateUser::where('uid' , $token->id)->delete();
        if(!$token){
            return response()->json([
               'message'=> 'Activation token invalied'
            ], 404);
        }
        User::where('id', $token->uid)
            ->update(['active'=> true]);
//        return response()->json([
//            'message' => 'Activation successful'
//        ], 200);
        // Put redirect
    }
}
