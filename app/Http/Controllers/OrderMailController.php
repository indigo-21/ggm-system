<?php

namespace App\Http\Controllers;

use App\Models\OrderMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\QuoteController;
use App\Models\OrderFile;
use App\Models\OrderPayment;
use App\Services\PdfService;
use Illuminate\Support\Facades\Auth;

class OrderMailController extends Controller
{
    public function __construct(
        protected PdfService $pdfService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(OrderMail $orderMail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderMail $orderMail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderMail $orderMail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderMail $orderMail)
    {
        //
    }

    public function send_email(Request $request){

        $orderId = $request->orderId;
        $mailTo = $request->mailTo;
        $mailBody = $request->mailBody;
        $attachments = [];

        $infoTimescale = $request->infoTimescale;
        $documentInsurance = $request->documentInsurance;
        $insurance = $request->insurance;
        $termsCondition = $request->termsCondition;

        $quote = $request->quote;
        $order = $request->order;
        $receipt = $request->receipt;
        $statement = $request->statement;

        $photo = $request->photo;
        $document = $request->document;
        $workingFile = $request->workingFile;



        if($infoTimescale){
            $attachments[]  = [
                                    "path" => "template/info_timescale.docx",
                                    "name" => "Info-Timescale.docx"
                                ];
        }

        if($documentInsurance){
           $attachments[] = [
                                "path" => "template/document_insurance.pdf",
                                "name" => "StoneGuard Flyer.pdf"
                            ];
        }

        if($insurance){
            $attachments[] = [
                                "path" => "template/insurance_stoneguard.pdf",
                                "name" => "Insurance Product Information Document.pdf"
                            ];
        }

        if($termsCondition){
            $attachments[] = [
                                "path" => "template/terms_and_conditions.pdf",
                                "name" => "Terms and Conditions.pdf"
                            ];
        }

        if($quote){
            $quotePath = $this->pdfService->generateQuote($orderId);
            $attachments[] = [
                    "path" => "$quotePath",
                    "name" => "Quotation-$orderId.pdf"
                ];
        }
        
        if($order){
            $orderPath = $this->pdfService->generateOrder($orderId);
              $attachments[] = [
                        "path" => "$orderPath",
                        "name" => "Order-$orderId.pdf"
                    ];
        }

        if($receipt){

            $orderPayments = OrderPayment::where("order_id", $orderId)->get();
            
            foreach ($orderPayments as $key => $orderPayment) {
                $orderPaymentId = $orderPayment->id;
                $receiptPath = $this->pdfService->generateReceipt($orderPaymentId);
                $attachments[] = [
                        "path" => "$receiptPath",
                        "name" => "Payment Receipt-$orderId-$orderPaymentId.pdf"
                    ];
            }
        }

        if($statement){
            $statementPath = $this->pdfService->generateStatement($orderId);
              $attachments[] = [
                        "path" => "$statementPath",
                        "name" => "Statement-$orderId.pdf"
                    ];
        }

        if($photo){
            $orderPhotos = OrderFile::where("file_type", 1)
                            ->where("order_id", $orderId)                            
                            ->whereNull('attach_email')
                            ->get();
                            
            foreach ($orderPhotos as $key => $orderPhoto) {
              $photoPath = $orderPhoto->filepath;
              $attachments[] = [
                                "path" => "public/".$photoPath,
                                "name" => $orderPhoto->filename
                            ];
            }
        }

        if($photo){
            $orderPhotos = OrderFile::where("file_type", 1)
                            ->where("order_id", $orderId)                            
                            ->whereNull('attach_email')
                            ->get();
                            
            foreach ($orderPhotos as $key => $orderPhoto) {
              $photoPath = $orderPhoto->filepath;
              $attachments[] = [
                                "path" => "public/".$photoPath,
                                "name" => $orderPhoto->filename
                            ];
            }
        }

        if($document){
            $orderDocuments = OrderFile::where("file_type", 2)
                            ->where("order_id", $orderId)                            
                            ->whereNull('attach_email')
                            ->get();
                            
            foreach ($orderDocuments as $key => $orderDocument) {
              $documentPath = $orderDocument->filepath;
              $attachments[] = [
                                "path" => "public/".$documentPath,
                                "name" => $orderDocument->filename
                            ];
            }
        }

        if($workingFile){
            $orderWorkingFiles = OrderFile::where("file_type", 2)
                            ->where("order_id", $orderId)                            
                            ->whereNull('attach_email')
                            ->get();
                            
            foreach ($orderWorkingFiles as $key => $orderWorkingFile) {
              $documentPath = $orderWorkingFile->filepath;
              $attachments[] = [
                                "path" => "public/".$documentPath,
                                "name" => $orderWorkingFile->filename
                            ];
            }
        }


        if($mailTo){
            $orderMail = new OrderMail();
            $orderMail->order_id = $orderId;
            $orderMail->mail_to = $mailTo;
            $orderMail->attachments = json_encode($attachments);
            $orderMail->mail_body = $mailBody;
            $orderMail->created_by = Auth::id();
            $result = $orderMail->save();

            if($result){
                Mail::to($mailTo)
                    ->cc('charles.verdadero@indigo21.com')
                    ->send(new OrderConfirmation($orderId, $mailBody, $attachments));
                return [
                        "success" => $result ? true : false
                        ]; 
            }
        }
        
        // try {
            // Mail::to('charles.verdadero@indigo21.com')
            //     ->send(new OrderConfirmation('Charles Verdader0', 123));
        // } catch (\Throwable $e) {
        //     Log::error('Email failed', ['error' => $e->getMessage()]);
        // }

        

    }



}
