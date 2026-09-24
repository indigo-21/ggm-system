<?php

namespace App\Services;

use App\Models\OrderAddedInscription;
use App\Models\OrderNewMemorial;
use App\Models\OrderRenovation;
use App\Models\OrderWashdown;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function upsertSchedule(Request $data, $id = false)
    {
        
        $orderTypeId = $data->orderTypeId;
        switch ($orderTypeId) {
            case '1':
                return self::upsertNewMemorial($data, $id);
                break;
            case '2':
                return self::upsertAddedInscription($data, $id);
                break;
            case '3':
                return self::upsertRenovation($data, $id);
                break;
            case '4':
                return self::upsertWashdown($data, $id);
                break;

            default:
                // code...
                break;
        }
    }

    /**
     * Resolve the schedule "Order Date" from the month/year selects shared by
     * every schedule form. Returns a Y-m-d string (first day of the chosen
     * month) or null when neither value is provided.
     *
     * Centralising this keeps the month/year -> date conversion identical
     * across all order types.
     */
    private function resolveOrderDate(Request $data): ?string
    {
        $month = $data->month;
        $year = $data->year;

        if (! $month || ! $year) {
            return null;
        }

        return Carbon::parse('01-'.$month.'-'.$year)->format('Y-m-d');
    }

    /**
     * Build the standard upsert response array shared by every order type,
     * so the result/message/view shape stays consistent in one place.
     */
    private function buildResult($model, bool $result, $id, string $view): array
    {
        
        return [
            'result' => $result,
            'tableData' => $model,
            'message' => $result
                ? (! $id ? 'Succesfully Created' : "Order No. $id updated Succesfully")
                : 'Detect issues in the schedule request',
            'view' => $view,
        ];
    }

    /**
     * Configuration-driven map of order type -> schedule model + index view.
     * Keeps the type resolution in a single place shared by filtering.
     *
     * @return array<int, array{model: class-string, view: string}>
     */
    private function scheduleTypeMap(): array
    {
        return [
            1 => ['model' => OrderNewMemorial::class,      'view' => 'pages.schedule.new-memorial.index'],
            2 => ['model' => OrderAddedInscription::class, 'view' => 'pages.schedule.added-inscription.index'],
            3 => ['model' => OrderRenovation::class,       'view' => 'pages.schedule.renovation.index'],
            4 => ['model' => OrderWashdown::class,         'view' => 'pages.schedule.washdown.index'],
        ];
    }

    /**
     * Build a filtered schedule listing for a given order type.
     *
     * Centralises the query logic (previously in the controller): resolves the
     * correct model/view for the order type and applies the optional fixing
     * status, payment status, month/year and column search filters.
     *
     * @param  array{
     *     orderTypeId?: int|string|null,
     *     fixingStatus?: int|string|null,
     *     paymentStatus?: int|string|null,
     *     orderMonth?: int|string|null,
     *     orderYear?: int|string|null,
     *     searchColumn?: string|null,
     *     searchInput?: string|null
     * }  $filters
     * @return array{schedules: \Illuminate\Database\Eloquent\Collection, view: string}
     */
    public function filterSchedules(array $filters): array
    {
        $orderTypeId = (int) ($filters['orderTypeId'] ?? 1);
        $map = $this->scheduleTypeMap();
        $config = $map[$orderTypeId] ?? $map[1];

        $fixingStatus = $filters['fixingStatus'] ?? null;
        $paymentStatus = $filters['paymentStatus'] ?? null;
        $orderMonth = $filters['orderMonth'] ?? null;
        $orderYear = $filters['orderYear'] ?? null;
        $searchColumn = $filters['searchColumn'] ?? null;
        $searchInput = $filters['searchInput'] ?? null;

        $allowedColumns = ['deceased_name', 'grave_number', 'invoice_no'];

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $config['model']::query()->with('order');

        // Note: New Memorial / Renovation carry fixing_status; Added Inscription
        // uses schedule_status and Washdown has neither. Only filter when the
        // column applies to avoid errors on tables without it.
        if (filled($fixingStatus) && in_array($orderTypeId, [1, 3], true)) {
            $query->where('fixing_status', $fixingStatus);
        }

        if (filled($paymentStatus)) {
            $query->where('payment_status', $paymentStatus);
        }

        if (filled($orderMonth) && filled($orderYear)) {
            $query->whereMonth('order_date', $orderMonth)
                ->whereYear('order_date', $orderYear);
        }

        if (filled($searchColumn) && filled($searchInput) && in_array($searchColumn, $allowedColumns, true)) {
            $query->whereHas('order', function ($q) use ($searchColumn, $searchInput) {
                $q->where($searchColumn, 'LIKE', "%{$searchInput}%");
            });
        }

        return [
            'schedules' => $query->get(),
            'view' => $config['view'],
        ];
    }

    public function upsertNewMemorial(Request $data, $id = false)
    {

        $newMemorial = ! $id ? new OrderNewMemorial : OrderNewMemorial::findOrFail($id);

        $newMemorial->order_id = $data->orderId;
        $newMemorial->order_date = self::resolveOrderDate($data);

        $newMemorial->for_fixing = $data->for_fixing;
        $newMemorial->fixing_date = Carbon::parse($data->fixing_date)->format('Y-m-d h:i:s');
        $newMemorial->fixing_status = $data->fixing_status;

        $newMemorial->payment_status = $data->payment_status;

        $newMemorial->view_location = $data->view_location;
        $newMemorial->view_status = $data->view_status;
        $newMemorial->view_date = Carbon::parse($data->view_date)->format('Y-m-d h:i:s');

        $newMemorial->description = $data->description;
        $newMemorial->issue = $data->issues;

        $newMemorial->is_customer_approved = $data->is_customer_approved ? 1 : 0;
        $newMemorial->is_inscription_factory_approved = $data->is_inscription_factory_approved ? 1 : 0;
        $newMemorial->inscription_factory_approved_timestamp = $data->is_inscription_factory_timestamp ? Carbon::parse($data->is_inscription_factory_timestamp)->format('Y-m-d h:i:s') : null;
        $newMemorial->is_burial_society_approved = $data->is_burial_society_approved ? 1 : 0;
        $newMemorial->is_permit_back = $data->is_permit_back ? 1 : 0;

        $newMemorial->{! $id ? 'created_by' : 'updated_by'} = Auth::id();

        $result = $newMemorial->save();

        return self::buildResult($newMemorial, $result, $id, 'pages.schedule.new-memorial');
    }

    public function upsertAddedInscription(Request $data, $id = false)
    {

        $addedInscription = ! $id ? new OrderAddedInscription : OrderAddedInscription::findOrFail($id);

        $addedInscription->order_id = $data->orderId;
        $addedInscription->order_date = self::resolveOrderDate($data);

        $addedInscription->schedule_date = Carbon::parse($data->schedule_date)->format('Y-m-d h:i:s');
        $addedInscription->schedule_status = $data->schedule_status;

        $addedInscription->payment_status = $data->payment_status;

        $addedInscription->details = $data->details;
        $addedInscription->extras = $data->extras;
        $addedInscription->issue = $data->issues;
        $addedInscription->letter_cutter_name = $data->letter_cutter_name;

        $addedInscription->is_customer_approved = $data->is_customer_approved ? 1 : 0;
        $addedInscription->is_inscription_factory_approved = $data->is_inscription_factory_approved ? 1 : 0;
        $addedInscription->inscription_factory_approved_timestamp = $data->is_inscription_factory_timestamp ? Carbon::parse($data->is_inscription_factory_timestamp)->format('Y-m-d h:i:s') : null;
        $addedInscription->is_burial_society_approved = $data->is_burial_society_approved ? 1 : 0;
        $addedInscription->is_permit_back = $data->is_permit_back ? 1 : 0;

        $addedInscription->{! $id ? 'created_by' : 'updated_by'} = Auth::id();

        $result = $addedInscription->save();

        return self::buildResult($addedInscription, $result, $id, 'pages.schedule.added-inscription');
    }

    public function upsertRenovation(Request $data, $id = false)
    {

        $renovation = ! $id ? new OrderRenovation : OrderRenovation::findOrFail($id);

        $renovation->order_id = $data->orderId;
        $renovation->order_date = self::resolveOrderDate($data);

        $renovation->fixing_date = $data->fixing_date ? Carbon::parse($data->fixing_date)->format('Y-m-d') : null;
        $renovation->fixing_status = $data->fixing_status;

        $renovation->payment_status = $data->payment_status;

        $renovation->description = $data->description;

        $renovation->view_location = $data->view_location;
        $renovation->view_status = $data->view_status;
        $renovation->view_date = $data->view_date ? Carbon::parse($data->view_date)->format('Y-m-d') : null;
        $renovation->view_remarks = $data->view_remarks;

        $renovation->details = $data->details;
        $renovation->issue = $data->issues;

        $renovation->is_permit_back = $data->is_permit_back ? 1 : 0;

        $renovation->{! $id ? 'created_by' : 'updated_by'} = Auth::id();

        $result = $renovation->save();

        return self::buildResult($renovation, $result, $id, 'pages.schedule.renovation');
    }

    public function upsertWashdown(Request $data, $id = false)
    {

        $washdown = ! $id ? new OrderWashdown : OrderWashdown::findOrFail($id);

        $washdown->order_id = $data->orderId;
        $washdown->order_date = self::resolveOrderDate($data);

        $washdown->date = $data->date ? Carbon::parse($data->date)->format('Y-m-d') : null;
        $washdown->payment_status = $data->payment_status;
        $washdown->is_completed = $data->is_completed ? 1 : 0;
        $washdown->details = $data->details;
        $washdown->issue = $data->issues;

        $washdown->{! $id ? 'created_by' : 'updated_by'} = Auth::id();

        $result = $washdown->save();

        return self::buildResult($washdown, $result, $id, 'pages.schedule.washdown');
    }
}
