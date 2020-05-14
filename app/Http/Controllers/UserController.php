<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use  App\User;
use  App\Profile;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function allUsers()
    {
         return response()->json(['users' =>  User::all()], 200);
    }

    /**
     * Get one user.
     *
     * @return Response
     */
    public function singleUser($id)
    {
        try {
            $user = User::Where('id',$id)->get();

            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'user not found!'], 404);
        }

    }
   public function emailUser($email)
   {
       try {
            $user = User::Where('email',$email)->get();

             return response()->json(['user' => $user], 200);

            } catch (\Exception $e) {

             return response()->json(['message' => 'user not found!'], 404);
          }
   } 

   /**
     * Get one user.
     *
     * @return Response
     */
    public function getProfile($userid)
    {
        try {
            $profile = Profile::Where('userid',$userid)->latest('created_at')->first();
            $path=url('/').'/public/images/'. $profile->img_location;
            if (file_exists($path)) {
                $file = file_get_contents($path);
                return response($file, 200)->header('Content-Type', 'image/jpg');
              }
            //return response()->make(file_get_contents($path), 200, [
              //'Content-Type' => Storage::mimeType($profile->img_location),
             // 'Content-Disposition' => 'inline; '.$profile->img_location,]);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Profile image not found!'], 404);
        }

    }   
   
}


