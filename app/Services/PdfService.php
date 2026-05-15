<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\OrderCost;
use App\Models\OrderPayment;

class PdfService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function generateQuote($orderId)
    {
        $orderData = Order::find($orderId);

        $customerData = $orderData->customer;
        $locationData = $orderData->location;

        $addressOne =  $customerData->address_one ?? "";
        $addressTwo =  $customerData->address_two ?? "";
        $cityCounty =  $customerData->city_county ?? "";
        $postCode =  $customerData->postcode ?? "";

        $data = [
            "title" => "Quotation-" . $orderId,
            "customerName" => $customerData->firstname . " " . $customerData->lastname,
            "printDate" => Carbon::now()->format('F d, Y H:i A'),
            "customerAddress" => $addressOne . " " . $addressTwo . " " . $cityCounty . " " . $postCode,
            "orderReference" => self::order_type_code($orderData->order_type_id) . $orderId,
            "customerFirstname" => $customerData->firstname,
            "deceasedName" => $orderData->deceased_name,
            "cemeteryName" => $orderData->cemetery->name,
            "graveNumber" => $orderData->grave_number,
            "headStone" => $orderData->design_headstone,
            "headStoneSize" => $orderData->size,
            "material" => $orderData->material,
            "orderCost" => $orderData->order_cost,
            "orderCostAdditionals" => $orderData->order_cost->additionals,
            "orderAdditionalNote" => $orderData->additional_notes,
            "locationName" => $orderData->location->name
        ];

        $pdf = Pdf::loadView('pdf.quotation', $data);

        $filename = "Quotation-{$orderId}.pdf";
        $relativePath = "pdfs/{$filename}";

        Storage::put($relativePath, $pdf->output());

        return $relativePath;
    }

    public function generateOrder($order_id){
        $orderId = $order_id;

        $orderData = Order::find($orderId);
        $customerData = $orderData->customer;
        $orderCostData = $orderData->order_cost;

        // $locationData = $orderData->location;

        $addressOne =  $customerData->address_one ?? "";
        $addressTwo =  $customerData->address_two ?? "";
        $cityCounty =  $customerData->city_county ?? "";
        $postCode =  $customerData->postcode ?? "";

        $data = [
                    "title" => "Order-".$orderId,
                    "printDate" => Carbon::now()->format("F d, Y"),
                    "orderDate" => Carbon::parse($orderData->createdAt)->format("F d, Y"),
                    "customerData" => $customerData,
                    "customerAddress" => $addressOne." ".$addressTwo." ".$cityCounty." ".$postCode,
                    "orderData" => $orderData,
                    "orderCost" => $orderCostData,
                    "orderDeposit" => $orderData->payments->first()
                ];
        
        $pdf = Pdf::loadView('pdf.order', $data);
        
        $filename = "Order-{$orderId}.pdf";
        $relativePath = "pdfs/{$filename}";

        Storage::put($relativePath, $pdf->output());
        return $relativePath;
        // return view("pdf.order", $data);
    }

    public function generateOrderNoPrice($order_id){
        $orderId = $order_id;

        $orderData = Order::find($orderId);
        $customerData = $orderData->customer;
        $orderCostData = $orderData->order_cost;

        // $locationData = $orderData->location;

        $addressOne =  $customerData->address_one ?? "";
        $addressTwo =  $customerData->address_two ?? "";
        $cityCounty =  $customerData->city_county ?? "";
        $postCode =  $customerData->postcode ?? "";

        $data = [
                    "title" => "Order-".$orderId,
                    "printDate" => Carbon::now()->format("F d, Y"),
                    "orderDate" => Carbon::parse($orderData->createdAt)->format("F d, Y"),
                    "customerData" => $customerData,
                    "customerAddress" => $addressOne." ".$addressTwo." ".$cityCounty." ".$postCode,
                    "orderData" => $orderData,
                ];
        
        $pdf = Pdf::loadView('pdf.order-no-price', $data);
        
        $filename = "Order_No_Price-{$orderId}.pdf";
        $relativePath = "pdfs/{$filename}";

        Storage::put($relativePath, $pdf->output());
        return $relativePath;

        // return view("pdf.order-no-price", $data);
    }

    public function generateReceipt($orderPaymentId){

        $orderPayment = OrderPayment::find($orderPaymentId);
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
        
        $filename = "Payment Receipt-{$orderId}-{$orderPaymentId}.pdf";
        $relativePath = "pdfs/{$filename}";

        Storage::put($relativePath, $pdf->output());

        return $relativePath;
    }

    public function generateStatement($orderId){

        $orderCost = OrderCost::where("order_id", $orderId)->first();
        $orderPayments = OrderPayment::where("order_id",$orderId)->get();
        // dd($orderCost->order);
        $orderData = $orderCost->order;
        $customerData = $orderData->customer;
        $locationData = $orderData->location;
        $amountPaid = 0;

        $paymentData = [];


        foreach ($orderPayments as $key => $orderPayment) {
            $paymentData[] = [
                    "amount" => number_format($orderPayment->amount, 2),
                    "method" => $this->payment_method($orderPayment->method),
                    "comment" => $orderPayment->comment,
                    "timestamp" => Carbon::parse($orderPayment->created_at)->format('F d, Y H:i A'),
                ];
            $amountPaid += floatval($orderPayment->amount);
        }

        $orderAmount = floatval($orderCost->gross_amount);
        $outstandingAmount = floatval($orderAmount) - floatval($amountPaid);

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
                    "orderAmount" => number_format($orderAmount,2),
                    "amountPaid" => number_format($amountPaid,2),
                    "outstandingAmount" => number_format($outstandingAmount,2),
                    "paymentData" => $paymentData,
                    
                ];
        // dd($data["paymentData"]);
        $pdf = Pdf::loadView('pdf.statement', $data);
        
        $filename = "Statement-{$orderId}.pdf";
        $relativePath = "pdfs/{$filename}";

        Storage::put($relativePath, $pdf->output());

        return $relativePath;

    }

    public function order_type_code($orderTypeId)
    {
        $code = "";
        switch ($orderTypeId) {
            case '1':
                $code = "NM/";
                break;
            case '2':
                $code = "AI";
                break;
            case '3':
                $code = "RN/";
                break;
            case '4':
                $code = "WD/";
                break;
            default:
                $code = "OT/";
                break;
        }
        return $code;
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
