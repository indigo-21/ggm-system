
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

        .table-bordered-none{
            border-collapse: collapse;
            width: 100%;
            border: 0px;
        }

        .table-bordered{
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        .table-bordered td{
            border: 2px solid black;
            padding: 5px 5px;
            margin:0px;
        }

        .table-bordered th{
            border: 2px solid black;
            padding: 5px 5px;
            margin:0px;
            font-size: 15px;
        }

        .table-bordered .no-border{
            border: 0px;
            padding: 5px 10px;
            margin:0px;
        }
        .bordered-bottom{
            border-bottom: 1px solid black;
        }
        .flex-td{
            /* display:flex; */
        }
        .label-left{
            /* width: 30%; */
            font-weight: 900;
        }
        .label-right{
            border-bottom:1px solid black;
            padding-bottom:3px;
            width:70%; 
            padding-left:5px;
        }
        .label-width-8 {
            width: 8%;
        }
        .value-width-25 {
            width: 25%;
            border-bottom:1px solid #000;
        }
        .spacer {
            width:2%;
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
                <span>41 Manor Park Crescent Edgware, <br> Middlesex. HA8 7LY</span><br>
                <strong>Tel:</strong> 0208 - 381 1525<br>
            </td>

            <td style="text-align:right;">
                <span>14 Claybury Broadway Clayhall, <br> Ilford Essex. IG5 OLQ</span><br>
                <strong>Tel:</strong> 0208 - 551 6866<br>
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
