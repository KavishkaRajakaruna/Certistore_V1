<?php

namespace App\Http\Controllers\v1;

use App\Certificate;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\certificateResourcesCollection;
use App\Http\Resources\Api\v1\certificateResources;
use Brick\Math\Exception\DivisionByZeroException;
use App\User;
//use Defuse\Crypto\File;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;

class certificatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('adminAuth')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function index()
    {
        try {
            return (new certificateResourcesCollection(Certificate::paginate()))
                ->response()
                ->setStatusCode(200);
        } catch (Execption $exception){
            return response() ->json([
                'message' => 'Internal Error'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'certiName' => 'required',
            'source' => 'required',
            'type' => 'required',
            'level' => 'required',
            'file' => 'required|mimes:jpg,jpeg|max:5000',
        ]);

        if ($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }

        try {
            $path = Storage::putFile('/userAssets/'.$request->user()->userId.'/certificates',new File($request->file));
            $certificate = new Certificate($request->all());
            $certificate->location = $path;
            $certificate->user_id= $request->user()->id;
            $certificate->save();
            return response()->json([
                'messsage' => 'File upload success'
            ], 200);
        } catch(Exception $exception){
            return response() ->json([
                'message' => 'File upload error. Internal Error'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function show(){
//    {   $a = 0;
//        $certificates= Certificate::find('user_id', Auth::id() );
//        foreach ($certificates as $viewCertificate){
//            $url = Storage::temporyUrl($viewCertificate->location, now()->addMinutes(30));
        if (Certificate::where('user_id', Auth::id() )->count() == 1){
            return (new certificateResources(certificate::where('user_id', Auth::id())->first()))
                                            ->response()
                                            -> setStatusCode('200');
        }
        elseif (Certificate::where('user_id', Auth::id())->count() > 1){
            return (new certificateResourcesCollection(Certificate::where('user_id', Auth::id())))
                ->response()
                ->setStatusCode('200');
        }
        else{
            return response() ->json([
                'message' => ' No records'
            ],200);
        }



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validator = Validator::make($request->all(), [
            //'userId' => 'required',
            'certiName'=> 'required',
            'source' => 'required',
            'type' => 'required',
            'level' => 'required',
            'file' => 'required|mimes:jpg,jpeg|max:5000',
        ]);
        if ($validator->fails()){
            return response() ->json([
                'message' => $validator->errors(),
            ],401);
        }
        $certificate->update($validator);
        return response() ->json([
            'message' => 'Certificate info updated successfully',
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        Storage::delete($certificate->location);
        return response()->json([
            'message' => 'Certificate deleted',
        ],200);
    }

     public function shareCertificate(Request $request){
        $certificateCollection[]=0;
        $validation = Validator::make($request->all(),[
            'user' => 'required',
            'to' => 'required',
            'certificates' => 'required'
        ]);

        //geenerate tempory  url
         $a = 0;
        foreach ($validation->certificates as $newCertificate){

            $certificate = Certificate::find('user_id' , $newCertificate);
            $url =Storage::temporyUrl($certificate->location, now()->addHours(3) );
            $certificateCollection[$a] = ['details'=>
                                                    ['id'=>$certificate->id,
                                                      'name' => $certificate->certiName,
                                                      'issued' => $certificate->source,
                                                      'type' => $certificate->type,
                                                      'level' => $certificate->level,  ],
                                         'url'=> $url,];
            $a =+1;
        }
        //Save to the database
        DB::table('share_certificates')->insert([
            'user_id' => $validation->user,
            'viewer_id' => $validation->to,
            'parameters' => $certificateCollection,
        ]);

//        $this->minusPoints();

         return response()->json(
             ['message' => 'Share successful',
         ],200);
     }
    //function to reduce points upon upload/share
    //$id => User ID , $amount=> number of shares or uploads , $method =>upload / share
    public function minusPoints($id , $amount, $method){}

    //function to add points upon deletion
    //$id => User ID , $amount=> number of shares or uploads
    public function addPoints($id, $amount){}
}
