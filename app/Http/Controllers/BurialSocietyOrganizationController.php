<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cemetery;
use App\Models\BurialSocietyOrganization;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class BurialSocietyOrganizationController extends Controller
{
    public function form_rule($id = false){
        return [
            "name"          => ["required", "string","min:2","max:900",Rule::unique('burial_society_organizations')->ignore($id ? $id : "")],
            "cemetery_id"   => ["required"]
        ];
    } 
    
    public function change_field_name(){
        return [
            "name"          => "Burial Society Organization",
            "cemetery_id"   => "Cemetery"
        ];
    }

    public function form_action($request, $id = false){
        $request->validate(self::form_rule($id),[],self::change_field_name());
        
        $data = !$id ? new BurialSocietyOrganization : BurialSocietyOrganization::findOrFail($id);

        $data->fill($request->only(["name","cemetery_id"]));

        $data->{$id ? "updated_by" : "created_by"} =  Auth::id();

        return $data->save() ?: dd("Error Found: Back End Issue!");
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ["burial_society_organizations" => BurialSocietyOrganization::all()];

        return view("pages.burial-society-organization.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = ["cemeteries" => Cemetery::all()];
        return view("pages.burial-society-organization.form",$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::form_action($request);
        $burial_society_organization_name = $request->name." added to Burial Society Organization";
        return redirect("/burial_society_organization")->with("success",$burial_society_organization_name);
    }

    /**
     * Display the specified resource.
     */
    public function show(BurialSocietyOrganization $burialSocietyOrganization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = [
                    "cemeteries"                    => Cemetery::all(),
                    "burial_society_organization"   => BurialSocietyOrganization::find($id)
                ];
        return view("pages.burial-society-organization.form",$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        self::form_action($request, $id);
        $burial_society_organization_name = $request->name." successfully updated";
        return redirect("/burial_society_organization")->with("success", $burial_society_organization_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data   = BurialSocietyOrganization::findOrFail($id);
        $name   = $data->name;
        $data->delete();
        return response()->json(['message' => "$name deleted successfully."]);
    }

    /**
     * Check if a burial society organization name already exists (case-insensitive, trimmed).
     */
    public function checkDuplicate(Request $request)
    {
        $name = trim($request->input('name', ''));

        $exists = BurialSocietyOrganization::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)])->exists();

        return response()->json(['exists' => $exists]);
    }
}
