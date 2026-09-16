<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\OrderPayment;
use App\Services\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderPaymentController extends Controller
{
    public function __construct(
        protected PdfService $pdfService
    ) {}

    public function get_table_data($id = false)
    {
        $order_payments = $id ? OrderPayment::where('id', $id)->get() : OrderPayment::all();
        $data = [];
        foreach ($order_payments as $key => $value) {
            $created_by = $value->created_user->firstname.' '.$value->created_user->lastname;
            array_push($data, [
                'id' => $value->id,
                'order_id' => $value->order_id,
                'payment_datetime' => Carbon::parse($value->payment_datetime)->format('Y-m-d h:i:s'),
                'payment_method' => $value->method,
                'amount' => $value->amount,
                'comment' => $value->comment,
                'created_by' => $created_by,
            ]);
        }

        return $data;
    }

    public function upsert(StorePaymentRequest $request)
    {
        $validated = $request->validated();
        $id = $validated['id'] ?? null;

        $datetime = Carbon::parse($validated['payment_datetime'])->format('Y-m-d H:i:s');

        $order_payment = ! $id ? new OrderPayment : OrderPayment::findOrFail($id);
        $order_payment->order_id = $validated['order_id'];
        $order_payment->method = $validated['payment_method'];
        $order_payment->amount = $validated['payment_amount'];
        $order_payment->payment_datetime = $datetime;
        // Comment is optional; store null when it is empty/omitted.
        $order_payment->comment = filled($validated['payment_comment'] ?? null)
            ? $validated['payment_comment']
            : null;
        $order_payment->{! $id ? 'created_by' : 'updated_by'} = Auth::id();

        if (! $order_payment->save()) {
            return response()->json([
                'message' => 'Something went wrong while saving the order payment.',
            ], 500);
        }

        return response()->json(self::get_table_data($order_payment->id));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $order_payment = OrderPayment::findOrFail($id);
        $result = $order_payment->delete();
        $data = $result ? self::get_table_data() : dd('Somethings Wrong in deleting order payment');

        return response()->json($data);
    }

    public function payment_receipt($order_payment_id)
    {

        $path = $this->pdfService->generateReceipt($order_payment_id);

        return response()->download(storage_path('app/'.$path));

        // return view("pdf.payment-receipt", $data);

    }

    public function payment_statement($orderId)
    {

        $path = $this->pdfService->generateStatement($orderId);

        return response()->download(storage_path('app/'.$path));

        // return view("pdf.statement", $data);
    }
}
