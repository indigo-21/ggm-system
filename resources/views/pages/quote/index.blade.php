<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item active">Quotation</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Quotation</h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>
                    <div class="col-lg-6 col-md-12 text-md-right">
                        <a href="{{ route('quote.create') }}" class="btn btn-default hidden-xs ml-2">Create
                            Quotation</a>
                        {{-- <button class="btn btn-secondary hidden-xs ml-2">New Report</button> --}}
                    </div>
                </div>
                <div class="bh_divider"></div>
            </div>
        </div>
    </x-slot>

    <div class="container">
        <div class="row clearfix row-deck">
            <form action="{{ route('quote.index_filtered') }}" method="POST">
                @csrf
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="body row">
                            <div class="col-4">
                                <input type="hidden" name="is_order" value="1">
                                <x-select class="z-index show-tick" name="order_type_id" label="Order Type"
                                    search="true">
                                    <option value="" selected>-All-</option>
                                    @foreach ($order_types as $order_type)
                                        <option value="{{ $order_type->id }}"
                                            {{ isset($filterInput) ? ($filterInput['orderTypeId'] == $order_type->id ? 'selected' : '') : '' }}>
                                            {{ $order_type->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="user_id" label="User" search="true">
                                    <option value="" disabled selected>-All-</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ isset($filterInput) ? ($filterInput['userId'] == $user->id ? 'selected' : '') : '' }}>
                                            {{ $user->firstname }} {{ $user->lastname }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-4">
                                <x-select class="z-index show-tick" name="invoice_status" label="Invoice Status"
                                    search="true">
                                    <option value="" disabled selected>-All-</option>
                                    <option value="0"
                                        {{ isset($filterInput) ? ($filterInput['invoicedStatus'] == 0 ? 'selected' : '') : '' }}>
                                        -Univoiced-</option>
                                    <option value="1"
                                        {{ isset($filterInput) ? ($filterInput['invoicedStatus'] == 1 ? 'selected' : '') : '' }}>
                                        -Invoiced-</option>
                                </x-select>
                            </div>
                            @php
                                $orderDateMonth = isset($filterInput) ? $filterInput['orderMonth'] : date('n');
                                $orderDateYear = isset($filterInput) ? $filterInput['orderYear'] : date('Y');
                            @endphp
                            <div class="col-2">
                                <x-select class="z-index show-tick" name="order_date_month" label="Order Date"
                                    search="true">
                                    @foreach ($months as $month)
                                        <option value="{{ $loop->iteration }}"
                                            {{ $orderDateMonth == $loop->iteration ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-2">
                                <x-select class="z-index show-tick" label="Year Filter" name="order_date_year"
                                    search="true">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ $orderDateYear == $year ? 'selected' : '' }}>{{ $year }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-3">
                                        <x-select class="z-index show-tick" name="search_column" label="Search"
                                            search="true">
                                            <option value="id"
                                                {{ isset($filterInput) ? ($filterInput['searchColumn'] == 'id' ? 'selected' : '') : '' }}>
                                                Order No.</option>
                                            <option value="customer_name"
                                                {{ isset($filterInput) ? ($filterInput['searchColumn'] == 'customer_name' ? 'selected' : '') : '' }}>
                                                Customer</option>
                                            <option value="deceased_name"
                                                {{ isset($filterInput) ? ($filterInput['searchColumn'] == 'deceased_name' ? 'selected' : '') : '' }}>
                                                Deceased</option>
                                            <option value="grave_number"
                                                {{ isset($filterInput) ? ($filterInput['searchColumn'] == 'grave_number' ? 'selected' : '') : '' }}>
                                                Grave No.</option>
                                            <option value="invoice_no"
                                                {{ isset($filterInput) ? ($filterInput['searchColumn'] == 'invoice_no' ? 'selected' : '') : '' }}>
                                                Invoice No.</option>
                                        </x-select>
                                    </div>
                                    <div class="col-9">
                                        <x-input type="text" name="search_input" label="Search Input"
                                            value="{{ isset($filterInput) ? $filterInput['searchInput'] : '' }}"
                                            inputformat="alphanumeric" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary hidden-xs ml-2 px-5">Search</button>
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
                        <h2>List of <strong>Quotes</strong></h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable"
                                style="font-size:90%">
                                <thead>
                                    <tr>
                                        <th>Order Date</th>
                                        <th>Customer Name</th>
                                        <th>Deceased Name</th>
                                        <th>Consecration/Required By</th>
                                        <th>Cemetery</th>
                                        <th>Grave No.</th>
                                        <th>Invoice No.</th>
                                        <th>Order Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotes as $quote)
                                        <tr>
                                            <th>{{ $quote->created_at->format('F d, Y') }}</th>
                                            <td>{{ $quote->customer?->firstname ?? '' }}
                                                {{ $quote->customer->lastname }}</td>
                                            <td>{{ $quote->deceased_name }}</td>
                                            <td class="text-center">
                                                {{ $quote->consecration_date ? date('F d, Y', strtotime($quote->consecration_date)) : '' }}
                                            </td>
                                            <td class="text-center">{{ $quote?->cemetery->name ?? '' }}</td>
                                            <td class="text-center">{{ $quote?->grave_number ?? '' }}</td>
                                            <td class="text-center">{{ $quote?->invoice_no ?? '' }}</td>
                                            <td class="text-center">
                                                @if (isset($quote?->order_note->is_order_complete) && $quote->order_note->is_order_complete == 1)
                                                    <span class="badge badge-primary">Completed</span>
                                                @else
                                                    <span class="badge badge-danger">Incomplete</span>
                                                @endif
                                                {{-- <span class="badge badge-warning">Warning</span>
                                                <span class="badge badge-info">Info</span> --}}
                                            </td>
                                            <td>{{ $quote->user->firstname }} {{ $quote->user->lastname }}</td>
                                            <td>
                                                <a href="{{ route('quote.edit', $quote->id) }}"
                                                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                                                    <i class="icon-eye"></i>&nbsp;View
                                                </a>
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
        <script src="{{ asset('assets/custom/js/quotes/index.js') }}"></script>
    </x-slot>

</x-app-layout>
