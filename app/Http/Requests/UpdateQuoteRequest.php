<?php

namespace App\Http\Requests;

use App\Models\Order;

class UpdateQuoteRequest extends QuoteRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'invoice_date' => ['nullable', 'date'],
        ]);
    }

    protected function customerIdForContacts(): ?int
    {
        return $this->resolveOrder()?->customer_id;
    }

    /**
     * The quote route param may be a bound model or a raw id depending on
     * route configuration, so resolve defensively.
     */
    private function resolveOrder(): ?Order
    {
        $param = $this->route('quote');

        if ($param instanceof Order) {
            return $param;
        }

        return $param ? Order::find($param) : null;
    }
}
