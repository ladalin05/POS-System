<link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Public+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

    <style>
        #invoice-wrap * { box-sizing: border-box; }

        #invoice-wrap {
            --ink: #1c2b26;
            --muted: #7d8b84;
            --line: #dfe3de;
            --paper: #fbfaf5;
            --paper-deep: #f2f0e7;
            --accent: #9a6a2f;
            --accent-deep: #6f4b1f;
            --ok: #3f7a4f;
            --ok-bg: #e7f2e8;
            --warn: #a15b1f;
            --warn-bg: #f7ecdd;
            --bad: #a13a2f;
            --bad-bg: #f7e3df;

            background: var(--paper-deep);
            padding: 20px;
            margin: -1.5rem;
            font-family: 'Public Sans', -apple-system, sans-serif;
            color: var(--ink);
        }

        .inv-sheet {
            position: relative;
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(28,43,38,.06);
            padding: 36px 40px 32px 40px;
            overflow: hidden;
        }

        .inv-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 18px;
            border-bottom: 2px solid var(--ink);
        }

        .photo {
            width: 58px;
            height: 58px;
            object-fit: contain;
            border-radius: 6px;
            border: 1px solid var(--line);
            background: #fff;
        }

        .brand-block {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-block h1 {
            margin: 0;
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 20px;
            color: var(--ink);
            line-height: 1.2;
        }

        .brand-block .sub {
            margin-top: 3px;
            font-size: 11.5px;
            color: var(--muted);
            line-height: 1.5;
        }

        .inv-title-block { text-align: right; }

        .inv-title-block .eyebrow {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10.5px;
            letter-spacing: 3px;
            color: var(--accent-deep);
            text-transform: uppercase;
        }

        .inv-title-block .num {
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 30px;
            color: var(--ink);
            line-height: 1.15;
        }

        .status-pill {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 12px;
            border-radius: 20px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .status-pill.paid { background: var(--ok-bg); color: var(--ok); }
        .status-pill.partial, .status-pill.pending { background: var(--warn-bg); color: var(--warn); }
        .status-pill.unpaid, .status-pill.overdue, .status-pill.cancelled { background: var(--bad-bg); color: var(--bad); }

        .inv-top {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 18px;
            margin-top: 22px;
        }

        .box h6 {
            margin: 0 0 10px 0;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--accent-deep);
            padding-bottom: 6px;
            border-bottom: 1px solid var(--line);
        }

        .field-row {
            display: grid;
            grid-template-columns: 68px 1fr;
            gap: 4px 10px;
            font-size: 13px;
            padding: 2px 0;
        }

        .field-row .lbl { color: var(--muted); }
        .field-row .val { font-weight: 600; color: var(--ink); }

        .right-box .field-row { grid-template-columns: 78px 1fr; }
        .right-box .val.mono { font-family: 'IBM Plex Mono', monospace; font-weight: 500; }

        .inv-table {
            width: 100%;
            margin-top: 26px;
            border-collapse: collapse;
        }

        .inv-table thead th {
            background: var(--ink);
            color: #fdf6ea;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .6px;
            text-transform: uppercase;
            padding: 10px 12px;
            text-align: left;
        }

        .inv-table th.num-col, .inv-table td.num-col { text-align: center; }
        .inv-table th.text-end, .inv-table td.text-end { text-align: right; }

        .inv-table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            vertical-align: top;
        }

        .inv-table tbody tr:nth-child(even) { background: #f8f7f1; }

        .item-name { font-weight: 600; color: var(--ink); }
        .item-code { font-size: 11px; color: var(--muted); margin-top: 2px; }

        .mono-num { font-family: 'IBM Plex Mono', monospace; }

        .totals-wrap {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-top: 18px;
        }

        .note {
            font-size: 11px;
            color: var(--muted);
            max-width: 260px;
            line-height: 1.6;
        }

        .totals { width: 270px; }

        .totals .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 4px;
            font-size: 13px;
            border-bottom: 1px solid var(--line);
        }

        .totals .row .lbl { color: var(--muted); }
        .totals .row .val { font-family: 'IBM Plex Mono', monospace; font-weight: 500; }

        .totals .row.due .val { color: var(--bad); font-weight: 600; }

        .totals .row.grand {
            border-bottom: none;
            border-top: 2px solid var(--ink);
            margin-top: 4px;
            padding-top: 12px;
        }

        .totals .row.grand .lbl {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 15px;
            color: var(--ink);
        }

        .totals .row.grand .val {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-size: 17px;
            color: var(--accent-deep);
        }

        .sign {
            width: 200px;
            text-align: center;
            color: var(--muted);
            font-size: 11.5px;
            margin-top: 40px;
            margin-left: auto;
        }

        .sign .line {
            height: 1px;
            background: var(--ink);
            opacity: .35;
            margin-bottom: 8px;
        }

        @media print {
            @page { size: A4; margin: 0; }
            html, body { margin: 0 !important; padding: 0 !important; background: #fff !important; }
            body * { visibility: hidden !important; }
            #invoice-print-area, #invoice-print-area * { visibility: visible !important; }
            #invoice-print-area {
                position: fixed !important;
                inset: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            #invoice-wrap { padding: 10mm !important; margin: 0 !important; background: #fff !important; }
            .inv-sheet { box-shadow: none !important; border: none !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .no-print { display: none !important; }
        }
    </style>

    @php
        $statusKey = strtolower($invoice->status ?? 'pending');
        $currencySymbol = $invoice->currency->symbol ?? '$';
        $rows = $invoiceDetails ?? ($invoice->details ?? []);
    @endphp

    <div id="invoice-print-area">
        <div id="invoice-wrap" >
            <div class="inv-sheet">

                <div class="inv-head">
                    <div class="brand-block">
                        <img class="photo" src="{{ $invoice->warehouse?->logo ?? 'http://localhost:9000/pos-system/no-image.png' }}" alt="Logo">
                        <div>
                            <h1>{{ $invoice->warehouse->name ?? '—' }}</h1>
                            <div class="sub">
                                {{ $invoice->warehouse->address ?? '—' }} &nbsp;·&nbsp; Tel: {{ $invoice->warehouse->phone ?? '—' }}<br>
                                Currency: {{ $invoice->currency->name ?? '—' }}
                            </div>
                        </div>
                    </div>
                    <div class="inv-title-block">
                        <div class="eyebrow">Sales Invoice</div>
                        <div class="num">{{ $invoice->invoice_no ?? '#' . $invoice->id }}</div>
                        <div class="status-pill {{ $statusKey }}">{{ $invoice->status ?? 'Pending' }}</div>
                    </div>
                </div>

                <div class="inv-top">
                    <div class="box">
                        <h6>Billed To</h6>
                        <div class="field-row">
                            <span class="lbl">Name</span>
                            <span class="val">{{ $invoice->customer->name ?? '—' }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Address</span>
                            <span class="val">{{ $invoice->customer->address ?? '—' }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Tel</span>
                            <span class="val">{{ $invoice->customer->phone ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="box right-box">
                        <h6>Details</h6>
                        <div class="field-row">
                            <span class="lbl">Invoice No</span>
                            <span class="val mono">{{ $invoice->invoice_no ?? '#' . $invoice->id }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Date</span>
                            <span class="val mono">{{ optional($invoice->invoice_date)->format('d M Y') ?? '—' }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Warehouse</span>
                            <span class="val">{{ $invoice->warehouse->name ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <table class="inv-table">
                    <thead>
                        <tr>
                            <th style="width:38px">No</th>
                            <th>Product</th>
                            <th class="num-col" style="width:80px">Qty</th>
                            <th class="text-end" style="width:100px">Unit Price</th>
                            <th class="text-end" style="width:90px">Tax</th>
                            <th class="text-end" style="width:100px">Discount</th>
                            <th class="text-end" style="width:110px">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $item)
                            <tr>
                                <td class="mono-num">{{ $i + 1 }}</td>
                                <td>
                                    <div class="item-name">{{ $item->product->name ?? '—' }}</div>
                                    <div class="item-code">{{ $item->product->code ?? $item->product->sku ?? '' }}</div>
                                </td>
                                <td class="num-col mono-num">{{ number_format($item->quantity ?? 0, 0) }}</td>
                                <td class="text-end mono-num">{{ $currencySymbol }}{{ number_format($item->unit_price ?? 0, 2) }}</td>
                                <td class="text-end mono-num">{{ $currencySymbol }}{{ number_format($item->tax ?? 0, 2) }}</td>
                                <td class="text-end mono-num">{{ $currencySymbol }}{{ number_format($item->discount ?? 0, 2) }}</td>
                                <td class="text-end mono-num">
                                    {{ $currencySymbol }}{{ number_format($item->total ?? (($item->quantity ?? 0) * ($item->unit_price ?? 0)), 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; color:var(--muted); padding:18px;">No items found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="totals-wrap">
                    <div class="note">Thank you for your business. Please retain this invoice for your records; it serves as proof of purchase.</div>
                    <div class="totals">
                        <div class="row"><span class="lbl">Subtotal</span><span class="val">{{ $currencySymbol }}{{ number_format($invoice->subtotal ?? 0, 2) }}</span></div>
                        <div class="row"><span class="lbl">Tax</span><span class="val">{{ $currencySymbol }}{{ number_format($invoice->tax_amount ?? 0, 2) }}</span></div>
                        <div class="row"><span class="lbl">Discount</span><span class="val">{{ $currencySymbol }}{{ number_format($invoice->discount_amount ?? 0, 2) }}</span></div>
                        <div class="row grand"><span class="lbl">Grand Total</span><span class="val">{{ $currencySymbol }}{{ number_format($invoice->grand_total ?? 0, 2) }}</span></div>
                        <div class="row"><span class="lbl">Paid</span><span class="val">{{ $currencySymbol }}{{ number_format($invoice->paid_amount ?? 0, 2) }}</span></div>
                        <div class="row due"><span class="lbl">Due</span><span class="val">{{ $currencySymbol }}{{ number_format($invoice->due_amount ?? 0, 2) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>