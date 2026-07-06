<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Schedule</li>
                            <li class="breadcrumb-item active">Creating Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Schedule Added Inscription</h1>
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
        <form id="form_validation" method="POST"
            action="{{ !isset($schedule) ? route('schedule.store') : route('schedule.update', $schedule->id) }}">
            @csrf
            @if (isset($schedule))
                @method('PUT')
            @endif
            <input type="hidden" name="orderId" value="{{ $order->id }}">
            <input type="hidden" name="orderTypeId" value="{{ $order->order_type_id }}">
            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="header">
                            <h2>Added Inscription</h2>
                        </div>
                        <div class="body row px-5">
                            <div class="col-6">
                                <div class="d-flex justify-content-between">
                                    <p><strong>Order Date:</strong></p>
                                    <p>{{ \Carbon\Carbon::parse($order->created_at)?->format('F d, Y A') }} (Ref:
                                        AI/{{ $order->id }})</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p><strong>Customer:</strong></p>
                                    <p>{{ $order->customer->firstname }} {{ $order->customer->lastname }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p><strong>Deceased:</strong></p>
                                    <p>{{ $order->deceased_name }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between">
                                    @php
                                        $consecrationDate = \Carbon\Carbon::parse($order->consecration_date);
                                    @endphp
                                    <p><strong>Consecration:</strong></p>
                                    <p>{{ $consecrationDate?->format('F d, Y A') }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p><strong>Cemetery:</strong></p>
                                    <p>{{ $order->cemetery->name }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p><strong>Grave No.:</strong></p>
                                    <p>{{ $order->grave_number }}</p>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 10px;">
                                    <x-select class="z-index show-tick" name="month" label="Month" search="true">
                                        @foreach ($months as $month)
                                            <option value="{{ $loop->iteration }}"
                                                {{ date('n') == $loop->iteration ? 'selected' : '' }}>
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <x-select class="z-index show-tick" label="Year" name="year" search="true">
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}"
                                                {{ date('Y') == $year ? 'selected' : '' }}>{{ $year }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <!-- Fixing Schedule Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Schedule</h2>
                        </div>
                        <div class="body row">
                            <div class="col-lg-6 col-12">
                                <x-input type="text" name="schedule_date" class="single-daterange clear-daterange"
                                    label="Date" value="{{isset($schedule) ? date('F d, Y', strtotime($schedule->schedule_date))  : $consecrationDate?->format('F d, Y') }}" />

                            </div>
                            <div class="col-lg-6 col-12">
                                <x-select class="z-index show-tick" name="schedule_status" label="Fixing Status"
                                    search="true">
                                    <option value="0" {{ isset($schedule) && $schedule->schedule_status == 0 ? "selected" : ""}}>-Assigned-</option>
                                    <option value="2" {{ isset($schedule) && $schedule->schedule_status == 2 ? "selected" : ""}}>-To be Done-</option>
                                    <option value="1" {{ isset($schedule) && $schedule->schedule_status == 1 ? "selected" : ""}}>-Completed-</option>
                                </x-select>
                            </div>
                        </div>
                    </div>
                    <!-- Payment Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Payment</h2>
                        </div>
                        <div class="body row">
                            @php
                                $totalAmount = floatVal($order->payments->sum("amount") ?? 0);
                                $grossAmount = floatVal($order->order_cost?->gross_amount ?? 0); 
                                $orderBalance = $grossAmount - $totalAmount;
                            @endphp
                            <div class="col-lg-4 col-12">
                                <x-input type="text" name="balance" label="Balance" readonly
                                    value="{{ number_format($orderBalance ?? 0, 2) }} " />
                            </div>
                            <div class="col-lg-4 col-12">
                                {{-- @php
                                    $paymentStatus = 0;
                                    $grossAmount = floatval($order->order_cost->gross_amount);
                                    $orderBalance = floatval($order->order_cost->balance);
                                    $halfGrossAmount = $grossAmount / 2;
                                    if ($orderBalance) {
                                        $paymentStatus = 1;
                                    } elseif ($orderBalance < $halfGrossAmount) {
                                        $paymentStatus = 2;
                                    } else {
                                        $paymentStatus = 0;
                                    }
                                @endphp --}}
                                <x-select class="z-index show-tick mb-0" name="payment_status" label="Payment Status"
                                    search="true">
                                    <option {{ isset($schedule) && $schedule->payment_status == 0 ? "selected" : ""}} value="0">-Unpaid-</option>
                                    <option {{ isset($schedule) && $schedule->payment_status == 1 ? "selected" : ""}} value="1">-Paid-</option>
                                    <option {{ isset($schedule) && $schedule->payment_status == 2 ? "selected" : ""}} value="2">-Part Paid-</option>
                                </x-select>
                            </div>
                            <div class="col-lg-4 col-12">
                                <x-input type="text" name="invoice_no" label="Invoice No." readonly
                                    value="{{ $order->invoice_no }}" />
                            </div>
                        </div>
                    </div>
                    @php
                        $permitBack = "";
                        if(isset($schedule)){
                            $permitBack = $schedule->is_permit_back == 1 ?'checked' : '';
                        }
                    @endphp
                    <!-- Permit Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Permit</h2>
                        </div>
                        <div class="body row">
                            <div class="col-4">
                                <div class="d-flex justify-content-between">
                                    <p>Back</p>
                                    <x-input type="checkbox" label="" name="is_permit_back" checked="{{$permitBack}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Approval Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Approval</h2>
                        </div>
                        @php
                            $customerApproved = "";
                            $inscriptionFactoryApproved = "";
                            $burialSocietyApproved = "";
                            

                            if(isset($schedule)){
                                $customerApproved = $schedule->is_customer_approved == 1 ? 'checked' : '';
                                $inscriptionFactoryApproved = $schedule->is_inscription_factory_approved == 1 ?'checked' : '';
                                $burialSocietyApproved = $schedule->is_burial_society_approved == 1 ?'checked' : '';
                                $permitBack = $schedule->is_permit_back == 1 ?'checked' : '';
                            }
                        @endphp
                        <div class="body row">
                            <div class="col-6">
                                <div class="d-flex justify-content-between">
                                    <p>Customer</p>
                                    <x-input type="checkbox" label="" name="is_customer_approved" checked="{{ $customerApproved  }}" />
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Inscription at Factory</p>
                                    <x-input type="checkbox" label="" name="is_inscription_factory_approved" checked="{{ $inscriptionFactoryApproved }}"/>
                                    <input type="hidden" name="is_inscription_factory_timestamp" value="{{ isset($schedule) && $schedule->inscription_factory_approved_timestamp ? date('F d, Y', strtotime($schedule->inscription_factory_approved_timestamp))  : '' }}">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Burial Society</p>
                                    <x-input type="checkbox" label="" name="is_burial_society_approved" checked="{{ $burialSocietyApproved }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <!-- Details Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Details</h2>
                        </div>
                        <div class="body row">
                            <div class="col-12">
                                <x-input class="mb-3" type="textarea" name="details" label="Reno Washdown" value="{{ isset($schedule) ? $schedule->details : '' }}" />
                            </div>
                            <div class="col-12">
                                <x-input class="mb-3" type="textarea" name="extras" label="Extras" value="{{ isset($schedule) ? $schedule->extras : '' }}" />
                            </div>
                            <div class="col-12">
                                <x-input type="textarea" name="issues" label="Issues" value="{{isset($schedule) ? $schedule->issue : '' }}" />
                            </div>

                        </div>
                    </div>
                    <!-- Letter Cutter Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Letter Cutter</h2>
                        </div>
                        <div class="body row">
                            <div class="col-12">
                                <x-input class="mb-3" type="textarea" name="letter_cutter_name" label="Name" value="{{ isset($schedule) ? $schedule->letter_cutter_name : '' }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 d-flex justify-content-center align-items-center">
                {{-- <x-buttons class="btn-secondary" type="button" label="Back" href="{{ route('schedule.index') }}" /> --}}
                <a href="{{ route('schedule.index') }}"
                    class="btn btn-secondary hidden-xs w-25 ml-2">
                    Back
                </a>
                {{-- <x-buttons class="btn-danger" type="button" label="View Order" /> --}}
                <a href="{{ url()->previous() }}"
                    class="btn btn-danger hidden-xs w-25 ml-2">
                    <i class="icon-eye"></i>&nbsp;View Order
                </a>
                <x-buttons id="submit_form" class="btn-primary" type="submit"
                    label="{{ !isset($schedule) ? 'Create' : 'Update' }}" />
            </div>
        </form>
    </div>
    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/schedule/index.js') }}"></script>
        <script src="{{ asset('assets/custom/js/schedule/added_inscription.js') }}"></script>
    </x-slot>
</x-app-layout>
