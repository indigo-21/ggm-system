<?php

namespace App\Services;
use App\Models\Order;
use App\Models\OrderCost;
use App\Models\OrderNote;
use App\Models\Cemetery;
use App\Models\BurialSocietyOrganization;
use App\Services\CustomerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class OrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct( protected CustomerService $customerService)
    {}

    public function order_upsert($request, $id = false){

        // $customer_id = $request->customer_id;
        $date_of_death = $request->date_of_death;
        $grave_number_checked = $request->grave_no_checked;
        $fixing_date = $request?->fixed_required_by ?? null;
        $customer_id = $this->customerService->form_action($request, $request->customer_id);
        
        if($date_of_death){
            $date_of_death = str_replace(['AM', 'PM'], '', $date_of_death);
            $date_of_death = trim($date_of_death).' 12:00 '.(str_contains($request->date_of_death, 'PM') ? 'PM' : 'AM'); 
        }

        if($fixing_date != null){
            $fixing_date = str_replace(" ", ' 01, ', $request?->fixed_required_by);
        }

        // Handle custom cemetery ("Others" option)
        $cemetery_id = $request->cemetery_id;
        if ($cemetery_id === 'others') {
            $custom_cemetery_name = trim($request->custom_cemetery_name ?? '');

            if (empty($custom_cemetery_name)) {
                return [
                    "success" => false,
                    "message" => "Please enter a cemetery name when selecting 'Others'.",
                    "order_id" => $id
                ];
            }

            // Case-insensitive duplicate check
            $existing = Cemetery::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($custom_cemetery_name)])->first();

            if ($existing) {
                return [
                    "success" => false,
                    "message" => "This cemetery already exists. Please select it from the list.",
                    "order_id" => $id
                ];
            }

            // Create the new cemetery
            $new_cemetery = new Cemetery();
            $new_cemetery->name = $custom_cemetery_name;
            $new_cemetery->created_by = Auth::id();
            $new_cemetery->save();

            $cemetery_id = $new_cemetery->id;
        }


        $data = !$id ? new Order() : Order::findOrFail($id);

        $data->order_type_id = $request->order_type_id;
        $data->location_id = $request->location_id;

        if($id){
            $data->invoice_no = $request->invoice_no;
            $data->invoice_date = Carbon::parse($request->invoice_date)->format('Y-m-d');
        }

        $data->customer_id = $customer_id;
        $data->date_of_death = $date_of_death ? Carbon::parse($date_of_death)->format('Y-m-d h:i:s') : null;
        $data->deceased_name = $request->deceased_name;

        // Handle No Consecration workflow
        $no_consecration = $request->has('no_consecration') && $request->no_consecration;

        if ($no_consecration) {
            // Clear consecration date when No Consecration is checked
            $data->consecration_date = null;

            // Reset all flags first
            $data->is_tba = 0;
            $data->is_approx = 0;
            $data->is_asap = 0;

            if (isset($request->fixed_date)) {
                switch ($request->fixed_date) {
                    case 'is_tba':
                        $data->is_tba = 1;
                        break;
                    case 'is_approx':
                        $data->is_approx = 1;
                        break;
                    default:
                        $data->is_asap = 1;
                        break;
                }
            }

            // Only store fixing_date when Approx is selected
            if ($request->fixed_date === 'is_approx' && $fixing_date != null) {
                $data->fixing_date = Carbon::parse($fixing_date)->format('Y-m-d');
            } else {
                $data->fixing_date = null;
            }
        } else {
            // Normal consecration workflow
            $data->consecration_date = $request->consecration_date ? Carbon::parse($request->consecration_date)->format('Y-m-d') : null;

            // Clear radio-related fields
            $data->is_tba = 0;
            $data->is_approx = 0;
            $data->is_asap = 0;
            $data->fixing_date = null;
        }

        $data->cemetery_id = $cemetery_id;


        // Handle custom burial society organization ("Others" option)
        $burial_society_organization_id = $request->burial_society_organization_id;
        if ($burial_society_organization_id === 'others') {
            $custom_bs_name = trim($request->custom_burial_society_name ?? '');

            if (empty($custom_bs_name)) {
                return [
                    "success" => false,
                    "message" => "Please enter a burial society organization name when selecting 'Others'.",
                    "order_id" => $id
                ];
            }

            // Case-insensitive duplicate check
            $existing_bs = BurialSocietyOrganization::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($custom_bs_name)])->first();

            if ($existing_bs) {
                return [
                    "success" => false,
                    "message" => "This burial society organization already exists. Please select it from the list.",
                    "order_id" => $id
                ];
            }

            // Create the new burial society organization associated with the cemetery
            $new_bs = new BurialSocietyOrganization();
            $new_bs->name = $custom_bs_name;
            $new_bs->cemetery_id = $cemetery_id;
            $new_bs->created_by = Auth::id();
            $new_bs->save();

            $burial_society_organization_id = $new_bs->id;
        }


        $data->burial_society_organization_id = $burial_society_organization_id;
        $data->grave_number = $request->grave_no;
        $data->grave_number_checked = Carbon::parse($request?->grave_no_checked)->format('Y-m-d') ?? null;
        $data->grave_space_id = $request->grave_space_id;
        $data->design_headstone = $request->design_headstone;

        // Handle "Others" for masterfile name-based fields
        $data->material = self::resolveOthersField($request, 'material', 'custom_material_name', 'materials');
        $data->material_colour = self::resolveOthersField($request, 'material_colour', 'custom_material_colour_name', 'colours');
        $data->size = $request->size;
        $data->base_ledger = self::resolveOthersField($request, 'base_ledger', 'custom_base_ledger_name', 'based_ledgers');
        $data->letter_type = self::resolveOthersField($request, 'letter_type', 'custom_letter_type_name', 'letter_types');
        $data->accessory = self::resolveOthersField($request, 'accessory', 'custom_accessory_name', 'accessories');
        $data->accessory_colour = self::resolveOthersField($request, 'accessory_colour', 'custom_accessory_colour_name', 'colours');

        $data->kerb_riser = $request->kerb_riser;
        $data->issue = $request->issue;
        $data->special_instruction = $request->special_instruction;
        $data->customer_notes = $request->customer_note;
        $data->additional_notes = $request->additional_note;

        $data->{$id ? "updated_by" : "created_by"} = Auth::id();
        $message = "Detect issues in the order request";
        $result = $data->save();
        
        if($result){
            
            $order_id = $data->id;
            $request["order_id"] = $order_id;
            $result_cost  = self::order_cost_upsert($request, $order_id);
            $result_note  = self::order_note_upsert($request, $order_id);
            
            $message = $id ? "Order No. $id has been successfully updated" : "New order successfully created.";
            if(!$result_cost) $message = "Detect issues in the Order Cost request";
            if(!$result_note) $message = "Detect issues in the Order Note request";
        }
        // return $result ? $result->id() : dd("Error on: Order Related");
        return [
                "success" => $result ? true : false,
                "message" => $message,
                "order_id" => $order_id
        ];
    }
    
    public function order_cost_upsert($request, $order_id = false){
        
        $orderCost = OrderCost::where("order_id", $order_id)->first();

        $cost_additional_description = $request->price_description;
        $cost_additional_amount = $request->price_amount;
        
        $data = $orderCost ?? new OrderCost();
        
        $data->order_id = $order_id;
        $data->description = $request->cost_description;
        $data->amount = str_replace(',', '', $request->cost_amount);
        $data->letter_count = $request->letters_no;
        $data->letter_amount = str_replace(',', '', $request->letters_amount);
        $data->letter_total_amount = str_replace(',', '', $request->letters_total_amount);
        $data->discount_description = $request->discount_description;
        $data->discount_amount = str_replace(',', '', $request->discount_amount);
        $data->total = str_replace(',', '', $request->total_amount);
        $data->cemetery_fee_description_1 = $request->cemetery_fee_description_1;
        $data->cemetery_fee_amount_1 = str_replace(',', '', $request->cemetery_fee_amount_1);
        $data->cemetery_fee_description_2 = $request->cemetery_fee_description_2;
        $data->cemetery_fee_amount_2 = str_replace(',', '', $request->cemetery_fee_amount_2);
        $data->grand_total = str_replace(',', '', $request->grand_total_amount);
        $data->deposit_description = $request->deposit_description;
        $data->deposit_amount = str_replace(',', '', $request->deposit_amount);
        $data->amount_received = str_replace(',', '', $request->amount_received);
        $data->balance = str_replace(',', '', $request->balance_amount);
        $data->net_amount = str_replace(',', '', $request->net_amount);
        $data->vat_rate = $request->vat_rate;
        $data->vat_amount = str_replace(',', '', $request->vat_amount);
        $data->zero_rated_fee = str_replace(',', '', $request->zero_rated_fees);
        $data->adjustment = str_replace(',', '', $request->adjustment);
        $data->gross_amount = str_replace(',', '', $request->gross_amount);
        $data->is_cost_analysis_print = $request->is_cost_analysis_print;
        $data->is_cost_analysis_trade = $request->is_cost_analysis_trade;
                        
        $result = $data->save();

        if($result && count($cost_additional_description)){
            $existing_additional_costs_ids = [];
            $existing_additional_costs = DB::table("order_cost_additionals")->where("order_cost_id", $data->id)->get();

            foreach ($existing_additional_costs as $key => $value) {
                array_push($existing_additional_costs_ids, $value->id);
            }  

            $order_cost_id = $data->id;
            $cost_additional_description_length = count($cost_additional_description);
            $cost_additional_data = [];
            for ($i=0; $i < $cost_additional_description_length ; $i++) { 
                if($cost_additional_description[$i]){
                   array_push($cost_additional_data, [
                        "order_cost_id" => $order_cost_id,
                        "description" => $cost_additional_description[$i],
                        "amount" => $cost_additional_amount[$i],
                    ]); 
                }
            }

            if(count($cost_additional_data) > 0){
                $result = DB::table("order_cost_additionals")->insert($cost_additional_data);
                if($result && count($existing_additional_costs_ids) > 0){
                    DB::table("order_cost_additionals")->whereIn("id", $existing_additional_costs_ids)->delete();
                }
            }

        }

        return $result ? $data->id : false;
    }

    public function order_note_upsert($request, $order_id = false){
        
        $orderNote = OrderNote::where("order_id", $order_id)->first();

        // $data = !$id ? new OrderNote() : OrderNote::findOrFail($id);
        $data = $orderNote ?? new OrderNote();

        $data->order_id = $request->order_id;
        $data->free_letters = $request->free_letters;
        $data->is_burial_society_fees_included = $request->is_burial_society_fees_included;
        $data->is_inscription_complete = $request->is_inscription_complete;
        $data->is_application_form_sent_to_bs_with_cheque = $request->is_sent_to_bs_with_cheque;
        $data->is_application_form_sent_to_bs_with_cheque_timestamp = $request->is_sent_to_bs_with_cheque_timestamp ? Carbon::parse($request->is_sent_to_bs_with_cheque_timestamp)->format('Y-m-d H:i:s') : null;
        $data->is_application_form_sent_to_bs_without_cheque = $request->is_sent_to_bs_without_cheque;
        $data->is_application_form_sent_to_bs_without_cheque_timestamp = $request->is_sent_to_bs_without_cheque_timestamp ? Carbon::parse($request->is_sent_to_bs_without_cheque_timestamp)->format('Y-m-d H:i:s') : null;
        $data->is_permit_not_required = $request->is_permit_not_required;
        $data->is_insurance = $request->is_insurance;
        $data->is_insurance_services = $request->is_insurance_services;
        $data->is_washdown_discussed = $request->is_washdown_discussed;
        $data->is_paid_by_bacs = $request->is_paid_by_bacs;
        $data->is_paid_by_bacs_timestamp = $request->is_paid_by_bacs_timestamp ? Carbon::parse($request->is_paid_by_bacs_timestamp)->format('Y-m-d H:i:s') : null;
        $data->is_full_inscription_received = $request->is_full_inscription_received;
        $data->is_sent_to_burial_society = $request->is_sent_to_burial_society;
        $data->is_received_from_burial_society = $request->is_received_from_burial_society;
        $data->is_order_complete = $request->is_order_complete;
        $data->inscription_sent_to_design_team_for_printout = $request->inscription_sent_to_design_team_for_printout ? Carbon::parse($request->inscription_sent_to_design_team_for_printout)->format('Y-m-d') : null;
        $data->inscription_sent_to_gary_for_printout = $request->inscription_sent_to_gary_for_printout ? Carbon::parse($request->inscription_sent_to_gary_for_printout)->format('Y-m-d') : null;
        $data->received_back_from_design_team = $request->received_back_from_design_team ? Carbon::parse($request->received_back_from_design_team)->format('Y-m-d') : null;
        $data->sent_to_customer = $request->sent_to_customer ? Carbon::parse($request->sent_to_customer)->format('Y-m-d') : null;
        $data->back_to_design_team_for_further_alterations = $request->back_to_design_team_for_further_alterations ? Carbon::parse($request->back_to_design_team_for_further_alterations)->format('Y-m-d') : null;
        $data->masonart_printout_approved = $request->masonart_printout_approved ? Carbon::parse($request->masonart_printout_approved)->format('Y-m-d') : null;
        // $data->approved_by_burial_society = Carbon::parse($request?->approved_by_burial_society)->format('Y-m-d') ?? null;
        $data->approved_by_burial_society = $request?->approved_by_burial_society;
        
        $result = $data->save();

        return $result ? $data->id : false;              
    }

    /**
     * Resolve a masterfile "Others" field: if value is 'others', validate + create record + return custom name.
     * Otherwise return the selected value directly.
     */
    private static function resolveOthersField($request, $fieldName, $customFieldName, $table)
    {
        $value = $request->input($fieldName);

        if ($value !== 'others') {
            return $value;
        }

        $customName = trim($request->input($customFieldName, ''));

        if (empty($customName)) {
            return null;
        }

        // Tables that use soft deletes
        $softDeleteTables = ['materials', 'based_ledgers', 'letter_types', 'accessories'];

        // Check for duplicate (case-insensitive)
        $query = DB::table($table)->whereRaw('LOWER(TRIM(name)) = ?', [strtolower($customName)]);
        if (in_array($table, $softDeleteTables)) {
            $query->whereNull('deleted_at');
        }
        $existing = $query->first();

        if ($existing) {
            // Record already exists — use its name (normalized from DB)
            return $existing->name;
        }

        // Create new record
        $now = now();
        $insertData = [
            'name' => $customName,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Add created_by for tables that support it
        if ($table !== 'colours') {
            $insertData['created_by'] = Auth::id();
        }

        DB::table($table)->insert($insertData);

        return $customName;
    }

}
