 <!-- For Photos Modal -->
 <div class="modal fade" id="orderDocumentsModal" tabindex="-1" role="dialog">
     <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="title" id="orderDocumentsModalLabel">Order Documents</h4>
             </div>
             <div class="modal-body" id="orderDocumentsModalBody">
                 <div class="order-document-form row">
                     <div class="col-12">
                         <x-input type="file" name="document_file" value="" label="Browse File" />
                     </div>
                     <div class="col-12">
                         <x-input type="text" name="document_filename" value="" label="File Name" />
                     </div>
                     <div class="col-12">
                         <x-input type="textarea" name="document_description" value="" label="Description" />
                     </div>
                     <div class="col-12 text-center py-4">
                         <button type="button" class="btn btn-danger btn-simple waves-effect w-25"
                             id="upload_documents_btn" order_id="{{ $quote?->id ?? '' }}">Upload</button>
                     </div>
                 </div>
                 <div class="order-docuent-table row">
                     <div class="col-12 mt-4 border-top">
                         <h5 class="title py-3">List of Documents</h5>
                         <table class="table table-bordered table-striped table-hover dataTable"
                             id="orderDocumentsTable" style="font-size:90%">
                             <thead>
                                 <tr>
                                     <th>File</th>
                                     <th>Description</th>
                                     <th>Email</th>
                                     <th style="width:10%;">Timestamp</th>
                                     <th style="width:10%;">Action</th>
                                 </tr>
                             </thead>
                             <tbody id="orderDocumentsTableBody">
                                @foreach ($quote->order_files as $document )
                                   @if ($document->file_type == "2")
                                        <tr class="file-item">
                                            <td>
                                                <a href="{{asset("storage/$document->filepath")}}" target="_blank">{{$document->filename}}</a>
                                            </td>
                                            <td>{{$document->description}}</td>
                                            <td>
                                                <div class="checkbox w-25">
                                                    <input id="document_is_no_email_checked" name="document_is_no_email_checked" type="checkbox" order_file_id="{{$document->id}}"  {{ $document->attach_email == '1' ? 'checked' : '' }}>
                                                    <label for="is_no_email_checked" class="ml-2">
                                                            Email
                                                    </label>
                                                </div>
                                            </td>
                                            <td>{{ $document->created_at->format('F d, Y h:m A') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-xs delete-order-photo-btn"
                                                        order_file_id="{{$document->id}}">Delete</button>
                                            </td>
                                        </tr>
                                   @endif
                                @endforeach
                             </tbody>
                         </table>
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
