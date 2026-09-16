<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation for creating/updating an order payment (receipt).
 *
 * Per the revised business rules, only Payment Date & Time, Payment Method and
 * Price Amount are mandatory. The Comment (and everything else) is optional.
 */
class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            // Present only on update; null/absent on create.
            'id' => ['nullable', 'integer', 'exists:order_payments,id'],

            // The form field is named "payment_timestamp", but the JS posts its
            // value under the key "payment_datetime" — validate the posted key.
            'payment_datetime' => ['required', 'string'],
            'payment_method' => ['required', 'integer', 'in:1,2,3,4,5'],
            'payment_amount' => ['required', 'numeric', 'min:0'],

            'payment_comment' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'payment_datetime.required' => 'Please select the payment date & time.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'The selected payment method is invalid.',
            'payment_amount.required' => 'Please enter the price amount.',
            'payment_amount.numeric' => 'The price amount must be a valid number.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'payment_datetime' => 'payment date & time',
            'payment_method' => 'payment method',
            'payment_amount' => 'price amount',
            'payment_comment' => 'comment',
        ];
    }
}
