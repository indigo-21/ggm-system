<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GraveSpace;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class GraveSpaceController extends Controller
{   
    
    public function form_rule($id = false){
        return [
            "name"          => ["required", "string","min:2","max:900",Rule::unique('grave_spaces')->ignore($id ? $id : "")],
          ];
    }

    public function change_field_name(){
        return [
            "name"          => "Grave Space Name",
        ];
    }

    public function form_action($request, $id = false){
        // Validation
            $request->validate(self::form_rule($id),[], self::change_field_name());

        // Calling Table | When $id is false it will do Create
            $data = !$id ? new GraveSpace : GraveSpace::findOrFail($id);

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
        $data = ["grave_spaces" => GraveSpace::all()];
        return view("pages.grave-space.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.grave-space.form");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::form_action($request);
        $grave_space_name = $request->name." added to Grave Space";
        return redirect("/grave_space")->with("success", $grave_space_name);
    }

    /**
     * Display the specified resource.
     */
    public function show(GraveSpace $graveSpace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = ["grave_space"=> GraveSpace::find($id)];
        return view("pages.grave-space.form",$data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        self::form_action($request, $id);
        $grave_space_name = $request->name." successfully Updated";
        return redirect("/grave_space")->with("success", $grave_space_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data   = GraveSpace::findOrFail($id);
        $name   = $data->name;
        $data->delete();
        return response()->json(["message"=>"$name deleted successfully"]);
    }
}
