<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Orders</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Orders</h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <a href="{{route('quote.create')}}" class="btn btn-default hidden-xs ml-2">Create Orders</a> --}}
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
                            <x-select class="z-index show-tick" name="order_type_id" label="Order Type" :required="true" search="true">
                                <option value="" disabled selected>-All-</option>
                                @foreach ($order_types as $order_type )
                                    <option value="{{$order_type->id}}" >{{$order_type->name}}</option>
                                @endforeach
                            </x-select> 
                        </div>
                        <div class="col-4">
                            <x-select class="z-index show-tick" name="user_id" label="User" :required="true" search="true">
                                <option value="" disabled selected>-All-</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}" >{{$user->firstname}} {{$user->lastname}}</option>
                                @endforeach
                            </x-select> 
                        </div>
                        <div class="col-4">
                            <x-select class="z-index show-tick" name="invoice_status" label="Invoice Status" search="true">
                                <option value="" disabled selected>-All-</option>
                                <option value="0">-Univoiced-</option>
                                <option value="1">-Invoiced-</option>
                            </x-select> 
                        </div>
                        <div class="col-2">
                            <x-select class="z-index show-tick" name="order_date_month" label="Order Date" search="true">
                                @foreach ($months as $month)
                                    <option value="{{ $loop->iteration }}" {{ date("n") == $loop->iteration ? "selected" : "" }}>{{$month}}</option>
                                @endforeach
                            </x-select> 
                        </div>
                        <div class="col-2">
                            <x-select class="z-index show-tick" label="Year Filter" name="order_date_year"  search="true">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ date("Y") == $year ? "selected" : "" }}>{{$year}}</option>
                                @endforeach
                            </x-select> 
                        </div>
                        <div class="col-8">
                            <div class="row">
                                <div class="col-3">
                                    <x-select class="z-index show-tick" name="search_column" label="Search" search="true">
                                        <option value="report_id">Order No.</option>
                                        <option value="ord_fullname">Customer</option>
                                        <option value="ord_deceased">Deceased</option>
                                        <option value="ord_grave_no">Grave No.</option>
                                        <option value="ord_invoice_no">Invoice No.</option>
                                    </x-select> 
                                </div>
                                <div class="col-9">
                                        <x-input type="text" name="search_input" label="Search Input" inputformat="alphanumeric"/>
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
                        <h2>List of <strong>Orders</strong></h2>   
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable" style="font-size:90%">
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
                                    @foreach ( $orders as $order )
                                        @if(trim($order->order_cost->deposit_description) != "" && $order->order_cost->deposit_amount > 0)
                                        <tr>
                                            <th>{{$order->created_at->format('F d, Y')}}</th>
                                            <td>{{$order->customer?->firstname ?? "" }} {{$order->customer->lastname}}</td>
                                            <td>{{$order->deceased_name}}</td>
                                            <td class="text-center">
                                                {{$order->consecration_date ? date('F d, Y', strtotime($order->consecration_date)) :  "" }}
                                            </td>
                                            <td class="text-center">{{$order?->cemetery->name ?? ""}}</td>
                                            <td class="text-center">{{$order?->grave_number ?? ""}}</td>
                                            <td class="text-center">{{$order?->invoice_no ?? ""}}</td>
                                            <td class="text-center">
                                                @if (isset($order?->order_note->is_order_complete) && $order->order_note->is_order_complete == 1)
                                                    <span class="badge badge-primary">Completed</span>
                                                @else
                                                    <span class="badge badge-danger">Incomplete</span>
                                                @endif
                                                {{-- <span class="badge badge-warning">Warning</span>
                                                <span class="badge badge-info">Info</span> --}}
                                            </td>
                                            <td>{{$order->user->firstname }} {{$order->user->lastname }}</td>
                                            <td>
                                                <a href="{{route('quote.edit', $order->id)}}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                                                    <i class="icon-eye"></i>&nbsp;View
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
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
        <script src="{{asset('assets/custom/js/order.js')}}"></script>
    </x-slot>
   
</x-app-layout>
