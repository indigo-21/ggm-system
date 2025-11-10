<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('burial_society_organization.index')}}">Burial Society  Organization</a></li>
                            <li class="breadcrumb-item active">{{ !isset($burial_society_organization) ? "Creating" : "Updating" }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{ !isset($burial_society_organization) ? "Create New Burial Society  Organization" : "Update ".$burial_society_organization->name }} </h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Burial Society  Organization</button> --}}
                        <a href="{{route('burial_society_organization.index')}}" class="btn btn-secondary hidden-xs ml-2 px-5">Back</a>
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
                    <form id="form_validation" method="POST" action="{{!isset($burial_society_organization) ? route('burial_society_organization.store') : route("burial_society_organization.update", $burial_society_organization->id)}}">
                        @csrf
                        @if(isset($burial_society_organization))
                            @method("PUT")
                        @endif
                        <div class="body">
                            <x-input type="text" name="name" value="{{ $burial_society_organization?->name ?? old('name') }}" inputformat="alphanumeric" label="Burial Society  Organization Name" :required="true" :error="$errors->first('name')"/>

                            <x-select class="z-index show-tick" name="cemetery_id" label="Cemetery" :required="true" search="true">
                                <option value="" disabled selected>-Select Cemetery-</option>
                                @php
                                    $old_cemetery = $burial_society_organization?->cemetery_id ?? old("cemeetery_id");
                                @endphp
                                @foreach ($cemeteries as $cemetery )
                                    <option value="{{$cemetery->id}}" {{$old_cemetery == $cemetery->id ? "selected" : ""}}>{{$cemetery->name}}</option>
                                @endforeach
                            </x-select> 
                        </div>
                        <div class="footer mb-3 d-flex justify-content-center align-items-center">
                            @if(isset($burial_society_organization))
                                <button class="btn btn-danger hidden-xs w-25 ml-2" id="soft-delete" type="button" label="{{$burial_society_organization->name}}" route="{{ route('burial_society_organization.destroy', $burial_society_organization->id) }}" landing_page="{{route('burial_society_organization.index')}}">Delete</button>
                            @endif
                            <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{ !isset($burial_society_organization) ? "Create" : "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('assets/custom/js/burial_society_organization.js')}}"></script>
    </x-slot>
   
</x-app-layout>
