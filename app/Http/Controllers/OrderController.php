<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderType;
use App\Models\User;
use App\Services\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function __construct(
        protected PdfService $pdfService
    ) {}

    public function default_required_data($isFrom = 'index', $id = false)
    {
        $data = [
            'order_types' => OrderType::all(),
            'users' => User::all(),
            'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'years' => ['2024', '2025', '2026'],
            'payment_methods' => [['id' => 1, 'name' => 'Cash'], ['id' => 2, 'name' => 'Cheque'], ['id' => 3, 'name' => 'Credit Card'], ['id' => 4, 'name' => 'Bank Transfer'], ['id' => 5, 'name' => 'Debit Card']],
            // "orders" => Order::whereRaw("MONTH(created_at) = MONTH(CURRENT_DATE())")->get()
            'orders' => Order::orders()
                ->forPeriod(now()->month, now()->year)
                ->get(),
        ];

        return $data;
    }

    public function index()
    {
        $data = self::default_required_data();

        return view('pages.order.index', $data);
    }

    public function index_filtered(Request $request)
    {

        $data = self::default_required_data();

        $isOrder = $request->is_order == 1;
        $orderTypeId = $request->order_type_id ?? 1;
        $userId = $request->user_id;
        $isInvoiced = $request->invoice_status == 1;
        $orderMonth = $request->order_date_month;
        $orderYear = $request->order_date_year;
        $searchColumn = $request->search_column;
        $searchInput = $request->search_input;
        $allowedColumns = ['id', 'customer_name', 'deceased_name', 'grave_number', 'invoice_no'];

        $query = Order::query()
            ->where('order_type_id', $orderTypeId)
            ->orders();

        if ($searchColumn && $searchInput && in_array($searchColumn, $allowedColumns)) {
            if ($searchColumn == 'customer_name') {
                $query->whereHas('customer', function ($q) use ($searchInput) {
                    $q->WhereRaw(
                        "CONCAT(firstname, ' ', lastname) LIKE ?",
                        ["%{$searchInput}%"]
                    );
                });
            } else {
                $query->where($searchColumn, $searchInput);
            }
        } else {
            if ($userId) {
                $query->where('created_by', $userId);
            }

            if ($isInvoiced) {
                $query->where('invoice_no', 1);
            }

            if ($orderMonth && $orderYear) {
                $query->whereMonth('created_at', $orderMonth)
                    ->whereYear('created_at', $orderYear);
            }
        }

        $data['orders'] = $query->get();
        $data['filterInput'] = [
            'orderTypeId' => $orderTypeId,
            'userId' => $userId,
            'invoicedStatus' => $request->invoice_status,
            'orderMonth' => $orderMonth,
            'orderYear' => $orderYear,
            'searchColumn' => $searchColumn,
            'searchInput' => $searchInput,
        ];

        return view('pages.order.index', $data);
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

    public function print_pdf($order_id)
    {
        $orderId = $order_id;

        $path = $this->pdfService->generateOrder($orderId);

        return response()->download(storage_path('app/'.$path));
        // return view("pdf.order", $data);
    }

    public function print_pdf_no_price($order_id)
    {
        $orderId = $order_id;

        $path = $this->pdfService->generateOrderNoPrice($orderId);

        return response()->download(storage_path('app/'.$path));

        // return view("pdf.order-no-price", $data);
    }
}
