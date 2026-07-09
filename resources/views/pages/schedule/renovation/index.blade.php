<x-app-layout>
    <x-slot name="customStyle">
        <style>
            .fixing-1 {
                background-color: #EF0C16;
                text-color: white;
            }

            .fixing-2 {
                background-color: #fff222;
                text-color: black;
            }

            .fixing-3 {
                background-color: #f8c8ee;
                text-color: black;
            }

            .fixing-4 {
                background-color: #f20dba;
                text-color: black;
            }

            .fixing-5 {
                background-color: #0541da;
                text-color: white;
            }

            .payment-1 {
                background-color: #3aab11;
            }

            .payment-2 {
                background-color: #66d63d;
            }

            .view-1 {
                background-color: #2df5f3;
            }

            .view-2 {
                background-color: #2df5f35c;
            }

            .permit-back {
                background-color: #ff8625;
            }

            .is-approved {
                background-color: #93ccf8;
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
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
            <form action="{{ route('schedule.index_filtered') }}" method="POST">
                @csrf
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="body row">
                            {{-- <div class="col-4">
                                <x-select class="z-index show-tick" name="order_type_id" label="Order Type"
                                    :required="true" search="true">
                                    @foreach ($order_types as $order_type)
                                        <option value="{{ $order_type->id }}">{{ $order_type->name }}</option>
                                    @endforeach
                                </x-select>
                            </div> --}}
                            <div class="col-6">
                                <x-select class="z-index show-tick" name="fixing_status" label="Fixing Status"
                                    search="true">
                                    <option value="" disabled selected>-All-</option>
                                    <option value="0">-Unfixed-</option>
                                    <option value="1">-Fixed-</option>
                                </x-select>
                            </div>
                            <div class="col-6">
                                <x-select class="z-index show-tick" name="payment_status" label="Payment Status"
                                    search="true">
                                    <option value="" disabled selected>-All-</option>
                                    @foreach ($payment_statuses as $payment_status)
                                        <option value="{{ $payment_status['id'] }}">{{ $payment_status['name'] }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-2">
                                <x-select class="z-index show-tick" name="order_date_month" label="Order Date"
                                    search="true">
                                    @foreach ($months as $month)
                                        <option value="{{ $loop->iteration }}"
                                            {{ date('n') == $loop->iteration ? 'selected' : '' }}>{{ $month }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-2">
                                <x-select class="z-index show-tick" label="Year Filter" name="order_date_year"
                                    search="true">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-3">
                                        <x-select class="z-index show-tick" name="search_column" label="Search"
                                            search="true">
                                            <option value="">-All-</option>
                                            <option value="deceased_name">Deceased.</option>
                                            <option value="grave_number">Grave No.</option>
                                            <option value="invoice_no">Invoice No.</option>
                                        </x-select>
                                    </div>
                                    <div class="col-9">
                                        <x-input type="text" name="search_input" label="Search Input"
                                            inputformat="alphanumeric" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button class="btn btn-primary hidden-xs ml-2 px-5" type="submit">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-6">
                                <h2>List of <strong>Schedules</strong></h2>
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
                                        <th colspan="3" class="text-center">Fixed</th>
                                        <th class="text-center">Deceased</th>
                                        <th class="text-center">Design</th>
                                        <th class="text-center" style="width:30%;">Description</th>
                                        <th class="text-center">Grave</th>
                                        <th class="text-center">Cemetery</th>
                                        <th class="text-center">c</th>
                                        <th class="text-center">b/s</th>
                                        <th class="text-center">Photo Sent</th>
                                        <th class="text-center">Export</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:70%;">
                                    @foreach ($schedules as $schedule)
                                        <tr class="schedule-table-row"
                                            href="{{ route('schedule.edit', [
                                                'orderTypeId' => $schedule->order->order_type_id,
                                                'scheduleId' => $schedule->id,
                                            ]) }}">
                                            <td
                                                class="text-center fixing{{ $schedule->fixing_status != 0 ? '-' . $schedule->fixing_status : '' }}">
                                                {{ $schedule?->fixing_date ? \Carbon\Carbon::parse($schedule->fixing_date)->format('d/m/Y') : '' }}
                                            </td>
                                            <td
                                                class="text-center fixing{{ $schedule->fixing_status != 0 ? '-' . $schedule->fixing_status : '' }}">
                                                {{ $schedule->order->invoice_no }}</td>
                                            <td
                                                class="text-center fixing{{ $schedule->fixing_status != 0 ? '-' . $schedule->fixing_status : '' }}">
                                                <span
                                                    class="badge badge-{{ $schedule->for_fixing == 0 ? 'danger' : 'success' }}">{{ $schedule->for_fixing == 0 ? 'Incomplete' : 'For Fixing' }}</span>
                                            </td>

                                            <td
                                                class="text-center payment{{ $schedule->payment_status != 0 ? '-' . $schedule->payment_status : '' }}">
                                                {{ $schedule->order->deceased_name }} </td>

                                            <td class="text-center">{{ $schedule->order->size }}
                                                {{ $schedule->order->design_headstone }}</td>

                                            <td class="text-center" style="white-space: pre-line;">
                                                {{ $schedule->description }}
                                                <span class="text-danger">{{ $schedule->issue }}</span>
                                            </td>

                                            <td
                                                class="text-center {{ $schedule->is_permit_back != 0 ? 'permit-back' : '' }}">
                                                {{ $schedule->order->grave_number }}</td>

                                            <td class="text-center">{{ $schedule->order->cemetery->name }}</td>

                                            <td
                                                class="text-center {{ $schedule->is_customer_approved ? 'is-approved' : '' }}">
                                                {{ $schedule->is_customer_approved ? 'x' : '' }}
                                            </td>

                                            <td
                                                class="text-center {{ $schedule->is_burial_society_approved ? 'is-approved' : '' }}">
                                                {{ $schedule->is_burial_society_approved ? 'x' : '' }}
                                            </td>

                                            <td
                                                class="text-center view{{ $schedule->view_status != 0 ? '-' . $schedule->view_status : '' }}">
                                                {{ $schedule?->view_date ? \Carbon\Carbon::parse($schedule->view_date)->format('D m/d') : '' }}
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <x-input class="m-auto" type="checkbox" label=""
                                                        name="export" />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/schedule/index.js') }}"></script>
    </x-slot>
</x-app-layout>
