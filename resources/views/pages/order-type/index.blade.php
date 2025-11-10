<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item active">Order Type</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Order Type</h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        <a href="{{route('order_type.create')}}" class="btn btn-default hidden-xs ml-2">Create Order Type</a>
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
                    <div class="header">
                        <h2>List of <strong>Order Types</strong></h2>   
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order Type</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order_types as  $order_type)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$order_type->name}}</td>
                                            <td>{{$order_type->user?->firstname ?? ""}} {{$order_type->user?->lastname ?? ""}}</td>
                                            <td>
                                                <a href="{{route('order_type.edit', $order_type->id)}}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
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
        <script src="{{asset('assets/custom/js/order_type.js')}}"></script>
    </x-slot>
   
</x-app-layout>
