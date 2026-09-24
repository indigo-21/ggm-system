<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderAddedInscription;
use App\Models\OrderNewMemorial;
use App\Models\OrderRenovation;
use App\Models\OrderType;
use App\Models\OrderWashdown;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function default_required_data($isFrom = 'index', $id = false)
    {
        $data = [
            'order_types' => OrderType::all(),
            'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'years' => ['2024', '2025', '2026', '2027', '2028'],
            'payment_statuses' => [['id' => 0, 'name' => 'Unpaid'], ['id' => 1, 'name' => 'Paid']],
        ];

        return $data;
    }

    public function index_default_data($orderType = 1)
    {
        switch ($orderType) {
            case '1':
                return OrderNewMemorial::all();
                break;
            case '2':
                return OrderAddedInscription::all();
                break;
            case '3':
                return OrderRenovation::all();
                break;
            case '4':
                return OrderWashdown::all();
                break;
        }
    }

    public function index()
    {
        $data = self::default_required_data();
        $data['schedules'] = self::index_default_data();

        return view('pages.schedule.new-memorial.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $orderType, int $orderId)
    {
        $data = self::default_required_data();
        $data['order'] = Order::findOrFail($orderId);

        switch ($orderType) {
            case '1':
                $scheduleData = OrderNewMemorial::where('order_id', $orderId)->first();
                if ($scheduleData) {
                    return redirect()
                        ->route('schedule.edit', [
                            'orderTypeId' => $orderType,
                            'scheduleId' => $scheduleData->id,
                        ]);
                } else {
                    return view('pages.schedule.new-memorial.form', $data);
                }
                break;
            case '2':
                $scheduleData = OrderAddedInscription::where('order_id', $orderId)->first();
                if ($scheduleData) {
                    return redirect()
                        ->route('schedule.edit', [
                            'orderTypeId' => $orderType,
                            'scheduleId' => $scheduleData->id,
                        ]);
                } else {
                    return view('pages.schedule.added-inscription.form', $data);
                }
                break;
            case '3':
                $scheduleData = OrderRenovation::where('order_id', $orderId)->first();
                if ($scheduleData) {
                    return redirect()
                        ->route('schedule.edit', [
                            'orderTypeId' => $orderType,
                            'scheduleId' => $scheduleData->id,
                        ]);
                } else {
                    return view('pages.schedule.renovation.form', $data);
                }
                break;
            case '4':
                $scheduleData = OrderWashdown::where('order_id', $orderId)->first();
                if ($scheduleData) {
                    return redirect()
                        ->route('schedule.edit', [
                            'orderTypeId' => $orderType,
                            'scheduleId' => $scheduleData->id,
                        ]);
                } else {
                    return view('pages.schedule.washdown.form', $data);
                }
                break;

            default:
                // code...
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ScheduleService $scheduleService)
    {
        return $this->persistSchedule($scheduleService->upsertSchedule($request));
    }

    /**
     * Display the specified resource.
     */
    public function show($orderTypeId = 1)
    {
        // Route parameters arrive as strings; normalise to an int so the
        // comparisons below and the data lookup behave consistently.
        $orderTypeId = (int) $orderTypeId;

        $data = self::default_required_data();
        $data['schedules'] = self::index_default_data($orderTypeId);
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
        $view = '';
        $tableData = [];

        switch ($orderTypeId) {
            case '1':
                $tableData = OrderNewMemorial::findOrFail($scheduleId);
                $view = 'pages.schedule.new-memorial.form';
                break;
            case '2':
                $tableData = OrderAddedInscription::findOrFail($scheduleId);
                $view = 'pages.schedule.added-inscription.form';
                break;
            case '3':
                $tableData = OrderRenovation::findOrFail($scheduleId);
                $view = 'pages.schedule.renovation.form';
                break;
            case '4':
                $tableData = OrderWashdown::findOrFail($scheduleId);
                $view = 'pages.schedule.washdown.form';
                break;
        }

        $data['order'] = $tableData->order;
        $data['schedule'] = $tableData;

        return view($view, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $scheduleId, ScheduleService $scheduleService)
    {
        return $this->persistSchedule($scheduleService->upsertSchedule($request, $scheduleId));
    }

    /**
     * Shared post-save handling for store()/update(): on failure return to the
     * form with an error; on success redirect to the schedule listing
     * pre-filtered on the month/year the order was scheduled for.
     */
    private function persistSchedule(array $scheduleData)
    {
        if (! $scheduleData['result']) {
            return redirect()->back()->withInput()->with('error', $scheduleData['message']);
        }

        $schedule = $scheduleData['tableData'];
        $orderDate = $schedule->order_date ? Carbon::parse($schedule->order_date) : null;
        

        return redirect()
            ->route('schedule.filtered', [
                'orderTypeId' => $schedule->order->order_type_id,
                'orderMonth' => $orderDate?->format('n'),
                'orderYear' => $orderDate?->format('Y'),
            ])
            ->with('message', $scheduleData['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Handle the schedule filter form submission (POST).
     */
    public function index_filtered(Request $request, ScheduleService $scheduleService)
    {
        return $this->renderFilteredView($scheduleService, [
            'orderTypeId' => $request->order_type_id ?? 1,
            'fixingStatus' => $request->fixing_status,
            'paymentStatus' => $request->payment_status,
            'orderMonth' => $request->order_date_month,
            'orderYear' => $request->order_date_year,
            'searchColumn' => $request->search_column,
            'searchInput' => $request->search_input,
        ]);
    }

    /**
     * Filtered schedule listing reachable via GET (used by the post-save
     * redirect and for shareable, refreshable pre-filtered URLs).
     */
    public function filtered(string $orderTypeId, string $orderMonth, string $orderYear, ScheduleService $scheduleService)
    {
        return $this->renderFilteredView($scheduleService, [
            'orderTypeId' => $orderTypeId,
            'fixingStatus' => "",
            'paymentStatus' => "",
            'orderMonth' => $orderMonth,
            'orderYear' => $orderYear,
            'searchColumn' => "",
            'searchInput' => "",
        ]);
    }

    /**
     * Render the correct order-type index view with the filtered schedules and
     * the reference data the view needs, echoing the active filter values back
     * so the form can preserve its state.
     */
    private function renderFilteredView(ScheduleService $scheduleService, array $filters)
    {
        $result = $scheduleService->filterSchedules($filters);

        $data = self::default_required_data();
        $data['schedules'] = $result['schedules'];
        $data['filters'] = $filters;
        
        return view($result['view'], $data);
    }
}
