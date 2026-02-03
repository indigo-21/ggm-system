<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Models\User;
use App\Models\OrderType;
use App\Models\Location;
use App\Models\Cemetery;
use App\Models\BurialSocietyOrganization;
use App\Models\Accessory;
use App\Models\BasedLedger;
use App\Models\GraveSpace;
use App\Models\LetterType;
use App\Models\Material;
use App\Models\Colour;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Order;
use App\Models\OrderCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService;

class QuoteController extends Controller
{

    public function default_required_data($isFrom = "index", $id = false){
        session()->forget('success');
        $data = [
                    "order_types" => OrderType::all(),
                    "users"       => User::all(),
                    "months"      => ["January","February","March","April","May","June","July","August","September","October","November","December"],
                    "years"       => ["2024","2025","2026"],
                ];
        if($isFrom == "form"){
            $data += [
                    "auth_session"                      => User::find(Auth::id()),
                    "locations"                         => Location::all(),
                    "cemeteries"                        => Cemetery::all(),
                    "burial_society_organizations"      => BurialSocietyOrganization::all(),
                    "grave_spaces"                      => GraveSpace::all(),
                    "letter_types"                      => LetterType::all(),
                    "materials"                         => Material::all(),
                    "base_ledgers"                      => BasedLedger::all(),
                    "accessories"                       => Accessory::all(),
                    "colours"                           => Colour::all(),
                    "customers"                         => Customer::all(),
                    "titles"                            => ["None", "Mr","Mrs","Miss","Ms","Dr"],
            ];
            if($id) {
                $quote    = Order::findOrFail($id);
                $emails   = CustomerContact::where("customer_id", $quote->customer_id)
                            ->where("contact_type", 1)
                            ->pluck('contact_value')   // extract the email values
                            ->toArray();
                $mobile_nos   = CustomerContact::where("customer_id", $quote->customer_id)
                            ->where("contact_type", 2)
                            ->pluck('contact_value')
                            ->toArray();
                $tel_nos   = CustomerContact::where("customer_id", $quote->customer_id)
                            ->where("contact_type", 3)
                            ->pluck('contact_value')
                            ->toArray();
                $data += [
                            "quote" => $quote,
                            "order_cost" => OrderCost::find($id),
                            "customer_email" => $emails ? implode(";", $emails): "",
                            "customer_mobile_no" => $mobile_nos ? implode(";", $mobile_nos) : "",
                            "customer_tel_no" => $tel_nos ? implode(";", $tel_nos) : "",
                        ];
            }
        }else{
            // $data["qoutes"] = Order::all();
        
            $data += [
                        "quotes" => Order::all()
                    ];
        }
        
        return $data;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $data = self::default_required_data();
        return view("pages.quote.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $data = self::default_required_data("form");
        return view("pages.quote.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, OrderService $order_service)
    {

        $result = $order_service->order_upsert($request);
        if(!$result["success"]){
            return redirect()->back()->with('error', $result["message"]);
        }else{
            $data = self::default_required_data();
            // return view("pages.quote.index", $data)->with("success", $result["message"]);
            //  return redirect()->route("quote.edit", $result["order_id"])->with("success", $result["message"], $data);
             return redirect()
                ->route('quote.edit', $result['order_id'])
                ->with('success', $result['message'])
                ->with('data', $data);
        }
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
        $data = self::default_required_data("form", $id);
        // dd($data["quote"]->order_note);
        return view("pages.quote.form", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, OrderService $order_service)
    {
      
        $result = $order_service->order_upsert($request, $id);
        if(!$result["success"]){
            return redirect()->back()->with('error', $result["message"]);
        }else{
            $data = self::default_required_data();
            session()->flash('success', $result['message']);
            // return view("pages.quote.index", $data);
            return redirect()->route("quote.edit", $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
