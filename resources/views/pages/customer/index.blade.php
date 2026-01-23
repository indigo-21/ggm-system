<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Customer</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Customer</h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        <a href="{{route('customer.create')}}" class="btn btn-default hidden-xs ml-2">Create Customer</a>
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
                        <div class="col-12 row">
                                <div class="col-3">
                                    <x-select class="z-index show-tick" name="search_column" label="Search" search="true">
                                        <option value="report_id">Customer</option>
                                        <option value="ord_fullname">Post Code</option>
                                        <option value="ord_deceased">Phone No.</option>
                                        <option value="ord_grave_no">Email</option>
                                    </x-select> 
                                </div>
                                <div class="col-6">
                                        <x-input type="text" name="search_input" label="Search Input" inputformat="alphanumeric"/>
                                </div>
                                <div class="col-3">
                                    <label for="">&nbsp;</label><br>
                                    <button class="btn btn-primary hidden-xs ml-2 px-5">Search</button>
                                </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="header">
                        <h2>List of <strong>Customers</strong></h2>   
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable" style="font-size:90%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Post Code</th>
                                        <th>Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer )
                                        <tr>
                                            <td>{{ $loop->iteration}}</td>
                                            <td>{{$customer->firstname }} {{$customer->lastname }}</td>
                                            <td>{{$customer->address_one }} {{$customer->address_two }} {{$customer->firstname }} {{$customer->city_county }}</td>
                                            <td>{{$customer->customer_contacts->first()?->contact_value ?? "" }}</td>
                                            <td>
                                                <a href="{{route('customer.edit', $customer->id)}}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
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
        <script src="{{asset('assets/custom/js/customer.js')}}"></script>
    </x-slot>
   
</x-app-layout>
