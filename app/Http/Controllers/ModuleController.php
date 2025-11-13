<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function form_rule($id = false){
        return [
            "name"          => ["required", "string","min:2","max:900",Rule::unique('modules')->ignore($id ? $id : "")],
            "route_name"    => ["required", "string","min:2","max:900",Rule::unique('modules')->ignore($id ? $id : "")],
        ];
    }

    public function change_field_name(){
        return [
            "name"          => "Module Name",
            "route_name"    => "Access Modules"
        ];
    }

    public function form_action($request, $id = false){
        // Validation
            $request->validate(self::form_rule($id),[], self::change_field_name());

        // Calling Table | When $id is false it will do Create
            $data = !$id ? new Module : Module::findOrFail($id);

        // Filling Fields
            $data->fill($request->only(["name","route_name"]));

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
        $data = [
            "modules" => Module::all()
        ];

        $is_restricted  = User::restricted(1);

        if($is_restricted){
            abort(403, 'Unauthorized action.');
        }

        return view('pages.module.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.module.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::form_action($request);
        $module_name = $request->name." added to module.";
        return redirect("/module")->with("success", $module_name);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {   
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = ["module"=>Module::find($id)];
        return view('pages.module.form' ,$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        self::form_action($request ,$id);
        $module_name  = $request->name." Successfully Updated";

        return redirect()->route('module.index')->with('success', $module_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        //
    }
}
