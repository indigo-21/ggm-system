<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OrderType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Response;
use Auth;

class OrderTypeController extends Controller
{
    public function form_rule($id = false){
        return [
            "name"          => ["required", "string","min:2","max:900",Rule::unique('order_types')->ignore($id ? $id : "")],
          ];
    }

    public function change_field_name(){
        return [
            "name"          => "Order Type",
        ];
    }
    public function form_action($request, $id = false){
        // Validation
            $request->validate(self::form_rule($id),[], self::change_field_name());

        // Calling Table | When $id is false it will do Create
            $data = !$id ? new OrderType : OrderType::findOrFail($id);

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
        $data = ["order_types" => OrderType::all()];
        return view("pages.order-type.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.order-type.form");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        self::form_action($request);
        $name = $request->name." added to OrderType";
        return redirect("/order_type")->with("success", $name);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderType $orderType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = ["order_type" => OrderType::find($id)];
        return view("pages.order-type.form",$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        self::form_action($request, $id);
        $name = $request->name." successfully Updated";
        return redirect("/order_type")->with("success", $name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data   = OrderType::findOrFail($id);
        $name   = $data->name;
        $data->delete();
        return response()->json(["message"=>"$name deleted successfully"]);
    }
}
