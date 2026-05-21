<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Schedule</li>
                            <li class="breadcrumb-item active">Creating Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Create New Schedule</h1>
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
            @if (isset($quote))
                @method('PUT')
            @endif
            <div class="row clearfix row-deck">
                <div class="col-12">
                    <div class="card top_widget">
                        <div class="header">
                            <h2>New Memorial</h2>
                        </div>
                        <div class="body row px-5">
                            <div class="col-6">
                                <div class="d-flex justify-content-between">
                                    <p><strong>Order Date:</strong></p>
                                    <p>19/11/25 (Ref: NM/42623)</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p><strong>Customer:</strong></p>
                                    <p>Sobel</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p><strong>Deceased:</strong></p>
                                    <p>Racel Sobel</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between">
                                    <p><strong>Consecration:</strong></p>
                                    <p>03/05/26</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p><strong>Cemetery:</strong></p>
                                    <p>Bushey</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p><strong>Grave No.:</strong></p>
                                    <p>BN4-7-33</p>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 10px;">
                                    <x-select class="z-index show-tick" name="month" label="Month"
                                        search="true">
                                        @foreach ($months as $month)
                                            <option value="{{ $loop->iteration }}" {{ date("n") == $loop->iteration ? "selected" : "" }}>
                                                {{$month}}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <x-select class="z-index show-tick" label="Year" name="year"
                                        search="true">
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}" {{ date("Y") == $year ? "selected" : "" }}>{{$year}}
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
                            <h2>Fixing Schedule</h2>
                        </div>
                        <div class="body row">
                            <div class="col-12">
                                <x-input type="text" name="date" class="daterange-am-pm clear-daterange" label="Date" />
                                <x-select class="z-index show-tick" name="fixing_status" label="Fixing Status"
                                    search="true">
                                    <option value="" disabled selected>-All-</option>
                                    <option value="0">-Unfixed-</option>
                                    <option value="1">-Fixed-</option>
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
                            <div class="col-12">
                                <x-select class="z-index show-tick mb-0" name="payment_status" label="Payment Status"
                                    search="true">
                                    <option value="" disabled selected>-All-</option>
                                    <option value="0">-Unpaid-</option>
                                    <option value="1">-Paid-</option>
                                </x-select>
                                <x-input type="text" name="invoice_no" label="Invoice No." value="27125EN" disabled />
                            </div>
                        </div>
                    </div>
                    <!-- Design Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Design</h2>
                        </div>
                        <div class="body row">
                            <div class="col-12">
                                <x-input type="text" name="design" label="Design" />
                            </div>
                        </div>
                    </div>
                    <!-- View Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>View</h2>
                        </div>
                        <div class="body row">
                            <div class="col-12">
                                <x-select class="z-index show-tick" name="location" label="Location" search="true">
                                    <option value="" disabled selected>-SELECT LOCATION-</option>
                                    <option value="0">By Photo</option>
                                </x-select>
                                <x-select class="z-index show-tick" name="location" label="Location" search="true">
                                    <option value="" disabled selected>-SELECT STATUS-</option>
                                    <option value="0">Photo Sent</option>
                                </x-select>
                                <x-input type="text" name="schedule_date" class="daterange-am-pm clear-daterange"
                                    label="Schedule Date" />
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
                                <x-input class="mb-3" type="textarea" name="description" label="Description" />
                                <x-input type="textarea" name="issues" label="Issues" />
                            </div>
                        </div>
                    </div>
                    <!-- Approval Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Approval</h2>
                        </div>
                        <div class="body row">
                            <div class="col-6">
                                <div class="d-flex justify-content-between">
                                    <p>Customer</p>
                                    <x-input type="checkbox" label="" name="customer" />
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Inscription at Factory</p>
                                    <x-input type="checkbox" label="" name="inscription_at_factory" />
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Burial Society</p>
                                    <x-input type="checkbox" label="" name="burial_society" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Email Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Email</h2>
                        </div>
                        <div class="body row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex justify-content-between">
                                        <p>Sent</p>
                                        <x-input type="checkbox" label="" name="sent" />
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p>Sent Post</p>
                                        <x-input type="checkbox" label="" name="sent_post" />
                                    </div>
                                    <x-input type="text" name="email_sent_date" class="daterange-am-pm clear-daterange"
                                        label="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Permit Card -->
                    <div class="card top_widget">
                        <div class="header d-flex justify-content-between align-items-center">
                            <h2>Permit</h2>
                        </div>
                        <div class="body row">
                            <div class="col-4">
                                <div class="d-flex justify-content-between">
                                    <p>Back</p>
                                    <x-input type="checkbox" label="" name="back" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 d-flex justify-content-center align-items-center">
                <x-buttons class="btn-secondary" type="button" label="Back" />
                <x-buttons class="btn-danger" type="button" label="View Order" />
                <x-buttons id="submit_form" class="btn-primary" type="button"
                    label="{{ !isset($schedule) ? 'Create' : 'Update' }}" />
            </div>
        </form>
    </div>
    <x-slot name="script">
        <script src="{{ asset('assets/custom/js/schedule.js') }}"></script>
    </x-slot>
</x-app-layout>