<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Configuration</a></li>
                            <li class="breadcrumb-item"><a href="{{route('user.index')}}">Users</a></li>
                            <li class="breadcrumb-item active">{{ !isset($user) ? "Creating" : "Updating" }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{ !isset($user) ? "Create New Account" : "Update ".$user->name }} </h1>
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
                    <form id="form_validation" method="POST" action="{{!isset($user) ? route('user.store') : route("user.update", $user->id)}}">
                        @csrf
                        @if(isset($user))
                            @method("PUT")
                        @endif
                        <div class="body">
                            <div class="row">
                                <div class="col-6">
                                    <x-input type="text" name="firstname" value="{{ $user?->firstname ?? old('firstname') }}" inputformat="alphanumeric" label="Firstname" :required="true" :error="$errors->first('firstname')"/>
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="lastname" value="{{ $user?->lastname ?? old('lastname') }}" inputformat="alphanumeric" label="Lastname" :required="true" :error="$errors->first('lastname')"/>
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="username" value="{{ $user?->username ?? old('username') }}" inputformat="alphanumeric" label="Username" :required="true" :error="$errors->first('username')"/>
                                </div>
                                <div class="col-6">
                                    <x-input type="text" name="email" value="{{ $user?->email ?? old('email') }}" inputformat="alphanumeric" label="Email Address" :required="true" :error="$errors->first('email')"/>
                                </div>
                                @if (!isset($user))
                                    <div class="col-6">
                                        <x-input type="password" name="password" value="{{ old('password') }}" inputformat="alphanumeric" label="Password" :required="true" :error="$errors->first('password')"/>
                                    </div>
                                    <div class="col-6">
                                        <x-input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" inputformat="alphanumeric" label="Confirm Password" :required="true" :error="$errors->first('password_confirmation')"/>
                                    </div>
                                @endif
                                <div class="col-6">
                                    <x-select class="z-index show-tick" name="location" label="Location" :required="true" search="true">
                                        <option value="" disabled selected>-Select Location-</option>
                                        @php
                                            $old_location = $user?->location_id ?? old("location");
                                        @endphp
                                        @foreach ($locations as $location )
                                            <option value="{{$location->id}}" {{$old_location == $location->id ? 'selected' : ''}}>{{$location->name}}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                                <div class="col-6">
                                    <x-select class="z-index show-tick" name="account_level" label="Access Level" :required="true" search="true">
                                        <option value="" disabled selected>-Select Access Level-</option>
                                        @php
                                            $old_account_level = $user?->account_level_id ?? old('account_level');
                                        @endphp
                                        @foreach ($account_levels as $account_level)
                                            <option value="{{$account_level->id}}" {{$old_account_level == $account_level->id ? 'selected' : ''}}>{{$account_level->name}}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                        </div>
                        <div class="footer d-flex justify-content-center align-items-center">
                            <a href="{{route('user.index')}}" class="btn btn-danger hidden-xs w-25 ml-2" type="button">Cancel</a>
                            <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{ !isset($user) ? "Create" : "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('assets/custom/js/user.js')}}"></script>
    </x-slot>
   
</x-app-layout>
