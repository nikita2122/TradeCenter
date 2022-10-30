<!doctype html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { size: 58mm 125mm; }
    </style>
    <style>
        body, html {
            margin: 0 !important;
            font-family: Helvetica !important;
        }
        .box {
            width: 54.4mm;
            position: relative;
            margin: 0 !important;
            padding-top: 4mm;
            padding-left: 1.8mm;
            font-size: 2.2mm;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .text-small {
            font-size: 1.9mm;
        }
        .line {
            width: 58mm;
            margin-left: -1.8mm;
            border-bottom: 1px solid black;
        }
        .width-100 {
            width: 100%;
        }

        .position-relative {
            position: relative;
        }
        .right-auto-box {
            position: absolute;
            top: 0px;
            right: 0px;
        }

        .mt-sm {
            margin-top: 1.4mm;
        }
        .mt-xs {
            margin-top: 0.5mm;
        }
        .mt-md {
            margin-top: 2mm;
        }
        .mb-md {
            margin-bottom: 3mm;
        }
        .mt-lg {
            margin-top: 5mm;
        }
        .mb-lg {
            margin-bottom: 5mm;
        }
        .text-company-name {
            width: 85%;
            font-size: 3.5mm;
            margin-left: auto;
            margin-right: auto;
        }
        .font-normal {
            font-weight: normal;
            font-family: DejaVu Sans, sans-serif;
        }

    </style>
</head>
<body>
    <div class="box">
        <div class="text-center text-bold text-company-name">{{$exchange->company->name}}</div>
        <div class="mt-sm text-center">{{$exchange->company->address}}</div>
        <div class="mt-sm">Company Phone Number: {{$exchange->company->phone_no}}</div>
        <div class="position-relative mt-sm text-bold  width-100">
            <div>Receipt No:{{str_pad($exchange->receipt, 3, '0', STR_PAD_LEFT)}}</div>
            <div class="right-auto-box">Date:{{date_format(date_create($exchange->date),'m/d/Y')}}</div>
        </div>
        <div class="mt-lg">Customer Name: {{$exchange->customer_name}}</div>
        <div class="mt-sm">:</div>
        <div class="mt-sm">Attended By: {{$exchange->user->name}}</div>

        <div class="mt-lg text-bold" style="float: left;">
            <div style="width: 17.5mm; float: left;">DESCRIPTION</div>
            <div style="width: 14mm; float: left; margin-left: 17.5mm;">CURRENCY</div>
            <div class="text-center" style="width: 11mm; float: left; margin-left: 31.5mm;">RATE</div>
            <div class="text-center" style="width: 10mm; margin-left: 42.5mm;">QTY</div>
        </div>

        <div class="line mt-xs" style="clear: left;"></div>
        <div class="line mt-xs"></div>
        <div class="mt-md text-small" style="float: left; margin-left: -1mm;">
            <div class="text-center" style="width: 17.5mm; float: left;">Currency Exchange</div>
            <div class="text-center" style="width: 14mm; float: left; margin-left: 18.5mm;">{{$exchange->currency->name}}</div>
            <div class="text-center" style="width: 11mm; float: left; margin-left: 32.5mm;">{{number_format($exchange->rate)}}</div>
            <div class="text-center" style="width: 10mm; margin-left: 43.5mm;">{{number_format($exchange->amount)}}</div>
        </div>

        <div class="mt-md" style="clear: left;"></div>
        <div class="mt-sm text-small text-bold" style="margin-left: -1mm;">{{$exchange->method}}</div>
        <div class="line mt-xs"></div>
        <div class="position-relative mt-md mb-lg text-bold width-100">
            <div class="right-auto-box" style="margin-right: 2.5mm;">
                <label>TOTAL AMOUNT: </label>
                <label class="font-normal">
                    {{($exchange->method == 'SELL' ? 'NGN ₦' : $exchange->currency->name).' '.number_format($exchange->total)}}
                </label>
            </div>
        </div>
        <div class="mt-lg"></div>
        <div class="line mt-lg mb-md"></div>
        <div class="text-bold mt-md mb-md">AMOUNT PAID BY CUSTOMER:
            <label class="font-normal" style="white-space: nowrap;">
                {{$exchange->method == 'SELL' ? $exchange->currency->name.' '.number_format($exchange->amount) :
                    'NGN ₦ '.number_format($exchange->amount*$exchange->rate) }}
            </label>
        </div>
        <div class="text-bold mt-md mb-md">CUSTOMER PAYMENT METHOD: {{$exchange->customer_pay}}</div>
        <div class="text-bold mt-md">COMPANY PAYMENT METHOD: {{$exchange->company_pay}}</div>
        <div class="text-center" style="position: absolute; bottom: 5mm;">Thank you for your patronage, we would love to have you next time</div>
    </div>
</body>
</html>
