<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item active">Burial Society Organization</li>
                        </ul>
                        <h1 class="mb-1 mt-1">Burial Society Organization</h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        <a href="{{route('burial_society_organization.create')}}" class="btn btn-default hidden-xs ml-2">Create Burial Society Organization</a>
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
                        <h2>List of <strong>Burial Society Organizations</strong></h2>   
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Burial Society Organization</th>
                                        <th>Cemetery</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($burial_society_organizations as  $burial_society_organization)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$burial_society_organization->name}}</td>
                                            <td>{{$burial_society_organization->cemetery->name}}</td>
                                            <td>{{$burial_society_organization->user?->firstname ?? ""}} {{$burial_society_organization->user?->lastname ?? ""}}</td>
                                            <td>
                                                <a href="{{route('burial_society_organization.edit', $burial_society_organization->id)}}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
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
        <script src="{{asset('assets/custom/js/burial_society_organization.js')}}"></script>
    </x-slot>
   
</x-app-layout>
