<!doctype html>
<html lang="km">

<head>
    <meta charset="utf-8" />
    <title>Invoice #{{ $sale->id ?? '—' }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", Arial, sans-serif;
            color: #222;
            background: #fff;
        }

        * {
            box-sizing: border-box;
        }

        /* Container: make full width for print */
        .wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 12px;
        }

        /* Header */
        .header {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .logo {
            width: 160px;
        }

        .logo img {
            width: 160px;
            height: auto;
            display: block;
        }

        .company {
            flex: 1;
            text-align: center;
            padding-top: 6px;
        }

        .company h1 {
            margin: 0;
            font-size: 30px;
            letter-spacing: 1px;
            color: #222;
        }

        .company .sub {
            margin-top: 6px;
            color: #444;
            font-size: 12.5px;
            line-height: 1.15;
        }

        /* QR / Invoice text */
        .meta-block {
            width: 180px;
            text-align: right;
        }

        .invoice-title {
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 8px;
        }

        .qr {
            width: 110px;
            height: 110px;
            border: 1px solid #ddd;
            display: inline-block;
        }

        /* small accent line under header */
        .accent-line {
            height: 6px;
            background: #2b83c6;
            margin-top: 8px;
            border-radius: 3px;
        }

        /* Info boxes row */
        .info-row {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }

        .box {
            flex: 1;
            border: 2px solid #2b83c6;
            border-radius: 8px;
            padding: 10px 12px;
            background: #fff;
        }

        .box .label {
            font-weight: 700;
            color: #2b83c6;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .right-box {
            width: 310px;
        }

        .info-grid div {
            margin-bottom: 4px;
            font-size: 13px;
        }

        /* Items table */
        .items {
            margin-top: 14px;
            border-collapse: collapse;
            width: 100%;
            font-size: 13px;
        }

        .items thead th {
            background: #1f7d9b;
            color: white;
            padding: 10px;
            font-weight: 700;
            border: 1px solid #1f5f8f;
            text-align: left;
        }

        .items tbody td {
            padding: 10px;
            border: 1px solid #d6d6d6;
            vertical-align: top;
        }

        .items tbody tr:nth-child(even) td {
            background: #fbfbfb;
        }

        /* small text */
        .text-right {
            text-align: right;
        }

        .small {
            font-size: 12px;
            color: #666;
        }

        /* Totals box to the right */
        .totals-wrap {
            width: 360px;
            float: right;
            margin-top: 14px;
        }

        .totals {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 8px 12px;
            border: 1px solid #d6d6d6;
            font-size: 13px;
        }

        .totals .label {
            background: #fff;
            color: #333;
        }

        .totals .amount {
            text-align: right;
            min-width: 120px;
        }

        .totals .grand {
            background: #e9f3ff;
            font-weight: 700;
        }

        /* Footer note */
        .note {
            clear: both;
            margin-top: 50px;
            font-size: 12px;
            color: #666;
        }

        /* ---------- Clean A4 print stylesheet (paste at end of <style>) ---------- */
        @page {
            size: A4 portrait;
            margin: 0;
        }



        @media print {
            .no-print {
                display: none;
            }

            /* ensure page dims */
            html,
            body {
                width: 110mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }

            /* hide everything, then show only invoice area */
            body * {
                visibility: hidden;
            }

            #invoice-print-area,
            #invoice-print-area * {
                visibility: visible;
            }

            /* make the print area exactly one A4 page */
            #invoice-print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 210mm;
                height: 297mm;
                box-sizing: border-box;
                padding: 0;
                /* inner page margin — change to 10mm or 8mm if you want smaller */
                margin: 0;
                background: #fff;
                overflow: hidden;
            }

            /* wrapper must use the full printable width inside page */
            .wrapper {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box;
            }

            /* restore header flex layout so invoice title won't overlap */
            .header {
                display: flex !important;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
                width: 100%;
                margin: 0 0 8mm 0;
                position: relative;
                /* so .meta-block absolute/align-self is safe */
                box-sizing: border-box;
            }

            .logo {
                flex: 0 0 140px;
                width: 140px;
            }

            .logo img {
                width: 140px;
                height: auto;
                display: block;
            }

            .company {
                flex: 1 1 auto;
                text-align: center;
                padding-top: 6px;
            }

            .company h1 {
                margin: 0;
                font-size: 20px;
            }

            /* invoice label aligned top-right and not overlapping */
            .meta-block {
                flex: 0 0 auto;
                text-align: right;
                align-self: flex-start;
                margin-left: 12px;
            }

            .invoice-title {
                font-weight: 700;
                font-size: 18px;
                margin: 0;
            }

            /* info boxes row */
            .info-row {
                display: flex !important;
                gap: 12px;
                margin-top: 6mm;
                width: 100%;
                box-sizing: border-box;
            }

            /* .info-row .box {
                flex: 1 1 auto;
            } */

            /* .info-row .right-box {
                flex: 0 0 380px;
                max-width: 380px;
            } */

            /* .box {
                border: 2px solid #1f7d9b;
                border-radius: 6px;
                padding: 10px;
                background: #fff;
                box-sizing: border-box;
            } */

            /* items table full width */
            .items {
                width: 100% !important;
                border-collapse: collapse;
                margin-top: 8mm;
                font-size: 11px;
                box-sizing: border-box;
            }

            .items thead th {
                background: #1f7d9b !important;
                color: #fff !important;
                font-weight: 700;
                padding: 10px;
                border: 1px solid #15607a;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .items tbody td {
                padding: 10px;
                border: 1px solid #d6d6d6;
                vertical-align: top;
                box-sizing: border-box;
            }

            .items tbody tr:nth-child(even) td {
                background: #fbfbfb;
            }

            /* text alignment for columns */
            .items th:nth-child(3),
            .items td:nth-child(3) {
                text-align: center;
            }

            .items th:nth-child(4),
            .items td:nth-child(4),
            .items th:nth-child(5),
            .items td:nth-child(5),
            .items th:nth-child(6),
            .items td:nth-child(6) {
                text-align: right;
            }

            /* totals box pinned to right inside page (keeps flow) */
            .totals-wrap {
                width: 360px;
                float: right;
                margin-top: 10mm;
                box-sizing: border-box;
            }

            .totals {
                width: 100%;
                border-collapse: collapse;
                box-sizing: border-box;
            }

            .totals td {
                padding: 10px 12px;
                border: 1px solid #d6d6d6;
                font-size: 13px;
            }

            .totals .amount {
                text-align: right;
                min-width: 120px;
            }

            .totals .grand {
                background: #e9f3ff;
                font-weight: 700;
            }

            .note {
                clear: both;
                margin-top: 12mm;
                font-size: 12px;
                color: #666;
            }

            /* ensure print color fidelity */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* double-check page rule */
            @page {
                margin: 0;
                size: A4 portrait;
            }
        }


        /* Responsive small tweaks for screen preview */
        @media (max-width: 900px) {
            /* .header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            } */

            .meta-block {
                text-align: center;
                width: auto;
                order: 3;
            }

            .right-box {
                width: 100%;
            }

            .totals-wrap {
                float: none;
                width: 100%;
            }

            .items thead th {
                background-color: #1f7d9b !important;
                color: white;
                padding: 10px;
                font-weight: 700;
                border: 1px solid #1f5f8f;
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div id="invoice-print-area" class="wrapper">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="logo">
            </div>

            <div class="company">
                <h1>WONDER FOOD COMPANY</h1>
                <div class="sub">
                    #321 ផ្លូវ (អាសយដ្ឋាន) • Tel: +855 16 92 77 37 / +855 99 92 77 37<br>
                    Email: wonderfoodcambodia@gmail.com
                </div>
            </div>

            <div class="meta-block">
                <div class="invoice-title">Invoice</div>

            </div>
        </div>

        <!-- <div class="accent-line"></div> -->

        <div class="info-row">
            <div class="box">
                <div class="label">Customer / អតិថិជន</div>
                <div class="info-grid">
                    <div><strong>Name :</strong> {{ $sale->customer->name ?? ($sale->customer_name ?? '') }}</div>
                    <div><strong>Address :</strong> {{ $sale->customer->address ?? ($sale->customer_address ?? '') }}
                    </div>
                    <div><strong>Tel :</strong> {{ $sale->customer->phone ?? ($sale->customer_phone ?? '') }}</div>
                </div>
            </div>

            <div class="box right-box">
                <div class="label">Reference / ល.អ</div>
                <div style="font-size:14px; margin-bottom:6px;"><strong>Ref:</strong> {{ $sale->reference_no ?? '—' }}
                </div>
                <div style="font-size:14px; margin-bottom:6px;"><strong>Date:</strong>
                    {{ $sale->date ?? ($sale->created_at ?? now()) }}</div>
                <div style="font-size:14px;"><strong>Salesman:</strong>
                    {{ $sale->biller_name ?? ($sale->biller_id ?? '') }}</div>
            </div>
        </div>

        <table class="items" role="table" aria-label="Invoice items">
            <thead>
                <tr>
                    <th style="width:50px; background-color:#1f7d9b;">No</th>
                    <th style="background-color:#1f7d9b; width:260px">Description</th>
                    <th style="background-color:#1f7d9b; width:110px; text-align:center">Quantity</th>
                    <th style="background-color:#1f7d9b; width:140px; text-align:right">Unit Price</th>
                    <th style="background-color:#1f7d9b; width:160px; text-align:right">Discount</th>
                    <th style="background-color:#1f7d9b; width:160px; text-align:right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    $rows = $saleItems ?? ($sale->saleItems ?? []);
                @endphp

                @forelse($rows as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $item->name ?? ($item['name'] ?? '') }}</div>
                            <div class="small">{{ $item->code ?? ($item['code'] ?? '') }}</div>
                        </td>
                        <td class="text-right">
                            {{ number_format($item->qty ?? ($item['qty'] ?? 0), 0) }}
                            <span>{{ $item->unit->name ?? '' }}</span>
                        </td>
                        <td class="text-right">${{ number_format($item->unit_price ?? ($item['unit_price'] ?? 0), 2) }}</td>
                        <td class="text-right">${{ number_format($item->discount ?? ($item['discount'] ?? 0), 2) }}</td>
                        <td class="text-right">
                            ${{ number_format($item->subtotal ?? ($item['subtotal'] ?? (($item->qty ?? 0) * ($item->unit_price ?? 0))), 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="small">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="totals-wrap" aria-hidden="false">
            <table class="totals" cellspacing="0" role="presentation">
                <tr>
                    <td class="label">Total</td>
                    <td class="amount">${{ number_format($sale->total ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Order Discount</td>
                    <td class="amount"> ${{ number_format($sale->discount ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Shipping</td>
                    <td class="amount">${{ number_format($sale->shipping ?? 0, 2) }}</td>
                </tr>
                <tr class="grand">
                    <td class="label"><strong>Grand Total</strong></td>
                    <td class="amount"><strong>${{ number_format($sale->grand_total ?? 0, 2) }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Balance</td>
                    <td class="amount">${{ number_format($sale->balance ?? 0, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="note">
            <div>កំណត់ចំណាំ: សម្រាប់ព័ត៌មានបន្ថែម សូមទាក់ទងមកក្រុមហ៊ុន។</div>
        </div>

        <button class="no-print" onclick="printInvoice()" style="padding:8px 16px; background:#2b83c6; color:white; 
               border:none; border-radius:4px; cursor:pointer;">
            Print
        </button>
    </div>
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</body>

</html>