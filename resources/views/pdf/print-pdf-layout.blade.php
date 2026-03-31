
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 25px;
            font-size: 12px;
        }
        .table-data{
            margin:10px 0px;
        }

        .header-title {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .contact-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .contact-table td {
            vertical-align: top;
            padding: 5px;
            width: 50%;
        }

        .center-text {
            text-align: center;
            margin-top: 10px;
        }
        .divider{
            border:2px solid black;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <h1 class="header-title">Gary Green</h1>
    <h4 class="header-title">- MONUMENTAL MASON LIMITED -</h4>

    <!-- Two-column Contact Section -->
    <table class="contact-table">
        <tr>
            <td>
                <span>41 Manor Park Crescent Edgware, Middlesex. HA8 7LY</span><br>
                <strong>Tel:</strong> 0208 - 381 1525<br>
                <strong>FAX:</strong> 0208 - 381 1535
            </td>

            <td style="text-align:right;">
                <span>4 Claybury Broadway Clayhall, Ilford Essex. IG5 OLQ</span><br>
                <strong>Tel:</strong> 0208 - 551 6866<br>
                <strong>FAX:</strong> 0208 - 503 9889
            </td>
        </tr>
    </table>

    <div class="center-text">
        <strong>Email:</strong> info@garygreenmemorials.co.uk
    </div>

    <!-- Injected Content -->
    <div style="margin-top: 20px;">
         @yield('content')
    </div>

</body>
</html>
