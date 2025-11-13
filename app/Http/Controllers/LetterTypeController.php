<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LetterType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
class LetterTypeController extends Controller
{   
    public function form_rule($id = false){
        return [
            "name"          => ["required", "string","min:2","max:900",Rule::unique('letter_types')->ignore($id ? $id : "")],
          ];
    }

    public function change_field_name(){
        return [
            "name"          => "Letter Type Name",
        ];
    }
    public function form_action($request, $id = false){
        // Validation
            $request->validate(self::form_rule($id),[], self::change_field_name());

        // Calling Table | When $id is false it will do Create
            $data = !$id ? new LetterType : LetterType::findOrFail($id);

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
        $data = ["letter_types" => LetterType::all()];
        return view("pages.letter-type.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.letter-type.form");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::form_action($request);
        $name = $request->name." added to Letter Type";
        return redirect("/letter_type")->with("success", $name);
    }

    /**
     * Display the specified resource.
     */
    public function show(LetterType $letterType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = ["letter_type"=> LetterType::find($id)];
        return view("pages.letter-type.form",$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        self::form_action($request, $id);
        $name = $request->name." successfully Updated";
        return redirect("/letter_type")->with("success", $name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data   = LetterType::findOrFail($id);
        $name   = $data->name;
        $data->delete();
        return response()->json(["message"=>"$name deleted successfully"]);
    }
}
