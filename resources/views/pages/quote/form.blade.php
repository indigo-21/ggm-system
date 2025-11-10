<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('quote.index')}}">Quotation</a></li>
                            <li class="breadcrumb-item active">{{ !isset($quote) ? "Creating" : "Updating" }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{ !isset($quote) ? "Create New Quotation" : "Update ".$quote->name }} </h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Quotation</button> --}}
                        <a href="{{route('quote.index')}}" class="btn btn-secondary hidden-xs ml-2 px-5">Back</a>
                    </div>
                </div>
                <div class="bh_divider"></div>
            </div>
        </div>
    </x-slot>

    <div class="container">
        <form id="form_validation" method="POST" action="{{!isset($quote) ? route('quote.store') : route("quote.update", $quote->id)}}"> 
            @csrf
            @if(isset($quote))
                @method("PUT")
            @endif
            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="body row">
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="order_type_id" label="Order Type" :required="true" search="true">
                                    <option value="" disabled selected>-Select Order Type-</option>
                                    @php
                                        $old_order_type = $quote?->order_type_id ?? old("order_type");
                                    @endphp
                                    @foreach ($order_types as $order_type )
                                        <option value="{{$order_type->id}}" {{$old_order_type == $order_type->id ? 'selected' : ''}}>{{$order_type->name}}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="location_id" label="Branch" :required="true" search="true">
                                    <option value="" disabled selected>-Select Branch-</option>
                                    @php
                                        $old_location = $quote?->location_id ?? old("order_type");
                                    @endphp
                                    @foreach ($locations as $location )
                                        <option value="{{$location->id}}" {{$old_location == $location->id ? 'selected' : ''}}>{{$location->name}}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-input type="text" name="order_date" value="" class="daterange" label="Order Date" :required="true" />
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="invoice_no" value="" inputformat="alphanumeric" label="Invoice No."/>
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="invoice_date" value="" class="daterange" label="Invoice Date"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="header">
                            <h2>Customer Details</h2>   
                        </div>
                        <div class="body row">
                            <div class="col-2">
                                <x-select class="z-index show-tick" name="title" label="Title">
                                    <option value="" disabled selected>-Select Title-</option>
                                    @foreach ($titles as $title )
                                        <option value="{{$title}}">{{$title}}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-5">
                                    <x-input type="text" name="firstname" value="" label="Firstname"/>
                            </div>
                            <div class="col-5">
                                    <x-input type="text" name="lastname" label="Lastname"/>
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="salutation" value="" label="Salutation"/>
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="email" label="Email Address"/>
                            </div>
                            
                            <div class="col-6">
                                <x-input type="text" name="address_1" value="" label="Address Line 1"/>
                            </div>
                            <div class="col-6">
                                <x-input type="text" name="address_2" value="" label="Address Line 2"/>
                            </div>

                            <div class="col-3">
                                <x-input type="text" name="city_county" value="" label="City / County"/>
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="post_code" value="" label="Post Code"/>
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="tel_no" value="" label="Tel. No."/>
                            </div>
                            <div class="col-3">
                                <x-input type="text" name="mobile_no" value="" label="Mobile No."/>
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
                                    <x-input type="text" name="deceased_name" value="" label="Deceased"/>
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="date_of_death" value="" class="daterange" label="Date of Death" />
                            </div>

                            <div class="col-6">
                                <x-input type="text" name="consecration_date" value="" class="daterange" label="Consecration / Required By" />
                            </div>

                            <div class="col-6">
                                <x-select class="z-index show-tick" name="title" label="Cemetery" search="true">
                                    <option value="" disabled selected>-Select Title-</option>
                                    @foreach ($cemeteries as $cemetery )
                                        <option value="{{$cemetery->id}}">{{$cemetery->name}}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="col-6">
                                <x-input type="radio" name="fixed_date" id="is_tba" value="is_tba" class="with-gap mr-2" label="To be Advised" />
                                <x-input type="radio" name="fixed_date" id="is_approx" value="is_approx" class="with-gap mr-2" label="Approximate" />
                                <x-input type="radio" name="fixed_date" id="is_asap" value="is_asap" class="with-gap mr-2" label="ASAP" />
                            </div>

                            <div class="col-6">
                                <x-select class="z-index show-tick" name="title" label="Title">
                                    <option value="" disabled selected>-Select Title-</option>
                                    @foreach ($titles as $title )
                                        <option value="{{$title}}">{{$title}}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            


                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 d-flex justify-content-center align-items-center">
                @isset($quote)
                    <button class="btn btn-danger hidden-xs w-25 ml-2" id="soft-delete" type="button" label="{{$quote->name}}" route="{{ route('quote.destroy', $quote->id) }}" landing_page="{{route('quote.index')}}">Delete</button>
                @endisset
                <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{ !isset($quote) ? "Create" : "Update" }}</button>
            </div>
        </form>
    </div>

    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/quotes.js') }}"></script>
    </x-slot>
   
</x-app-layout>
