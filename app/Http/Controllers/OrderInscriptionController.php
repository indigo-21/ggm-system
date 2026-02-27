<?php

namespace App\Http\Controllers;

use App\Models\OrderInscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class OrderInscriptionController extends Controller
{
    public function upsert(Request $request){
        $id = $request->order_inscription_id;
        $order_inscription = !$id ? new OrderInscription() : OrderInscription::find($id);
        $order_inscription->order_id = $request->order_id;
        $order_inscription->inscription = $request->order_inscription;
        $order_inscription->{!$id ? 'created_by' : 'updated_by'} = Auth::id();
        $order_inscription->save();
        return response()->json([
            'status' => 'success',
            'message' => !$id ? 'Order Inscription created successfully.' : 'Order Inscription updated successfully.',
            'order_inscription' => $order_inscription
        ]);
    }

    public function approval(Request $request){
        $id = $request->inscription_id;
        $inscription_status  = $request->inscription_status;
        $inscription_remarks = $request->inscription_remarks;
        
        $is_approved = $inscription_status == "1" ? "Approved" : "Rejected";

        $inscription = OrderInscription::find($id);
        $inscription->status = $inscription_status;
        $inscription->remarks = $inscription_remarks;
        $inscription->reviewed_by = Auth::id();
        $inscription->save();

        $order_inscription = [
            "id" => $inscription->id,
            "reviewed_by" => $inscription->reviewed_user->firstname." ".$inscription->reviewed_user->lastname,
            "reviewed_at" => Carbon::parse($inscription->updated_at)->format('F d, Y H:i:s')
        ];

        return response()->json([
            'status' => 'success',
            'message' => "Order Inscription $is_approved successfully",
            'order_inscription' => $order_inscription
        ]);
    }
}
