<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Models\User;
use App\Models\OrderType;
use App\Models\Location;
use App\Models\Cemetery;
use App\Models\BurialSocietyOrganization;
use App\Models\Accessory;
use App\Models\BasedLedger;
use App\Models\GraveSpace;
use App\Models\LetterType;
use App\Models\Material;
use App\Models\Colour;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Order;
use App\Models\OrderCost;
use App\Models\OrderInscription;
use App\Models\OrderInstructionNote;
use App\Models\OrderPayment;
use App\Models\OrderNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService;
use App\Services\PdfService;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{

    public function __construct(
        protected PdfService $pdfService
    ) {}


    public function default_required_data($isFrom = "index", $id = false)
    {
        session()->forget('success');
        $data = [
            "order_types" => OrderType::all(),
            "users"       => User::all(),
            "months"      => ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            "years"       => ["2024", "2025", "2026"],
            "payment_methods" => [["id" => 1, "name" => "Cash"], ["id" => 2, "name" => "Cheque"], ["id" => 3, "name" => "Credit Card"], ["id" => 4, "name" => "Bank Transfer"], ["id" => 5, "name" => "Debit Card"]],
        ];
        if ($isFrom == "form") {
            $data += [
                "auth_session"                      => User::find(Auth::id()),
                "locations"                         => Location::all(),
                "cemeteries"                        => Cemetery::all(),
                "burial_society_organizations"      => BurialSocietyOrganization::all(),
                "grave_spaces"                      => GraveSpace::all(),
                "letter_types"                      => LetterType::all(),
                "materials"                         => Material::all(),
                "base_ledgers"                      => BasedLedger::all(),
                "accessories"                       => Accessory::all(),
                "colours"                           => Colour::all(),
                "customers"                         => Customer::all(),
                "titles"                            => ["None", "Mr", "Mrs", "Miss", "Ms", "Dr"],
            ];
            if ($id) {
                $quote    = Order::findOrFail($id);
                $emails   = CustomerContact::where("customer_id", $quote->customer_id)
                    ->where("contact_type", 1)
                    ->pluck('contact_value')   // extract the email values
                    ->toArray();
                $mobile_nos   = CustomerContact::where("customer_id", $quote->customer_id)
                    ->where("contact_type", 2)
                    ->pluck('contact_value')
                    ->toArray();
                $tel_nos   = CustomerContact::where("customer_id", $quote->customer_id)
                    ->where("contact_type", 3)
                    ->pluck('contact_value')
                    ->toArray();
                // $order_cost     = OrderCost::find($id);
                $order_cost     = OrderCost::where("order_id", $id)->first();
                $order_payments = OrderPayment::where("order_id", $id)->get();
                $total_deposit = $order_payments->sum('amount');
                $order_inscription = OrderInscription::where("order_id", $id)->first();
                // $order_payments = OrderPayment::where('order_id', $id)->get();
                // $total_deposit  = $order_payments->sum(fn ($p) => (float) $p->amount);

                $data += [
                    "quote" => $quote,
                    "order_cost" => $order_cost,
                    "order_note" => OrderNote::where("order_id", $id)->first(),
                    "customer_email" => $emails ? implode(";", $emails) : "",
                    "customer_mobile_no" => $mobile_nos ? implode(";", $mobile_nos) : "",
                    "customer_tel_no" => $tel_nos ? implode(";", $tel_nos) : "",
                    "order_instruction_notes" => OrderInstructionNote::where("order_id", $id)->get(),
                    "total_deposit" => $total_deposit,
                    "order_payments" => $order_payments,
                    "order_balance" => floatVal($order_cost->gross_amount ?? 0) - floatVal($total_deposit),
                ];
                if ($order_inscription) {
                    $data["order_inscription"] = $order_inscription;
                    $data["order_inscription_count"] = mb_strlen(trim(preg_replace('/\s+/', ' ', strip_tags($order_inscription->inscription))));;
                }
            }
        } else {
            // $data["qoutes"] = Order::all();

            // $data += [
            //     "quotes" => Order::whereRaw("MONTH(created_at) = MONTH(CURRENT_DATE())")
            //                     ->whereHas("payments")
            //             ->get()
            // ];
            $data+=[
                    "quotes" => Order::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->doesntHave('payments')
                                    ->get()
                ];
        }

        return $data;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = self::default_required_data();
        return view("pages.quote.index", $data);
    }

    public function index_filtered(Request $request)
    {

        $data  = self::default_required_data();

        $isOrder = $request->is_order == 1;
        $orderTypeId = $request->order_type_id ?? 1;
        $userId = $request->user_id;
        $isInvoiced = $request->invoice_status == 1;
        $orderMonth = $request->order_date_month;
        $orderYear = $request->order_date_year;
        $searchColumn = $request->search_column;
        $searchInput = $request->search_input;
        $allowedColumns = ["id", "customer_name", "deceased_name", "grave_number", "invoice_no",];

        $query = Order::where("order_type_id", $orderTypeId)
                        ->doesntHave("payments");

        if ($searchColumn && $searchInput && in_array($searchColumn, $allowedColumns)) {
            if ($searchColumn == "customer_name") {
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
            // if ($userId) {
            //     $query->where("created_by", $userId);
            // }

            // if ($isInvoiced) {
            //     $query->where("invoice_no", 1);
            // }

            if ($orderYear && $orderYear) {
                $query->whereRaw("MONTH(created_at) = $orderMonth");
                $query->whereRaw("YEAR(created_at) = $orderYear");
            }
        }

        

       
        $data["quotes"] = $query->get();
        $data["filterInput"] = [
                                 "orderTypeId" => $orderTypeId,
                                 "userId" => $userId,
                                 "invoicedStatus" => $request->invoice_status,
                                 "orderMonth" => $orderMonth,
                                 "orderYear" => $orderYear,
                                 "searchColumn" => $searchColumn,
                                 "searchInput" => $searchInput
                                ];


        return view("pages.quote.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = self::default_required_data("form");
        return view("pages.quote.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, OrderService $order_service)
    {
        
        $result = $order_service->order_upsert($request);
        if (!$result["success"]) {
            return redirect()->back()->with('error', $result["message"]);
        } else {
            $data = self::default_required_data();
            // return view("pages.quote.index", $data)->with("success", $result["message"]);
            //  return redirect()->route("quote.edit", $result["order_id"])->with("success", $result["message"], $data);
            return redirect()
                ->route('quote.edit', $result['order_id'])
                ->with('success', $result['message'])
                ->with('data', $data);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = self::default_required_data("form", $id);
        // dd($data["quote"]->order_note);
        return view("pages.quote.form", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, OrderService $order_service)
    {
        $result = $order_service->order_upsert($request, $id);
        if (!$result["success"]) {
            return redirect()->back()->with('error', $result["message"]);
        } else {
            $data = self::default_required_data();
            session()->flash('success', $result['message']);
            // return view("pages.quote.index", $data);
            return redirect()->route("quote.edit", $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function upsertOrderInstructionNote(Request $request)
    {

        $order_id = $request->order_id;
        $type_of_note = $request->method == "note" ? 1 : 2;
        $id = $request->order_instruction_note_id;
        $data = !$id ? new OrderInstructionNote() : OrderInstructionNote::findOrFail($id);
        $data->order_id = $order_id;
        $data->type_of_note = $type_of_note;
        $data->notes = $request->notes;
        $data->{!$id ? 'created_by' : 'updated_by'} = Auth::id();
        $result = $data->save();
        $return_data = [];

        if ($result) {
            $order_instructions = OrderInstructionNote::where("order_id", $order_id)->get();
            foreach ($order_instructions as $key => $instruction) {
                if ($instruction->type_of_note == $type_of_note) {
                    $instruction = [
                        "order_instruction_note_id" => $instruction->id,
                        "notes" => $instruction->notes,
                        "created_by" => $instruction->created_user->firstname . " " . $instruction->created_user->lastname,
                        "created_at" => Carbon::parse($instruction->created_at)->format('F d, Y H:i:s'),
                        "updated_by" => $instruction->updated_user?->firstname ?? '' . " " . $instruction->updated_user?->lastname ?? '',
                        "updated_at" => Carbon::parse($instruction->updated_at)->format('F d, Y H:i:s')
                    ];
                    array_push($return_data, $instruction);
                }
            }
        }

        return $result ? $return_data : false;
    }

    public function getOrderInstructionNote(Request $request)
    {
        $order_id = $request->order_id;
        $is_note = $request->type === "note";
        $data = [];
        $order_instruction_notes = OrderInstructionNote::where("order_id", $order_id)
            ->where("type_of_note", $is_note ? 1 : 2)
            ->get();

        foreach ($order_instruction_notes as $key => $instruction_note) {
            array_push(
                $data,
                [
                    "id" => $instruction_note->id,
                    "notes" => $instruction_note->notes,
                    "created_by" => $instruction_note->created_user->firstname . " " . $instruction_note->created_user->lastname,
                    "created_at" => Carbon::parse($instruction_note->created_at)->format('F d, Y H:i:s'),
                    "updated_by" => $instruction_note->updated_user?->firstname ?? '' . " " . $instruction_note->updated_user?->lastname ?? '',
                    "updated_at" => Carbon::parse($instruction_note->updated_at)->format('F d, Y H:i:s')
                ]
            );
        }
        // dd($order_instruction_notes->get());

        return response()->json(["data" => $data]);
    }

    public function print_pdf($order_id)
    {
        $orderId = $order_id;

        $path = $this->pdfService->generateQuote($orderId);

        return response()->download(storage_path("app/" . $path));
        // return view('pdf.quotation', $data);
    }
}
