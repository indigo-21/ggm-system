<x-app-layout>
    <x-slot name="header">
        <div class="block-header">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <ul class="breadcrumb pl-0 pb-0 ">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Masterfile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('letter_type.index')}}">Letter Type</a></li>
                            <li class="breadcrumb-item active">{{ !isset($letter_type) ? "Creating" : "Updating" }} Form</li>
                        </ul>
                        <h1 class="mb-1 mt-1">{{ !isset($letter_type) ? "Create New Letter Type" : "Update ".$letter_type->name }} </h1>
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
                    </div>            
                    <div class="col-lg-6 col-md-12 text-md-right">
                        {{-- <button class="btn btn-default hidden-xs ml-2">Create Letter Type</button> --}}
                        <a href="{{route('letter_type.index')}}" class="btn btn-secondary hidden-xs ml-2 px-5">Back</a>
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
                    <form id="form_validation" method="POST" action="{{!isset($letter_type) ? route('letter_type.store') : route("letter_type.update", $letter_type->id)}}">
                        @csrf
                        @if(isset($letter_type))
                            @method("PUT")
                        @endif
                        <div class="body">
                            <x-input type="text" name="name" value="{{ $letter_type?->name ?? old('name') }}" inputformat="alphanumeric" label="Letter Type Name" :required="true" :error="$errors->first('name')"/>
                        </div>
                        <div class="footer mb-3 d-flex justify-content-center align-items-center">
                            @if (isset($letter_type))
                                <button class="btn btn-danger hidden-xs w-25 ml-2" id="soft-delete" type="button" label="{{$letter_type->name}}" route="{{ route('letter_type.destroy', $letter_type->id) }}" landing_page="{{route('letter_type.index')}}">Delete</button>
                            @endif
                            <button class="btn btn-primary hidden-xs w-25 ml-2" type="submit">{{ !isset($letter_type) ? "Create" : "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('assets/custom/js/letter_type.js')}}"></script>
    </x-slot>
   
</x-app-layout>
