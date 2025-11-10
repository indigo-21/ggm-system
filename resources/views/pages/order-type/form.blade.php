<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('accessory.index')}}">Accessory</a></li>
                            <li class="breadcrumb-item active">{{ !isset($accessory) ? "Creating" : "Updating" }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{ !isset($accessory) ? "Create New Accessory" : "Update ".$accessory->name }} </h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Accessory</button> --}}
                        <a href="{{route('accessory.index')}}" class="btn btn-secondary hidden-xs ml-2 px-5">Back</a>
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
                    <form id="form_validation" method="POST" action="{{!isset($accessory) ? route('accessory.store') : route("accessory.update", $accessory->id)}}">
                        @csrf
                        @if(isset($accessory))
                            @method("PUT")
                        @endif
                        <div class="body">
                            <x-input type="text" name="name" value="{{ $accessory?->name ?? old('name') }}" inputformat="alphanumeric" label="Accessory Name" :required="true" :error="$errors->first('name')"/>
                        </div>
                        <div class="footer mb-3 d-flex justify-content-center align-items-center">
                            @isset($accessory)
                                <button class="btn btn-danger hidden-xs w-25 ml-2" id="soft-delete" type="button" label="{{$accessory->name}}" route="{{ route('accessory.destroy', $accessory->id) }}" landing_page="{{route('accessory.index')}}">Delete</button>
                            @endisset
                            <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{ !isset($accessory) ? "Create" : "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('assets/custom/js/accessory.js')}}"></script>
    </x-slot>
   
</x-app-layout>
