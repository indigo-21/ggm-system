<x-app-layout>
    <style>
        @keyframes cemetery-pulse {
            0%   { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 150, 136, 0.4); }
            40%  { transform: scale(1.02); box-shadow: 0 0 8px 4px rgba(0, 150, 136, 0.2); }
            70%  { transform: scale(0.99); box-shadow: 0 0 4px 2px rgba(0, 150, 136, 0.1); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 150, 136, 0); }
        }

        #custom_cemetery_wrapper.cemetery-animate {
            animation: cemetery-pulse 0.8s ease-in-out 1;
        }

        #custom_burial_society_wrapper.cemetery-animate {
            animation: cemetery-pulse 0.8s ease-in-out 1;
        }

        .custom-others-input.cemetery-animate {
            animation: cemetery-pulse 0.8s ease-in-out 1;
        }

        @media (prefers-reduced-motion: reduce) {
            #custom_cemetery_wrapper.cemetery-animate,
            #custom_burial_society_wrapper.cemetery-animate,
            .custom-others-input.cemetery-animate {
                animation: none;
            }
        }
    </style>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('quote.index') }}">Quotation</a></li>
                            <li class="breadcrumb-item active">{{ !isset($quote) ? 'Creating' : 'Updating' }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">
                            {{ !isset($quote) ? 'Create New Quotation' : 'Update Quotation #' . $quote->id }}
                        </h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Quotation</button> --}}
                        <a href="{{ route('quote.index') }}" class="btn btn-secondary hidden-xs ml-2 px-5">Back</a>
                    </div>
                </div>
                <div class="bh_divider"></div>
            </div>
        </div>
    </x-slot>

    <div class="container">
        @if (Session::has('error'))
            <div class="alert alert-danger">
                <strong>Please contact your Administrator!</strong> {{ Session::get('error') }}
            </div>
        @endif

        <form id="form_validation" method="POST"
            action="{{ !isset($quote) ? route('quote.store') : route('quote.update', $quote->id) }}">
            @csrf
            @if (isset($quote))
                @method('PUT')
            @endif
            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="body row">
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="order_type_id" label="Order Type"
                                    :required="true" search="true">
                                    <option value="" disabled selected>-Select Order Type-</option>
                                    @php
                                        $old_order_type = $quote?->order_type_id ?? old('order_type_id');
                                    @endphp
                                    @foreach ($order_types as $order_type)
                                        <option value="{{ $order_type->id }}"
                                            {{ $old_order_type == $order_type->id ? 'selected' : '' }}>
                                            {{ $order_type->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="location_id" label="Branch" :required="true">
                                    <option value="" disabled selected>-Select Branch-</option>
                                    @php
                                        $old_location = $quote?->location_id ?? $auth_session->location_id;
                                    @endphp
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}"
                                            {{ $old_location == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                @php
                                    $order_date = isset($quote)
                                        ? $quote->created_at->format('F d, Y')
                                        : old('order_type');
                                @endphp
                                <x-input type="text" name="order_date" value="{{ $order_date }}"
                                    class="daterange-has-current" label="Order Date" :required="true" />
                            </div>
                            @if (isset($quote))
                                @php
                                    $invoice_no = $quote->invoice_no ? $quote->invoice_no : '';
                                    $invoice_date = $quote->invoice_date
                                        ? date('F d, Y', strtotime($quote->invoice_date))
                                        : '';
                                @endphp
                                <div class="col-6">
                                    <x-input type="text" name="invoice_no" value="{{ $invoice_no }}"
                                        inputformat="alphanumeric" label="Invoice No." />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="invoice_date" value="{{ $invoice_date }}"
                                        class="daterange" label="Invoice Date" />
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Customer Details</h2>
                            <input type="hidden" name="customer_id"
                                value="{{ isset($quote) ? $quote->customer_id : '' }} ">

                            {{ $errors->any() }}

                            @if (!isset($quote))
                                <button type="button" class="btn btn-default btn-simple waves-effect"
                                    id="searchCustomer" data-dismiss="modal">Search Exisiting Customer</button>
                            @endif


                        </div>
                        <div class="body row">
                            <div class="col-2">
                                <x-select class="z-index show-tick" name="title" label="Title">
                                    <option value="" disabled selected>-Select Title-</option>
                                    @php
                                        $old_title = isset($quote) ? $quote->customer->title : old('title');
                                    @endphp
                                    @foreach ($titles as $title)
                                        <option value="{{ $title }}"
                                            {{ $old_title == $title ? 'selected' : '' }}>{{ $title }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-5">
                                <x-input type="text" name="firstname"
                                    value="{{ isset($quote) ? $quote->customer->firstname : old('firstname') }}"
                                    label="Firstname" />
                            </div>
                            <div class="col-5">
                                <x-input type="text" name="lastname" label="Lastname"
                                    value="{{ isset($quote) ? $quote->customer->lastname : old('lastname') }}"
                                    required="true" :error="$errors->first('lastname')" />
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="salutation"
                                    value="{{ isset($quote) ? $quote->customer->salutation : old('salutation') }}"
                                    label="Salutation" />
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="email" inputformat="specialcharacter"
                                    value="{{ isset($quote) ? $customer_email : old('email') }}" label="Email Address"
                                    readonly="true" :error="$errors->first('email')" />
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="address_1" value="" label="Address Line 1"
                                    value="{{ isset($quote) ? $quote->customer?->address_one : old('address_1') }}" />
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="address_2" value="" label="Address Line 2"
                                    value="{{ isset($quote) ? $quote->customer?->address_two : old('address_2') }}" />
                            </div>

                            <div class="col-3">
                                <x-input type="text" name="city_county" label="City / County"
                                    value="{{ isset($quote) ? $quote->customer?->city_county : old('city_county') }}" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="post_code"
                                    value="{{ isset($quote) ? $quote->customer?->postcode : old('post_code') }}"
                                    label="Post Code" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="tel_no"
                                    value="{{ isset($quote) ? $customer_tel_no : old('tel_no') }}" label="Tel. No."
                                    readonly="true" :error="$errors->first('tel_no')" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="mobile_no"
                                    value="{{ isset($quote) ? $customer_mobile_no : old('mobile_no') }}"
                                    label="Mobile No." readonly="true" :error="$errors->first('mobile_no')" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="header">
                            <h2>Order Details</h2>
                        </div>
                        <div class="body row">
                            <div class="col-6">
                                <x-input type="text" name="deceased_name"
                                    value="{{ isset($quote) ? $quote->deceased_name : old('deceased_name') }}"
                                    label="Deceased" />
                            </div>

                            <div class="col-6">
                                @php
                                    $date_of_death = isset($quote)
                                        ? ($quote->date_of_death
                                            ? date('F d, Y A', strtotime($quote->date_of_death))
                                            : '')
                                        : old('date_of_death');
                                @endphp
                                <x-input type="text" name="date_of_death" value="{{ $date_of_death }}"
                                    class="daterange-am-pm clear-daterange" label="Date of Death" />
                            </div>

                            <div class="col-6">
                                @php
                                    $consecration_date = isset($quote)
                                        ? ($quote->consecration_date
                                            ? date('F d, Y', strtotime($quote->consecration_date))
                                            : '')
                                        : old('consecration_date');
                                @endphp
                                <x-input type="text" name="consecration_date" value="{{ $consecration_date }}"
                                    class="daterange clear-daterange" label="Consecration / Required By" />
                            </div>

                            <div class="col-6">
                                @php
                                    $old_cemetery = isset($quote) ? $quote?->cemetery_id ?? '' : old('cemetery_id');
                                @endphp
                                <x-select class="z-index show-tick" name="cemetery_id" label="Cemetery"
                                    search="true">
                                    <option value="" disabled {{ $old_cemetery == '' ? 'selected' : '' }}>
                                        -Select Cemeteries-</option>
                                    @foreach ($cemeteries as $cemetery)
                                        <option {{ $old_cemetery == $cemetery->id ? 'selected' : '' }}
                                            value="{{ $cemetery->id }}">
                                            {{ $cemetery->name }}
                                        </option>
                                    @endforeach
                                    <option value="others" {{ old('cemetery_id') == 'others' ? 'selected' : '' }}>Others</option>
                                </x-select>
                            </div>

                            <div class="col-6" id="custom_cemetery_wrapper" style="{{ old('cemetery_id') == 'others' ? '' : 'display:none;' }}">
                                <div class="form-group form-float">
                                    <label for="custom_cemetery_name">Custom Cemetery Name</label>
                                    <input type="text" class="form-control" name="custom_cemetery_name" id="custom_cemetery_name"
                                        value="{{ old('custom_cemetery_name') }}" placeholder="Enter new cemetery name">
                                    <label id="custom_cemetery_name-error" class="error" for="custom_cemetery_name" style="display:none;"></label>
                                </div>
                            </div>

                            <div class="col-6 row">
                                <div class="col-6">
                                    @php
                                        $is_tba = isset($quote) ? $quote->is_tba : old('fixed_date') == 1;
                                        $is_approx = isset($quote) ? $quote->is_approx : old('fixed_date') == 2;
                                        $is_asap = isset($quote) ? $quote->is_asap : old('fixed_date') == 3;
                                    @endphp
                                    <div class="col-12">
                                        <x-input type="radio" name="fixed_date" id="is_tba"
                                            checked="{{ $is_tba }}" value="is_tba" class="with-gap mr-2"
                                            label="To be Advised" />
                                    </div>
                                    <div class="col-12">
                                        <x-input type="radio" name="fixed_date" id="is_approx"
                                            checked="{{ $is_approx }}" value="is_approx" class="with-gap mr-2"
                                            label="Approximate" />
                                    </div>
                                    <div class="col-12">
                                        <x-input type="radio" name="fixed_date" id="is_asap"
                                            checked="{{ $is_asap }}" value="is_asap" class="with-gap mr-2"
                                            label="ASAP" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12">
                                        @php
                                            $fixing_date = isset($quote)
                                                ? ($quote->fixing_date
                                                    ? date('F Y', strtotime($quote->fixing_date))
                                                    : '')
                                                : '';
                                        @endphp
                                        <x-input type="text" name="fixed_required_by" value="{{ $fixing_date }}"
                                            class="month-year" label="Required By" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                @php
                                    $old_burial_society_organization = isset($quote)
                                        ? $quote?->burial_society_organization_id ?? ''
                                        : old('burial_society_organization_id');
                                @endphp
                                <x-select class="z-index show-tick" name="burial_society_organization_id"
                                    label="Burial Society Organization">
                                    <option value="" disabled
                                        {{ $old_burial_society_organization == '' ? 'selected' : '' }}>-Select Burial
                                        Society Organization-
                                    </option>
                                    @foreach ($burial_society_organizations as $burial_society_organization)
                                        <option
                                            {{ $old_burial_society_organization == $burial_society_organization->id ? 'selected' : '' }}
                                            class="cemetery_{{ $burial_society_organization->cemetery_id }} d-none"
                                            value="{{ $burial_society_organization->id }}">
                                            {{ $burial_society_organization->name }}
                                        </option>
                                    @endforeach
                                    <option value="others" class="cemetery_others d-none"
                                        {{ old('burial_society_organization_id') == 'others' ? 'selected' : '' }}>Others</option>
                                </x-select>
                            </div>

                            <div class="col-6" id="custom_burial_society_wrapper" style="{{ old('burial_society_organization_id') == 'others' ? '' : 'display:none;' }}">
                                <div class="form-group form-float">
                                    <label for="custom_burial_society_name">Custom Burial Society Organization Name</label>
                                    <input type="text" class="form-control" name="custom_burial_society_name" id="custom_burial_society_name"
                                        value="{{ old('custom_burial_society_name') }}" placeholder="Enter new burial society organization name">
                                    <label id="custom_burial_society_name-error" class="error" for="custom_burial_society_name" style="display:none;"></label>
                                </div>
                            </div>

                            <div class="col-4">
                                <x-input type="text" name="grave_no"
                                    value="{{ isset($quote) ? $quote->grave_number : old('grave_no') }}"
                                    label="Grave Number" />
                            </div>
                            <div class="col-4">
                                @php
                                    $grave_no_checked = isset($quote)
                                        ? date('F d, Y', strtotime($quote->grave_number_checked))
                                        : old('grave_no_checked');
                                @endphp
                                <x-input type="text" name="grave_no_checked" value="{{ $grave_no_checked }}"
                                    class="daterange clear-daterange" label="Grave Number Checked" />
                            </div>
                            <div class="col-4">
                                @php
                                    $old_grave_space = isset($quote)
                                        ? $quote?->grave_space_id ?? ''
                                        : old('grave_space_id');
                                @endphp
                                <x-select class="z-index show-tick" name="grave_space_id" label="Grave Space">
                                    <option value="" disabled {{ $old_grave_space == '' ? 'selected' : '' }}>
                                        -Select Burial Grave Space-</option>
                                    @foreach ($grave_spaces as $grave_space)
                                        <option {{ $old_grave_space == $grave_space->id }}
                                            value="{{ $grave_space->id }}">
                                            {{ $grave_space->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>


                            <div class="col-4">
                                <x-input type="text" name="design_headstone"
                                    value="{{ isset($quote) ? $quote->design_headstone : old('design_headstone') }}"
                                    label="Design / Headstone" />
                            </div>
                            <div class="col-4">
                                @php
                                    $old_material = isset($quote) ? $quote->material : old('material');
                                    $material_data = [];
                                    foreach ($materials as $material) {
                                        $material_data[] = $material->name;
                                    }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="material"
                                    label="Material" search="true">

                                    <option value="" disabled {{ $old_material == '' ? 'selected' : '' }}>
                                        -Select Material-</option>

                                    @if ($old_material != '' && !in_array($old_material, $material_data))
                                        <option value="{{ $old_material }}" selected isother="true">
                                            {{ $old_material }}</option>
                                    @endif

                                    @foreach ($materials as $material)
                                        <option {{ $old_material == $material->name ? 'selected' : '' }}
                                            value="{{ $material->name }}">
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                                <div class="custom-others-input" id="custom_material_wrapper" style="display:none;">
                                    <input type="text" class="form-control" name="custom_material_name" id="custom_material_name"
                                        value="{{ old('custom_material_name') }}" placeholder="Enter new material name">
                                    <label id="custom_material_name-error" class="error" for="custom_material_name" style="display:none;"></label>
                                </div>
                            </div>
                            <div class="col-4">
                                @php
                                    $old_material_colour = isset($quote)
                                        ? $quote->material_colour
                                        : old('material_colour');
                                    $material_colour_data = [];
                                    foreach ($colours as $colour) {
                                        $material_colour_data[] = $colour->name;
                                    }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="material_colour"
                                    label="Material - Colour" search="true">
                                    <option value="" disabled
                                        {{ $old_material_colour == '' ? 'selected' : '' }}>-Select Colour-</option>
                                    @if ($old_material_colour != '' && !in_array($old_material_colour, $material_colour_data))
                                        <option value="{{ $old_material_colour }}" selected isother="true">
                                            {{ $old_material_colour }}</option>
                                    @endif
                                    @foreach ($colours as $colour)
                                        <option {{ $old_material == $colour->name ? 'selected' : '' }}
                                            value="{{ $colour->name }}">
                                            {{ $colour->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                                <div class="custom-others-input" id="custom_material_colour_wrapper" style="display:none;">
                                    <input type="text" class="form-control" name="custom_material_colour_name" id="custom_material_colour_name"
                                        value="{{ old('custom_material_colour_name') }}" placeholder="Enter new colour name">
                                    <label id="custom_material_colour_name-error" class="error" for="custom_material_colour_name" style="display:none;"></label>
                                </div>
                            </div>
                            <div class="col-4">
                                @php
                                    $old_size = isset($quote) ? $quote->size : old('size');
                                @endphp
                                <x-input type="text" name="size" value="{!! $old_size !!}"
                                    label="Size" />
                            </div>
                            <div class="col-4">
                                @php
                                    $old_base_ledger = isset($quote) ? $quote->base_ledger : old('base_ledger');
                                    $base_ledger_data = [];
                                    foreach ($base_ledgers as $base_ledger) {
                                        $base_ledger_data[] = $base_ledger->name;
                                    }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="base_ledger"
                                    label="Base Ledger" search="true">
                                    <option value="" disabled {{ $old_base_ledger == '' ? 'selected' : '' }}>
                                        -Select Base Ledger-</option>
                                    @if ($old_base_ledger != '' && !in_array($old_base_ledger, $base_ledger_data))
                                        <option value="{{ $old_base_ledger }}" selected isother="true">
                                            {{ $old_base_ledger }}</option>
                                    @endif
                                    @foreach ($base_ledgers as $base_ledger)
                                        <option {{ $old_base_ledger == $base_ledger->name ? 'selected' : '' }}
                                            value="{{ $base_ledger->name }}">
                                            {{ $base_ledger->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                                <div class="custom-others-input" id="custom_base_ledger_wrapper" style="display:none;">
                                    <input type="text" class="form-control" name="custom_base_ledger_name" id="custom_base_ledger_name"
                                        value="{{ old('custom_base_ledger_name') }}" placeholder="Enter new base ledger name">
                                    <label id="custom_base_ledger_name-error" class="error" for="custom_base_ledger_name" style="display:none;"></label>
                                </div>
                            </div>
                            <div class="col-4">
                                @php
                                    $old_letter_type = isset($quote) ? $quote->letter_type : old('letter_type');
                                    $letter_type_data = [];
                                    foreach ($letter_types as $letter_type) {
                                        $letter_type_data[] = $letter_type->name;
                                    }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="letter_type"
                                    label="Letter Type" search="true">
                                    <option value="" disabled {{ $old_letter_type == '' ? 'selected' : '' }}>
                                        -Select Letter Type-</option>
                                    @if ($old_letter_type != '' && !in_array($old_letter_type, $letter_type_data))
                                        <option value="{{ $old_letter_type }}" selected isother="true">
                                            {{ $old_letter_type }}</option>
                                    @endif
                                    @foreach ($letter_types as $letter_type)
                                        <option {{ $old_letter_type == $letter_type->name }}
                                            value="{{ $letter_type->name }}">
                                            {{ $letter_type->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                                <div class="custom-others-input" id="custom_letter_type_wrapper" style="display:none;">
                                    <input type="text" class="form-control" name="custom_letter_type_name" id="custom_letter_type_name"
                                        value="{{ old('custom_letter_type_name') }}" placeholder="Enter new letter type name">
                                    <label id="custom_letter_type_name-error" class="error" for="custom_letter_type_name" style="display:none;"></label>
                                </div>
                            </div>
                            <div class="col-4">
                                @php
                                    $old_accessory = isset($quote) ? $quote->accessory : old('accessory');
                                    $accessory_data = [];
                                    foreach ($accessories as $accessory) {
                                        $accessory_data[] = $accessory->name;
                                    }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="accessory"
                                    label="Accessories" search="true">
                                    <option value="" disabled {{ $old_accessory == '' ? 'selected' : '' }}>
                                        -Select Accessories Type-</option>
                                    @if ($old_accessory != '' && !in_array($old_accessory, $accessory_data))
                                        <option value="{{ $old_accessory }}" selected isother="true">
                                            {{ $old_accessory }}</option>
                                    @endif
                                    @foreach ($accessories as $accessory)
                                        <option {{ $old_accessory == $accessory->name ? 'selected' : '' }}
                                            value="{{ $accessory->name }}">
                                            {{ $accessory->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                                <div class="custom-others-input" id="custom_accessory_wrapper" style="display:none;">
                                    <input type="text" class="form-control" name="custom_accessory_name" id="custom_accessory_name"
                                        value="{{ old('custom_accessory_name') }}" placeholder="Enter new accessory name">
                                    <label id="custom_accessory_name-error" class="error" for="custom_accessory_name" style="display:none;"></label>
                                </div>
                            </div>
                            <div class="col-4">
                                @php
                                    $old_accessory_colour = isset($quote)
                                        ? $quote->accessory_colour
                                        : old('accessory_colour');
                                    $accessory_colour_data = [];
                                    foreach ($colours as $accessory_colour) {
                                        $accessory_colour_data[] = $accessory_colour->name;
                                    }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="accessory_colour"
                                    label="Accessories - Colour" search="true">
                                    <option value="" disabled
                                        {{ $old_accessory_colour == '' ? 'selected' : '' }}>-Select Colour-</option>
                                    @foreach ($colours as $accessory_colour)
                                        <option
                                            {{ $old_accessory_colour == $accessory_colour->name ? 'selected' : '' }}
                                            value="{{ $accessory_colour->name }}">
                                            {{ $accessory_colour->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                                <div class="custom-others-input" id="custom_accessory_colour_wrapper" style="display:none;">
                                    <input type="text" class="form-control" name="custom_accessory_colour_name" id="custom_accessory_colour_name"
                                        value="{{ old('custom_accessory_colour_name') }}" placeholder="Enter new colour name">
                                    <label id="custom_accessory_colour_name-error" class="error" for="custom_accessory_colour_name" style="display:none;"></label>
                                </div>
                            </div>

                            <div class="col-4">
                                <x-input type="text" name="kerb_riser"
                                    value="{{ isset($quote) ? $quote->kerb_riser : old('kerb_riser') }}"
                                    label="Kerbs / Risers" />
                            </div>



                            <div class="col-12">
                                <x-input type="textarea" name="issue"
                                    value="{{ isset($quote) ? $quote->issue : old('issue') }}" label="Issues" />
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="header">
                            <h2>Cost</h2>
                        </div>
                        <div class="body row">
                            <div class="col-6 row px-5">
                                <div class="col-12 row">
                                    <div class="col-7">
                                        @php
                                            $old_cost_description = isset($order_cost)
                                                ? $order_cost?->description ?? ''
                                                : '';
                                        @endphp
                                        <x-input type="text" name="cost_description"
                                            value="{{ $old_cost_description }}" label="Price Description" />
                                    </div>
                                    <div class="col-5">
                                        @php
                                            $old_cost_amount = isset($order_cost)
                                                ? $order_cost?->amount ?? '0.00'
                                                : '0.00';
                                        @endphp
                                        <x-input type="text" class="text-right cost-computation for-total-amount"
                                            name="cost_amount" value="{{ $old_cost_amount }}"
                                            label="Price Amount" />
                                    </div>
                                </div>
                                <div class="col-12 row letters-section">
                                    <div class="col-2">
                                        @php
                                            $old_letters_no = isset($order_cost)
                                                ? $order_cost?->letter_count ?? '0'
                                                : '0';
                                        @endphp
                                        <x-input type="text" class="cost-computation" name="letters_no"
                                            value="{{ $old_letters_no }}" :label="false" />
                                    </div>
                                    <div class="col-2">
                                        <small class="fw-bold">Letters @</small>
                                    </div>
                                    <div class="col-3">
                                        @php
                                            $old_letters_amount = isset($order_cost)
                                                ? $order_cost?->letter_amount ?? '0.00'
                                                : '0.00';
                                        @endphp
                                        <x-input type="text" class="text-right cost-computation"
                                            name="letters_amount" value="{{ $old_letters_amount }}"
                                            :label="false" />
                                    </div>
                                    <div class="col-5">
                                        @php
                                            $old_letters_total_amount = isset($order_cost)
                                                ? $order_cost?->letter_total_amount ?? '0.00'
                                                : '0.00';
                                        @endphp
                                        <x-input type="text" class="text-right cost-computation for-total-amount"
                                            name="letters_total_amount" readonly="true"
                                            value="{{ $old_letters_total_amount }}" :label="false" />
                                    </div>
                                </div>

                                @isset($order_inscription_count)
                                    <div class="col-12">
                                        <div class="">Inscription letter count: <strong
                                                class="fw-bold">{{ $order_inscription_count }}</strong> </div>
                                    </div>
                                @endisset

                                @if (isset($order_cost) && count($order_cost->additionals) > 0)
                                    @foreach ($order_cost->additionals as $index => $additional)
                                        <div class="col-12 row cost-additional-section">
                                            <div class="col-1 d-flex align-items-center">
                                                <button type="button"
                                                    class="btn btn-danger btn-simple waves-effect remove-additional-cost d-flex justify-content-center align-items-center">
                                                    <i class="zmdi zmdi-minus-circle"></i> </button>
                                            </div>
                                            <div class="col-6">
                                                <x-input type="textarea"
                                                    name="price_description[{{ $index }}]"
                                                    value="{{ $additional?->description ?? '' }}"
                                                    :label="false" />
                                            </div>
                                            <div class="col-5 d-flex align-items-center">
                                                <x-input type="text"
                                                    class="text-right cost-computation for-total-amount"
                                                    name="price_amount[{{ $index }}]"
                                                    value="{{ $additional?->amount ?? '0.00' }}" :label="false" />
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @for ($index = 0; $index < 4; $index++)
                                        <div class="col-12 row cost-additional-section">
                                            <div class="col-1 d-flex align-items-center">
                                                <button type="button"
                                                    class="btn btn-danger btn-simple waves-effect remove-additional-cost d-flex justify-content-center align-items-center">
                                                    <i class="zmdi zmdi-minus-circle"></i> </button>
                                            </div>
                                            <div class="col-6">
                                                <x-input type="textarea"
                                                    name="price_description[{{ $index }}]" value=""
                                                    :label="false" />
                                            </div>
                                            <div class="col-5 d-flex align-items-center">
                                                <x-input type="text"
                                                    class="text-right cost-computation for-total-amount"
                                                    name="price_amount[{{ $index }}]" value="0.00"
                                                    :label="false" />
                                            </div>
                                        </div>
                                    @endfor
                                @endif


                                <div class="col-12 row mt-5">
                                    <div class="col-12">
                                        <button type="button"
                                            class="btn btn-danger btn-simple waves-effect w-100 add-additional-cost">
                                            Add Cost </button>
                                    </div>
                                </div>

                                <div class="col-12 row mt-5">
                                    <div class="col-7">
                                        <x-input type="text" name="discount_description"
                                            value="{{ $order_cost?->discount_description ?? '' }}"
                                            label="Discount" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right cost-computation"
                                            name="discount_amount"
                                            value="{{ $order_cost?->discount_amount ?? '0.00' }}" label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="total_amount"
                                            value="{{ $order_cost?->total ?? '0.00' }}" readonly="true"
                                            label="Total" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="cemetery_fee_description_1"
                                            value="{{ $order_cost?->cemetery_fee_description_1 ?? '' }}"
                                            label="Cemetery Fees 1" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right zero-rated cost-computation"
                                            name="cemetery_fee_amount_1"
                                            value="{{ $order_cost?->cemetery_fee_amount_1 ?? '0.00' }}"
                                            label="Amount" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="cemetery_fee_description_2"
                                            value="{{ $order_cost?->cemetery_fee_description_2 ?? '' }}"
                                            label="Cemetery Fees 2" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right zero-rated cost-computation"
                                            name="cemetery_fee_amount_2"
                                            value="{{ $order_cost?->cemetery_fee_amount_2 ?? '0.00' }}"
                                            label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row my-3">
                                    @php
                                        $is_red_label = false;
                                        $grand_total_label = 'Grand Total';
                                        if (isset($quote)) {
                                            $grand_total_additional_label = !$order_note?->is_inscription_complete
                                                ? 'Incomplete'
                                                : 'Completed';
                                            $is_red_label = !$order_note?->is_inscription_complete;
                                            if ($quote->order_type_id == 1 || $quote->order_type_id == 2) {
                                                $grand_total_label =
                                                    'Grand Total - Inscription ' . $grand_total_additional_label;
                                            }
                                        }
                                    @endphp
                                    <div class="col-7 col-7 d-flex align-items-center">
                                        <label class="{{ $is_red_label ? 'text-danger' : '' }}"
                                            for="grand_total_amount">{{ $grand_total_label }}</label>
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="grand_total_amount"
                                            value="{{ number_format($order_cost?->grand_total ?? 0, 2) ?? '0.00' }}"
                                            readonly="true" :label="false" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="col-7">
                                        @php
                                            $deposit_description = isset($order_payments)
                                                ? $order_payments->first()?->comment
                                                : '';
                                        @endphp
                                        <x-input type="text" name="deposit_description"
                                            value="{{ $deposit_description ?? '' }}" label="Deposit" />
                                    </div>
                                    <div class="col-5">
                                        @php
                                            $deposit_amount = isset($order_payments)
                                                ? number_format($order_payments->first()->amount ?? 0, 2)
                                                : '0.00';
                                        @endphp
                                        <x-input type="text" class="text-right cost-computation"
                                            name="deposit_amount" value="{{ $deposit_amount }}" label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="amount_received"
                                            value="{{ number_format($total_deposit ?? 0, 2) ?? '0.00' }}"
                                            readonly="true" label="Amount Received" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="balance_amount"
                                            value="{{ number_format($order_balance ?? 0, 2) ?? '0.00' }}"
                                            readonly="true" label="Balance" />
                                    </div>
                                </div>

                            </div>

                            <div class="col-6">
                                <div class="col-12 py-1">
                                    <x-input type="textarea" class="text-right" name="special_instruction"
                                        value="{{ isset($quote) ? $quote->special_instruction : '' }}"
                                        label="Special Instruction (for Admin)" />
                                </div>

                                @isset($quote)
                                    <div class="col-12 d-flex align-items-center py-5">
                                        <button type="button" class="btn btn-danger btn-simple waves-effect mr-5"
                                            order_id="{{ $quote->id }}" id="note_btn">Notes</button>
                                        <button type="button" class="btn btn-danger btn-simple waves-effect"
                                            order_id="{{ $quote->id }}" id="factory_note_btn">Factory Notes</button>
                                    </div>
                                @endisset

                                <div class="col-12 py-3">
                                    <x-input type="textarea" class="text-right" name="customer_note"
                                        value="{{ isset($quote) ? $quote->customer_notes : '' }}"
                                        label="Customer Notes" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="header">
                            <h2>VAT Analysis</h2>
                        </div>
                        <div class="body row">
                            <div class="col-6 row px-5">
                                @php
                                    $net_amount = $order_cost?->net_amount ?? 0;
                                    $vat_rate = $order_cost?->vat_rate ?? 20;
                                    $vat_amount = $order_cost?->vat_amount ?? 0;
                                    $zero_rated_fee = $order_cost?->zero_rated_fee ?? 0;
                                    $adjustment = $order_cost?->adjustment ?? 0;
                                    $gross_amount = $order_cost?->gross_amount ?? 0;
                                @endphp
                                <div class="col-12 row">
                                    <div class="col-12">
                                        <x-input type="text" class="text-right" name="net_amount"
                                            value="{{ number_format($net_amount, 2) }}" readonly="true"
                                            label="Net Amount" />
                                    </div>
                                    <div class="col-12">
                                        <x-input type="text" class="text-right" name="vat_rate"
                                            value="{{ $vat_rate }}" readonly="true" label="VAT Rate (%)" />
                                    </div>

                                    <div class="col-12">
                                        <x-input type="text" class="text-right" name="vat_amount"
                                            value="{{ number_format($vat_amount, 2) }}" readonly="true"
                                            label="VAT Amount" />
                                    </div>

                                    <div class="col-6">
                                        <x-input type="text" class="text-right" name="zero_rated_fees"
                                            value="{{ number_format($zero_rated_fee, 2) }}" readonly="true"
                                            label="Zero Rated Fees" />
                                    </div>

                                    <div class="col-6">
                                        <x-input type="text" class="text-right"
                                            class="text-right cost-computation" name="adjustment"
                                            value="{{ number_format($adjustment, 2) }}" label="Adjustment" />
                                    </div>

                                    <div class="col-12">
                                        <x-input type="text" class="text-right" name="gross_amount"
                                            value="{{ number_format($gross_amount, 2) }}" readonly="true"
                                            label="Gross Amount" />
                                    </div>

                                    <div class="col-6">
                                        <x-select class="z-index show-tick" name="vat_print" label="Print">
                                            <option value="0">- No -</option>
                                            <option value="1">- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-6">
                                        <x-select class="z-index show-tick" name="trade_print" label="Trade">
                                            <option value="0">- No -</option>
                                            <option value="1">- Yes -</option>
                                        </x-select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                &nbsp;
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @isset($quote)
                <div class="row clearfix row-deck">
                    <div class="col-12">
                        <div class="card top_widget">
                            <div class="header">
                                <h2>Notes</h2>
                            </div>
                            <div class="body row">
                                <div class="col-6 row px-5">
                                    <div class="col-12 row">
                                        <div class="col-6">
                                            <x-input type="text" name="free_letters"
                                                value="{{ $order_note?->free_letters ?? '' }}" label="Free Letters" />
                                        </div>

                                        <div class="col-6">
                                            @php
                                                $oldBurialSociety = $order_note?->is_burial_society_fees_included;
                                            @endphp
                                            <x-select class="z-index show-tick" name="is_burial_society_fees_included" labelstyle="font-size:95% !important"
                                                label="Burial society fees included">
                                                @if ($oldBurialSociety == "0")
                                                    <option value="0" selected>
                                                        - No -</option>
                                                @else
                                                    <option value="1" selected
                                                        >- Yes -</option>                                                    
                                                @endif

                                                
                                            </x-select>
                                        </div>

                                        <div class="col-6">
                                            <x-select class="z-index show-tick" name="is_inscription_completed"
                                                label="Inscription Complete">
                                                <option value="0"
                                                    {{ $order_note?->is_inscription_complete == 0 ? 'selected' : '' }}>-
                                                    No -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_inscription_complete == 1 ? 'selected' : '' }}>-
                                                    Yes -</option>
                                            </x-select>
                                        </div>

                                        <div class="col-6">
                                            <x-select class="z-index show-tick size" name="is_sent_to_bs_with_cheque" labelstyle="font-size:95% !important"
                                                has_timestamp="true"
                                                timestamp="{{ $order_note?->is_application_form_sent_to_bs_with_cheque_timestamp ?? false }}"
                                                label="Application Form Sent to B/S with Cheque ">
                                                <option value="0"
                                                    {{ $order_note?->is_application_form_sent_to_bs_with_cheque == 0 ? 'selected' : '' }}>
                                                    - No -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_application_form_sent_to_bs_with_cheque == 1 ? 'selected' : '' }}>
                                                    - Yes -</option>
                                            </x-select>
                                        </div>

                                        <div class="col-6">
                                            <x-select class="z-index show-tick" name="is_sent_to_bs_without_cheque" labelstyle="font-size:88% !important"
                                                has_timestamp="true"
                                                timestamp="{{ $order_note?->is_application_form_sent_to_bs_without_cheque_timestamp ?? false }}"
                                                label="Application Form Sent to B/S without Cheque">
                                                <option value="0"
                                                    {{ $order_note?->is_application_form_sent_to_bs_without_cheque == 0 ? 'selected' : '' }}>
                                                    - No -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_application_form_sent_to_bs_without_cheque == 1 ? 'selected' : '' }}>
                                                    - Yes -</option>
                                            </x-select>
                                        </div>

                                        <div class="col-6">
                                            <x-select class="z-index show-tick" name="is_permit_not_required"
                                                label="Permit Not Required">
                                                <option value="0"
                                                    {{ $order_note?->is_permit_not_required == 0 ? 'selected' : '' }}>- No
                                                    -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_permit_not_required == 1 ? 'selected' : '' }}>-
                                                    Yes -</option>
                                            </x-select>
                                        </div>

                                        <div class="col-6">
                                            <x-select class="z-index show-tick" name="is_insurance" label="Stoneguard">
                                                <option value="0"
                                                    {{ $order_note?->is_insurance == 0 ? 'selected' : '' }}>- Not
                                                    Applicable -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_insurance == 1 ? 'selected' : '' }}>- To Print -
                                                </option>
                                                <option value="2"
                                                    {{ $order_note?->is_insurance == 2 ? 'selected' : '' }}>- Print -
                                                </option>
                                            </x-select>
                                        </div>

                                        <div class="col-6">
                                            <x-select class="z-index show-tick" name="is_insurance_services"
                                                label="Stoneguard Services">
                                                <option value="0"
                                                    {{ $order_note?->is_insurance_services == 0 ? 'selected' : '' }}>- No
                                                    -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_insurance_services == 1 ? 'selected' : '' }}>- Yes
                                                    -</option>
                                            </x-select>
                                        </div>

                                        <div class="col-12">
                                            <x-select class="z-index show-tick" name="is_washdown_discussed"
                                                label="Washdown Discussed">
                                                <option value="0"
                                                    {{ $order_note?->is_washdown_discussed == 0 ? 'selected' : '' }}>- No
                                                    -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_washdown_discussed == 1 ? 'selected' : '' }}>- Yes
                                                    -</option>
                                            </x-select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="col-12 row">

                                        <div class="col-4">
                                            <x-select class="z-index show-tick" name="is_paid_by_bacs"
                                                has_timestamp="true"
                                                timestamp="{{ $order_note?->is_paid_by_bacs_timestamp ?? false }}"
                                                label="Paid by Bacs">
                                                <option value="0"
                                                    {{ $order_note?->is_paid_by_bacs == 0 ? 'selected' : '' }}>- No -
                                                </option>
                                                <option value="1"
                                                    {{ $order_note?->is_paid_by_bacs == 1 ? 'selected' : '' }}>- Yes -
                                                </option>
                                            </x-select>
                                        </div>

                                        <div class="col-4">
                                            <x-select class="z-index show-tick" name="is_full_inscription_received"
                                                label="Full Inscriptions received">
                                                <option value="0"
                                                    {{ $order_note?->is_full_inscription_received == 0 ? 'selected' : '' }}>
                                                    - No -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_full_inscription_received == 1 ? 'selected' : '' }}>
                                                    - Yes -</option>
                                            </x-select>
                                        </div>

                                        <div class="col-4">
                                            <x-select class="z-index show-tick" name="is_sent_to_burial_society"
                                                label="Sent to burial society">
                                                <option value="0"
                                                    {{ $order_note?->is_sent_to_burial_society == 0 ? 'selected' : '' }}>-
                                                    No -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_sent_to_burial_society == 1 ? 'selected' : '' }}>-
                                                    Yes -</option>
                                            </x-select>
                                        </div>

                                        <div class="col-6">
                                            <x-select class="z-index show-tick" name="is_received_from_burial_society"
                                                label="Received from burial society">
                                                <option value="0"
                                                    {{ $order_note?->is_received_from_burial_society == 0 ? 'selected' : '' }}>
                                                    - No -</option>
                                                <option value="1"
                                                    {{ $order_note?->is_received_from_burial_society == 1 ? 'selected' : '' }}>
                                                    - Yes -</option>
                                            </x-select>
                                        </div>

                                        <div class="col-6">
                                            <x-select class="z-index show-tick" name="is_order_complete"
                                                label="Order complete">
                                                <option value="0"
                                                    {{ $order_note?->is_order_complete == 0 ? 'selected' : '' }}>- No -
                                                </option>
                                                <option value="1"
                                                    {{ $order_note?->is_order_complete == 1 ? 'selected' : '' }}>- Yes -
                                                </option>
                                            </x-select>
                                        </div>

                                        @php
                                            $inscription_sent_to_design_team_for_printout = isset(
                                                $order_note->inscription_sent_to_design_team_for_printout,
                                            )
                                                ? \Carbon\Carbon::parse(
                                                    $order_note->inscription_sent_to_design_team_for_printout,
                                                )->format('F d, Y')
                                                : '';
                                            $inscription_sent_to_gary_for_printout = isset(
                                                $order_note->inscription_sent_to_gary_for_printout,
                                            )
                                                ? \Carbon\Carbon::parse(
                                                    $order_note->inscription_sent_to_gary_for_printout,
                                                )->format('F d, Y')
                                                : '';
                                            $received_back_from_design_team = isset(
                                                $order_note->received_back_from_design_team,
                                            )
                                                ? \Carbon\Carbon::parse(
                                                    $order_note->received_back_from_design_team,
                                                )->format('F d, Y')
                                                : '';
                                            $sent_to_customer = isset($order_note->sent_to_customer)
                                                ? \Carbon\Carbon::parse($order_note->sent_to_customer)->format(
                                                    'F d, Y',
                                                )
                                                : '';
                                            $back_to_design_team_for_further_alterations = isset(
                                                $order_note->back_to_design_team_for_further_alterations,
                                            )
                                                ? \Carbon\Carbon::parse(
                                                    $order_note->back_to_design_team_for_further_alterations,
                                                )->format('F d, Y h:i A')
                                                : '';
                                            $masonart_printout_approved = isset($order_note->masonart_printout_approved)
                                                ? \Carbon\Carbon::parse($order_note->masonart_printout_approved)->format(
                                                    'F d, Y h:i A',
                                                )
                                                : '';
                                        @endphp

                                        <div class="col-6">
                                            <x-input type="text" name="inscription_sent_to_design_team_for_printout"
                                                value="{{ $inscription_sent_to_design_team_for_printout }}"
                                                class="daterange clear-daterange"
                                                label="Inscription sent to Design Team for Printout" />
                                        </div>
                                        <div class="col-6">
                                            <x-input type="text" name="inscription_sent_to_gary_for_printout"
                                                value="{{ $inscription_sent_to_gary_for_printout }}"
                                                class="daterange clear-daterange"
                                                label="Inscription sent to Gary for Printout" />
                                        </div>
                                        <div class="col-6">
                                            <x-input type="text" name="received_back_from_design_team"
                                                value="{{ $received_back_from_design_team }}"
                                                class="daterange clear-daterange" label="Received back from Design Team" />
                                        </div>
                                        <div class="col-6">
                                            <x-input type="text" name="sent_to_customer" value="{{ $sent_to_customer }}"
                                                class="daterange clear-daterange" label="Sent to Customer" />
                                        </div>
                                        <div class="col-6">
                                            <x-input type="text" name="back_to_design_team_for_further_alterations"
                                                value="{{ $back_to_design_team_for_further_alterations }}"
                                                class="daterange clear-daterange"
                                                label="Back to Design Team for further alterations" />
                                        </div>
                                        <div class="col-6">
                                            <x-input type="text" name="masonart_printout_approved"
                                                value="{{ $masonart_printout_approved }}" class="daterange clear-daterange"
                                                label=" MasonArt Printout Approved" />
                                        </div>
                                        <div class="col-12">
                                            <x-select class="z-index show-tick" name="approved_by_burial_society"
                                                label="Approved by Burial Society">
                                                <option value="0"
                                                    {{ $order_note?->approved_by_burial_society == 0 ? 'selected' : '' }}>- No
                                                    -</option>
                                                <option value="1"
                                                    {{ $order_note?->approved_by_burial_society == 1 ? 'selected' : '' }}>-
                                                    Yes -</option>
                                            </x-select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12">
                                        
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endisset

            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="body row">
                            <div class="col-12">
                                <x-input type="textarea" name="additional_note"
                                    value="{{ isset($quote) ? $quote->additional_notes : '' }}"
                                    label="Additional Notes" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @isset($quote)
                <div class="row clearfix row-deck">
                    <div class="col-12">
                        <div class="card top_widget">
                            <div class="header">
                                <h2>Order Actions</h2>
                            </div>
                            <div class="body row d-flex justify-content-center align-items-center flex-wrap mt-3">
                                <button type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="inscription_btn" order_id="{{ $quote?->id ?? '' }}">Inscription</button>
                                <a type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                     href="{{ url('schedule/create/'.$old_order_type.'/'. $quote?->id) }}" 
                                     target="_blank" order_id="{{ $quote?->id ?? '' }}"
                                    >Schedule</a>
                                <button type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="receipts_btn" order_id="{{ $quote?->id ?? '' }}">Receipts</button>
                                <button type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="email_btn" order_id="{{ $quote?->id ?? '' }}">Email</button>
                                <button type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="history_btn" order_id="{{ $quote?->id ?? '' }}">History</button>
                                <button type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="photos_btn" order_id="{{ $quote?->id ?? '' }}">Photos</button>
                                <button type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="documents_btn" order_id="{{ $quote?->id ?? '' }}">Documents</button>
                                <button type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="working_files_btn" order_id="{{ $quote?->id ?? '' }}">Working Files</button>
                                <a type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="print_quotation_btn" href="{{ url('pdf/quotation/' . $quote?->id) }}"
                                    target="_blank" order_id="{{ $quote?->id ?? '' }}">Print Quotation</a>
                                @if ($deposit_description && $deposit_amount)
                                    <a type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="print_order_btn" href="{{ url('pdf/order/' . $quote?->id) }}" target="_blank"
                                    order_id="{{ $quote?->id ?? '' }}">Print Order</a>
                                @endif
                                <a type="button" class="btn btn-danger btn-simple waves-effect m-2 w-25"
                                    id="print_no_prices_btn" href="{{ url('pdf/order_no_price/' . $quote?->id) }}"
                                    target="_blank" order_id="{{ $quote?->id ?? '' }}">Print - No Prices</a>
                            </div>
                        </div>

                    </div>
                </div>
            @endisset

            <div class="mb-3 d-flex justify-content-center align-items-center">
                <x-buttons class="btn-secondary" type="button" label="Back" />

                @isset($quote)
                    <button class="btn btn-danger hidden-xs w-25 ml-2" id="soft-delete" type="button"
                        label="{{ $quote->name }}" route="{{ route('quote.destroy', $quote->id) }}"
                        landing_page="{{ route('quote.index') }}">Delete</button>
                @endisset
                <x-buttons id="submit_form" class="btn-primary" type="button"
                    label="{{ !isset($quote) ? 'Create' : 'Update' }}" />
            </div>


        </form>
    </div>

    <x-slot name="modal">
        <!-- For Customer Modal -->
        <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="title" id="customerModalLabel">List of Customers</h4>
                    </div>
                    <div class="modal-body" id="customerModalBody">
                        <div class="w-100">
                            <table class="table table-bordered table-striped table-hover dataTable" id="customerTable"
                                style="font-size:90%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Post Code</th>
                                        <th>Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="customerTableBody">
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $customer->firstname }} {{ $customer->lastname }}</td>
                                            <td>{{ $customer->address_one }} {{ $customer->address_two }}
                                                {{ $customer->firstname }} {{ $customer->city_county }}</td>
                                            <td>{{ $customer->customer_contacts->first()?->contact_value ?? '' }}</td>
                                            <td>
                                                <button
                                                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center existing-customer-btn"
                                                    customer-data="{{ $customer }}">
                                                    <i class="icon-eye"></i>&nbsp;Add
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-simple waves-effect" id="formModalClose"
                            data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- For Select Others Modal -->
        <div class="modal fade" id="forOthersModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="title" id="forOthersModalLabel">Others</h4>
                    </div>
                    <div class="modal-body" id="forOthersModalBody">
                        <x-input type="text" name="for_others_modal" value="" label="Other" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-simple waves-effect"
                            data-dismiss="modal">CLOSE</button>
                        <button type="button" class="btn btn-primary btn-simple waves-effect" id="otherModalSave"
                            data-dismiss="modal" selectfor="">SAVE</button>
                    </div>
                </div>
            </div>
        </div>

        @isset($quote)

            {{-- 
                For Notes Modal and Factory Notes Modal, we are using the same table to display the notes, just changing the content based on the type of note (1 for order instruction notes and 2 for factory notes). We also have a hidden input to store the id of the note being edited, if any. When the save button is clicked, we check if there is an id in the hidden input, if yes then we update the existing note, if not then we create a new note. After saving, we refresh the notes table to reflect the changes.
            --}}
            @php
                $order_instruction_notes = $quote->order_instruction_notes ?? [];
                $order_payments = $order_payments ?? [];
            @endphp
            <!-- For Notes Modal -->
            <div class="modal fade" id="notesModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="notesModalLabel">Add Notes</h4>
                        </div>
                        <div class="modal-body row" id="notesModalBody">
                            <div class="col-12 py-1">
                                <input type="hidden" name="order_instruction_note_id" value="">
                                <x-input type="textarea" class="text-right" name="order_notes" value=""
                                    label="Order Notes" />
                            </div>
                            <div class="col-12 py-3 text-center">
                                <button type="button" class="btn btn-danger btn-simple waves-effect w-25 d-none"
                                    id="cancel_order_note_btn" order_id="{{ $quote?->id ?? '' }}">Cancel</button>
                                <button type="button" class="btn btn-primary btn-simple waves-effect w-25"
                                    id="save_order_note_btn" order_id="{{ $quote?->id ?? '' }}">Save</button>
                            </div>
                            <div class="col-12">
                                <table class="table table-bordered table-striped table-hover dataTable" id="notesTable"
                                    style="font-size:90%">
                                    <thead>
                                        <tr>
                                            <th>Notes</th>
                                            <th style="width:10%;">User</th>
                                            <th style="width:10%;">Timestamp</th>
                                            <th style="width:10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="notesTableBody">
                                        @foreach ($order_instruction_notes as $instruction_note)
                                            @if ($instruction_note->type_of_note == '1')
                                                <tr class="order_instruction">
                                                    <td class="order_instruction_note">{{ $instruction_note->notes }}
                                                    </td>
                                                    <td>
                                                        <small>Created By:
                                                            {{ $instruction_note->created_user?->firstname ?? '' }}
                                                            {{ $instruction_note->created_user?->lastname ?? '' }}
                                                        </small>
                                                        <br>
                                                        <small>Updated By:
                                                            {{ $instruction_note->updated_user?->firstname ?? '' }}
                                                            {{ $instruction_note->updated_user?->lastname ?? '' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>Created At:
                                                            {{ date('F d, Y', strtotime($instruction_note->created_at)) ?? '' }}
                                                        </small>
                                                        <br>
                                                        <small>Updated At:
                                                            {{ date('F d, Y', strtotime($instruction_note->updated_at)) ?? '' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <button
                                                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center edit-order-instruction-note"
                                                            type_of_note="note"
                                                            order_instruction_note_id="{{ $instruction_note->id }}">
                                                            <i class="zmdi zmdi-border-color"></i>&nbsp;Edit
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-simple waves-effect px-5"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- For Factory Notes Modal -->
            <div class="modal fade" id="factoryNotesModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="factoryNotesModalLabel">List of Factory Notes</h4>
                        </div>
                        <div class="modal-body row" id="factoryNotesModalBody">
                            <div class="col-12 py-1">
                                <input type="hidden" name="order_instruction_factory_note_id" value="">
                                <x-input type="textarea" class="text-right" name="order_factory_notes" value=""
                                    label="Factory Notes" />
                            </div>
                            <div class="col-12 py-3 text-center">
                                <button type="button" class="btn btn-danger btn-simple waves-effect w-25 d-none"
                                    id="cancel_factory_note_btn" order_id="{{ $quote?->id ?? '' }}">Cancel</button>
                                <button type="button" class="btn btn-primary btn-simple waves-effect w-25"
                                    id="save_factory_note_btn" order_id="{{ $quote?->id ?? '' }}">Save</button>
                            </div>
                            <div class="col-12">


                                <table class="table table-bordered table-striped table-hover dataTable"
                                    id="factoryNotesTable" style="font-size:90%">
                                    <thead>
                                        <tr>
                                            <th>Notes</th>
                                            <th style="width:10%;">User</th>
                                            <th style="width:10%;">Timestamp</th>
                                            <th style="width:10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="factoryNotesTableBody">
                                        @foreach ($order_instruction_notes as $instruction_note)
                                            @if ($instruction_note->type_of_note == '2')
                                                <tr class="order_instruction">
                                                    <td class="order_instruction_note">{!! $instruction_note->notes !!}</td>
                                                    <td>
                                                        <small>Created By:
                                                            {{ $instruction_note->created_user?->firstname ?? '' }}
                                                            {{ $instruction_note->created_user?->lastname ?? '' }}
                                                        </small>
                                                        <br>
                                                        <small>Updated By:
                                                            {{ $instruction_note->updated_user?->firstname ?? '' }}
                                                            {{ $instruction_note->updated_user?->lastname ?? '' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>Created At:
                                                            {{ date('F d, Y', strtotime($instruction_note->created_at)) ?? '' }}
                                                        </small>
                                                        <br>
                                                        <small>Updated At:
                                                            {{ date('F d, Y', strtotime($instruction_note->updated_at)) ?? '' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <button
                                                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center edit-order-instruction-note"
                                                            type_of_note="factory_note"
                                                            order_instruction_note_id="{{ $instruction_note->id }}">
                                                            <i class="zmdi zmdi-border-color"></i>&nbsp;Edit
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-simple waves-effect px-5"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            @include('pages.quote.modals.payments');

            <!-- For Inscription Modal -->
            <div class="modal fade" id="inscriptionModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="inscriptionModalLabel">Order Inscription</h4>
                        </div>
                        <div class="modal-body" id="inscriptionModalBody">
                            <div class="inscription-form row">
                                <div class="col-12 py-1">
                                    <input type="hidden" name="order_inscription_id"
                                        value="{{ isset($order_inscription) ? $order_inscription->id : '' }}">
                                    <x-input type="textarea" class="text-right" name="order_inscription" value=""
                                        label="Inscription"
                                        value="{{ isset($order_inscription) ? $order_inscription->inscription : '' }}" />
                                </div>
                                <div class="col-12 py-3 text-center">
                                    <a type="button" class="btn btn-danger btn-simple waves-effect w-25"
                                        id="print_inscription_btn" target="_blank"
                                        href="{{ url('pdf/inscription/' . $quote?->id) }}"
                                        order_id="{{ $quote?->id ?? '' }}">Print Inscription</a>
                                    <button type="button" class="btn btn-danger btn-simple waves-effect w-25"
                                        id="save_inscription_btn" order_id="{{ $quote?->id ?? '' }}">Save</button>
                                </div>
                                <div class="col-12 text-center" id="inscription_message"></div>

                            </div>

                            @isset($order_inscription)
                                <div class="approval-form row mt-4 border-top pt-2">
                                    <div class="col-12">
                                        @php
                                            $old_reviewed_by = isset($order_inscription->reviewed_by)
                                                ? $order_inscription->reviewed_user->firstname .
                                                    ' ' .
                                                    $order_inscription->reviewed_user->lastname
                                                : '-';
                                            $old_reviewed_timestamp = isset($order_inscription->reviewed_by)
                                                ? date('F d, Y h:i A', strtotime($order_inscription->updated_at))
                                                : '-';
                                        @endphp
                                        <strong>Approved / Rejected By: </strong> <span
                                            id="reviewed_by">{{ $old_reviewed_by }}</span> <br>
                                        <strong>Date of Approval: </strong> <span
                                            id="reviewed_timestamp">{{ $old_reviewed_timestamp }}</span>
                                    </div>
                                    <div class="col-12 pt-2 mt-2">
                                        @php
                                            $old_order_inscription_status = isset($order_inscription)
                                                ? $order_inscription->status
                                                : '';
                                        @endphp
                                        <x-select class="z-index show-tick" name="order_inscription_status"
                                            label="Order Inscription Status" :required="true">
                                            <option value="" disabled selected>-Select Status-</option>
                                            <option value="0"
                                                {{ $old_order_inscription_status == '0' ? 'selected' : '' }}>-Reject-</option>
                                            <option value="1"
                                                {{ $old_order_inscription_status == '1' ? 'selected' : '' }}>-Approved-
                                            </option>
                                        </x-select>
                                    </div>
                                    <div class="col-12">
                                        <x-input type="textarea" name="inscription_remarks"
                                            value="{{ isset($order_inscription) ? $order_inscription->remarks : '' }}"
                                            label="Remarks" />
                                    </div>
                                    <div class="col-12 py-3 text-center">
                                        <button type="button" class="btn btn-danger btn-simple waves-effect w-25"
                                            id="save_approval_btn" order_id="{{ $quote?->id ?? '' }}">Submit</button>
                                    </div>
                                </div>
                            @endisset
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-simple waves-effect"
                                data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>

            @include('pages.quote.modals.email');

            @include('pages.quote.modals.photos');

            @include('pages.quote.modals.documents');

            @include('pages.quote.modals.working-files')
        @endisset

    </x-slot>


    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/quotes/index.js') }}"></script>
        <script src="{{ asset('assets/custom/js/quotes/inscription.js') }}"></script>
        <script src="{{ asset('assets/custom/js/quotes/payment.js') }}"></script>
        <script src="{{ asset('assets/custom/js/quotes/document.js') }}"></script>
        <script src="{{ asset('assets/custom/js/quotes/photos.js') }}"></script>
        <script src="{{ asset('assets/custom/js/quotes/working_files.js') }}"></script>
        <script src="{{ asset('assets/custom/js/quotes/email.js') }}"></script>
    </x-slot>

</x-app-layout>
