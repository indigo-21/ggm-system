<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderNewMemorial;
use App\Models\OrderAddedInscription;
use App\Models\OrderType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\ScheduleService;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function default_required_data($isFrom = "index", $id = false){
        $data = [
                    "order_types" => OrderType::all(),
                    "months"      => ["January","February","March","April","May","June","July","August","September","October","November","December"],
                    "years"       => ["2024","2025","2026","2027"],
                    "payment_statuses" => [["id" => 0, "name" => "Unpaid"], ["id" => 1, "name" => "Paid"]],
                ];
        
        return $data;
    }

    public function index_default_data($orderType = 1){
        switch ($orderType) {
            case '1':
                return OrderNewMemorial::all();
                break;
            case '2':
                return OrderAddedInscription::all();
                break;
        }
    }
    



    public function index()
    {
        $data = self::default_required_data();
        $data["schedules"] = self::index_default_data();
        return view('pages.schedule.new-memorial.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $orderType, int $orderId)
    {
        $data = self::default_required_data();
        $data["order"] = Order::findOrFail($orderId);

        switch ($orderType) {
            case '1':
                    $scheduleData = OrderNewMemorial::where("order_id", $orderId)->first();
                    if($scheduleData){
                       return redirect()
                        ->route('schedule.edit', [
                        'orderTypeId' => $orderType,
                        'scheduleId' => $scheduleData->id]);
                    }else{
                        return view("pages.schedule.new-memorial.form", $data);
                    }
                break;
            case '2':
                    $scheduleData = OrderAddedInscription::where("order_id", $orderId)->first();
                    if($scheduleData){
                       return redirect()
                        ->route('schedule.edit', [
                        'orderTypeId' => $orderType,
                        'scheduleId' => $scheduleData->id]);
                    }else{
                        return view("pages.schedule.added-inscription.form", $data);
                    }
                break;
            
            default:
                # code...
                break;
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ScheduleService $scheduleService )
    {
        $scheduleData = $scheduleService->upsertSchedule($request);

        if(!$scheduleData["result"]){
            
            return redirect()->back()->with('error', $scheduleData["message"]);
        }else{
            $view = $scheduleData["view"];
            $data = self::default_required_data();
            $orderTypeId = $scheduleData["tableData"]->order->order_type_id;
            $scheduleId = $scheduleData["tableData"]->id;
            $message = $scheduleData["message"];
            $order = $scheduleData["tableData"]->order();
            
            return redirect()
                    ->route('schedule.edit', [
                        'orderTypeId' => $orderTypeId,
                        'scheduleId' => $scheduleId])
                    ->with("message", $message );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $orderTypeId = 1)
    {
        $data = self::default_required_data();
        $data["schedules"] = self::index_default_data($orderTypeId);
        // dd($data["schedules"]->first()->letter_type);
        switch ($orderTypeId) {
            case 1:
                return view('pages.schedule.new-memorial.index', $data);
                break;
            case 2:
                return view('pages.schedule.added-inscription.index', $data);
                break;
            case 3:
                return view('pages.schedule.renovation.index', $data);
                break;
            case 4:
                return view('pages.schedule.washdown.index', $data);
                break;
            default:
                return view('pages.schedule.new-memorial.index', $data);
                break;
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $orderTypeId, string $scheduleId)
    {
        $data = self::default_required_data();
        $view = "";
        $tableData = [];
    
        switch ($orderTypeId) {
            case '1':
                $tableData = OrderNewMemorial::findOrFail($scheduleId);
                $view = "pages.schedule.new-memorial.form";
                break;
            case '2':
                $tableData = OrderAddedInscription::findOrFail($scheduleId);
                $view = "pages.schedule.added-inscription.form";
                break;
        }

        $data["order"] = $tableData->order;       
        $data["schedule"] = $tableData;  
         
        return view($view, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $scheduleId, ScheduleService $scheduleService)
    {

        $scheduleData = $scheduleService->upsertSchedule($request, $scheduleId);

        if($scheduleData["result"]){
            return redirect()->back()->with('error', $scheduleData["message"]);
        }else{
            $view = $scheduleData["view"];
            $data = self::default_required_data();
            $orderTypeId = $scheduleData["tableData"]->order->order_type_id;
            $scheduleId = $scheduleData["tableData"]->id;
            $message = $scheduleData["message"];
            $order = $scheduleData["tableData"]->order();

            return redirect()
                    ->route('schedule.edit', [
                        'orderTypeId' => $orderTypeId,
                        'scheduleId' => $scheduleId])
                    ->with("message", $message );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    public function index_filtered( Request $request){
       
        $data  = self::default_required_data();
        
        $orderTypeId = $request->order_type_id ?? 1;
        $fixingStatus = $request->fixing_status;
        $paymentStatus = $request->payment_status;
        $orderMonth = $request->order_date_month;
        $orderYear = $request->order_date_year;
        $searchColumn = $request->search_column;
        $searchInput = $request->search_input;
        $allowedColumns = ["deceased_name","grave_number","invoice_no"];
        
        switch ($orderTypeId) {
            case '1':
                $query = OrderNewMemorial::query();
                $views = "pages.schedule.new-memorial.index";
                break;
            case '2':
                $query = OrderNewMemorial::query();
                $views = "pages.schedule.added-inscription.index";
                break;
            
            default:
                $query = OrderNewMemorial::query();
                $views = "pages.schedule.index";
                break;
        }

        if($fixingStatus != ""){
            $query->where("fixing_status", $fixingStatus);
        }
        
        if($paymentStatus != ""){
            $query->where("payment_status", $paymentStatus);
        }
        
        if($orderYear && $orderYear){
            $query->whereMonth('created_at', $orderMonth)
                    ->whereYear('created_at', $orderYear);
        }

        
        if($searchColumn && $searchInput && in_array($searchColumn, $allowedColumns)){
            $query->whereHas('order', function($q) use ($searchColumn, $searchInput){
                $q->where($searchColumn, 'LIKE', "%{$searchInput}%");
            });
        }
            

        $data["schedules"] = $query->get();


        return view($views, $data);
    }
}
