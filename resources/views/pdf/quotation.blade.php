@extends('pdf.print-pdf-layout')

@section('content')
    <div style="font-size:80%;"
        <table class="table-bordered">
            <tr>
                <td colspan="3"><strong>Customer Name: </strong>{{ $customerName }} </td>
                <td><strong>Date: </strong>{{ $printDate }} </td>
            </tr>
            <tr>
                <td colspan="3"><strong>Address: </strong> {{ $customerAddress }} </td>
                <td><strong>Ref: </strong> {{ $orderReference }} </td>
            </tr>
        </table>
        <br><br>
        <strong>Dear {{ $customerFirstname }}, </strong>
        <br>
        <center>
            Memorial of the late {{ $deceasedName }}
            <br>
            <span style="text-decoration:underline;">{{ $cemeteryName }} {{ $graveNumber }}</span>
        </center>
        <br>
        I refer to your enquiry regarding the above Memorial. <br>
        Please find detailed below our quotation:
        <br>
        <br>
        <table class="table-bordered">
            <tr>
                <td colspan="4" style="text-align:center; font-weight:bold;">{{ $headStone }} - {{ $headStoneSize }} -
                    {{ $material }}</td>
            </tr>
            @if ($orderCost->description && $orderCost->amount)
                <tr>
                    <td class="no-border" colspan="2">{{ $orderCost->description }}</td>
                    <td class="no-border" colspan="2" style="text-align:right"> £
                        {{ $orderCost->amount ? number_format($orderCost->amount, 2) : '0' }}</td>

                </tr>
            @endif
            @if ($orderCost->letter_count && $orderCost->letter_amount)
                <tr>
                    <td class="no-border" colspan="2">{{ $orderCost->letter_count }} Letters @ £
                        {{ number_format($orderCost->letter_amount, 2) }}</td>
                    <td class="no-border" colspan="2" style="text-align:right;">£ {{ $orderCost->letter_total_amount }}
                    </td>
                </tr>
            @endif

            @foreach ($orderCostAdditionals as $additional)
                <tr>
                    <td class="no-border" colspan="2">{{ $additional->description }}</td>
                    <td class="no-border" colspan="2" style="text-align:right">£
                        {{ number_format($additional->amount, 2) }}</td>
                </tr>
            @endforeach
        </table>
        <br>
        <strong>{{ $orderAdditionalNote }}</strong>
        <br><br>
        When deciding to place an order a deposit of £1,500.00 will be required. <br>
        Please do not hesitate to contact me should you require any further information regarding this quotation. <br>
        I assure you of our best attention at all times. <br><br>
        Yours sincerely
        <br><br>

        <h3>GARY GREEN MEMORIALS - {{ $locationName }}</h3>
        <br>
        If paying deposit by bank transfer details as follows <br>
        Barclays Bank / Account Name: Gary Green Monumental Mason Limited <br>
        Account No: 70390909 / Sort Code: 20-44-22 <br>
        Please include your reference number when paying <br>
        Cheque?s to be made payable to: Gary Green Monumental Mason Ltd <br>
        This quote is valid for 30 days <br>

    </div>
@endsection
