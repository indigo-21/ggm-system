<?php

namespace App\Services;

use App\Exceptions\OrderPersistenceException;
use App\Models\BurialSocietyOrganization;
use App\Models\Cemetery;
use App\Models\Order;
use App\Models\OrderCost;
use App\Models\OrderNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected CustomerService $customerService) {}

    public function order_upsert($request, $id = false)
    {
        try {
            $order_id = DB::transaction(function () use ($request, $id) {
                return $this->persistQuote($request, $id);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Laravel handle field-level validation errors (redirect back
            // with the error bag) instead of collapsing them into a flash message.
            throw $e;
        } catch (OrderPersistenceException $e) {
            // Domain-level validation failure (e.g. duplicate "Others" record).
            // The transaction has already rolled back at this point.
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'order_id' => $id ?: null,
            ];
        } catch (\Throwable $e) {
            // Unexpected failure — roll back and surface a generic message while
            // logging the real cause for investigation.
            Log::error('Quote upsert failed', [
                'order_id' => $id ?: null,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'message' => 'Detect issues in the order request',
                'order_id' => $id ?: null,
            ];
        }

        return [
            'success' => true,
            'message' => $id ? "Order No. $id has been successfully updated" : 'New order successfully created.',
            'order_id' => $order_id,
        ];
    }

    /**
     * Persist the order and its cost/note children within an open transaction.
     * Throws on any failure so the surrounding transaction rolls back.
     *
     * @return int the persisted order id
     */
    private function persistQuote($request, $id = false): int
    {
        $date_of_death = $request->date_of_death;
        $data_of_death_period = $request->period;
        $fixing_date = $request?->fixed_required_by ?? null;
        $customer_id = $this->customerService->form_action($request, $request->customer_id);

        if (! $customer_id) {
            throw new OrderPersistenceException('Detect issues in the customer request');
        }

        if ($data_of_death_period) {
            $date_of_death = trim($date_of_death).' 12:00 '.$data_of_death_period;
        }

        if ($fixing_date != null) {
            $fixing_date = str_replace(' ', ' 01, ', $request?->fixed_required_by);
        }

        $cemetery_id = $this->resolveCemetery($request);

        $data = ! $id ? new Order : Order::findOrFail($id);

        $data->order_type_id = $request->order_type_id;
        $data->location_id = $request->location_id;

        if ($id) {
            $data->invoice_no = $request->invoice_no;
            $data->invoice_date = $request->invoice_date
                ? Carbon::parse($request->invoice_date)->format('Y-m-d')
                : null;
        }

        $data->customer_id = $customer_id;
        $data->date_of_death = $date_of_death ? Carbon::parse($date_of_death)->format('Y-m-d h:i:s') : null;
        $data->deceased_name = $request->deceased_name;

        // Handle No Consecration workflow
        $no_consecration = $request->has('no_consecration') && $request->no_consecration;

        if ($no_consecration) {
            // Clear consecration date when No Consecration is checked
            $data->consecration_date = null;

            // Reset all flags first
            $data->is_tba = 0;
            $data->is_approx = 0;
            $data->is_asap = 0;

            if (isset($request->fixed_date)) {
                switch ($request->fixed_date) {
                    case 'is_tba':
                        $data->is_tba = 1;
                        break;
                    case 'is_approx':
                        $data->is_approx = 1;
                        break;
                    default:
                        $data->is_asap = 1;
                        break;
                }
            }

            // Only store fixing_date when Approx is selected
            if ($request->fixed_date === 'is_approx' && $fixing_date != null) {
                $data->fixing_date = Carbon::parse($fixing_date)->format('Y-m-d');
            } else {
                $data->fixing_date = null;
            }
        } else {
            // Normal consecration workflow
            $data->consecration_date = $request->consecration_date ? Carbon::parse($request->consecration_date)->format('Y-m-d') : null;

            // Clear radio-related fields
            $data->is_tba = 0;
            $data->is_approx = 0;
            $data->is_asap = 0;
            $data->fixing_date = null;
        }

        $data->cemetery_id = $cemetery_id;
        $data->burial_society_organization_id = $this->resolveBurialSociety($request, $cemetery_id);
        $data->grave_number = $request->grave_no;
        $data->grave_number_checked = $request->grave_no_checked
            ? Carbon::parse($request->grave_no_checked)->format('Y-m-d')
            : null;
        $data->grave_space_id = $request->grave_space_id;
        $data->design_headstone = $request->design_headstone;

        // Handle "Others" for masterfile name-based fields
        $data->material = self::resolveOthersField($request, 'material', 'custom_material_name', 'materials');
        $data->material_colour = self::resolveOthersField($request, 'material_colour', 'custom_material_colour_name', 'colours');
        $data->size = $request->size;
        $data->base_ledger = self::resolveOthersField($request, 'base_ledger', 'custom_base_ledger_name', 'based_ledgers');
        $data->letter_type = self::resolveOthersField($request, 'letter_type', 'custom_letter_type_name', 'letter_types');
        $data->accessory = self::resolveOthersField($request, 'accessory', 'custom_accessory_name', 'accessories');
        $data->accessory_colour = self::resolveOthersField($request, 'accessory_colour', 'custom_accessory_colour_name', 'colours');

        $data->kerb_riser = $request->kerb_riser;
        $data->issue = $request->issue;
        $data->special_instruction = $request->special_instruction;
        $data->customer_notes = $request->customer_note;
        $data->additional_notes = $request->additional_note;

        $data->{$id ? 'updated_by' : 'created_by'} = Auth::id();

        if (! $data->save()) {
            throw new OrderPersistenceException('Detect issues in the order request');
        }

        $order_id = $data->id;
        $request['order_id'] = $order_id;

        if (! self::order_cost_upsert($request, $order_id)) {
            throw new OrderPersistenceException('Detect issues in the Order Cost request');
        }

        if (! self::order_note_upsert($request, $order_id)) {
            throw new OrderPersistenceException('Detect issues in the Order Note request');
        }

        return $order_id;
    }

    /**
     * Resolve the cemetery id, creating a new cemetery when the "Others" option
     * is selected. Throws when the requested custom cemetery already exists.
     */
    private function resolveCemetery($request): ?int
    {
        $cemetery_id = $request->cemetery_id;

        if ($cemetery_id !== 'others') {
            return $cemetery_id !== null && $cemetery_id !== '' ? (int) $cemetery_id : null;
        }

        $custom_cemetery_name = trim($request->custom_cemetery_name ?? '');

        // Empty case is guarded by the Form Request (required_if); keep a defensive check.
        if ($custom_cemetery_name === '') {
            throw new OrderPersistenceException("Please enter a cemetery name when selecting 'Others'.");
        }

        $existing = Cemetery::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($custom_cemetery_name)])->first();

        if ($existing) {
            throw new OrderPersistenceException('This cemetery already exists. Please select it from the list.');
        }

        $new_cemetery = new Cemetery;
        $new_cemetery->name = $custom_cemetery_name;
        $new_cemetery->created_by = Auth::id();
        $new_cemetery->save();

        return $new_cemetery->id;
    }

    /**
     * Resolve the burial society organization id, creating a new record when the
     * "Others" option is selected. Throws when a duplicate custom name exists.
     */
    private function resolveBurialSociety($request, ?int $cemetery_id): ?int
    {
        $burial_society_organization_id = $request->burial_society_organization_id;

        if ($burial_society_organization_id !== 'others') {
            return $burial_society_organization_id !== null && $burial_society_organization_id !== ''
                ? (int) $burial_society_organization_id
                : null;
        }

        $custom_bs_name = trim($request->custom_burial_society_name ?? '');

        if ($custom_bs_name === '') {
            throw new OrderPersistenceException("Please enter a burial society organization name when selecting 'Others'.");
        }

        $existing_bs = BurialSocietyOrganization::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($custom_bs_name)])->first();

        if ($existing_bs) {
            throw new OrderPersistenceException('This burial society organization already exists. Please select it from the list.');
        }

        $new_bs = new BurialSocietyOrganization;
        $new_bs->name = $custom_bs_name;
        $new_bs->cemetery_id = $cemetery_id;
        $new_bs->created_by = Auth::id();
        $new_bs->save();

        return $new_bs->id;
    }

    public function order_cost_upsert($request, $order_id = false)
    {

        $orderCost = OrderCost::where('order_id', $order_id)->first();

        $cost_additional_description = $request->input('price_description', []);
        $cost_additional_amount = $request->input('price_amount', []);

        $data = $orderCost ?? new OrderCost;

        $data->order_id = $order_id;
        $data->description = $request->cost_description;
        $data->amount = self::toDecimal($request->cost_amount);
        $data->letter_count = self::toDecimal($request->letters_no);
        $data->letter_amount = self::toDecimal($request->letters_amount);
        $data->letter_total_amount = self::toDecimal($request->letters_total_amount);
        $data->discount_description = $request->discount_description;
        $data->discount_amount = self::toDecimal($request->discount_amount);
        $data->total = self::toDecimal($request->total_amount);
        $data->cemetery_fee_description_1 = $request->cemetery_fee_description_1;
        $data->cemetery_fee_amount_1 = self::toDecimal($request->cemetery_fee_amount_1);
        $data->cemetery_fee_description_2 = $request->cemetery_fee_description_2;
        $data->cemetery_fee_amount_2 = self::toDecimal($request->cemetery_fee_amount_2);
        $data->grand_total = self::toDecimal($request->grand_total_amount);
        $data->deposit_description = $request->deposit_description;
        $data->deposit_amount = self::toDecimal($request->deposit_amount);
        $data->amount_received = self::toDecimal($request->amount_received);
        $data->balance = self::toDecimal($request->balance_amount);
        $data->net_amount = self::toDecimal($request->net_amount);
        $data->vat_rate = self::toDecimal($request->vat_rate);
        $data->vat_amount = self::toDecimal($request->vat_amount);
        $data->zero_rated_fee = self::toDecimal($request->zero_rated_fees);
        $data->adjustment = self::toDecimal($request->adjustment);
        $data->gross_amount = self::toDecimal($request->gross_amount);
        $data->is_cost_analysis_print = $request->is_cost_analysis_print ?? 0;
        $data->is_cost_analysis_trade = $request->is_cost_analysis_trade ?? 0;

        $result = $data->save();

        if ($result) {
            $cost_additional_description = is_array($cost_additional_description)
                ? $cost_additional_description
                : [];
            $cost_additional_amount = is_array($cost_additional_amount)
                ? $cost_additional_amount
                : [];
            $cost_additional_data = [];
            foreach ($cost_additional_description as $index => $description) {
                if (filled($description)) {
                    $cost_additional_data[] = [
                        'order_cost_id' => $data->id,
                        'description' => $description,
                        'amount' => self::toDecimal($cost_additional_amount[$index] ?? 0),
                    ];
                }
            }

            // Replace the child rows as a set.  This also removes stale rows
            // when the user clears every additional-cost field on an update.
            DB::table('order_cost_additionals')->where('order_cost_id', $data->id)->delete();
            if (count($cost_additional_data) > 0) {
                $result = DB::table('order_cost_additionals')->insert($cost_additional_data);
            }
        }

        return $result ? $data->id : false;
    }

    public function order_note_upsert($request, $order_id = false)
    {

        $orderNote = OrderNote::where('order_id', $order_id)->first();

        // $data = !$id ? new OrderNote() : OrderNote::findOrFail($id);
        $data = $orderNote ?? new OrderNote;

        $data->order_id = $request->order_id;
        $data->free_letters = $request->free_letters;
        $data->is_burial_society_fees_included = $request->is_burial_society_fees_included;
        $data->is_inscription_complete = $request->is_inscription_complete;
        $data->is_application_form_sent_to_bs_with_cheque = $request->is_sent_to_bs_with_cheque;
        $data->is_application_form_sent_to_bs_with_cheque_timestamp = $request->is_sent_to_bs_with_cheque_timestamp ? Carbon::parse($request->is_sent_to_bs_with_cheque_timestamp)->format('Y-m-d H:i:s') : null;
        $data->is_application_form_sent_to_bs_without_cheque = $request->is_sent_to_bs_without_cheque;
        $data->is_application_form_sent_to_bs_without_cheque_timestamp = $request->is_sent_to_bs_without_cheque_timestamp ? Carbon::parse($request->is_sent_to_bs_without_cheque_timestamp)->format('Y-m-d H:i:s') : null;
        $data->is_permit_not_required = $request->is_permit_not_required;
        $data->is_insurance = $request->is_insurance;
        $data->is_insurance_services = $request->is_insurance_services;
        $data->is_washdown_discussed = $request->is_washdown_discussed;
        $data->is_paid_by_bacs = $request->is_paid_by_bacs;
        $data->is_paid_by_bacs_timestamp = $request->is_paid_by_bacs_timestamp ? Carbon::parse($request->is_paid_by_bacs_timestamp)->format('Y-m-d H:i:s') : null;
        $data->is_full_inscription_received = $request->is_full_inscription_received;
        $data->is_sent_to_burial_society = $request->is_sent_to_burial_society;
        $data->is_received_from_burial_society = $request->is_received_from_burial_society;
        $data->is_order_complete = $request->is_order_complete;
        $data->inscription_sent_to_design_team_for_printout = $request->inscription_sent_to_design_team_for_printout ? Carbon::parse($request->inscription_sent_to_design_team_for_printout)->format('Y-m-d') : null;
        $data->inscription_sent_to_gary_for_printout = $request->inscription_sent_to_gary_for_printout ? Carbon::parse($request->inscription_sent_to_gary_for_printout)->format('Y-m-d') : null;
        $data->received_back_from_design_team = $request->received_back_from_design_team ? Carbon::parse($request->received_back_from_design_team)->format('Y-m-d') : null;
        $data->sent_to_customer = $request->sent_to_customer ? Carbon::parse($request->sent_to_customer)->format('Y-m-d') : null;
        $data->back_to_design_team_for_further_alterations = $request->back_to_design_team_for_further_alterations ? Carbon::parse($request->back_to_design_team_for_further_alterations)->format('Y-m-d') : null;
        $data->masonart_printout_approved = $request->masonart_printout_approved ? Carbon::parse($request->masonart_printout_approved)->format('Y-m-d') : null;
        // $data->approved_by_burial_society = Carbon::parse($request?->approved_by_burial_society)->format('Y-m-d') ?? null;
        $data->approved_by_burial_society = $request?->approved_by_burial_society;

        $result = $data->save();

        return $result ? $data->id : false;
    }

    /**
     * Normalize a currency/numeric form value into a decimal string.
     *
     * Strips thousands separators and coerces empty/blank input to 0 so that
     * MySQL (in strict mode) never receives '' for a numeric column, which is
     * what caused the "Incorrect double value: ''" error on empty fee fields.
     */
    private static function toDecimal($value, float $default = 0): string
    {
        if ($value === null) {
            return (string) $default;
        }

        $normalized = str_replace(',', '', trim((string) $value));

        return is_numeric($normalized) ? $normalized : (string) $default;
    }

    /**
     * Resolve a masterfile "Others" field: if value is 'others', validate + create record + return custom name.
     * Otherwise return the selected value directly.
     */
    private static function resolveOthersField($request, $fieldName, $customFieldName, $table)
    {
        $value = $request->input($fieldName);

        if ($value !== 'others') {
            return $value;
        }

        $customName = trim($request->input($customFieldName, ''));

        if (empty($customName)) {
            return null;
        }

        // Tables that use soft deletes
        $softDeleteTables = ['materials', 'based_ledgers', 'letter_types', 'accessories'];

        // Check for duplicate (case-insensitive)
        $query = DB::table($table)->whereRaw('LOWER(TRIM(name)) = ?', [strtolower($customName)]);
        if (in_array($table, $softDeleteTables)) {
            $query->whereNull('deleted_at');
        }
        $existing = $query->first();

        if ($existing) {
            // Record already exists — use its name (normalized from DB)
            return $existing->name;
        }

        // Create new record
        $now = now();
        $insertData = [
            'name' => $customName,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Add created_by for tables that support it
        if ($table !== 'colours') {
            $insertData['created_by'] = Auth::id();
        }

        DB::table($table)->insert($insertData);

        return $customName;
    }
}
