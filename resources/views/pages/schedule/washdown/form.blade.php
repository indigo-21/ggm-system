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
                        <h1 class="mb-1 mt-1">Schedule Washdown</h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>
                    <div class="col-lg-6 col-md-12 text-md-right">
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
                            <h2>Washdown</h2>
                        </div>
                        <div class="body row px-5">
                            <div class="col-6">
                                <div class="d-flex justify-content-between">
                                    <p><strong>Order Date:</strong></p>
                                    <p>{{ \Carbon\Carbon::parse($order->created_at)?->format('F d, Y A') }} (Ref:
                                        W/{{ $order->id }})</p>
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
                                    <p><strong>Consecration:</strong></p>
                                    <p>{{ \Carbon\Carbon::parse($order->consecration_date)?->format('F d, Y A') }}</p>
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
                <div class="col-12">
                    <!-- Schedule Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Schedule</h2>
                        </div>
                        <div class="body row">
                            <div class="col-lg-6 col-12">
                                <x-input type="text" name="date" class="single-daterange clear-daterange"
                                    label="Date"
                                    value="{{ isset($schedule) && $schedule->date ? date('F d, Y', strtotime($schedule->date)) : '' }}" />
                            </div>
                            <div class="col-lg-6 col-12">
                                <x-select class="z-index show-tick mb-0" name="payment_status" label="Payment Status"
                                    search="true">
                                    <option {{ isset($schedule) && $schedule->payment_status == 0 ? 'selected' : '' }}
                                        value="0">-Unpaid-</option>
                                    <option {{ isset($schedule) && $schedule->payment_status == 1 ? 'selected' : '' }}
                                        value="1">-Paid-</option>
                                    <option {{ isset($schedule) && $schedule->payment_status == 2 ? 'selected' : '' }}
                                        value="2">-Part Paid-</option>
                                </x-select>
                            </div>
                            @php
                                $isCompleted = '';
                                if (isset($schedule)) {
                                    $isCompleted = $schedule->is_completed == 1 ? 'checked' : '';
                                }
                            @endphp
                            <div class="col-12 mt-3">
                                <div class="d-flex align-items-center" style="gap: 10px;">
                                    <p class="mb-0">Completed</p>
                                    <x-input type="checkbox" label="" name="is_completed"
                                        checked="{{ $isCompleted }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <!-- Details Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Details</h2>
                        </div>
                        <div class="body row">
                            <div class="col-12">
                                <x-input class="mb-3" type="textarea" name="details" label="Details"
                                    value="{{ isset($schedule) ? $schedule->details : '' }}" />
                            </div>
                            <div class="col-12">
                                <x-input type="textarea" name="issues" label="Issues"
                                    value="{{ isset($schedule) ? $schedule->issue : '' }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 d-flex justify-content-center align-items-center">
                <a href="{{ route('schedule.index') }}" class="btn btn-secondary hidden-xs w-25 ml-2">
                    Back
                </a>
                <a href="{{ route('quote.edit', $order->id) }}" class="btn btn-danger hidden-xs w-25 ml-2">
                    <i class="icon-eye"></i>&nbsp;View Order
                </a>
                <x-buttons id="submit_form" class="btn-primary" type="submit"
                    label="{{ !isset($schedule) ? 'Create' : 'Update' }}" />
            </div>
        </form>
    </div>
    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/schedule/index.js') }}"></script>
    </x-slot>
</x-app-layout>
