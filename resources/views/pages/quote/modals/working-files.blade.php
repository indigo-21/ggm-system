<div class="modal fade" id="orderWorkingFilesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="orderWorkingFilesModalLabel">Order Working Files</h4>
            </div>
            <div class="modal-body" id="orderWorkingFilesModalBody">
                <div class="order-working-files-form row">
                    {{-- <div class="col-4">
                        <x-input type="text" name="workingfiles_date" value="" label="Date" />
                    </div> --}}
                    <div class="col-6">
                        <x-input type="file" name="workingfiles_file" value="" label="Browse File" />
                    </div>
                    <div class="col-6">
                        <x-input type="text" name="workingfiles_filename" value="" label="File Name" />
                    </div>
                    <div class="col-12">
                        <x-input type="textarea" name="workingfiles_description" value="" label="Description" />
                    </div>
                    <div class="col-12 text-center py-4">
                        <button type="button" class="btn btn-danger btn-simple waves-effect w-25"
                            id="upload_workingfiles_btn" order_id="{{ $quote?->id ?? '' }}">Upload</button>
                    </div>
                </div>
                <div class="order-docuent-table row">
                    <div class="col-12 mt-4 border-top">
                        <h5 class="title py-3">List of Working Files</h5>
                        <div class="working-files-gallery row">
                           @foreach ($quote->order_files as $workfile )
                               @if ($workfile->file_type == "3")
                                    <div class="col-4 file-item">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">{{ $workfile->created_at->format("F d, Y h:m A") }}</h5>
                                            </div>
                                            <center class="py-2">
                                                <a href="{{ asset("storage/".$workfile->filepath) }}">
                                                    <i class="zmdi zmdi-file-text"></i> &nbsp; {{ \Illuminate\Support\Str::after($workfile->filename, '_') }}
                                                </a>
                                            </center>
                                            <div
                                                class="card-body text-center d-flex align-items-center justify-content-center flex-wrap">
                                                <button type="button"
                                                    class="btn btn-danger btn-xs delete-order-photo-btn"
                                                    order_file_id="{{$workfile->id}}">Delete</button>
                                                <x-input type="checkbox" class="mx-3 w-50" name="is_no_email_checked"
                                                    value="" label="No Email" />
                                            </div>
                                        </div>
                                    </div>
                               @endif
                           @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-simple waves-effect"
                    data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
