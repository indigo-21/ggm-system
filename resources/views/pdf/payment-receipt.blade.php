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

        /* ---------- Payment detail table ---------- */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .detail-table td {
            font-size: 9.5px;
            padding: 4px 8px;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
        }

        .detail-table .detail-label {
            width: 30%;
            font-weight: bold;
            white-space: nowrap;
        }

        .detail-table .detail-value {
            text-align: right;
        }

        .payment-group {
            page-break-inside: avoid;
        }

        .payment-group + .payment-group {
            margin-top: 10px;
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

    <div class="doc-title">{{ $locationName }} Payment Receipt</div>

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

    {{-- ===================== Payment Details ===================== --}}
    <div class="section">
        <div class="section-title">Payment Details</div>
        @foreach ($paymentData as $payment)
            <table class="detail-table payment-group">
                <tr>
                    <td class="detail-label">Amount Received</td>
                    <td class="detail-value">{{ $payment['amount'] }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Method</td>
                    <td class="detail-value">{{ $payment['method'] }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Comment</td>
                    <td class="detail-value">{{ $payment['comment'] ?: '—' }}</td>
                </tr>
            </table>
        @endforeach

        <div class="produced-on">This document was produced on {{ $paymentDate }}.</div>
    </div>

</body>

</html>
