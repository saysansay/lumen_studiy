<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;
use  App\Profile;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }

    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function uploadProfile(Request $request) {
        $this->validate($request, array(
            'email' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ));
          //save the data to the database
            $profile  = new Profile ;
            $profile->email = $request->email;
        if(!$request->hasFile('image')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        $file = $request->file('image');
        if(!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
        }
        
        $path = base_path().'/public/images';
        $file->move($path, $file->getClientOriginalName());
        $profile->img_location =$file->getClientOriginalName();
        $profile->save();
        return response()->json(['message' => 'Upload File Success!'], 200);
     }
    

     public function store(Request $request)
    {
      $this->validate($request, array(
        'email' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ));
      //save the data to the database
        $profile  = new Profile ;
        $profile->email = $request->email;

        if($request->hasFile('image')){
          $image = $request->file('image');
          $filename = time() . '.' . $image->getClientOriginalExtension();
          Image::make($image)->resize(300, 300)->save( base_path().'/public/images' . $filename  );
          $profile->img_location = $filename;
          $profile->save();
        };

      $profile->save();

      response()->json(['profile' =>  Profile::all()], 200);
    }
     
}
