<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class OrderPaymentController extends Controller
{
    public function get_table_data(){
        $order_payments = OrderPayment::all();
        $data = [];
        foreach ($order_payments as $key => $value) {
            $created_by = $value->created_user->firstname.' '.$value->created_user->lastname;
            array_push($data, [
                "id" => $value->id,
                "order_id" => $value->order_id,
                "payment_datetime" => Carbon::parse($value->payment_datetime)->format('Y-m-d h:i:s'),
                "payment_method" => $value->method,
                "amount" => $value->amount,
                "comment" => $value->comment,
                "created_by" => $created_by
            ]);
        }
        return $data;
    }

    public function upsert(Request $request){
        $id = $request->id;
        $datetime = Carbon::parse($request?->payment_timestamp)->format('Y-m-d h:i:s') ?? null;
        $order_payment = !$id ? new OrderPayment() : OrderPayment::find($id);
        $order_payment->order_id = $request->order_id;
        $order_payment->method = $request->payment_method;
        $order_payment->amount = $request->payment_amount;
        $order_payment->payment_datetime = $datetime;
        $order_payment->comment = $request->payment_comment;
        $order_payment->{$id ? "updated_by" : "created_by" } = Auth::id();
        $result = $order_payment->save();
        $data = $result ? self::get_table_data() : dd("Somethings Wrong in saving order payment");
        return response()->json($data);
    }

    public function destroy(Request $request){
        $id = $request->id;
        $order_payment = OrderPayment::findOrFail($id);
        $result = $order_payment->delete();
        $data = $result ? self::get_table_data() : dd("Somethings Wrong in deleting order payment");
        return response()->json($data);
    }

    public function payment_receipt($order_payment_id){

        $orderPayment = OrderPayment::find($order_payment_id);
        $orderData = $orderPayment->order;
        $customerData = $orderData->customer;
        $locationData = $orderData->location;

        $orderId = $orderPayment->order_id;

        $paymentData = [
            [
                "amount" => number_format($orderPayment->amount, 2),
                "method" => $this->payment_method($orderPayment->method),
                "comment" => $orderPayment->comment
            ]
        ];

        $addressOne =  $customerData->address_one ?? "";
        $addressTwo =  $customerData->address_two ?? "";
        $cityCounty =  $customerData->city_county ?? "";
        $postCode =  $customerData->postcode ?? "";

        $data = [
                    "title" => "Payment Receipt-".$orderId,
                    "locationName" => $locationData->name,
                    "customerName" => $customerData->firstname." ".$customerData->lastname,
                    "customerAddress" => $addressOne." ".$addressTwo." ".$cityCounty." ".$postCode,
                    "deceasedName" => $orderData->deceased_name,
                    "cemetery" => $orderData->cemetery->name,
                    "graveNumber" => $orderData->grave_number,
                    "paymentDate" => Carbon::parse($orderPayment->created_at)->format('F d, Y H:i A'),
                    "paymentData" => $paymentData
                ];
        $pdf = Pdf::loadView('pdf.payment-receipt', $data);
        
        $filename = "Payment Receipt-{$orderId}.pdf";
        $relativePath = "pdfs/{$filename}.pdf";

        Storage::put($relativePath, $pdf->output());
        return response()->download(storage_path('app/'.$relativePath));

        // return view("pdf.payment-receipt", $data);

    }

    public function payment_statement($order_id){
        dd($order_id);
    }

    public function payment_method($paymentMethod = false){
        $method = "";
        switch ($paymentMethod) {
            case '1':
                $method = "Cash";
                break;
            case '2':
                $method = "Cheque";
                break;
            case '3':
                $method = "Credit Card";
                break;
            case '4':
                $method = "Bank Transfer";
                break;
            default:
                $method = "Debit Card";
                break;
        }

        return $method;
    }

}
