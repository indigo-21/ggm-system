<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @php
        $customerName = trim(($customerData->firstname ?? '') . ' ' . ($customerData->lastname ?? ''));
        $deceasedName = trim(strip_tags((string) ($orderData->deceased_name ?? '')));

        $money = fn ($value) => '£' . number_format((float) ($value ?? 0), 2);
        $moneyNeg = fn ($value) => '-£' . number_format((float) ($value ?? 0), 2);

        // Build the invoice line items from the same order_cost fields the Order
        // PDF uses, so figures stay consistent between the two documents.
        $lineItems = [];

        if ($orderCost && $orderCost->description && $orderCost->amount) {
            $lineItems[] = ['desc' => $orderCost->description, 'amount' => (float) $orderCost->amount];
        }

        if ($orderCost && $orderCost->letter_count && $orderCost->letter_amount) {
            $lineItems[] = [
                'desc' => $orderCost->letter_count . ' Letters @ £' . number_format($orderCost->letter_amount, 2),
                'amount' => (float) $orderCost->letter_total_amount,
            ];
        }

        if ($orderCost) {
            foreach ($orderCost->additionals as $additional) {
                if (filled($additional->description)) {
                    $lineItems[] = ['desc' => $additional->description, 'amount' => (float) $additional->amount];
                }
            }
        }

        $hasDiscount = $orderCost && (filled($orderCost->discount_description) || (float) ($orderCost->discount_amount ?? 0) != 0);

        // Sub-total of the memorial work (before cemetery fees). Prefer the
        // stored "total"; fall back to summing the rendered line items.
        $lineItemsSum = collect($lineItems)->sum('amount');
        $workTotal = $orderCost && $orderCost->total !== null
            ? (float) $orderCost->total
            : $lineItemsSum - ($hasDiscount ? (float) ($orderCost->discount_amount ?? 0) : 0);

        // Cemetery fee rows (up to two), rendered only when present.
        $cemeteryFees = [];
        if ($orderCost && (float) ($orderCost->cemetery_fee_amount_1 ?? 0) != 0) {
            $cemeteryFees[] = [
                'desc' => $orderCost->cemetery_fee_description_1 ?: 'Cemetery Fees',
                'amount' => (float) $orderCost->cemetery_fee_amount_1,
            ];
        }
        if ($orderCost && (float) ($orderCost->cemetery_fee_amount_2 ?? 0) != 0) {
            $cemeteryFees[] = [
                'desc' => $orderCost->cemetery_fee_description_2 ?: 'Cemetery Fees',
                'amount' => (float) $orderCost->cemetery_fee_amount_2,
            ];
        }
        $cemeteryFeesSum = collect($cemeteryFees)->sum('amount');

        // Grand total including cemetery fees. Prefer the stored grand_total.
        $grandTotal = $orderCost && $orderCost->grand_total !== null
            ? (float) $orderCost->grand_total
            : $workTotal + $cemeteryFeesSum;

        $received = (float) ($amountReceived ?? 0);

        // Balance due. Prefer the stored balance; otherwise derive it so the
        // figure is always internally consistent.
        $balanceDue = $orderCost && $orderCost->balance !== null
            ? (float) $orderCost->balance
            : $grandTotal - $received;
    @endphp

    <style>
        @page {
            margin: 30px 42px;
        }

        * {
            box-sizing: border-box;
        }

        body {
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

        /* ---------- Header (shared with Order PDF) ---------- */
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

        /* ---------- Section headings (shared with Order PDF) ---------- */
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

        /* ---------- Info grid (label/value pairs, shared with Order PDF) ---------- */
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

        /* Stacked customer address, matching the Order PDF. */
        .address-lines {
            width: 100%;
            border-collapse: collapse;
        }

        .address-lines td {
            padding: 1px 0 2px 0;
            border-bottom: 1px solid #999;
            line-height: 1.3;
        }

        /* ---------- Invoice header split (customer left / meta right) ---------- */
        .invoice-head {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 6px;
        }

        .invoice-head > tr > td {
            vertical-align: top;
        }

        .invoice-party {
            width: 50%;
            padding-right: 18px;
        }

        .invoice-party .party-name {
            font-weight: bold;
        }

        .invoice-party .party-line {
            line-height: 1.4;
        }

        .invoice-meta {
            width: 50%;
        }

        .invoice-meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-meta td {
            font-size: 9.5px;
            padding: 1.5px 4px 1.5px 0;
            vertical-align: top;
        }

        .invoice-meta .meta-label {
            font-weight: bold;
            white-space: nowrap;
            width: 42%;
        }

        .re-line {
            text-align: center;
            font-weight: bold;
            margin: 16px 0 10px 0;
        }

        /* ---------- Invoice line items ---------- */
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .invoice-items td {
            font-size: 10px;
            padding: 2.5px 6px;
            vertical-align: top;
        }

        .invoice-items .item-desc {
            text-align: left;
        }

        .invoice-items .item-amount {
            width: 24%;
            text-align: right;
            white-space: nowrap;
        }

        /* A thin rule above sub-total / total rows to echo the reference's
           dashed separators, without a fully boxed table. */
        .rule-top td {
            border-top: 1px solid #000;
        }

        .row-strong td {
            font-weight: bold;
        }

        .row-balance td {
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        /* ---------- Payment / closing notes ---------- */
        .pay-notes {
            margin-top: 14px;
            font-size: 9.5px;
            line-height: 1.5;
        }

        .pay-notes .emphasis {
            font-weight: bold;
        }

        .pay-block {
            margin-top: 10px;
        }

        /* ---------- Footer ---------- */
        .company-footer {
            margin-top: 26px;
            padding-top: 6px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 8px;
            line-height: 1.4;
        }
    </style>
</head>

<body>

    {{-- ===================== Header (shared with Order PDF) ===================== --}}
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
                Tel : 0208 - 381 1525<br>
                Fax : 0208 - 381 1535
            </td>
            <td class="branch-right">
                14 Claybury Broadway<br>
                Clayhall, Ilford<br>
                Essex. IG5 OLQ<br>
                Tel : 0208 - 551 6866<br>
                Fax : 0208 - 503 9889<br>
                eMail : info@garygreenmemorials.co.uk
            </td>
        </tr>
    </table>

    <div class="doc-title">{{ $orderData->location->name ?? '' }} INVOICE</div>

    {{-- ===================== Customer + Invoice Meta ===================== --}}
    <table class="invoice-head keep-together">
        <tr>
            <td class="invoice-party">
                <div class="party-name">{{ $customerName ?: '—' }}</div>
                @foreach ($customerAddressLines as $line)
                    <div class="party-line">{{ $line }}</div>
                @endforeach
            </td>
            <td class="invoice-meta">
                <table>
                    <tr>
                        <td class="meta-label">Invoice No. :</td>
                        <td>{{ $invoiceNo ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Invoice Date :</td>
                        <td>{{ $invoiceDate ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Consecration :</td>
                        <td>{{ $consecrationDate ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Cemetery :</td>
                        <td>{{ $orderData->cemetery->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Grave No. :</td>
                        <td>{{ $orderData->grave_number ?: '—' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===================== Re: Memorial ===================== --}}
    <div class="re-line">Re: The Memorial of the late {{ $deceasedName ?: '—' }}</div>

    {{-- ===================== Invoice Items ===================== --}}
    <table class="invoice-items keep-together">
        {{-- ----- Work line items ----- --}}
        @foreach ($lineItems as $item)
            <tr>
                <td class="item-desc">{{ $item['desc'] }}</td>
                <td class="item-amount">{{ $money($item['amount']) }}</td>
            </tr>
        @endforeach

        {{-- ----- Discount (only when present) ----- --}}
        @if ($hasDiscount)
            <tr>
                <td class="item-desc">{{ $orderCost->discount_description ?: 'Discount' }}</td>
                <td class="item-amount">{{ $moneyNeg($orderCost->discount_amount) }}</td>
            </tr>
        @endif

        {{-- ----- Work sub-total ----- --}}
        <tr class="rule-top row-strong">
            <td class="item-desc">TOTAL</td>
            <td class="item-amount">{{ $money($workTotal) }}</td>
        </tr>

        {{-- ----- Cemetery fees (only when present) ----- --}}
        @foreach ($cemeteryFees as $fee)
            <tr>
                <td class="item-desc">{{ $fee['desc'] }}</td>
                <td class="item-amount">{{ $money($fee['amount']) }}</td>
            </tr>
        @endforeach

        {{-- ----- Grand total (shown when cemetery fees add to the work total) ----- --}}
        @if (! empty($cemeteryFees))
            <tr class="rule-top row-strong">
                <td class="item-desc"></td>
                <td class="item-amount">{{ $money($grandTotal) }}</td>
            </tr>
        @endif

        {{-- ----- Amount received (only when any payment recorded) ----- --}}
        @if ($received != 0)
            <tr>
                <td class="item-desc">Amount Received</td>
                <td class="item-amount">{{ $moneyNeg($received) }}</td>
            </tr>
        @endif

        {{-- ----- Balance due (emphasised) ----- --}}
        <tr class="row-balance">
            <td class="item-desc">Balance due for payment</td>
            <td class="item-amount">{{ $money($balanceDue) }}</td>
        </tr>
    </table>

    {{-- ===================== Payment / Closing Notes ===================== --}}
    <div class="pay-notes">
        <p>The above price includes Free insurance for 6 months (if opted in), VAT and Cemetery fees.</p>

        <div class="pay-block">
            <p>If paying by Bank Transfer details as follows</p>
            <p>Barclays Bank / Account Name: G Green Monumental Mason Limited</p>
            <p>Account No: 70390909 / Sort Code: 20-44-22</p>
            <p>Please include your invoice / order number as the reference</p>
            <p>Cheque's to be made payable to: Gary Green Monumental Mason Ltd</p>
        </div>

        <div class="pay-block">
            <p class="emphasis">THE MEMORIAL IS NEARING COMPLETION.</p>
            <p class="emphasis">A PHOTO WILL BE SENT TO YOU IN DUE COURSE</p>
        </div>

        <p class="pay-block">Payment to be made on inspection of memorial photo and before erecting in cemetery.</p>
    </div>

    {{-- ===================== Footer ===================== --}}
    <div class="company-footer">
        Company Registered Office: The Retreat, 406 Roding Lane South, Woodford Green, Essex IG8 8EY<br>
        VAT Reg No: 248146204 &nbsp; Company Reg No: 10034881
    </div>

</body>

</html>
