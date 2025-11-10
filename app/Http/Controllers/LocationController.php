<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Response;
use Auth;

class LocationController extends Controller
{

    public function form_rule($id = false){
        return [
            "name"          => ["required", "string","min:2","max:900",Rule::unique('locations')->ignore($id ? $id : "")],
          ];
    }

    public function change_field_name(){
        return [
            "name"          => "Location Name",
        ];
    }

    public function form_action($request, $id = false){
        // Validation
            $request->validate(self::form_rule($id),[], self::change_field_name());

        // Calling Table | When $id is false it will do Create
            $data = !$id ? new Location : Location::findOrFail($id);

        // Filling Fields
            $data->fill($request->only(["name"]));

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
        $data = ["locations" => Location::all()];

        $is_restricted  = User::restricted(3);

        if($is_restricted){
            abort(403, 'Unauthorized action.');
        }

        return view("pages.location.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.location.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::form_action($request);
        $location_name = $request->name." added to Location.";
        return redirect("/location")->with("success", $location_name);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = ["location" => Location::find($id)];
        return view('pages.location.form' ,$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        self::form_action($request ,$id);
        $location_name  = $request->name." Successfully Updated";
        return redirect()->route('location.index')->with('success', $location_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
    }
}
