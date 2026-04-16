<!-- For Photos Modal -->
<div class="modal fade" id="orderPhotosModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="orderPhotosModalLabel">Order Photos</h4>
            </div>
            <div class="modal-body" id="orderPhotosModalBody">
                <div class="row">
                    <div class="col-12">
                        <x-input type="file" name="order_photos" value="" label="Upload Photos"
                            multiple="multiple" />
                    </div>
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-danger btn-simple waves-effect w-25"
                            id="upload_photos_btn" order_id="{{ $quote?->id ?? '' }}">Upload</button>
                    </div>
                    <div class="col-12 mt-4 border-top">
                        <h5 class="title py-3">List of Photos</h5>
                        <div class="photo-gallery row" id="photoGallery">
                            @foreach ($quote->order_files as $photo )
                                @if ($photo->file_type == "1")
                                    <div class="col-4 file-item">
                                        <div class="card">
                                            <img src="{{ asset("storage/$photo->filepath") }}"
                                                class="card-img-top" alt="Order Photo">
                                            <div
                                                class="card-body text-center d-flex align-items-center justify-content-center flex-wrap">
                                                
                                                <button type="button" class="btn btn-danger btn-xs delete-order-photo-btn"
                                                    order_file_id="{{ $photo->id }}">Delete</button>
                                                <x-input type="checkbox" class="mx-3 w-50" name="is_no_email_checked"
                                                    value="" label="No Email" order_file_id="{{ $photo->id }}" />
                                                <button type="button" class="btn btn-danger btn-xs rotate-photo-btn"
                                                    order_file_id="">Rotate</button>
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
