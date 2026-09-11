<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @php
        // Density is provided by PdfService::quoteDensity(). Fall back to "normal"
        // so the template still renders if called without it.
        $density = $density ?? 'normal';
    @endphp

    <style>
        @page {
            margin: 34px 45px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            line-height: 1.45;
            color: #000;
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0 0 8px 0;
        }

        strong {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Keep logically-grouped blocks from splitting across pages when the
           content genuinely overflows onto a second page. */
        .keep-together {
            page-break-inside: avoid;
        }

        /* ---------- Header ---------- */
        .company-name {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0;
        }

        .company-subtitle {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 2px 0 0 0;
        }

        .branch-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .branch-table td {
            vertical-align: top;
            font-size: 11px;
            line-height: 1.4;
        }

        .branch-left {
            width: 50%;
            text-align: left;
        }

        .branch-right {
            width: 50%;
            text-align: right;
        }

        /* ---------- Recipient / meta ---------- */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .meta-table td {
            vertical-align: top;
            font-size: 12px;
            line-height: 1.5;
        }

        .meta-left {
            width: 60%;
            text-align: left;
        }

        .meta-right {
            width: 40%;
            text-align: left;
        }

        /* ---------- Body ---------- */
        .salutation {
            margin-top: 26px;
        }

        .memorial-subject {
            margin: 20px 0;
            text-align: center;
            line-height: 1.6;
        }

        /* ---------- Quotation line items ---------- */
        .quote-heading {
            text-align: center;
            font-weight: bold;
            margin: 22px 0 10px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        .items-table tr {
            page-break-inside: avoid;
        }

        .items-table td {
            padding: 3px 0;
            font-size: 12px;
        }

        .item-desc {
            text-align: left;
            padding-left: 90px;
        }

        .item-amount {
            text-align: right;
            padding-right: 40px;
            white-space: nowrap;
        }

        /* ---------- Notes / closing ---------- */
        .price-note {
            margin-top: 20px;
            font-weight: bold;
        }

        .closing-block {
            margin-top: 18px;
        }

        .signoff {
            margin-top: 30px;
        }

        .memorials-line {
            font-weight: bold;
            text-decoration: underline;
            font-size: 13px;
            margin-top: 6px;
        }

        /* ---------- Bank details ---------- */
        .bank-details {
            margin-top: 22px;
            line-height: 1.55;
        }

        /* =====================================================================
           COMPACT density — tighten spacing/type for busier quotations so the
           closing block and bank details stay on the first page.
           ===================================================================== */
        body.compact {
            font-size: 11px;
            line-height: 1.35;
        }

        body.compact .company-name { font-size: 26px; }
        body.compact .branch-table { margin-top: 10px; }
        body.compact .branch-table td { font-size: 10.5px; line-height: 1.3; }
        body.compact .meta-table { margin-top: 20px; }
        body.compact .meta-table td { line-height: 1.4; }
        body.compact .salutation { margin-top: 18px; }
        body.compact .memorial-subject { margin: 14px 0; line-height: 1.45; }
        body.compact p { margin: 0 0 6px 0; }
        body.compact .quote-heading { margin: 16px 0 8px 0; }
        body.compact .items-table td { padding: 2px 0; font-size: 11px; }
        body.compact .price-note { margin-top: 14px; }
        body.compact .closing-block { margin-top: 13px; }
        body.compact .signoff { margin-top: 20px; }
        body.compact .bank-details { margin-top: 16px; line-height: 1.45; }

        /* =====================================================================
           DENSE density — maximum tightening for very long content. Still keeps
           readable type (>= 10px) and clear separation between sections.
           ===================================================================== */
        body.dense {
            font-size: 10.5px;
            line-height: 1.3;
        }

        body.dense .company-name { font-size: 24px; }
        body.dense .company-subtitle { font-size: 11px; }
        body.dense .branch-table { margin-top: 8px; }
        body.dense .branch-table td { font-size: 10px; line-height: 1.25; }
        body.dense .meta-table { margin-top: 14px; }
        body.dense .meta-table td { font-size: 11px; line-height: 1.35; }
        body.dense .salutation { margin-top: 12px; }
        body.dense .memorial-subject { margin: 10px 0; line-height: 1.35; }
        body.dense p { margin: 0 0 5px 0; }
        body.dense .quote-heading { margin: 12px 0 6px 0; }
        body.dense .items-table td { padding: 1.5px 0; font-size: 10.5px; }
        body.dense .price-note { margin-top: 10px; }
        body.dense .closing-block { margin-top: 10px; }
        body.dense .signoff { margin-top: 14px; }
        body.dense .memorials-line { font-size: 12px; }
        body.dense .bank-details { margin-top: 12px; line-height: 1.35; }
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

    {{-- ===================== Recipient & Meta ===================== --}}
    <table class="meta-table">
        <tr>
            <td class="meta-left">
                {{ $customerName }}<br>
                @if (trim($customerAddress) !== '')
                    {!! nl2br(e(trim($customerAddress))) !!}
                @endif
            </td>
            <td class="meta-right">
                {{ $printDate }}<br>
                Ref: {{ $orderReference }}
            </td>
        </tr>
    </table>

    {{-- ===================== Body ===================== --}}
    <p class="salutation">Dear {{ $customerFirstname }},</p>

    <div class="memorial-subject">
        Memorial of the late {!! $deceasedName !!}<br>
        <span class="underline">{{ trim($cemeteryName . ' ' . $graveNumber) }}</span>
    </div>

    <p>
        I refer to your enquiry regarding the above Memorial.<br>
        Please find detailed below our quotation:
    </p>

    {{-- ===================== Quotation Items ===================== --}}
    @php
        // Join only the populated parts with " - " so the heading never starts
        // or ends with a stray separator when some fields are empty.
        $quoteHeading = collect([$headStone, $headStoneSize, $material])
            ->map(fn ($part) => trim((string) $part))
            ->filter()
            ->implode(' - ');
    @endphp

    <div class="keep-together">
        @if ($quoteHeading !== '')
            <div class="quote-heading"><span class="underline">{{ $quoteHeading }}</span></div>
        @endif

        <table class="items-table">
            @if ($orderCost && $orderCost->description && $orderCost->amount)
                <tr>
                    <td class="item-desc">{{ $orderCost->description }}</td>
                    <td class="item-amount">£{{ number_format($orderCost->amount, 2) }}</td>
                </tr>
            @endif

            @if ($orderCost && $orderCost->letter_count && $orderCost->letter_amount)
                <tr>
                    <td class="item-desc">{{ $orderCost->letter_count }} Letters @ £{{ number_format($orderCost->letter_amount, 2) }}</td>
                    <td class="item-amount">£{{ number_format($orderCost->letter_total_amount, 2) }}</td>
                </tr>
            @endif

            @foreach ($orderCostAdditionals as $additional)
                @if ($additional->description)
                    <tr>
                        <td class="item-desc">{{ $additional->description }}</td>
                        <td class="item-amount">£{{ number_format($additional->amount, 2) }}</td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>

    {{-- ===================== Notes & Closing ===================== --}}
    @if (trim($orderAdditionalNote ?? '') !== '')
        <p class="price-note">{{ $orderAdditionalNote }}</p>
    @endif

    @php
        $depositAmount = number_format($depositRequired ?? 0, 2);
    @endphp

    <div class="keep-together">
        <p class="closing-block">When deciding to place an order a deposit of £{{ $depositAmount }} will be required.</p>
        <p>Please do not hesitate to contact me should you require any further information regarding this quotation.</p>
        <p>I assure you of our best attention at all times.</p>

        <p class="signoff">Yours sincerely,</p>

        <div class="memorials-line">GARY GREEN MEMORIALS - {{ $locationName }}</div>
    </div>

    {{-- ===================== Bank Details ===================== --}}
    <div class="bank-details keep-together">
        If paying deposit by bank transfer details as follows<br>
        Barclays Bank / Account Name: Gary Green Monumental Mason Limited<br>
        Account No: 70390909 / Sort Code: 20-44-22<br>
        Please include your reference number when paying<br>
        Cheques to be made payable to: Gary Green Monumental Mason Ltd<br>
        This quote is valid for 30 days
    </div>

</body>

</html>
