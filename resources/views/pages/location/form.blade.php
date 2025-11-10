<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('location.index')}}">Location</a></li>
                            <li class="breadcrumb-item active">{{ !isset($location) ? "Creating" : "Updating" }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{ !isset($location) ? "Create New Location" : "Update ".$location->name }} </h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Location</button> --}}
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
                    <form id="form_validation" method="POST" action="{{!isset($location) ? route('location.store') : route("location.update", $location->id)}}">
                        @csrf
                        @if(isset($location))
                            @method("PUT")
                        @endif
                        <div class="body">
                            <x-input type="text" name="name" value="{{ $location?->name ?? old('name') }}" inputformat="alphanumeric" label="Location Name" :required="true" :error="$errors->first('name')"/>
                        </div>
                        <div class="footer d-flex justify-content-center align-items-center">
                            <a href="{{route('location.index')}}" class="btn btn-danger hidden-xs w-25 ml-2" type="button">Cancel</a>
                            <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{ !isset($location) ? "Create" : "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('assets/custom/js/location.js')}}"></script>
    </x-slot>
   
</x-app-layout>
