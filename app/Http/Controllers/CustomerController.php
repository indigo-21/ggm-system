<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
   

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            "months"      => ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            "years"       => ["2024", "2025", "2026"],
            "customers"   => Customer::all()
        ];
        return view("pages.customer.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            "titles"        => ["None", "Mr", "Mrs", "Miss", "Ms", "Dr"],
            "months"        => ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            "years"         => ["2024", "2025", "2026"],
        ];
        return view("pages.customer.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CustomerService $customer) {
        $result = $customer->form_action($request);
        return redirect("/customer")->with("success", $result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $customer = Customer::find($id);
        $emails   = $customer->customer_contacts
                    ->where("contact_type", 1)
                    ->pluck('contact_value')   // extract the email values
                    ->toArray();
        $mobile_nos   = $customer->customer_contacts
                    ->where("contact_type", 2)
                    ->pluck('contact_value')   // extract the mobile values
                    ->toArray();
        $tel_nos   = $customer->customer_contacts
                    ->where("contact_type", 3)
                    ->pluck('contact_value')   // extract the tel_no values
                    ->toArray();
        $data = [
            "titles"        => ["None", "Mr", "Mrs", "Miss", "Ms", "Dr"],
            "months"        => ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            "years"         => ["2024", "2025", "2026"],
            "customer"      => $customer,
            "customer_emails" => implode(";", $emails),
            "customer_mobile_nos" => implode(";", $mobile_nos),
            "customer_tel_nos" => implode(";", $tel_nos),
        ];
         return view("pages.customer.form",$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, CustomerService $customer )
    {
        $result = $customer->form_action($request, $id);
        return redirect("/customer")->with("success", $result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
