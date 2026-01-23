<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer Details</a></li>
                            <li class="breadcrumb-item active">{{ !isset($customer) ? 'Creating' : 'Updating' }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1"> {{ !isset($customer) ? 'Create' : 'Edit ' . $customer->name }} Customer
                        </h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Customer</button> --}}
                        <a href="{{ route('customer.index') }}" class="btn btn-secondary hidden-xs ml-2 px-5">Back</a>
                    </div>
                </div>
                <div class="bh_divider"></div>
            </div>
        </div>
    </x-slot>

    <div class="container">
        <form id="form_validation" method="POST"
            action="{{ !isset($customer) ? route('customer.store') : route('customer.update', $customer->id) }}">
            @csrf
            @if (isset($customer))
                @method('PUT')
            @endif

            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
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

            <div class="mb-3 d-flex justify-content-center align-items-center">
                    <x-buttons class="btn-secondary" type="button" label="Back"/>

                @isset($customer)
                    <button class="btn btn-danger hidden-xs w-25 ml-2" id="soft-delete" type="button"
                        label="{{ $customer->name }}" route="{{ route('customer.destroy', $customer->id) }}"
                        landing_page="{{ route('customer.index') }}">Delete</button>
                @endisset
                    <x-buttons class="btn-primary" type="submit" label="{{ !isset($customer) ? 'Create' : 'Update' }}"/>
            </div>
        </form>
    </div>


    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/customer.js') }}"></script>
    </x-slot>

</x-app-layout>
