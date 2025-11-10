<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\Location;
use App\Models\AccountLevel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
// use Response;
// use Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function form_rule($id = false){
        
        $data = [
            "firstname"     => ["required", "string","min:2","max:900"],
            "lastname"      => ["required", "string","min:2","max:900"],
            "username"      => ["required", "string","min:2","max:900",Rule::unique('users')->ignore($id ? $id : "")],
            "email"         => ["required", "string","email","min:2","max:900",Rule::unique('users')->ignore($id ? $id : "")],
            "account_level" => ["required"],
            "location"      => ["required"],
        ];

        if(!$id){
            // $data["password"] = ["required", "confirmed", Password::min(8)->mixedCase()->letters()->numbers()->symbols()];
            $data["password"] = ["required", "confirmed", Password::min(8)->mixedCase()];
        }

        return $data;
    }

    public function change_field_name($id = false){
        $data = [
                    "firstname"     => "Firstname",
                    "lastname"      => "Lastname",
                    "username"      => "Username",
                    "email"         => "Email",
                    "account_level" => "Access Level",
                    "location"      => "Location"
                ];

        if(!$id){
           $data["password"]  = "Password";
        }

        return $data;
    }

    public function form_action($request, $id = false){
        
        // Validation
            $request->validate(self::form_rule($id),[], self::change_field_name($id));

           
        // Calling Table | When $id is false it will do Create
            $data = !$id ? new User() : User::findOrFail($id);
        // Filling Fields
        $data->firstname        = $request->firstname;
        $data->lastname         = $request->lastname;
        $data->username         = $request->username;
        $data->email            = $request->email;
        if(!$id){
            $data->password     = Hash::make($request->password);
        }
        $data->account_level_id = $request->account_level;
        $data->location_id      = $request->location;

        // Add exclude on the field Form
            $data->{$id ? "updated_by" : "created_by"} = Auth::id();
        
        // Saving alterations
            return $data->save() ?: dd("Error Found: Back End Issue!");

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ["users" => User::all()];

        return view("pages.user.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
                    "locations"         => Location::all(),
                    "account_levels"    => AccountLevel::all(),
                ];
        return view('pages.user.form' , $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::form_action($request);
        $user_fullname = $request->firstname." ". $request->lastname." added to user.";
        return redirect("/user")->with("success", $user_fullname);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = [
                    "user" => User::find($id),
                    "locations"         => Location::all(),
                    "account_levels"    => AccountLevel::all(),
                ];
        return view('pages.user.form' ,$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        self::form_action($request ,$id);
        $user_fullname = $request->firstname." ". $request->lastname." succefully updated";
        return redirect()->route('user.index')->with('success', $user_fullname);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
