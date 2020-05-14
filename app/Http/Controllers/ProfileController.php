<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use  App\Profile;

class ProfileController extends Controller
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
     * Get one user.
     *
     * @return Response
     */
    public function getProfile($email)
    {
        try {
            $profile = Profile::Where('email',$email)->latest('created_at')->first();;

            return response()->json(['profile' => $profile], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Profile image not found!'], 404);
        }

    }   
}


