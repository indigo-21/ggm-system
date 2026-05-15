<!-- For Payments(Receipts Button) Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="paymentModalLabel">Order Payments</h4>
            </div>
            <div class="modal-body" id="paymentModalBody">
                <h3>Payment History</h3>
                <div class="payment-form row">
                    <div class="col-4">
                        <x-input type="text" name="payment_timestamp" value=""
                            class="daterange-timestamp clear-daterange" label="Payment Date & Time" />
                    </div>
                    <div class="col-4">
                        <x-select class="z-index show-tick" name="payment_method" label="Payment Method"
                            :required="true">
                            <option value="" disabled selected>-Select Payment Method-</option>
                            @foreach ($payment_methods as $payment_method)
                                <option value="{{ $payment_method['id'] }}">
                                    {{ $payment_method['name'] }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="col-4">
                        <x-input type="text" class="text-right cost-computation" name="payment_amount" value=""
                            label="Price Amount" />
                    </div>
                    <div class="col-12">
                        <x-input type="textarea" name="payment_comment" value="" label="Comment" />
                    </div>

                    <div class="col-12 py-3 text-center">
                        <a href="{{ url('pdf/payment_statement/' . $quote->id) }}" target="_blank" type="button" class="btn btn-outline-danger btn-simple waves-effect w-25" id="save_payment_btn"
                            order_id="{{ $quote?->id ?? '' }}">Statement</a>
                        <button type="button" class="btn btn-outline-danger btn-simple waves-effect w-25" id="save_payment_btn"
                            order_id="{{ $quote?->id ?? '' }}">Save</button>
                    </div>

                </div>

                <table class="table table-bordered table-striped table-hover dataTable" id="paymentTable"
                    style="font-size:90%">
                    <thead>
                        <tr>
                            <th style="width:15%;">User</th>
                            <th style="width:10%;">Date Time</th>
                            <th style="width:10%;">Method</th>
                            <th>Amount</th>
                            <th>Comment</th>
                            <th style="width:10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="paymentTableBody">
                        @foreach ($order_payments as $order_payment)
                            <tr>
                                <td>{{ $order_payment->created_user->firstname ?? '' }}
                                    {{ $order_payment->created_user->lastname ?? '' }}</td>
                                <td>{{ date('F d, Y h:i A', strtotime($order_payment->payment_datetime)) ?? '' }}</td>
                                <td>
                                    @switch($order_payment->payment_method)
                                        @case(1)
                                            Cash
                                        @break

                                        @case(2)
                                            Cheque
                                        @break

                                        @case(3)
                                            Credit Card
                                        @break

                                        @case(4)
                                            Bank Transfer
                                        @break

                                        @default
                                            Debit Card
                                    @endswitch
                                </td>
                                <td class="text-right">{{ number_format($order_payment->amount, 2) }}</td>
                                <td>{{ $order_payment->comment }}</td>
                                <td class="text-center">
                                    <a type="button" class="btn btn-danger btn-xs"
                                        href="{{ url('pdf/payment_receipt/' . $order_payment->id) }}"
                                        target="_blank">Print Receipt</a>
                                    <button type="button" class="btn btn-danger btn-xs order_payment_destroy"
                                        order_payment_id="{{ $order_payment->id }}">Delete</button>
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
