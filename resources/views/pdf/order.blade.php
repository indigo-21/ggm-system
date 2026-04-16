@extends('pdf.print-pdf-layout')

@section("content")
    <div class="divider"></div>
       <center> <h2>{{$orderData->location->name}} Order {{ $orderData->id }}</h2></center>
    <div class="divider"></div>
        <table class="table-bordered">
            <tr>
                <th colspan="2" style="font-weight:bold;text-align:left"><h2>Customer Details</h2></th>
            </tr>
            <tr>
                <td><strong>Customer Name: </strong>{{ $customerData->firstname }} {{ $customerData->lastname }} </td>
                <td><strong>Order Date: </strong> {{$orderDate}} </td>
            </tr>
            <tr>
                <td rowspan="3"><strong>Address: </strong> {{ $customerAddress }} </td>
                <td><strong>Email: </strong> 
                    @foreach ($customerData->customer_contacts as $contact )
                        @if ($contact->contact_type == 1)
                            {{$contact->contact_value}} <br>
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td><strong>Tel No: </strong> 
                    @foreach ($customerData->customer_contacts as $contact )
                        @if ($contact->contact_type == 3)
                            {{$contact->contact_value}} <br>
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td><strong>Mobile No: </strong> 
                    @foreach ($customerData->customer_contacts as $contact )
                        @if ($contact->contact_type == 2)
                            {{$contact->contact_value}} <br>
                        @endif
                    @endforeach
                </td>
            </tr>
        </table>

        <table class="table-bordered">
            <tr>
                <th colspan="2" style="text-align:left">
                    <h2>Order Details</h2>
                </th>
            </tr>
            <tr>
                <td><strong>Deceased: </strong> {{$orderData->deceased_name}}</td>
                <td><strong>Date of Death: </strong> {{ \Carbon\Carbon::parse($orderData->date_or_death)?->format("F d, Y A") }} </td>
            </tr>
            <tr>
                <td><strong>Consecration: </strong> {{ \Carbon\Carbon::parse($orderData->consecration_date)?->format("F d, Y") }}</td>
                <td><strong>Cemetery: </strong> {{$orderData->cemetery->name ?? ''}} </td>
            </tr>
            <tr>
                <td><strong>Grave No.: </strong> {{$orderData->grave_number}}</td>
                <td><strong>Burial Society Organization: </strong> {{ $orderData->burial_society_organization->name }} </td>
            </tr>
            <tr>
                <td><strong>Material: </strong> {{$orderData->material}}</td>
                <td><strong>Grave Space: </strong> {{$orderData->grave_space->name}} </td>
            </tr>
            <tr>
                <td><strong>Colour: </strong> {{$orderData->material_colour}}</td>
                <td><strong>Letter Type: </strong> {{$orderData->letter_type}} </td>
            </tr>
            <tr>
                <td><strong>Design/Headstone: </strong> {{$orderData->design_headstone}}</td>
                <td><strong>Kerbs/Risers: </strong> {{$orderData->kerb_riser}} </td>
            </tr>
             <tr>
                <td><strong>Size: </strong> {{$orderData->size}}</td>
                <td><strong>Accessories: </strong> {{$orderData->accessory}} </td>
            </tr>
             <tr>
                <td><strong>Based Ledger: </strong> {{$orderData->based_ledger}}</td>
                <td><strong>Colour </strong> {{$orderData->accessory_colour}} </td>
            </tr>
        </table>
        
        <br><br><br>

        <table class="table-bordered">
            <tr>
                <th style="width:50%;"><h2>Cost</h2></th>
                <th style="width:50%;"><h2>Customer Notes</h2></th>
            </tr>
   
            <tr>
                @if ($orderCost->description && $orderCost->amount)
                    <td class="no-border" style="border-left:1px solid black;border-bottom:1px solid black;border-top:1px solid black;">
                        <table style="width:100%;">
                            <tr>
                                <td style="padding:0px;border:none;">{{ $orderCost->description }}</td>
                                <td style="padding:0px;border:none;text-align:right;">£ {{$orderCost->amount ? number_format($orderCost->amount, 2) : "0" }}</td>
                            </tr>
                        </table>
                    </td>
                @else
                    <td> </td>
                @endif
                    <td rowspan="8"  style="border:1px solid black">{{$orderData->customer_notes}}</td>
                </tr>

            @if ($orderCost->letter_count && $orderCost->letter_amount)
                <tr>
                    <td class="no-border" style="border:1px solid black;">
                        <table style="width:100%;">
                            <tr>
                                <td style="padding:0px;border:none;">{{$orderCost->letter_count}} Letters @ £ {{ number_format($orderCost->letter_amount, 2) }}</td>
                                <td style="padding:0px;border:none;text-align:right;">£ {{$orderCost->letter_total_amount ? number_format($orderCost->letter_total_amount, 2) : "0.00"}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif

            @foreach ($orderCost->additionals as $additional )
                <tr>
                    <td class="no-border" style="border:1px solid black;">
                        <table style="width:100%;">
                            <tr>
                                <td style="padding:0px;border:none;">{{$additional->description}}</td>
                                <td style="padding:0px;border:none;text-align:right;">£ {{$additional->amount ? number_format($additional->amount, 2) : "0" }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td class="no-border" style="border:1px solid black;">
                    <table style="width:100%;">
                        <tr>
                            <td style="padding:0px;border:none;"><strong>Grand Total</strong></td>
                            <td style="padding:0px;border:none;text-align:right;">£ {{ number_format($orderCost->grand_total, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="no-border" style="border:1px solid black;">
                    <table style="width:100%;">
                        <tr>
                            <td style="padding:0px;border:none;"><strong>Deposit: </strong> {{$orderDeposit->comment}}</td>
                            <td style="padding:0px;border:none;text-align:right;">- £ {{$orderDeposit ? number_format($orderDeposit->amount, 2) : "0" }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    <div class="divider"></div>

    <br>
    <h3>NOTES</h3> 
    Price includes cemetery fees, VAT & 100 letters, extra letters @ £2.70 a letter <br>
    We will conduct an inspection to see if there are any renovations that need doing to Libby's memorial and let you know accordingly. <br>
    Extra letters are charged at £2.70 Each inc VAT.<br>
    Burial Society and Foundation Fees (for members) are also included.<br>
    All memorials to be paid for prior to erection in the Cemetery.<br>
    Free 6 months insurance with invoice - To receive this offer please advise, by telephone or email, if you would like us to pass on your details to Stoneguard.<br>
    All goods remain the property of Gary Green Memorials until paid for in full.<br>
    Gary Green Memorials order marble and granite materials at the time the customer places the order, therefore when a replica or convert memorial is required, there might <br>
    be differences in the marble graining or the colour of the granite.<br>
    Please make us aware or remove any items on the grave that are valuable to yourself.<br>
    When ordering Marble, as it is a porous material it is susceptible to Brown and Orange stains caused by the elements.<br>
    Unfortunately, once stained these marks cannot be removed but over time may fade through oxidisation from the sun.<br>
    This document was produced on {{$printDate}}

    <br><br>

    <h3>DECLARATION</h3>
    I the undersigned, agree that the memorial detailed above is to my specification. 

    <br><br>

    <table class="table-bordered">
        <tr>
            <td class="no-border"><strong>Signed: </strong> _________________________________ </td>
            <td class="no-border"><strong>Dated: </strong> _________________________________ </td>
        </tr>
    </table>
    

@endsection

