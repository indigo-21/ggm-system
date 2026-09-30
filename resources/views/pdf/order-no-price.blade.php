<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @php
        // Density is provided by PdfService::orderDensity(); fall back to "normal".
        $density = $density ?? 'normal';

        // Pre-group customer contacts by type so the template stays clean.
        $emails = $customerData->customer_contacts->where('contact_type', 1)->pluck('contact_value')->filter();
        $telNos = $customerData->customer_contacts->where('contact_type', 3)->pluck('contact_value')->filter();
        $mobileNos = $customerData->customer_contacts->where('contact_type', 2)->pluck('contact_value')->filter();

        $dateOfDeath = $orderData->date_of_death
            ? \Carbon\Carbon::parse($orderData->date_of_death)->format('jS F Y')
            : '';
        $consecration = $orderData->consecration_date
            ? \Carbon\Carbon::parse($orderData->consecration_date)->format('jS F Y')
            : '';
        $fixing_date = $orderData->fixing_date
            ? \Carbon\Carbon::parse($orderData->fixing_date)->format('F Y')
            : '';
    @endphp

    <style>
        @page {
            margin: 30px 42px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            /* Base type: bumped ~10% from the previous 9.5px for readability. */
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10.5px;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0 0 6px 0;
        }

        strong {
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .keep-together {
            page-break-inside: avoid;
        }

        /* ---------- Header ---------- */
        .brand-header {
            text-align: center;
            margin: 0;
        }

        .brand-logo {
            width: 364px;
            height: auto;
        }

        .branch-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .branch-table td {
            vertical-align: top;
            font-size: 9px;
            line-height: 1.3;
        }

        .branch-left { width: 50%; text-align: left; }
        .branch-right { width: 50%; text-align: right; }

        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 13px 0 3px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #000;
        }

        /* ---------- Section headings ---------- */
        .section {
            margin-top: 9px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #000;
        }

        /* ---------- Info grid (label/value pairs) ---------- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            vertical-align: top;
            padding: 2.5px 8px 2.5px 0;
            font-size: 9.5px;
            line-height: 1.35;
        }

        .info-label {
            font-weight: bold;
            white-space: nowrap;
        }

        .info-value {
            border-bottom: 1px solid #999;
        }

        /* Stacked customer address: one component per line, each underlined,
           matching the client-facing address layout. */
        .address-lines {
            width: 100%;
            border-collapse: collapse;
        }

        .address-lines td {
            padding: 1px 0 2px 0;
            border-bottom: 1px solid #999;
            line-height: 1.3;
        }

        /* ---------- Notes ---------- */
        .notes-box {
            border: 1px solid #000;
            padding: 6px 8px;
            min-height: 34px;
            font-size: 9.5px;
            line-height: 1.35;
        }

        /* =====================================================================
           COMPACT density
           ===================================================================== */
        body.compact { font-size: 10px; line-height: 1.25; }
        body.compact .brand-logo { width: 328px; }
        body.compact .branch-table { margin-top: 7px; }
        body.compact .branch-table td { font-size: 8.5px; }
        body.compact .doc-title { font-size: 12px; margin: 9px 0 3px 0; padding-bottom: 4px; }
        body.compact .section { margin-top: 8px; }
        body.compact .section-title { font-size: 9.5px; margin-bottom: 3px; }
        body.compact .info-table td { padding: 1.5px 8px 1.5px 0; font-size: 9px; }
        body.compact .notes-box { padding: 4px 6px; min-height: 26px; font-size: 9px; }

        /* =====================================================================
           DENSE density
           ===================================================================== */
        body.dense { font-size: 9.5px; line-height: 1.25; }
        body.dense .brand-logo { width: 300px; }
        body.dense .branch-table { margin-top: 6px; }
        body.dense .branch-table td { font-size: 8.5px; line-height: 1.25; }
        body.dense .doc-title { font-size: 11px; margin: 8px 0 3px 0; padding-bottom: 4px; }
        body.dense .section { margin-top: 8px; }
        body.dense .section-title { font-size: 9px; margin-bottom: 3px; }
        body.dense .info-table td { padding: 1.5px 8px 1.5px 0; font-size: 8.5px; }
        body.dense .notes-box { padding: 4px 6px; min-height: 24px; font-size: 8.5px; }
    </style>
</head>

<body class="{{ $density }}">

    {{-- ===================== Header ===================== --}}
    <div class="brand-header">
        <img class="brand-logo" src="{{ public_path('assets/images/xs/gary-green-pdf.png') }}"
            alt="Gary Green - Monumental Mason Limited">
    </div>

    <table class="branch-table">
        <tr>
            <td class="branch-left">
                41 Manor Park Crescent<br>
                Edgware, Middlesex.<br>
                HA8 7LY<br>
                Tel : 0208 - 381 1525
            </td>
            <td class="branch-right">
                14 Claybury Broadway<br>
                Clayhall, Ilford<br>
                Essex. IG5 OLQ<br>
                Tel : 0208 - 551 6866<br>
                eMail : info@garygreenmemorials.co.uk
            </td>
        </tr>
    </table>

    <div class="doc-title">{{ $orderData->location->name ?? '' }} Order #{{ $orderData->id }}</div>

    {{-- ===================== Customer Details ===================== --}}
    <div class="section keep-together">
        <div class="section-title">Customer Details</div>
        <table class="info-table">
            <tr>
                <td style="width:15%;" class="info-label">Customer Name</td>
                <td style="width:35%;" class="info-value">{{ trim($customerData->firstname . ' ' . $customerData->lastname) }}</td>
                <td style="width:15%;" class="info-label">Order Date</td>
                <td style="width:35%;" class="info-value">{{ \Carbon\Carbon::parse($orderDate)->format('jS F Y')  }}</td>
            </tr>
            <tr>
                <td class="info-label" style="vertical-align:top;">Address</td>
                <td style="vertical-align:top; padding:3px 8px 3px 0;">
                    @if (!empty($customerAddressLines))
                        <table class="address-lines">
                            @foreach ($customerAddressLines as $line)
                                <tr><td>{{ $line }}</td></tr>
                            @endforeach
                        </table>
                    @else
                        <span class="info-value">—</span>
                    @endif
                </td>
                <td class="info-label" style="vertical-align:top;">Email</td>
                <td class="info-value" style="vertical-align:top;">{{ $emails->isNotEmpty() ? $emails->implode(', ') : '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Tel No</td>
                <td class="info-value">{{ $telNos->isNotEmpty() ? $telNos->implode(', ') : '—' }}</td>
                <td class="info-label">Mobile No</td>
                <td class="info-value">{{ $mobileNos->isNotEmpty() ? $mobileNos->implode(', ') : '—' }}</td>
            </tr>
        </table>
    </div>

    {{-- ===================== Order Details ===================== --}}
    <div class="section">
        <div class="section-title">Order Details</div>
        <table class="info-table">
            <tr>
                <td style="width:16%;" class="info-label">Deceased</td>
                <td style="width:17%;" class="info-value">{!! $orderData->deceased_name !!}</td>
                <td style="width:16%;" class="info-label">Date of Death</td>
                <td style="width:17%;" class="info-value">{{ $dateOfDeath ?: '—' }}</td>
                <td style="width:16%;" class="info-label">Consecration</td>
                <td style="width:18%;" class="info-value">
                    @if($consecration)
                        {{ $consecration }}
                    @elseif($orderData->is_asap)
                        ASAP
                    @elseif($orderData->is_tba)
                        TBA
                    @elseif($orderData->is_approx)
                        Approx — {{$fixing_date}}
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <td class="info-label">Cemetery</td>
                <td class="info-value">{{ $orderData->cemetery->name ?? '—' }}</td>
                <td class="info-label">Grave No.</td>
                <td class="info-value">{{ $orderData->grave_number ?: '—' }}</td>
                <td class="info-label">Burial Society</td>
                <td class="info-value">{{ $orderData->burial_society_organization->name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Material</td>
                <td class="info-value">{{ $orderData->material ?: '—' }}</td>
                <td class="info-label">Grave Space</td>
                <td class="info-value">{{ $orderData->grave_space->name ?? '—' }}</td>
                <td class="info-label">Colour</td>
                <td class="info-value">{{ $orderData->material_colour ?: '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Letter Type</td>
                <td class="info-value">{{ $orderData->letter_type ?: '—' }}</td>
                <td class="info-label">Design/Headstone</td>
                <td class="info-value">{{ $orderData->design_headstone ?: '—' }}</td>
                <td class="info-label">Kerbs/Risers</td>
                <td class="info-value">{{ $orderData->kerb_riser ?: '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Size</td>
                <td class="info-value">{!! $orderData->size ?: '—' !!}</td>
                <td class="info-label">Accessories</td>
                <td class="info-value">{{ $orderData->accessory ?: '—' }}</td>
                <td class="info-label">Based Ledger</td>
                <td class="info-value">{{ $orderData->base_ledger ?: '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Accessory Colour</td>
                <td class="info-value">{{ $orderData->accessory_colour ?: '—' }}</td>
                <td class="info-label"></td>
                <td class="info-value" style="border:0;"></td>
                <td class="info-label"></td>
                <td class="info-value" style="border:0;"></td>
            </tr>
        </table>
    </div>

    {{-- ===================== Factory Notes =====================
         Price-free document: the Cost, Customer Notes (deposit) and Balance
         blocks from the standard Order PDF are intentionally omitted. --}}
    <div class="section keep-together">
        <div class="section-title">Factory Notes</div>
        <div class="notes-box">
            @forelse ($orderData->order_instruction_notes->where('type_of_note', 2) as $instruction_note)
                {{ $instruction_note->notes }}<br>
            @empty
                N/A
            @endforelse
        </div>
    </div>

</body>

</html>
