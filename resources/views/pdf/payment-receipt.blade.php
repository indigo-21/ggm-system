@extends('pdf.print-pdf-layout')

@section("content")
    <div class="divider"></div>
        <div style="padding:10px 10px;text-align:center;"> 
            <h2>{{$locationName}} Receipt</h2>
        </div>
    <div class="divider"></div>
        <table class="table-bordered">
            <tr>
                <td colspan="4"><strong>Customer Name: </strong>{{ $customerName }} </td>
            </tr>
            <tr>
                <td colspan="4"><strong>Address: </strong> {{ $customerAddress }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Date:</strong> {{$paymentDate}}</td>
                {{-- <td><strong>Time: </strong> testings</td> --}}
                <td><strong>Cemetery: </strong> testings</td>
                <td><strong>Grave No.:</strong> testings</td>
            </tr>
            <tr>
                <td colspan="4">
                    <center>The Memorial of the late {{$deceasedName}} </center>
                </td>
            </tr>
        </table>
    <div class="divider"></div>
        <table class="table-bordered">
            @foreach ($paymentData as $payment)
                <tr>
                    <td style="font-weight:bold; width: 120px;">Amount Received: </td>
                    <td colspan="3" style="text-align: right" >{{$payment["amount"]}}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold; width: 120px;">Method: </td>
                    <td colspan="3" style="text-align: right">{{$payment["method"]}}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold; width: 120px;">Comment: </td>
                    <td colspan="3" style="text-align: right">{{$payment["comment"]}}</td>
                </tr>
            @endforeach
        </table>
    

@endsection

