<?php

namespace App\Http\Controllers;

use App\Models\OrderInscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'data' => $order_inscription
        ]);
    }
}
