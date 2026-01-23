<x-app-layout>
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
                        <h1 class="mb-1 mt-1">{{ !isset($quote) ? 'Create New Quotation' : 'Update ' . $quote->name }}
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
                                        $old_order_type = $quote?->order_type_id ?? old('order_type');
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
                                <x-input type="text" name="order_date" value="" class="daterange"
                                    label="Order Date" :required="true" />
                            </div>
                            @if (isset($quote))
                                :
                                <div class="col-6">
                                    <x-input type="text" name="invoice_no" value="" inputformat="alphanumeric"
                                        label="Invoice No." />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="invoice_date" value="" class="invoice-date"
                                        label="Invoice Date" />
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
                            <h2>Order Details</h2>
                            <button type="button" class="btn btn-default btn-simple waves-effect" id="searchCustomer" data-dismiss="modal">Search Exisiting Customer</button>
                        </div>
                        <div class="body row">
                            <div class="col-2">
                                <x-select class="z-index show-tick" name="title" label="Title">
                                    <option value="" disabled selected>-Select Title-</option>
                                    @php
                                        $old_title =  $customer?->title ?? old("title");
                                    @endphp
                                    @foreach ($titles as $title)
                                        <option value="{{ $title }}" {{$old_title == $title ? 'selected' : ''}}>{{ $title }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-5">
                                <x-input type="text" name="firstname" value="{{ $customer?->firstname ?? old('firstname') }}" label="Firstname" />
                            </div>
                            <div class="col-5">
                                <x-input type="text" name="lastname" label="Lastname" value="{{ $customer?->lastname ?? old('lastname') }}" required="true" :error="$errors->first('lastname')"/>
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="salutation" value="{{ $customer?->salutation ?? old('salutation') }}" label="Salutation" />
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="email" inputformat="specialcharacter" value="{{ $customer_emails ?? old('email') }}" label="Email Address" readonly="true" :error="$errors->first('email')"/>
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="address_1" value="" label="Address Line 1" value="{{ $customer?->address_one ?? old('address_1') }}"/>
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="address_2" value="" label="Address Line 2" value="{{ $customer?->address_two ?? old('address_2') }}"/>
                            </div>

                            <div class="col-3">
                                <x-input type="text" name="city_county" label="City / County" value="{{ $customer?->city_county ?? old('city_county') }}" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="post_code" value="{{ $customer?->postcode ?? old('post_code') }}" label="Post Code" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="tel_no" value="{{ $customer_tel_nos ?? old('tel_no') }}" label="Tel. No." readonly="true" :error="$errors->first('tel_no')" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="mobile_no" value="{{ $customer_mobile_nos ?? old('mobile_no') }}" label="Mobile No." readonly="true" :error="$errors->first('mobile_no')" />
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
                                <x-input type="text" name="deceased_name" value="" label="Deceased" />
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="date_of_death" value="" class="daterange-am-pm"
                                    label="Date of Death" />
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="consecration_date" value="" class="daterange"
                                    label="Consecration / Required By" />
                            </div>

                            <div class="col-6">
                                <x-select class="z-index show-tick" name="cemetery_id" label="Cemetery"
                                    search="true">
                                    <option value="" disabled selected>-Select Cemeteries-</option>
                                    @foreach ($cemeteries as $cemetery)
                                        <option value="{{ $cemetery->id }}">
                                            {{ $cemetery->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="col-6 row">
                                <div class="col-6">
                                    <div class="col-12">
                                        <x-input  type="radio" name="fixed_date" id="is_tba" value="is_tba"
                                            class="with-gap mr-2" label="To be Advised" />
                                    </div>
                                    <div class="col-12">
                                        <x-input  type="radio" name="fixed_date" id="is_approx" value="is_approx"
                                            class="with-gap mr-2" label="Approximate" />
                                    </div>
                                    <div class="col-12">
                                        <x-input  type="radio" name="fixed_date" id="is_asap" value="is_asap"
                                            class="with-gap mr-2" label="ASAP" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12">
                                        <x-input type="text" name="consecration_date" value="" class="month-year" label="Required By"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <x-select class="z-index show-tick" name="burial_society_organization_id"
                                    label="Burial Society Organization">
                                    <option value="" disabled selected>-Select Burial Society Organization-
                                    </option>
                                    @foreach ($burial_society_organizations as $burial_society_organization)
                                        <option
                                            class="cemetery_{{ $burial_society_organization->cemetery_id }} d-none"
                                            value="{{ $burial_society_organization->id }}">
                                            {{ $burial_society_organization->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="col-4">
                                <x-input type="text" name="grave_no" value="" label="Grave Number" />
                            </div>
                            <div class="col-4">
                                <x-input type="text" name="grave_no_checked" value="" class="daterange"
                                    label="Grave Number Checked" />
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="grave_space_id" label="Grave Space">
                                    <option value="" disabled selected>-Select Burial Grave Space-</option>
                                    @foreach ($grave_spaces as $grave_space)
                                        <option value="{{ $grave_space->id }}">
                                            {{ $grave_space->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>


                            <div class="col-4">
                                <x-input type="text" name="design_headstone" value=""
                                    label="Design / Headstone" />
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="material_id" label="Material"
                                    search="true">
                                    <option value="" disabled selected>-Select Material-</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="material_colour" label="Material - Colour"
                                    search="true">
                                    <option value="" disabled selected>-Select Colour-</option>
                                    @foreach ($colours as $colour)
                                        <option value="{{ $colour->name }}">
                                            {{ $colour->name }}
                                        </option>
                                    @endforeach
                                    <option value="0">Others</option>
                                </x-select>
                            </div>
                             <div class="col-4">
                                <x-input type="text" name="size" value="" label="Size" />
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="base_ledger_id" label="Base Ledger"
                                    search="true">
                                    <option value="" disabled selected>-Select Base Ledger-</option>
                                    @foreach ($base_ledgers as $base_ledger)
                                        <option value="{{ $base_ledger->id }}">
                                            {{ $base_ledger->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="letter_type_id" label="Letter Type"
                                    search="true">
                                    <option value="" disabled selected>-Select Letter Type-</option>
                                    @foreach ($letter_types as $letter_type)
                                        <option value="{{ $letter_type->id }}">
                                            {{ $letter_type->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="accessory_id" label="Accessories"
                                    search="true">
                                    <option value="" disabled selected>-Select Accessories Type-</option>
                                    @foreach ($accessories as $accessory)
                                        <option value="{{ $accessory->id }}">
                                            {{ $accessory->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="accessory_colour" label="Accessories - Colour"
                                    search="true">
                                    <option value="" disabled selected>-Select Colour-</option>
                                    @foreach ($colours as $colour)
                                        <option value="{{ $colour->name }}">
                                            {{ $colour->name }}
                                        </option>
                                    @endforeach
                                        <option value="0">Others</option>
                                </x-select>
                            </div>
                            
                            <div class="col-4">
                                <x-input type="text" name="size" value="" label="Kerbs / Risers" />
                            </div>

                            

                            <div class="col-12">
                                <x-input type="textarea" name="issues" value="" label="Issues" />
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
                                        <x-input type="text" name="price_description" value="" label="Price Description" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="price_description_amount" value="0.00" label="Price Amount" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-2">
                                        <x-input type="text" name="letters_no" value="0" :label="false"/>
                                    </div>
                                    <div class="col-2">
                                        <span class="fw-bold">Letters @</span>
                                    </div>
                                    <div class="col-3">
                                        <x-input type="text" class="text-right" name="letters_amount" value="0.00" :label="false"/>
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="letters_total_amount" value="0.00" :label="false" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="price_description_1" value="" :label="false"/>
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="price_amount_1" value="0.00" :label="false" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="price_description_2" value="" :label="false"/>
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="price_amount_2" value="0.00" :label="false"/>
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="price_description_3" value="" :label="false"/>
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="price_amount_3" value="0.00" :label="false"/>
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="discount_description" value="" label="Discount" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="discount_amount" value="0.00" label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="total" value="0.00" label="Total" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="cemetery_fees_description" value="" label="Cemetery Fees" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="cemetery_fees_amount" value="0.00" label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="grand_total_amount" value="0.00" label="Grand Total" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="cemetery_fees_description" value="" label="Deposit" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right" name="cemetery_fees_amount" value="0.00" label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="amount_received" value="0.00" label="Amount Received" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="balance_amount" value="0.00" label="Balance" />
                                    </div>
                                </div>

                            </div>

                            <div class="col-6">
                                <div class="col-12">
                                     <x-input type="text" class="text-right" name="special_instructions" value="" label="Special Instruction (for Admin)" />
                                </div>

                                <div class="col-12">
                                     <x-input type="text" class="text-right" name="customer_notes" value="" label="Customer Notes" />
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
                                <div class="col-12 row">
                                    <div class="col-12">
                                        <x-input type="text" name="net_amount" value="" label="Net Amount" />
                                    </div>
                                    <div class="col-12">
                                        <x-input type="text" name="vat_rate" value="" label="VAT Rate (%)" />
                                    </div>
                                    
                                    <div class="col-12">
                                        <x-input type="text" name="vat_amount" value="" label="VAT Amount" />
                                    </div>
                                    
                                    <div class="col-6">
                                        <x-input type="text" name="zero_rated_fees" value="" label="Zero Rated Fees" />
                                    </div>

                                    <div class="col-6">
                                        <x-input type="text" name="adjustment" value="" label="Adjustment" />
                                    </div>

                                    <div class="col-12">
                                        <x-input type="text" name="gross_amount" value="" label="Gross Amount" />
                                    </div>

                                    <div class="col-6">
                                        <x-select class="z-index show-tick" name="vat_print" label="Print">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-6">
                                        <x-select class="z-index show-tick" name="trade_print" label="Trade">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
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
 
            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="header">
                            <h2>Notes</h2>
                        </div>
                        <div class="body row">
                            <div class="col-6 row px-5">
                                <div class="col-12 row">
                                    <div class="col-12">
                                        <x-input type="text" name="free_letters" value="" label="Free Letters" />
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_burial_society_fees_included" label="Burial society fees included">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_inscription_completed" label="Inscription Complete">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_sent_to_bs_with_cheque" label="Application Form Sent to B/S with Cheque ">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_sent_to_bs_without_cheque" label="Application Form Sent to B/S without Cheque">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>



                                </div>                                
                            </div>

                            <div class="col-6">
                               <div class="col-12 row">

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_paid_by_bacs" label="Paid by Bacs">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_full_inscription_received" label="Full Inscriptions received">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_sent_to_burial_society" label="Sent to burial society">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_received_from_burial_society" label="Received from burial society">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="is_ordered_complete" label="Order complete">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>
                                    
                                </div>  
                            </div>

                            <div class="col-12">
                                <div class="col-6">
                                    <x-input type="text" name="inscription_sent_to_design_team_for_printout" value="" class="daterange"
                                        label="Inscription sent to Design Team for Printout" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="inscription_sent_to_gary_for_printout" value="" class="daterange"
                                            label="Inscription sent to Gary for Printout" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="inscription_sent_to_design_team_for_printout" value="" class="daterange"
                                        label="Received back from Design Team" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="inscription_sent_to_design_team_for_printout" value="" class="daterange"
                                        label="Sent to Customer" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="inscription_sent_to_design_team_for_printout" value="" class="daterange"
                                        label="Back to Design Team for further alterations" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="inscription_sent_to_design_team_for_printout" value="" class="daterange"
                                        label=" MasonArt Printout Approved" />
                                </div>
                                <div class="col-6">
                                     <x-select class="z-index show-tick" name="is_approved_by_burial_society" label="Approved by Burial Society">
                                        <option value="0" >- No -</option>
                                        <option value="1" >- Yes -</option>
                                    </x-select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="body row">
                            <div class="col-12">
                                <x-input type="textarea" name="additional_notes" value="" label="Additional Notes" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 d-flex justify-content-center align-items-center">
                    <x-buttons class="btn-secondary" type="button" label="Back"/>

                @isset($quote)
                    <button class="btn btn-danger hidden-xs w-25 ml-2" id="soft-delete" type="button"
                        label="{{ $quote->name }}" route="{{ route('quote.destroy', $quote->id) }}"
                        landing_page="{{ route('quote.index') }}">Delete</button>
                @endisset
                    <x-buttons class="btn-primary" type="submit" label={{ !isset($quote) ? 'Create' : 'Update' }}/>
            </div>
        </form>
    </div>

    <x-slot name="modal">
        <!-- Large Size -->
            <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title" id="customerModalLabel">List of Customers</h4>
                        </div>
                        <div class="modal-body" id="customerModalBody"> 
                            <table class="table table-bordered table-striped table-hover dataTable" id="customerTable" style="font-size:90%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Post Code</th>
                                        <th>Telephone No.</th>
                                        <th>Cemetery</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="customerTableBody" >
                                  
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-simple waves-effect" id="formModalClose" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
    </x-slot>

    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/quotes.js') }}"></script>
    </x-slot>

</x-app-layout>
