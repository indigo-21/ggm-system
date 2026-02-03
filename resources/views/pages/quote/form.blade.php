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
                        <h1 class="mb-1 mt-1">{{ !isset($quote) ? 'Create New Quotation' : 'Update Quotation' . $quote->id }}
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
        @if(Session::has('error'))
            <div class="alert alert-danger">
                <strong>Please contact your Administrator!</strong> {{Session::get('error')}}
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
                                @php
                                    $order_date = isset($quote) ? $quote->created_at->format("F d, Y") : old('order_type');
                                @endphp
                                <x-input type="text" name="order_date" value="{{$order_date}}" class="daterange-has-current"
                                    label="Order Date" :required="true" />
                            </div>
                            @if (isset($quote))
                                @php
                                    $invoice_no = $quote->invoice_no? $quote->invoice_no : "";
                                    $invoice_date = $quote->invoice_date? date('F d, Y', strtotime($quote->invoice_date)) : "";
                                @endphp
                                <div class="col-6">
                                    <x-input type="text" name="invoice_no" value="{{ $invoice_no  }}" inputformat="alphanumeric"
                                        label="Invoice No." />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="invoice_date" value="{{ $invoice_date }}" class="daterange"
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
                            <h2>Customer Details</h2>
                            <input type="hidden" name="customer_id" value="{{ isset($quote) ? $quote->customer_id : '' }} ">
                    
                            @if (!isset($quote))
                                <button type="button" class="btn btn-default btn-simple waves-effect" id="searchCustomer" data-dismiss="modal">Search Exisiting Customer</button>
                            @endif
                    
                        
                        </div>
                        <div class="body row">
                            <div class="col-2">
                                <x-select class="z-index show-tick" name="title" label="Title">
                                    <option value="" disabled selected>-Select Title-</option>
                                    @php
                                        $old_title =  isset($quote) ? $quote->customer->title : old("title");
                                    @endphp
                                    @foreach ($titles as $title)
                                        <option value="{{ $title }}" {{$old_title == $title ? 'selected' : ''}}>{{ $title }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-5">
                                <x-input type="text" name="firstname" value="{{ isset($quote) ? $quote->customer->firstname : old('firstname') }}" label="Firstname" />
                            </div>
                            <div class="col-5">
                                <x-input type="text" name="lastname" label="Lastname" value="{{ isset($quote) ? $quote->customer->lastname : old('lastname') }}" required="true" :error="$errors->first('lastname')"/>
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="salutation" value="{{ isset($quote) ? $quote->customer->salutation :  old('salutation') }}" label="Salutation" />
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="email" inputformat="specialcharacter" value="{{ isset($quote) ? $customer_email : old('email') }}" label="Email Address" readonly="true" :error="$errors->first('email')"/>
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="address_1" value="" label="Address Line 1" value="{{ isset($quote) ? $quote->customer?->address_one : old('address_1') }}"/>
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="address_2" value="" label="Address Line 2" value="{{ isset($quote) ? $quote->customer?->address_two : old('address_2') }}"/>
                            </div>

                            <div class="col-3">
                                <x-input type="text" name="city_county" label="City / County" value="{{ isset($quote) ? $quote->customer?->city_county : old('city_county') }}" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="post_code" value="{{ isset($quote) ? $quote->customer?->postcode : old('post_code') }}" label="Post Code" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="tel_no" value="{{ isset($quote) ? $customer_tel_no : old('tel_no') }}" label="Tel. No." readonly="true" :error="$errors->first('tel_no')" />
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="mobile_no" value="{{ isset($quote) ? $customer_mobile_no : old('mobile_no') }}" label="Mobile No." readonly="true" :error="$errors->first('mobile_no')" />
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
                                <x-input type="text" name="deceased_name" value="{{isset($quote) ? $quote->deceased_name : old('deceased_name') }}" label="Deceased" />
                            </div>

                            <div class="col-6">
                                @php
                                    $date_of_death = isset($quote) ? ( $quote->date_of_death ? date("F d, Y A", strtotime($quote->date_of_death)) : "" ) : old('date_of_death');
                                @endphp
                                <x-input type="text" name="date_of_death" value="{{$date_of_death}}" class="daterange-am-pm clear-daterange"
                                    label="Date of Death" />
                            </div>

                            <div class="col-6">
                                @php
                                    $consecration_date = isset($quote) ? ($quote->consecration_date ? date("F d, Y", strtotime($quote->consecration_date)) : "" ) : old("consecration_date");
                                @endphp
                                <x-input type="text" name="consecration_date" value="{{$consecration_date}}" class="daterange clear-daterange"
                                    label="Consecration / Required By" />
                            </div>

                            <div class="col-6">
                                @php
                                    $old_cemetery = isset($quote) ? ($quote?->cemetery_id ?? "") : old('cemetery_id');
                                @endphp
                                <x-select class="z-index show-tick" name="cemetery_id" label="Cemetery"
                                    search="true">
                                    <option value="" disabled {{$old_cemetery == "" ? "selected" : ""}} >-Select Cemeteries-</option>
                                    @foreach ($cemeteries as $cemetery)
                                        <option {{$old_cemetery == $cemetery->id ? "selected" : ""}} value="{{ $cemetery->id }}">
                                            {{ $cemetery->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="col-6 row">
                                <div class="col-6">
                                    @php
                                        $is_tba = isset($quote) ? $quote->is_tba : old('fixed_date') == 1;
                                        $is_approx = isset($quote) ? $quote->is_approx : old('fixed_date') == 2;
                                        $is_asap = isset($quote) ? $quote->is_asap : old('fixed_date') == 3;
                                    @endphp
                                    <div class="col-12">
                                        <x-input  type="radio" name="fixed_date" id="is_tba" checked="{{$is_tba}}" value="is_tba"
                                            class="with-gap mr-2" label="To be Advised" />
                                    </div>
                                    <div class="col-12">
                                        <x-input  type="radio" name="fixed_date" id="is_approx" checked="{{$is_approx}}" value="is_approx"
                                            class="with-gap mr-2" label="Approximate" />
                                    </div>
                                    <div class="col-12">
                                        <x-input  type="radio" name="fixed_date" id="is_asap" checked="{{$is_asap}}" value="is_asap"
                                            class="with-gap mr-2" label="ASAP" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12">
                                        @php
                                            $fixing_date = isset($quote) ? ($quote->fixing_date ? date("F Y", strtotime($quote->fixing_date)) : "" ) : "";
                                        @endphp
                                        <x-input type="text" name="fixed_required_by" value="{{$fixing_date}}" class="month-year" label="Required By"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                @php
                                    $old_burial_society_organization = isset($quote) ? ($quote?->burial_society_organization_id ?? "") : old('burial_society_organization_id');
                                @endphp
                                <x-select class="z-index show-tick" name="burial_society_organization_id"
                                    label="Burial Society Organization">
                                    <option value="" disabled {{$old_burial_society_organization == "" ? "selected" : ""}}>-Select Burial Society Organization-
                                    </option>
                                    @foreach ($burial_society_organizations as $burial_society_organization)
                                        <option
                                            {{$old_burial_society_organization == $burial_society_organization->id  ? "selected" : ""}}
                                            class="cemetery_{{ $burial_society_organization->cemetery_id }} d-none"
                                            value="{{ $burial_society_organization->id }}">
                                            {{ $burial_society_organization->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="col-4">
                                <x-input type="text" name="grave_no" value="{{isset($quote) ? $quote->grave_number : old('grave_no')}}" label="Grave Number" />
                            </div>
                            <div class="col-4">
                                @php
                                    $grave_no_checked = isset($quote) ? date("F d, Y", strtotime($quote->grave_number_checked)) : old('grave_no_checked');
                                @endphp
                                <x-input type="text" name="grave_no_checked" value="{{$grave_no_checked}}" class="daterange clear-daterange"
                                    label="Grave Number Checked" />
                            </div>
                            <div class="col-4">
                                @php
                                    $old_grave_space = isset($quote) ? ($quote?->grave_space_id ?? "") : old('grave_space_id');
                                @endphp
                                <x-select class="z-index show-tick" name="grave_space_id" label="Grave Space">
                                    <option value="" disabled {{$old_grave_space == "" ? "selected" : ""}}>-Select Burial Grave Space-</option>
                                    @foreach ($grave_spaces as $grave_space)
                                        <option 
                                            {{$old_grave_space == $grave_space->id}}
                                            value="{{ $grave_space->id }}">
                                            {{ $grave_space->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>


                            <div class="col-4">
                                <x-input type="text" name="design_headstone" value="{{isset($quote) ? $quote->design_headstone : old('design_headstone')}}"
                                    label="Design / Headstone" />
                            </div>
                            <div class="col-4">
                                @php
                                        $old_material = isset($quote) ? $quote->material : old("material");
                                        $material_data = [];
                                        foreach ($materials as $material) {
                                            $material_data[] = $material->name;
                                        }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="material" label="Material"
                                    search="true">

                                    <option value="" disabled {{$old_material == "" ? "selected" : "" }}>-Select Material-</option>
                                    
                                    @if ($old_material != "" && !in_array($old_material, $material_data))
                                        <option value="{{$old_material}}" selected isother="true">{{$old_material}}</option>
                                    @endif

                                    @foreach ($materials as $material)
                                        <option 
                                            {{$old_material == $material->name ? "selected" : ""}}                                       
                                            value="{{ $material->name }}">
                                            {{ $material->name }}
                                        </option>
                                        @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                            </div>
                            <div class="col-4">
                                 @php
                                        $old_material_colour = isset($quote) ? $quote->material_colour : old("material_colour");
                                        $material_colour_data = [];
                                        foreach ($colours as $colour) {
                                            $material_colour_data[] = $colour->name;
                                        }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="material_colour" label="Material - Colour"
                                    search="true">
                                    <option value="" disabled {{$old_material_colour == "" ? "selected" : "" }}>-Select Colour-</option>
                                    @if ($old_material_colour != "" && !in_array($old_material_colour, $material_colour_data))
                                        <option value="{{$old_material_colour}}" selected isother="true">{{$old_material_colour}}</option>
                                    @endif
                                    @foreach ($colours as $colour)
                                        <option 
                                            {{ $old_material == $colour->name ? "selected" : "" }}
                                            value="{{ $colour->name }}">
                                            {{ $colour->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                            </div>
                             <div class="col-4">
                                @php
                                    $old_size = isset($quote) ? $quote->size : old('size');
                                @endphp
                                <x-input type="text" name="size" value="{!! $old_size !!}" label="Size" />
                            </div>
                            <div class="col-4">
                                @php
                                        $old_base_ledger = isset($quote) ? $quote->base_ledger : old("base_ledger");
                                        $base_ledger_data = [];
                                        foreach ($base_ledgers as $base_ledger) {
                                            $base_ledger_data[] = $base_ledger->name;
                                        }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="base_ledger" label="Base Ledger"
                                    search="true">
                                    <option value="" disabled {{$old_base_ledger == "" ? "selected" : "" }}>-Select Base Ledger-</option>
                                    @if ($old_base_ledger != "" && !in_array($old_base_ledger, $base_ledger_data))
                                        <option value="{{$old_base_ledger}}" selected isother="true">{{$old_base_ledger}}</option>
                                    @endif
                                    @foreach ($base_ledgers as $base_ledger)
                                        <option 
                                            {{$old_base_ledger == $base_ledger->name ? "selected" : ""}}
                                            value="{{ $base_ledger->name }}">
                                            {{ $base_ledger->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                            </div>
                            <div class="col-4">
                                @php
                                        $old_letter_type = isset($quote) ? $quote->letter_type : old("letter_type");
                                        $letter_type_data = [];
                                        foreach ($letter_types as $letter_type) {
                                            $letter_type_data[] = $letter_type->name;
                                        }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="letter_type" label="Letter Type"
                                    search="true">
                                    <option value="" disabled {{$old_letter_type == "" ? "selected" : ""}}>-Select Letter Type-</option>
                                     @if ($old_letter_type != "" && !in_array($old_letter_type, $letter_type_data))
                                        <option value="{{$old_letter_type}}" selected isother="true">{{$old_letter_type}}</option>
                                    @endif
                                    @foreach ($letter_types as $letter_type)
                                        <option 
                                            {{$old_letter_type == $letter_type->name}}
                                            value="{{ $letter_type->name }}">
                                            {{ $letter_type->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                            </div>
                            <div class="col-4">
                                @php
                                        $old_accessory = isset($quote) ? $quote->accessory : old("accessory");
                                        $accessory_data = [];
                                        foreach ($accessories as $accessory) {
                                            $accessory_data[] = $accessory->name;
                                        }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="accessory" label="Accessories"
                                    search="true">
                                    <option value="" disabled {{$old_accessory == "" ? "selected" : ""}}>-Select Accessories Type-</option>
                                    @if ($old_accessory != "" && !in_array($old_accessory, $accessory_data))
                                        <option value="{{$old_accessory}}" selected isother="true">{{$accessory_data}}</option>
                                    @endif
                                    @foreach ($accessories as $accessory)
                                        <option 
                                            {{$old_accessory == $accessory->name ? "selected" : ""}}
                                            value="{{ $accessory->name }}">
                                            {{ $accessory->name }}
                                        </option>
                                    @endforeach
                                    <option value="others">Others</option>
                                </x-select>
                            </div>
                            <div class="col-4">
                                @php
                                        $old_accessory_colour = isset($quote) ? $quote->accessory_colour : old("accessory_colour");
                                        $accessory_colour_data = [];
                                        foreach ($colours as $accessory_colour) {
                                            $accessory_colour_data[] = $accessory_colour->name;
                                        }
                                @endphp
                                <x-select class="z-index show-tick with-others-option" name="accessory_colour" label="Accessories - Colour"
                                    search="true">
                                    <option value="" disabled {{$old_accessory_colour == "" ? "selected" : ""}}>-Select Colour-</option>
                                    @foreach ($colours as $accessory_colour)
                                        <option 
                                            {{$old_accessory_colour == $accessory_colour->name ? "selected" : ""}}
                                            value="{{ $accessory_colour->name }}">
                                            {{ $accessory_colour->name }}
                                        </option>
                                    @endforeach
                                        <option value="others">Others</option>
                                </x-select>
                            </div>
                            
                            <div class="col-4">
                                <x-input type="text" name="kerb_riser" value="{{isset($quote) ? $quote->kerb_riser : old('kerb_riser')}}" label="Kerbs / Risers" />
                            </div>

                            

                            <div class="col-12">
                                <x-input type="textarea" name="issue" value="{{isset($quote) ? $quote->issue : old('issue')}}" label="Issues" />
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
                                            $old_cost_description = isset($order_cost) ? $order_cost?->description ?? '' : '';
                                        @endphp
                                        <x-input type="text" name="cost_description" value="{{ $old_cost_description }}" label="Price Description" />
                                    </div>
                                    <div class="col-5">
                                        @php
                                            $old_cost_amount = isset($order_cost) ? $order_cost?->amount ?? '0.00' : '0.00';
                                        @endphp
                                        <x-input type="text" class="text-right price-amount amount-prices" name="cost_amount" value="{{ $old_cost_amount }}" label="Price Amount" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-2">
                                        @php
                                            $old_letters_no = isset($order_cost) ? $order_cost?->letter_count ?? '0' : '0';
                                        @endphp
                                        <x-input type="text" class="amount-prices" name="letters_no" value="{{$old_letters_no}}" :label="false"/>
                                    </div>
                                    <div class="col-2">
                                        <span class="fw-bold">Letters @</span>
                                    </div>
                                    <div class="col-3">
                                        @php
                                            $old_letters_amount = isset($order_cost) ? $order_cost?->letter_amount ?? '0.00' : '0.00';
                                        @endphp
                                        <x-input type="text" class="text-right amount-prices" name="letters_amount" value="{{$old_letters_amount}}" :label="false"/>
                                    </div>
                                    <div class="col-5">
                                        @php
                                            $old_letters_total_amount = isset($order_cost) ? $order_cost?->old_letters_total_amount ?? '0.00' : '0.00';
                                        @endphp
                                        <x-input type="text" class="text-right price-amount" name="letters_total_amount" readonly="true" value="{{$old_letters_total_amount}}" :label="false" />
                                    </div>
                                </div>

                                <div class="additional-cost-container">
                                    @foreach ( $order_cost->additionals as $index => $additional )
                                        <div class="col-12 row">
                                            <div class="col-2">
                                                <button type="button" class="btn btn-danger btn-simple waves-effect mr-5 remove-additional-cost"> - </button>
                                            </div>
                                            <div class="col-5">
                                                <x-input type="textarea" name="price_description[{{ $index }}]" value="{{ $additional?->description ?? '' }}" :label="false"/>
                                            </div>
                                            <div class="col-5 d-flex align-items-center">
                                                <x-input type="text" class="text-right price-amount amount-prices" name="price_amount[{{ $index }}]" value="{{ $additional?->amount ?? '0.00' }}" :label="false" />
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                

                                @for ($index = 0; $index < 3 ; $index++)
                                    <div class="col-12 row">
                                        <div class="col-1 d-flex align-items-center">
                                            <button type="button" class="btn btn-danger btn-simple waves-effect remove-additional-cost d-flex justify-content-center align-items-center"> <i class="zmdi zmdi-minus-circle"></i> </button>
                                        </div>
                                        <div class="col-6">
                                            <x-input type="textarea" name="price_description[{{ $index }}]" value="" :label="false"/>
                                        </div>
                                        <div class="col-5 d-flex align-items-center">
                                            <x-input type="text" class="text-right price-amount amount-prices" name="price_amount[{{ $index }}]" value="0.00" :label="false" />
                                        </div>
                                    </div>
                                @endfor

                                
                                <div class="col-12 row mt-5">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-danger btn-simple waves-effect w-100 remove-additional-cost"> Add Cost </button>
                                    </div>
                                </div>
                                    
                                <div class="col-12 row mt-5">
                                    <div class="col-7">
                                        <x-input type="text" name="discount_description" value="" label="Discount" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right price-amount amount-prices" name="discount_amount" value="0.00" label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="total_amount" value="0.00" readonly="true" label="Total" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="cemetery_fees_description_1" value="" label="Cemetery Fees 1" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right zero-rated amount-prices" name="cemetery_fees_amount_1" value="0.00" label="Amount" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="cemetery_fees_description_2" value="" label="Cemetery Fees 1" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right zero-rated amount-prices" name="cemetery_fees_amount_2" value="0.00" label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="grand_total_amount" value="0.00" readonly="true" label="Grand Total" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="col-7">
                                        <x-input type="text" name="deposit_description" value="" label="Deposit" />
                                    </div>
                                    <div class="col-5">
                                        <x-input type="text" class="text-right amount-prices" name="deposit_amount" value="0.00" label="Amount" />
                                    </div>
                                </div>

                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="amount_received" value="0.00" readonly="true" label="Amount Received" />
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="offset-7 col-5">
                                        <x-input type="text" class="text-right" name="balance_amount" value="0.00" readonly="true" label="Balance" />
                                    </div>
                                </div>

                            </div>

                            <div class="col-6">
                                <div class="col-12 py-1">
                                     <x-input type="textarea" class="text-right" name="special_instruction" value="{{ isset($quote) ? $quote->special_instructions : '' }}" label="Special Instruction (for Admin)" />
                                </div>

                                @isset($quote)
                                    <div class="col-12 d-flex align-items-center py-5">
                                        <button type="button" class="btn btn-danger btn-simple waves-effect mr-5">Notes</button>
                                        <button type="button" class="btn btn-danger btn-simple waves-effect">Factory Notes</button>
                                    </div>
                                @endisset

                                <div class="col-12 py-3">
                                     <x-input type="textarea" class="text-right" name="customer_note" value="{{ isset($quote) ? $quote->customer_notes : '' }}" label="Customer Notes" />
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
                                        <x-input type="text" class="text-right" name="net_amount" value="0.00" readonly="true" label="Net Amount" />
                                    </div>
                                    <div class="col-12">
                                        <x-input type="text" class="text-right" name="vat_rate" value="20.0" readonly="true" label="VAT Rate (%)" />
                                    </div>
                                    
                                    <div class="col-12">
                                        <x-input type="text" class="text-right" name="vat_amount" value="0.00" readonly="true" label="VAT Amount" />
                                    </div>
                                    
                                    <div class="col-6">
                                        <x-input type="text" class="text-right" name="zero_rated_fees" value="" readonly="true" label="Zero Rated Fees" disabled="true"/>
                                    </div>

                                    <div class="col-6">
                                        <x-input type="text" class="text-right" class="text-right zero-rated amount-prices" name="adjustment" value="0.00" label="Adjustment" />
                                    </div>

                                    <div class="col-12">
                                        <x-input type="text" class="text-right" name="gross_amount" value="0.00" readonly="true" label="Gross Amount" />
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

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="permit_not_required" label="Permit Not Required">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="insurance" label="Stoneguard">
                                            <option value="0" >- Not Applicable -</option>
                                            <option value="1" >- To Print -</option>
                                            <option value="2" >- Print -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="insurance_service" label="Stoneguard Sevices">
                                            <option value="0" >- No -</option>
                                            <option value="1" >- Yes -</option>
                                        </x-select>
                                    </div>

                                    <div class="col-12">
                                        <x-select class="z-index show-tick" name="washdown_discussed" label="Washdown Discussed">
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
                                    <x-input type="text" name="inscription_sent_to_design_team_for_printout" value="" class="daterange clear-daterange"
                                        label="Inscription sent to Design Team for Printout" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="inscription_sent_to_gary_for_printout" value="" class="daterange clear-daterange"
                                            label="Inscription sent to Gary for Printout" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="received_back_from_design_team" value="" class="daterange clear-daterange"
                                        label="Received back from Design Team" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="sent_to_customer" value="" class="daterange clear-daterange"
                                        label="Sent to Customer" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="back_to_design_team_for_further_alterations" value="" class="daterange clear-daterange"
                                        label="Back to Design Team for further alterations" />
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="masonArt_printout_approved" value="" class="daterange clear-daterange"
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
                                <x-input type="textarea" name="additional_note" value="{{ isset($quote) ? $quote->additional_notes : '' }}" label="Additional Notes" />
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
                    <x-buttons id="submit_form" class="btn-primary" type="button" label="{{ !isset($quote) ? 'Create' : 'Update' }}" />
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
                                        <th>Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="customerTableBody" >
                                     @foreach ($customers as $customer )
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{$customer->firstname }} {{$customer->lastname }}</td>
                                        <td>{{$customer->address_one }} {{$customer->address_two }} {{$customer->firstname }} {{$customer->city_county }}</td>
                                        <td>{{$customer->customer_contacts->first()?->contact_value ?? "" }}</td>
                                        <td>
                                             <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center existing-customer-btn" customer-data="{{$customer}}">
                                                    <i class="icon-eye"></i>&nbsp;Add
                                                </button>
                                        </td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-simple waves-effect" id="formModalClose" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Large Size -->
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
                            <button type="button" class="btn btn-danger btn-simple waves-effect" data-dismiss="modal">CLOSE</button>
                            <button type="button" class="btn btn-primary btn-simple waves-effect" id="otherModalSave" data-dismiss="modal" selectfor="">SAVE</button>
                        </div>
                    </div>
                </div>
            </div>
    </x-slot>

    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/quotes.js') }}"></script>
    </x-slot>

</x-app-layout>
