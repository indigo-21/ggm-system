<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Schedule</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Schedule</h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <a href="{{route('quote.create')}}" class="btn btn-default hidden-xs ml-2">Create
                            Orders</a> --}}
                        {{-- <button class="btn btn-secondary hidden-xs ml-2">New Report</button> --}}
                    </div>
                </div>
                <div class="bh_divider"></div>
            </div>
        </div>
    </x-slot>
    <div class="container">
        <div class="row clearfix row-deck">
            <div class="col-12">
                <div class="card top_widget">
                    <div class="body row">
                        <div class="col-4">
                            <x-select class="z-index show-tick" name="order_type_id" label="Order Type" :required="true"
                                search="true">
                                <option value="" disabled selected>-All-</option>
                                @foreach ($order_types as $order_type)
                                    <option value="{{$order_type->id}}">{{$order_type->name}}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="col-4">
                            <x-select class="z-index show-tick" name="fixing_status" label="Fixing Status"
                                search="true">
                                <option value="" disabled selected>-All-</option>
                                <option value="0">-Unfixed-</option>
                                <option value="1">-Fixed-</option>
                            </x-select>
                        </div>
                        <div class="col-4">
                            <x-select class="z-index show-tick" name="payment_status" label="Payment Status"
                                search="true">
                                <option value="" disabled selected>-All-</option>
                                <option value="0">-Unfixed-</option>
                                <option value="1">-Fixed-</option>
                            </x-select>
                        </div>
                        <div class="col-2">
                            <x-select class="z-index show-tick" name="order_date_month" label="Order Date"
                                search="true">
                                @foreach ($months as $month)
                                    <option value="{{ $loop->iteration }}" {{ date("n") == $loop->iteration ? "selected" : "" }}>{{$month}}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="col-2">
                            <x-select class="z-index show-tick" label="Year Filter" name="order_date_year"
                                search="true">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ date("Y") == $year ? "selected" : "" }}>{{$year}}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="col-8">
                            <div class="row">
                                <div class="col-3">
                                    <x-select class="z-index show-tick" name="search_column" label="Search"
                                        search="true">
                                        <option value="report_id">Order No.</option>
                                        <option value="ord_fullname">Customer</option>
                                        <option value="ord_deceased">Deceased</option>
                                        <option value="ord_grave_no">Grave No.</option>
                                        <option value="ord_invoice_no">Invoice No.</option>
                                    </x-select>
                                </div>
                                <div class="col-9">
                                    <x-input type="text" name="search_input" label="Search Input"
                                        inputformat="alphanumeric" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-right">
                            <button class="btn btn-primary hidden-xs ml-2 px-5">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-6">
                                <h2>List of <strong>Orders</strong></h2>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <button class="btn btn-danger hidden-xs ml-2 px-5">Print</button>
                                <button class="btn btn-primary hidden-xs ml-2 px-5">Export</button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-custom spacing5 mb-0">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">Fixed</th>
                                        <th class="text-center">Deceased</th>
                                        <th class="text-center">Design</th>
                                        <th class="text-center">Description</th>
                                        <th colspan="2" class="text-center">In Shop</th>
                                        <th class="text-center">Grave</th>
                                        <th class="text-center">Cemetery</th>
                                        <th class="text-center">c</th>
                                        <th class="text-center">b/s</th>
                                        <th class="text-center">Export</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">03/05/2026</td>
                                        <td class="text-center">27125EN</td>
                                        <td class="text-center">Rachel Sobel</td>
                                        <td class="text-center">4'Replica</td>
                                        <td class="text-center" style="white-space: pre-line;">
                                            Balmoral Red Granite
                                            13.04.2026 RUBBING ON CHAIR FOR TERRY - JR
                                            20/ 4 emailed Gary to send to Terry to cut.
                                            Washdown existing memorial joshfamilysobel@hotmail.com send photos to
                                            customer once paid for

                                            5/5 & 19/5 chased for payment Load 23 Ordered 09.12.2025
                                        </td>
                                        <td class="text-center">P</td>
                                        <td class="text-center">Tue 28/04</td>
                                        <td class="text-center">BN4-7-33</td>
                                        <td class="text-center">Bushey</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                            <x-input class="m-auto" type="checkbox" label="" name="export" /></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>