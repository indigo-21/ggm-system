@extends('pdf.print-pdf-layout')

@section("content")
    {{-- <div class="divider"></div> --}}

        <div style="padding:10px 10px;text-align:center;"> 
            <h2>{{$locationName}} Receipt</h2>
        </div>

    {{-- <div class="divider"></div> --}}

        <table class="table-bordered-none">
            <tr>
                <td colspan="2"><strong>Customer Name:</strong> {{ $customerName }} </td>
                <td colspan="2" style="text-align:right"><strong>Date:</strong> {{$paymentDate}}</td>

            </tr>
            <tr>
                <td colspan="4"><strong>Address: </strong> {{ $customerAddress }} </td>
            </tr>
            <tr>
                {{-- <td><strong>Time: </strong> testings</td> --}}
                <td colspan="2"><strong>Cemetery: </strong> testings</td>
                <td colspan="2" style="text-align:right"><strong>Grave No.:</strong> testings</td>
            </tr>
            <tr>
                <td colspan="4" style="padding:20px">
                    <center>The Memorial of the late {{$deceasedName}} </center>
                </td>
            </tr>
        </table>

    {{-- <div class="divider"></div> --}}

        <table class="table-bordered-none">
            <tr>
                <td style="font-weight:bold; width: 120px;">Order Amount: </td>
                <td colspan="3" style="text-align: right" >{{$orderAmount}}</td>
            </tr>
            <tr>
                <td style="font-weight:bold; width: 120px;">Amount Paid: </td>
                <td colspan="3" style="text-align: right">{{$amountPaid}}</td>
            </tr>
            <tr>
                <td style="font-weight:bold; width: 120px;">Outstanding Amount: </td>
                <td colspan="3" style="text-align: right">{{$outstandingAmount}}</td>
            </tr>
        </table>

        <table class="table-bordered" style="border:1px solid black;border-radius:10px;margin-top:10px; padding:10px;">
            <tr>
                <th style="width:30%;">Timestamp</th>
                <th style="width:20%;">Method</th>
                <th style="width:20%;">Amount</th>
                <th>Remarks</th>
            </tr>
            @foreach ($paymentData as $payment)
                <tr>
                    <td style="text-align:center;">{{ $payment["timestamp"] }} </td>
                    <td> {{ $payment["method"] }}</td>
                    <td style="font-weight:bold; width: 120px; text-align:right;">{{ $payment["amount"] }} </td>
                    <td>{{$payment["comment"]}}</td>
                </tr>
            @endforeach
            
            
        </table>
    

@endsection

