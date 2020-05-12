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


