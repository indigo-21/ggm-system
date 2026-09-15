<!DOCTYPE html>
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
            ? \Carbon\Carbon::parse($orderData->date_of_death)->format('F d, Y A')
            : '';
        $consecration = $orderData->consecration_date
            ? \Carbon\Carbon::parse($orderData->consecration_date)->format('F d, Y')
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
            /* Base type scaled down ~15% (11px -> 9.5px) to improve
               single-page printability while staying readable. */
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9.5px;
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
        .company-name {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0;
        }

        .company-subtitle {
            text-align: center;
            font-size: 9.5px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 2px 0 0 0;
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

        /* ---------- Cost table ---------- */
        .cost-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .cost-table th {
            border: 1px solid #000;
            background: #f0f0f0;
            padding: 4px 7px;
            text-align: left;
            font-size: 9.5px;
        }

        .cost-table td {
            border: 1px solid #000;
            padding: 3px 7px;
            font-size: 9.5px;
        }

        .cost-table tr {
            page-break-inside: avoid;
        }

        .cost-desc { text-align: left; }
        .cost-amount { text-align: right; white-space: nowrap; width: 28%; }

        .cost-total td {
            font-weight: bold;
            background: #f7f7f7;
        }

        /* ---------- Notes / terms ---------- */
        .notes-box {
            border: 1px solid #000;
            padding: 6px 8px;
            min-height: 34px;
            font-size: 9.5px;
            line-height: 1.35;
        }

        .terms {
            margin-top: 3px;
            font-size: 8px;
            line-height: 1.3;
        }

        .terms-list {
            margin: 0;
            padding-left: 14px;
        }

        .terms-list li {
            margin-bottom: 1px;
        }

        .produced-on {
            margin-top: 5px;
            font-style: italic;
            font-size: 8.5px;
        }

        /* ---------- Declaration / signature ---------- */
        .declaration {
            margin-top: 12px;
        }

        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .sign-table td {
            width: 50%;
            font-size: 9.5px;
            padding-right: 20px;
        }

        .sign-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 60%;
        }

        /* =====================================================================
           COMPACT density (~15% smaller than the previous compact tier)
           ===================================================================== */
        body.compact { font-size: 9px; line-height: 1.25; }
        body.compact .company-name { font-size: 19.5px; }
        body.compact .company-subtitle { font-size: 9px; }
        body.compact .branch-table { margin-top: 7px; }
        body.compact .branch-table td { font-size: 8.5px; }
        body.compact .doc-title { font-size: 12px; margin: 9px 0 3px 0; padding-bottom: 4px; }
        body.compact .section { margin-top: 8px; }
        body.compact .section-title { font-size: 9.5px; margin-bottom: 3px; }
        body.compact .info-table td { padding: 1.5px 8px 1.5px 0; font-size: 9px; }
        body.compact .cost-table td,
        body.compact .cost-table th { padding: 2px 6px; font-size: 9px; }
        body.compact .notes-box { padding: 4px 6px; min-height: 26px; font-size: 9px; }
        body.compact .terms { font-size: 7.5px; line-height: 1.25; }
        body.compact .produced-on { margin-top: 3px; font-size: 8px; }
        body.compact .declaration { margin-top: 9px; }
        body.compact .sign-table { margin-top: 11px; }
        body.compact .sign-table td { font-size: 9px; }

        /* =====================================================================
           DENSE density (~15% smaller than the previous dense tier)
           ===================================================================== */
        body.dense { font-size: 8.5px; line-height: 1.25; }
        body.dense .company-name { font-size: 18.5px; }
        body.dense .company-subtitle { font-size: 8.5px; }
        body.dense .branch-table { margin-top: 6px; }
        body.dense .branch-table td { font-size: 8.5px; line-height: 1.25; }
        body.dense .doc-title { font-size: 11px; margin: 8px 0 3px 0; padding-bottom: 4px; }
        body.dense .section { margin-top: 8px; }
        body.dense .section-title { font-size: 9px; margin-bottom: 3px; }
        body.dense .info-table td { padding: 1.5px 8px 1.5px 0; font-size: 8.5px; }
        body.dense .cost-table td,
        body.dense .cost-table th { padding: 2px 5px; font-size: 8.5px; }
        body.dense .notes-box { padding: 4px 6px; min-height: 24px; font-size: 8.5px; }
        body.dense .terms { font-size: 7.5px; line-height: 1.25; }
        body.dense .produced-on { margin-top: 3px; font-size: 8px; }
        body.dense .declaration { margin-top: 9px; }
        body.dense .sign-table { margin-top: 12px; }
        body.dense .sign-table td { font-size: 8.5px; }
    </style>
</head>

<body class="{{ $density }}">

    {{-- ===================== Header ===================== --}}
    <div class="company-name">Gary Green</div>
    <div class="company-subtitle">- MONUMENTAL MASON LIMITED -</div>

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
                <td style="width:35%;" class="info-value">{{ $orderDate }}</td>
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
                <td style="width:18%;" class="info-value">{{ $consecration ?: '—' }}</td>
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

    {{-- ===================== Cost ===================== --}}
    <div class="section keep-together">
        <div class="section-title">Cost</div>
        <table class="cost-table">
            <tr>
                <th class="cost-desc">Description</th>
                <th class="cost-amount">Amount</th>
            </tr>

            @if ($orderCost && $orderCost->description && $orderCost->amount)
                <tr>
                    <td class="cost-desc">{{ $orderCost->description }}</td>
                    <td class="cost-amount">£{{ number_format($orderCost->amount, 2) }}</td>
                </tr>
            @endif

            @if ($orderCost && $orderCost->letter_count && $orderCost->letter_amount)
                <tr>
                    <td class="cost-desc">{{ $orderCost->letter_count }} Letters @ £{{ number_format($orderCost->letter_amount, 2) }}</td>
                    <td class="cost-amount">£{{ number_format($orderCost->letter_total_amount ?? 0, 2) }}</td>
                </tr>
            @endif

            @if ($orderCost)
                @foreach ($orderCost->additionals as $additional)
                    @if ($additional->description)
                        <tr>
                            <td class="cost-desc">{{ $additional->description }}</td>
                            <td class="cost-amount">£{{ number_format($additional->amount ?? 0, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif

            <tr class="cost-total">
                <td class="cost-desc">Grand Total</td>
                <td class="cost-amount">£{{ number_format($orderCost->grand_total ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="cost-desc"><strong>Deposit:</strong> {{ $orderDeposit?->comment ?? '' }}</td>
                <td class="cost-amount">- £{{ $orderDeposit ? number_format($orderDeposit->amount, 2) : '0.00' }}</td>
            </tr>
        </table>
    </div>

    {{-- ===================== Customer Notes ===================== --}}
    <div class="section keep-together">
        <div class="section-title">Customer Notes</div>
        <div class="notes-box">{{ $orderData->customer_notes ?: 'N/A' }}</div>
    </div>

    {{-- ===================== Notes / Terms ===================== --}}
    <div class="section">
        <div class="section-title">Notes</div>
        <div class="terms">
            <ul class="terms-list">
                <li>Price includes cemetery fees, VAT &amp; 100 letters; extra letters @ £2.70 a letter inc VAT.</li>
                <li>We will conduct an inspection to see if there are any renovations that need doing to the memorial and let you know accordingly.</li>
                <li>Burial Society and Foundation Fees (for members) are also included.</li>
                <li>All memorials to be paid for prior to erection in the Cemetery.</li>
                <li>Free 6 months insurance with invoice &mdash; to receive this offer please advise, by telephone or email, if you would like us to pass your details to Stoneguard.</li>
                <li>All goods remain the property of Gary Green Memorials until paid for in full.</li>
                <li>Marble and granite materials are ordered when the customer places the order; a replica or convert memorial may show differences in marble graining or granite colour.</li>
                <li>Please make us aware of, or remove, any items on the grave that are valuable to yourself.</li>
                <li>Marble is porous and susceptible to brown/orange staining from the elements; once stained these marks cannot be removed but may fade over time.</li>
            </ul>
            <div class="produced-on">This document was produced on {{ $printDate }}.</div>
        </div>
    </div>

    {{-- ===================== Declaration ===================== --}}
    <div class="section declaration keep-together">
        <div class="section-title">Declaration</div>
        <p>I, the undersigned, agree that the memorial detailed above is to my specification.</p>
        <table class="sign-table">
            <tr>
                <td><strong>Signed:</strong> <span class="sign-line"></span></td>
                <td><strong>Dated:</strong> <span class="sign-line"></span></td>
            </tr>
        </table>
    </div>

</body>

</html>
