@extends('pdf.print-pdf-layout')

@section("content")
    <div class="divider"></div>

    <!-- Two-column Contact Section -->
    <table class="contact-table">
        <tr>
            <td>
                <div class="table-data"<strong>Order Date: </strong> {{$orderDate}} </div> 
                <div class="table-data"<strong>Last Amended:</strong> {{$lastAmended}}</div> 
                <div class="table-data"<strong>Customer Name:</strong> {{$customerName}} </div> 
                <div class="table-data"<strong>Deceased Name:</strong> {{$deceasedName}} </div>
            </td>

            <td>
                <div class="table-data" <strong>Reference: </strong> {{$reference}}  </div>
                <div class="table-data" <strong>Consecration:</strong> {{$consecrationDate}} </div>
                <div class="table-data" <strong>Cemetery:</strong> {{$cemetery}} </div>
                <div class="table-data" <strong>Grave No.:</strong> {{$graveNumber}} </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="center-text" style="margin-top:30px;">
        {!! $inscription !!}
    </div>

@endsection

