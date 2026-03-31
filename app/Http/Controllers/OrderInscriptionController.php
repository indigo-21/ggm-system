<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderInscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

    public function printPdf($orderId){
        $order = Order::find($orderId);
        
        $data = [
                    "title" => "Inscription-".$orderId,
                    "orderDate" => Carbon::parse($order->created_at)->format('F d, Y H:i:s'),
                    "lastAmended" => Carbon::parse($order->order_inscription->updated_at)->format('F d, Y H:i:s'),
                    "customerName" => $order->customer->title." ".$order->customer->firstname." ".$order->customer->lastname,
                    "deceasedName" => $order->deceased_name,
                    "reference" => $this->getReferenceCode($order->order_type_id)."-".$orderId,
                    "consecrationDate" => Carbon::parse($order->consecration_date)->format('F d, Y'),
                    "cemetery" => $order->cemetery->name,
                    "graveNumber" => $order->grave_number,
                    "inscription" => $order->order_inscription->inscription


                ];
        $pdf = Pdf::loadView('pdf.inscription', $data);
        
        // Save to storage folder
        Storage::put("pdfs/Inscription-{$orderId}.pdf", $pdf->output());

        // Return PDF to browser
        return $pdf->download("Inscription-{$orderId}.pdf");

        // return $pdf->stream('Inscription-'.$orderId.'.pdf');
        // return view("pdf.inscription" , $data);
    }

    public function getReferenceCode($orderTypeId){
        switch ($orderTypeId) {
            case '1':
                return "NM/";
                break;
            case '2':
                return "AI/";
                break;
            case '3':
                return "REN";
                break;
            case '4':
                return "WD/";
                break;
            
            default:
                return "OT/";
                break;
        }
    }
}
