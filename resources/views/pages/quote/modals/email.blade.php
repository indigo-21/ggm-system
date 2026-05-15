<!-- For Email Modal -->
<div class="modal fade" id="orderEmailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="orderEmailModalLabel">Order Emails</h4>
            </div>
            <div class="modal-body" id="orderEmailModalBody">
                <div class="payment-form row">
                    <div class="col-12">
                        <x-input type="text" name="order_email_to" inputformat="specialcharacter" value=""
                            label="Email Address" />
                    </div>

                    <div class="col-12">
                        <x-input type="textarea" name="order_email_body" value="" label="Email Details"
                            rows="10" />
                    </div>

                    <div class="col-12 mt-4 border-top pt-2">
                        <h5>Attachments</h5>
                        <div class="attachtment-container d-flex align-items-center justify-content-start flex-wrap">
                            <x-input type="checkbox" name="is_info_timescale_checked" value=""
                                label="Info and Timescale" />

                            <x-input type="checkbox" name="is_quotation_checked" value="" label="Quotation" />

                            <x-input type="checkbox" name="is_order_checked" value="" label="Order" />

                            <x-input type="checkbox" name="is_terms_and_conditions_checked" value=""
                                label="Terms and Conditions" />

                            <x-input type="checkbox" name="is_document_insurance_checked" value=""
                                label="Document template Stoneguard" />

                            <x-input type="checkbox" name="is_insurance_checked" value="" label="Insurance" />

                            @isset($order_inscription)
                                <x-input type="checkbox" name="is_inscription_checked" value="" label="Inscription" />
                            @endisset

                            @if (count($order_payments) > 0)
                                <x-input type="checkbox" name="is_receipts_checked" value="" label="Receipts" />
                                <x-input type="checkbox" name="is_statement_checked" value="" label="Statement" />
                            @endif
                            @php

                                $orderFiles = $quote->order_files;
                                $hasPhotos = 0;
                                $hasDocuments = 0;
                                $hasWorkingFiles = 0;

                                foreach ($orderFiles as $orderFile) {
                                    // Skip invalid or missing files
                                    if ($orderFile->attach_file != 1) {
                                        switch ($orderFile->file_type) {
                                            case 1:
                                                $hasPhotos += 1;
                                                break;

                                            case 2:
                                                $hasDocuments += 1;
                                                break;

                                            case 3:
                                                $hasWorkingFiles += 1;
                                                break;
                                        }
                                    }
                                }

                            @endphp
                            
                            @if ($hasPhotos > 0)
                                <x-input type="checkbox" name="is_photos_checked" value="" label="Photos" />
                            @endif

                            @if ($hasDocuments > 0)
                                <x-input type="checkbox" name="is_documents_checked" value="" label="Documents" />
                            @endif

                            @if ($hasWorkingFiles > 0)
                                <x-input type="checkbox" name="is_working_files_checked" value=""
                                    label="Working Files" />
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <h5>Email Template</h5>
                        <div class="email-template-container d-flex align-items-center justify-content-start flex-wrap">
                            <x-input type="checkbox" name="is_stoneguard_checked" value="1" label="Stoneguard"
                                class="email-template" />
                            <x-input type="checkbox" name="is_washdown_checked" value="2" label="Washdown"
                                class="email-template" />
                        </div>
                    </div>

                    <div class="col-12">
                        <h5>Review Template</h5>
                        <div class="review-template d-flex align-items-center justify-content-start flex-wrap">
                            <x-input type="checkbox" name="is_new_memorial_checked" value="3"
                                label="Review – New memorial" class="email-template" />
                            <x-input type="checkbox" name="is_renovation_checked" value="4"
                                label="Review – Renovation" class="email-template" />
                            <x-input type="checkbox" name="is_added_inscription_checked" value="5"
                                label="Review – Added inscription" class="email-template" />
                        </div>
                    </div>

                    <div class="col-12 py-3 text-center">
                        <button type="button" class="btn btn-danger btn-simple waves-effect w-25"
                            id="save_order_email_btn" order_id="{{ $quote?->id ?? '' }}">Send</button>
                    </div>

                </div>

                <table class="table table-bordered table-striped table-hover dataTable" id="emailTable"
                    style="font-size:90%">
                    <thead>
                        <tr>
                            <th style="width:10%;">Date Time</th>
                            <th style="width:15%;">User</th>
                            <th>Email To</th>
                            <th style="width:10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="emailTableBody">
                        @foreach ($quote->order_emails as $orderMail)
                            <tr>
                                <td>{{$orderMail->created_at->format("F d, Y h:m A")}}</td>
                                <td>{{$orderMail->user->firstname}} {{$orderMail->user->lastname}}</td>
                                <td>{{ $orderMail->mail_to }}</td>
                                <td>
                                    <a type="button" href="{{$orderMail->id}}" class="btn btn-danger btn-simple waves-effect w-100"
                                         >View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-simple waves-effect"
                    data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
