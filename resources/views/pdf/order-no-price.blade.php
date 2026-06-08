@extends('pdf.print-pdf-layout')

@section('content')
    <div style="font-size:100%;">
        <center>
            <h2>{{ $orderData->location->name }} Order {{ $orderData->id }}</h2>
        </center>
        <table class="table-bordered-none">
            <tr>
                <th colspan="2" style="font-weight:bold;text-align:left">
                    <h2>Customer Details</h2>
                </th>
            </tr>
            <tr>
                <td><strong>Customer Name: </strong>{{ $customerData->firstname }} {{ $customerData->lastname }} </td>
                <td><strong>Order Date: </strong> {{ $orderDate }} </td>
            </tr>
            <tr>
                <td rowspan="3"><strong>Address: </strong> {{ $customerAddress }} </td>
                <td><strong>Email: </strong>
                    @foreach ($customerData->customer_contacts as $contact)
                        @if ($contact->contact_type == 1)
                            {{ $contact->contact_value }} <br>
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td><strong>Tel No: </strong>
                    @foreach ($customerData->customer_contacts as $contact)
                        @if ($contact->contact_type == 3)
                            {{ $contact->contact_value }} <br>
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td><strong>Mobile No: </strong>
                    @foreach ($customerData->customer_contacts as $contact)
                        @if ($contact->contact_type == 2)
                            {{ $contact->contact_value }} <br>
                        @endif
                    @endforeach
                </td>
            </tr>
        </table>

        {{-- <table class="table-bordered-none">
            <tr>
                <th colspan="2" style="text-align:left">
                    <h2>Order Details</h2>
                </th>
            </tr>
            <tr>
                <td><strong>Deceased: </strong> {{ $orderData->deceased_name }}</td>
                <td><strong>Date of Death: </strong>
                    {{ \Carbon\Carbon::parse($orderData->date_or_death)?->format('F d, Y A') }} </td>
            </tr>
            <tr>
                <td><strong>Consecration: </strong>
                    {{ \Carbon\Carbon::parse($orderData->consecration_date)?->format('F d, Y') }}</td>
                <td><strong>Cemetery: </strong> {{ $orderData->cemetery->name ?? '' }} </td>
            </tr>
            <tr>
                <td><strong>Grave No.: </strong> {{ $orderData->grave_number }}</td>
                <td><strong>Burial Society Organization: </strong> {{ $orderData->burial_society_organization->name }}
                </td>
            </tr>
            <tr>
                <td><strong>Material: </strong> {{ $orderData->material }}</td>
                <td><strong>Grave Space: </strong> {{ $orderData->grave_space->name }} </td>
            </tr>
            <tr>
                <td><strong>Colour: </strong> {{ $orderData->material_colour }}</td>
                <td><strong>Letter Type: </strong> {{ $orderData->letter_type }} </td>
            </tr>
            <tr>
                <td><strong>Design/Headstone: </strong> {{ $orderData->design_headstone }}</td>
                <td><strong>Kerbs/Risers: </strong> {{ $orderData->kerb_riser }} </td>
            </tr>
            <tr>
                <td><strong>Size: </strong> {{ $orderData->size }}</td>
                <td><strong>Accessories: </strong> {{ $orderData->accessory }} </td>
            </tr>
            <tr>
                <td><strong>Based Ledger: </strong> {{ $orderData->based_ledger }}</td>
                <td><strong>Colour </strong> {{ $orderData->accessory_colour }} </td>
            </tr>
        </table> --}}
        <table class="table-bordered-none">
            <tr>
                <th colspan="3" style="text-align:left">
                    <h2>Order Details</h2>
                </th>
            </tr>
            <tr>
                <td class="flext-td">
                    <div class="label-left">Deceased: </div>
                    <div class="label-right">{{ $orderData->deceased_name }}</div>
                </td>
                <td class="flext-td">
                    <div class="label-left">Date of Death: </div>
                    <div class="label-right"> {{ \Carbon\Carbon::parse($orderData->date_or_death)?->format('F d, Y A') }}
                    </div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Consecration: </div>
                    <div class="label-right">
                        {{ \Carbon\Carbon::parse($orderData->consecration_date)?->format('F d, Y') }}

                    </div>
                </td>
            </tr>
            <tr>

                <td class="flex-td">
                    <div class="label-left">Cemetery: </div>
                    <div class="label-right">{{ $orderData->cemetery->name ?? '' }}</div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Grave No.: </div>
                    <div class="label-right">{{ $orderData->grave_number }}</div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Burial Society Organization: </div>
                    <div class="label-right">
                        {{ $orderData->burial_society_organization->name }}

                    </div>
                </td>
            </tr>
            <tr>
                <td class="flex-td">
                    <div class="label-left">Material: </div>
                    <div class="label-right">
                        {{ $orderData->material }}
                    </div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Grave Space: </div>
                    <div class="label-right">
                        {{ $orderData->grave_space->name }}
                    </div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Colour: </div>
                    <div class="label-right">
                        {{ $orderData->material_colour }}
                    </div>
                </td>
            </tr>
            <tr>

                <td class="flex-td">
                    <div class="label-left">Letter Type: </div>
                    <div class="label-right">
                        {{ $orderData->letter_type }}
                    </div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Design/Headstone: </div>
                    <div class="label-right">
                        {{ $orderData->design_headstone }}
                    </div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Kerbs/Risers: </div>
                    <div class="label-right">
                        {{ $orderData->kerb_riser }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="flex-td">
                    <div class="label-left">Size: </div>
                    <div class="label-right">
                        {{ $orderData->size }}
                    </div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Accessories: </div>
                    <div class="label-right">
                        {{ $orderData->accessory }}
                    </div>
                </td>
                <td class="flex-td">
                    <div class="label-left">Based Ledger: </div>
                    <div class="label-right">
                        {{ $orderData->base_ledger ?? 'N/A' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="flex-td">
                    <div class="label-left">Colour </div>
                    <div class="label-right">
                        {{ $orderData->accessory_colour }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="table-bordered-none">
            <tr>
                <th colspan="2" style="text-align:left;">
                    <h2>Factory Notes</h2>
                </th>
            </tr>
            <tr>
                <td colspan="2">
                    @foreach ($orderData->order_instruction_notes as $instruction_note)
                        @if ($instruction_note->type_of_note == 2)
                            {{ $instruction_note->notes }} <br>
                        @endif
                    @endforeach
                </td>
            </tr>
        </table>
    </div>
@endsection
