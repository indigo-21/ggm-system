<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccountLevel;
use App\Models\Module;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class AccountLevelController extends Controller
{
    
    public function form_rule($id = false){
        return [
            "name"          => ["required","string","min:2","max:900",Rule::unique("account_levels")->ignore($id ? $id : "")],
            "module_ids"    => ["required"]
        ];
    }

    public function change_field_name(){
        return [
            "name"          => "Account Level",
            "module_ids"    => "Access Module"
        ];
    }

    public function form_action($request, $id = false){

        $request->validate(self::form_rule($id),[],self::change_field_name());
    
        $data = !$id ? new AccountLevel : AccountLevel::findOrFail($id);
        
        // $data->fill($request->only(["name","module_ids"]));
       
        $data->name          = $request->name;
        $data->module_ids    = is_array($request->module_ids) ? implode(',', $request->module_ids) : null;

        $data->{$id ? "updated_by" : "created_by"} = Auth::id();

        return $data->save() ?: dd("Error Found: Back End Issue!");
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data           = ["account_levels" => AccountLevel::all()];
        $is_restricted  = User::restricted(2);

        if($is_restricted){
            abort(403, 'Unauthorized action.');
        }
        
        return view('pages.account-level.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            "modules" => Module::all()
        ];

        return view('pages.account-level.form' ,$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::form_action($request);
        $account_level = $request->name." added to account Level";
        return redirect("/account_level")->with("success", $account_level);
    }       

    /**
     * Display the specified resource.
     */
    public function show(AccountLevel $accountLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = [
            "modules"        => Module::all(),
            "account_level" => AccountLevel::find($id)
        ];

        return view('pages.account-level.form' ,$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        self::form_action($request, $id);
        $account_level = $request->name." successfully updated";
        return redirect("/account_level")->with("success", $account_level);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountLevel $accountLevel)
    {
        //
    }
}
