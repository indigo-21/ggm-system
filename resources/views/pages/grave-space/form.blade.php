<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('grave_space.index')}}">Grave Space</a></li>
                            <li class="breadcrumb-item active">{{ !isset($grave_space) ? "Creating" : "Updating" }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{ !isset($grave_space) ? "Create New Grave Space" : "Update ".$grave_space->name }} </h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Grave Space</button> --}}
                        <a href="{{route('grave_space.index')}}" class="btn btn-secondary hidden-xs ml-2 px-5">Back</a>
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
                    <form id="form_validation" method="POST" action="{{!isset($grave_space) ? route('grave_space.store') : route("grave_space.update", $grave_space->id)}}">
                        @csrf
                        @if(isset($grave_space))
                            @method("PUT")
                        @endif
                        <div class="body">
                            <x-input type="text" name="name" value="{{ $grave_space?->name ?? old('name') }}" inputformat="alphanumeric" label="Grave Space Name" :required="true" :error="$errors->first('name')"/>
                        </div>
                        <div class="footer mb-3 d-flex justify-content-center align-items-center">
                            @if (isset($grave_space))
                                <button class="btn btn-danger hidden-xs w-25 ml-2" id="soft-delete" type="button" label="{{$grave_space->name}}" route="{{ route('grave_space.destroy', $grave_space->id) }}" landing_page="{{route('grave_space.index')}}">Delete</button>
                            @endif

                            <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{ !isset($grave_space) ? "Create" : "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('assets/custom/js/grave_space.js')}}"></script>
    </x-slot>
   
</x-app-layout>
