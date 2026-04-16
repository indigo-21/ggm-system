<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\OrderType;
use App\Models\Location;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{   
    
    public function default_required_data($isFrom = "index", $id = false){
        $data = [
                    "order_types" => OrderType::all(),
                    "users"       => User::all(),
                    "months"      => ["January","February","March","April","May","June","July","August","September","October","November","December"],
                    "years"       => ["2024","2025","2026"],
                    "payment_methods" => [["id" => 1, "name" => "Cash"], ["id" => 2, "name" => "Cheque"], ["id" => 3, "name" => "Credit Card"], ["id" => 4, "name" => "Bank Transfer"], ["id" => 5, "name" => "Debit Card"]],
                    "orders" => Order::whereRaw("MONTH(created_at) = MONTH(CURRENT_DATE())")->get()
                ];
        
        return $data;
    }

    public function index()
    {
        $data = self::default_required_data();
        return view("pages.order.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function print_pdf($order_id){
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
        return response()->download(storage_path('app/'.$relativePath));

    }

    public function print_pdf_no_price($order_id){
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
        return response()->download(storage_path('app/'.$relativePath));

        // return view("pdf.order-no-price", $data);
    }

    public function order_type_code($orderTypeId){
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
}
