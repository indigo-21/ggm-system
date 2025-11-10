<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('account_level.index')}}">Account Level</a></li>
                            <li class="breadcrumb-item active">{{ !isset($account_level) ? "Creating" : "Updating" }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{ !isset($account_level) ? "Create New Account Level" : "Update ".$account_level->name }} </h1>
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
                    <form id="form_validation" method="POST" action="{{!isset($account_level) ? route('account_level.store') : route("account_level.update", $account_level->id)}}">
                        @csrf
                        @if(isset($account_level))
                            @method("PUT")
                        @endif
                        <div class="body">
                            <x-input type="text" name="name" value="{{ $account_level?->name ?? old('name') }}" inputformat="alphanumeric" label="Account Level Name" :required="true" :error="$errors->first('name')"/>
                        
                            <x-select label="Access Modules" name="module_ids[]" :required="true" :multiple="true">
                                @php
                                    $old_modules =  old("module_ids", []);
                                    if(isset($account_level)){
                                        $old_modules = explode(",", $account_level->module_ids);
                                    }
                                @endphp

                                <option value="" disabled>-Select Modules-</option>

                                @foreach ($modules as $module)
                                    <option value="{{$module->id}}" {{ in_array($module->id, $old_modules) ? "selected" : ""  }}>
                                        {{$module->name}}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="footer d-flex justify-content-center align-items-center">
                            <a href="{{route('account_level.index')}}" class="btn btn-danger hidden-xs w-25 ml-2" type="button">Cancel</a>
                            <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{ !isset($account_level) ? "Create" : "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('assets/custom/js/account-level.js')}}"></script>
    </x-slot>
   
</x-app-layout>
