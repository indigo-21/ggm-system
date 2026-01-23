<?php

namespace App\Services;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function contacts_validation($id, $type = "email") {

        $contact_type = 1;
        $description = "email";

        if($type == "mobile_no"){
            $contact_type = 2;
            $description = "mobile no.";
        }

        if($type == "tel_no"){
            $contact_type = 3;
            $description = "tel no.";

        }

        return function ($attribute, $value, $fail) use ($id, $contact_type, $description) {

                    // Split emails by semicolon
                    $contacts = array_filter(array_map('trim', explode(';', $value)));

                    foreach ($contacts as $contact) {

                        // Validate email format
                        if ($contact_type === 1 && !filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                            $fail("The email $contact is not a valid email address.");
                            return;
                        }

                         if ($contact_type === 2 && !preg_match('/^[0-9\-()+ ]{7,20}$/', $contact)) {
                            $fail("The $description $contact is not a valid mobile number.");
                            return;
                        }

                        if ($contact_type === 3 && !preg_match('/^[0-9\-()+ ]{7,20}$/', $contact)) {
                            $fail("The $description $contact is not a valid telephone number.");
                            return;
                        }

                        // Check uniqueness in DB
                        $exists = DB::table('customer_contacts')
                            ->where('contact_type', $contact_type)
                            ->where('contact_value', $contact)
                            ->when($id, fn($q) => $q->where('customer_id', '!=', $id))
                            ->exists();

                        if ($exists) {
                            $fail("The $description '$contact' has already been taken.");
                            return;
                        }
                    }
                }  ;

    }

    public function form_rule($id = false)
    {
        
        return [
            "lastname" => ["required", "string"],
            "email"    => ["nullable","string", self::contacts_validation($id, "email")],
            "mobile_no"=> ["nullable","string", self::contacts_validation($id, "mobile_no")],
            "tel_no"   => ["nullable","string", self::contacts_validation($id, "tel_no")],
        ];

    }

    public function form_action($request, $id = false)
    {
        $result = false;
        $issue = "Parent table";
        
        // try {
        //     $request->validate(self::form_rule($id));
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     dd($e->errors());
        // }

        $request->validate(self::form_rule($id));

        $data = !$id ? new Customer() : Customer::findOrFail($id);
        $data->title = $request->title;
        $data->firstname = $request->firstname;
        $data->lastname = $request->lastname;
        $data->salutation = $request->salutation;
        $data->address_one = $request->address_1;
        $data->address_two = $request->address_2;
        $data->city_county = $request->city_county;
        $data->postcode = $request->post_code;

        $data->{$id ? "updated_by" : "created_by"} = Auth::id();
       
        if ($data->save()) {
           if(!$request->email && !$request->mobile_no && !$request->tel_no){
            $result = true;
           }else{
                $issue = "Children table";
                $customer_id = $data->id;
                $contacts = [
                    "email" => $request->email ? explode(";", $request->email) : [],
                    "tel_no" => $request->tel_no ? explode(";", $request->tel_no) : [],
                    "mobile_no" => $request->mobile_no ? explode(";", $request->mobile_no) : [],
                ];
                DB::table("customer_contacts")->where("customer_id", $customer_id)->delete();
                $result = self::customerContacts($contacts, $customer_id);
                // if(!$result){
                //     DB::table("customers")->where("id", $customer_id)->delete();
                // }
           }
        }
        return $result ? $data->id : false;
    }

    public function customerContacts($contacts, $customer_id) {
        $emails = $contacts["email"];
        $mobile_nos = $contacts["mobile_no"];
        $tel_nos = $contacts["tel_no"];
        $data = [];

        if($emails){
            foreach ($emails as $key => $email) {
                $temp = [
                    "customer_id" => $customer_id,
                    "contact_type" => 1,
                    "contact_value" => $email
                ];
                array_push($data, $temp);
            }
        }

        if($mobile_nos){
            foreach ($mobile_nos as $key => $mobile_no) {
                $temp = [
                    "customer_id" => $customer_id,
                    "contact_type" => 2,
                    "contact_value" => $mobile_no
                ];
                array_push($data, $temp);
            }
        }
        
        if($tel_nos){
            foreach ($tel_nos as $key => $tel_no) {
                $temp = [
                    "customer_id" => $customer_id,
                    "contact_type" => 3,
                    "contact_value" => $tel_no
                ];
                array_push($data, $temp);
            }
        }

        return DB::table("customer_contacts")->insert($data);
    }


}
