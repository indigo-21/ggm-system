<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('module.index')}}">Account Level</a></li>
                            <li class="breadcrumb-item active">{{!isset($module) ? "Creating" : "Updating"}} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{!isset($module)  ? "Create New" : "Update".$module->name}} Module</h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Account Level</button> --}}
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
                    {{-- <div class="header">
                        <h2>List of <strong>Account Level</strong></h2>   
                    </div> --}}
                    <form id="form_validation" method="POST" action="{{!isset($module) ? route('module.store') : route("module.update", $module->id)}}">
                        @csrf
                        @if(isset($module))
                            @method("PUT")
                        @endif
                        <div class="body">
                            <x-input type="text" name="name" value="{{ $module?->name ?? old('name') }}" inputformat="alphanumeric" label="Module Name" :required="true" :error="$errors->first('name')"/>
                        
                            <x-input type="text" name="route_name" value="{{ $module?->route_name ?? old('route_name') }}" inputformat="alphanumeric" label="Route Name" :required="true" :error="$errors->first('route_name')"/>
                        </div>
                        <div class="footer d-flex justify-content-center align-items-center mb-4">
                            <a href="{{route('module.index')}}" class="btn btn-danger hidden-xs w-25 ml-2" type="button">Cancel</a>
                            <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{!isset($module) ? "Create" : "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('assets/custom/js/module.js')}}"></script>
    </x-slot>
   
</x-app-layout>
