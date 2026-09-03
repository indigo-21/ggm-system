<?php

namespace App\Http\Requests;

use App\Services\CustomerService;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared validation for creating and updating a quote/order.
 *
 * Concrete Store/Update requests extend this class and layer on any
 * context-specific rules (e.g. invoice fields on update).
 */
abstract class QuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * The customer id the contact-uniqueness checks should ignore.
     * Null on create; the existing customer id on update.
     */
    abstract protected function customerIdForContacts(): ?int;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customerService = app(CustomerService::class);
        $customerId = $this->customerIdForContacts();

        return [
            // Order essentials
            'order_type_id' => ['required', 'integer', 'exists:order_types,id'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],

            // Customer core + contacts (contact uniqueness handled by CustomerService closures)
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', $customerService->contacts_validation($customerId, 'email')],
            'mobile_no' => ['nullable', 'string', $customerService->contacts_validation($customerId, 'mobile_no')],
            'tel_no' => ['nullable', 'string', $customerService->contacts_validation($customerId, 'tel_no')],

            // Masterfile "Others" free-text: required only when the corresponding
            // select is set to "others". This replaces the ad-hoc checks that used
            // to live inside OrderService.
            'custom_cemetery_name' => ['nullable', 'required_if:cemetery_id,others', 'string', 'max:255'],
            'custom_burial_society_name' => ['nullable', 'required_if:burial_society_organization_id,others', 'string', 'max:255'],
            'custom_material_name' => ['nullable', 'required_if:material,others', 'string', 'max:255'],
            'custom_material_colour_name' => ['nullable', 'required_if:material_colour,others', 'string', 'max:255'],
            'custom_base_ledger_name' => ['nullable', 'required_if:base_ledger,others', 'string', 'max:255'],
            'custom_letter_type_name' => ['nullable', 'required_if:letter_type,others', 'string', 'max:255'],
            'custom_accessory_name' => ['nullable', 'required_if:accessory,others', 'string', 'max:255'],
            'custom_accessory_colour_name' => ['nullable', 'required_if:accessory_colour,others', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'custom_cemetery_name.required_if' => "Please enter a cemetery name when selecting 'Others'.",
            'custom_burial_society_name.required_if' => "Please enter a burial society organization name when selecting 'Others'.",
            'custom_material_name.required_if' => "Please enter a material name when selecting 'Others'.",
            'custom_material_colour_name.required_if' => "Please enter a colour name when selecting 'Others'.",
            'custom_base_ledger_name.required_if' => "Please enter a base ledger name when selecting 'Others'.",
            'custom_letter_type_name.required_if' => "Please enter a letter type name when selecting 'Others'.",
            'custom_accessory_name.required_if' => "Please enter an accessory name when selecting 'Others'.",
            'custom_accessory_colour_name.required_if' => "Please enter a colour name when selecting 'Others'.",
        ];
    }
}
