<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

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

        .re-line {
            text-align: center;
            font-weight: bold;
            margin: 14px 0 6px 0;
        }

        /* ---------- Account summary (label / amount rows) ---------- */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .summary-table td {
            font-size: 9.5px;
            padding: 3px 8px;
            vertical-align: bottom;
        }

        .summary-table .summary-label {
            font-weight: bold;
            white-space: nowrap;
        }

        .summary-table .summary-amount {
            width: 30%;
            text-align: right;
            white-space: nowrap;
            border-bottom: 1px solid #000;
        }

        /* Outstanding balance: strongest emphasis with a double rule. */
        .summary-outstanding .summary-label,
        .summary-outstanding .summary-amount {
            font-weight: bold;
        }

        .summary-outstanding .summary-amount {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        /* ---------- Payment history table ---------- */
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .history-table th {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            text-align: left;
            padding: 4px 8px;
            border-bottom: 1.5px solid #000;
        }

        .history-table td {
            font-size: 9.5px;
            padding: 4px 8px;
            border-bottom: 1px solid #ccc;
            vertical-align: top;
        }

        .history-table tr {
            page-break-inside: avoid;
        }

        .history-table .col-amount {
            text-align: right;
            white-space: nowrap;
            font-weight: bold;
        }

        .produced-on {
            margin-top: 10px;
            font-style: italic;
            font-size: 8.5px;
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

    <div class="doc-title">{{ $locationName }} Statement</div>

    {{-- ===================== Customer Details ===================== --}}
    <div class="section keep-together">
        <div class="section-title">Customer Details</div>
        <table class="info-table">
            <tr>
                <td style="width:15%;" class="info-label">Customer Name</td>
                <td style="width:35%;" class="info-value">{{ $customerName }}</td>
                <td style="width:15%;" class="info-label">Date</td>
                <td style="width:35%;" class="info-value">{{ $paymentDate }}</td>
            </tr>
            <tr>
                <td class="info-label">Address</td>
                <td class="info-value">{{ $customerAddress }}</td>
                <td class="info-label">Cemetery</td>
                <td class="info-value">{{ $cemetery ?: '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Grave No.</td>
                <td class="info-value">{{ $graveNumber ?: '—' }}</td>
                <td class="info-label"></td>
                <td class="info-value" style="border:0;"></td>
            </tr>
        </table>
    </div>

    {{-- ===================== Re: Memorial ===================== --}}
    <div class="re-line">Re: The Memorial of the late {{ $deceasedName ?: '—' }}</div>

    {{-- ===================== Account Summary ===================== --}}
    <div class="section keep-together">
        <div class="section-title">Account Summary</div>
        <table class="summary-table">
            <tr>
                <td class="summary-label">Order Amount</td>
                <td class="summary-amount">{{ $orderAmount }}</td>
            </tr>
            <tr>
                <td class="summary-label">Amount Paid</td>
                <td class="summary-amount">{{ $amountPaid }}</td>
            </tr>
            <tr class="summary-outstanding">
                <td class="summary-label">Outstanding Amount</td>
                <td class="summary-amount">{{ $outstandingAmount }}</td>
            </tr>
        </table>
    </div>

    {{-- ===================== Payment History ===================== --}}
    <div class="section">
        <div class="section-title">Payment History</div>
        <table class="history-table">
            <tr>
                <th style="width:32%;">Timestamp</th>
                <th style="width:20%;">Method</th>
                <th style="width:20%;" class="text-right">Amount</th>
                <th>Remarks</th>
            </tr>
            @foreach ($paymentData as $payment)
                <tr>
                    <td>{{ $payment['timestamp'] }}</td>
                    <td>{{ $payment['method'] }}</td>
                    <td class="col-amount">{{ $payment['amount'] }}</td>
                    <td>{{ $payment['comment'] ?: '—' }}</td>
                </tr>
            @endforeach
        </table>

        <div class="produced-on">This document was produced on {{ $paymentDate }}.</div>
    </div>

</body>

</html>
