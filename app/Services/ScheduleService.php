<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\OrderNewMemorial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function upsertSchedule($data, $id = false){
        $orderTypeId = $data->orderTypeId;
        switch ($orderTypeId) {
            case '1':
                return self::upsertNewMorial($data, $id);
                break;
            
            default:
                # code...
                break;
        }
    }
    public function upsertNewMorial($data, $id = false){
            
        $message = "Detect issues in the New Memorial request";

        $newMemorial = !$id ? new OrderNewMemorial() : OrderNewMemorial::findOrFail($id);
        
        $newMemorial->order_id = $data->orderId;

        $newMemorial->for_fixing = $data->for_fixing;
        $newMemorial->fixing_date =  Carbon::parse($data->fixing_date)->format('Y-m-d h:i:s');
        $newMemorial->fixing_status =  $data->fixing_status;

        $newMemorial->payment_status = $data->payment_status;

        $newMemorial->view_location =  $data->view_location;
        $newMemorial->view_status =  $data->view_status;
        $newMemorial->view_date =  Carbon::parse($data->view_date)->format('Y-m-d h:i:s');

        $newMemorial->description =  $data->description;
        $newMemorial->issue =  $data->issues;

        $newMemorial->is_customer_approved =  $data->is_customer_approved ? 1 : 0;
        $newMemorial->is_inscription_factory_approved =  $data->is_inscription_factory_approved ? 1 : 0;
        $newMemorial->inscription_factory_approved_timestamp =  $data->is_inscription_factory_timestamp ? Carbon::parse($data->is_inscription_factory_timestamp)->format('Y-m-d h:i:s') : NULL;
        $newMemorial->is_burial_society_approved =  $data->is_burial_society_approved ? 1 : 0;
        $newMemorial->is_permit_back =  $data->is_permit_back ? 1 : 0;

        $newMemorial->{!$id ? "created_by" : "updated_by"} = Auth::id();
        
        $result = $newMemorial->save();
        if($result){
            $message = !$id ? "Succesfully Created" : "Order No. $id updated Succesfully";
        }

        return [
            "result" => $result,
            "tableDate" => $newMemorial,
            "message" => $message,
            "view" => "pages.schedule.new-memorial"
        ];

    }
}
