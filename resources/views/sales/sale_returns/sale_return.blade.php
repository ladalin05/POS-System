<link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Public+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

    <style>
        #return-wrap * { box-sizing: border-box; }

        #return-wrap {
            --ink: #1c2b26;
            --muted: #7d8b84;
            --line: #dfe3de;
            --paper: #fbfaf5;
            --paper-deep: #f2f0e7;
            --accent: #9a3a2f;
            --accent-deep: #7a2a20;
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

        .rtn-sheet {
            position: relative;
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(28,43,38,.06);
            padding: 36px 40px 32px 40px;
            overflow: hidden;
        }

        .rtn-sheet::before {
            content: "RETURN";
            position: absolute;
            top: 46px;
            right: -46px;
            transform: rotate(45deg);
            background: var(--accent);
            color: #fdf6ea;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 4px 52px;
            box-shadow: 0 2px 4px rgba(0,0,0,.15);
        }

        .rtn-head {
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

        .rtn-title-block { text-align: right; }

        .rtn-title-block .eyebrow {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10.5px;
            letter-spacing: 3px;
            color: var(--accent-deep);
            text-transform: uppercase;
        }

        .rtn-title-block .num {
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

        .status-pill.settled { background: var(--ok-bg); color: var(--ok); }
        .status-pill.partial { background: var(--warn-bg); color: var(--warn); }
        .status-pill.owed { background: var(--bad-bg); color: var(--bad); }

        .rtn-top {
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
            grid-template-columns: 90px 1fr;
            gap: 4px 10px;
            font-size: 13px;
            padding: 2px 0;
        }

        .field-row .lbl { color: var(--muted); }
        .field-row .val { font-weight: 600; color: var(--ink); }

        .right-box .val.mono { font-family: 'IBM Plex Mono', monospace; font-weight: 500; }

        .rtn-table {
            width: 100%;
            margin-top: 26px;
            border-collapse: collapse;
        }

        .rtn-table thead th {
            background: var(--ink);
            color: #fdf6ea;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .6px;
            text-transform: uppercase;
            padding: 10px 12px;
            text-align: left;
        }

        .rtn-table th.num-col, .rtn-table td.num-col { text-align: center; }
        .rtn-table th.text-end, .rtn-table td.text-end { text-align: right; }

        .rtn-table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            vertical-align: top;
        }

        .rtn-table tbody tr:nth-child(even) { background: #f8f7f1; }

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

        .totals .row.balance .val { color: var(--bad); font-weight: 600; }

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
            #return-print-area, #return-print-area * { visibility: visible !important; }
            #return-print-area {
                position: fixed !important;
                inset: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            #return-wrap { padding: 10mm !important; margin: 0 !important; background: #fff !important; }
            .rtn-sheet { box-shadow: none !important; border: none !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .no-print { display: none !important; }
        }
    </style>

    @php
        $balance = $saleReturn->balance ?? 0;
        $statusKey = $balance <= 0 ? 'settled' : (($saleReturn->paid ?? 0) > 0 ? 'partial' : 'owed');
        $statusLabel = $balance <= 0 ? 'Settled' : (($saleReturn->paid ?? 0) > 0 ? 'Partially Refunded' : 'Balance Owed');
        $rows = $saleReturnItems ?? ($saleReturn->items ?? []);
    @endphp

    <div id="return-print-area">
        <div id="return-wrap">
            <div class="rtn-sheet">

                <div class="rtn-head">
                    <div class="brand-block">
                        <img class="photo" src="{{ $saleReturn->warehouse?->logo ?? 'http://localhost:9000/pos-system/no-image.png' }}" alt="Logo">
                        <div>
                            <h1>{{ $saleReturn->warehouse->name ?? '—' }}</h1>
                            <div class="sub">
                                {{ $saleReturn->warehouse->address ?? '—' }} &nbsp;·&nbsp; Tel: {{ $saleReturn->warehouse->phone ?? '—' }}
                            </div>
                        </div>
                    </div>
                    <div class="rtn-title-block">
                        <div class="eyebrow">Sale Return</div>
                        <div class="num">{{ $saleReturn->reference_no ?? '#' . $saleReturn->id }}</div>
                        <div class="status-pill {{ $statusKey }}">{{ $statusLabel }}</div>
                    </div>
                </div>

                <div class="rtn-top">
                    <div class="box">
                        <h6>Returned By</h6>
                        <div class="field-row">
                            <span class="lbl">Name</span>
                            <span class="val">{{ $saleReturn->customer->name ?? '—' }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Address</span>
                            <span class="val">{{ $saleReturn->customer->address ?? '—' }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Tel</span>
                            <span class="val">{{ $saleReturn->customer->phone ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="box right-box">
                        <h6>Details</h6>
                        <div class="field-row">
                            <span class="lbl">Return Ref</span>
                            <span class="val mono">{{ $saleReturn->reference_no ?? '#' . $saleReturn->id }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Date</span>
                            <span class="val mono">{{ optional($saleReturn->date)->format('d M Y') ?? '—' }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Original Sale</span>
                            <span class="val mono">{{ $saleReturn->sale->reference_no ?? ($saleReturn->sale_id ? '#' . $saleReturn->sale_id : '—') }}</span>
                        </div>
                        <div class="field-row">
                            <span class="lbl">Warehouse</span>
                            <span class="val">{{ $saleReturn->warehouse->name ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <table class="rtn-table">
                    <thead>
                        <tr>
                            <th style="width:38px">No</th>
                            <th>Product</th>
                            <th class="num-col" style="width:90px">Qty</th>
                            <th class="text-end" style="width:110px">Price</th>
                            <th class="text-end" style="width:120px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $item)
                            <tr>
                                <td class="mono-num">{{ $i + 1 }}</td>
                                <td>
                                    <div class="item-name">{{ $item->product->name ?? '—' }}</div>
                                    <div class="item-code">{{ $item->product_code ?? ($item->product->code ?? '') }}</div>
                                </td>
                                <td class="num-col mono-num">{{ number_format($item->quantity ?? 0, 0) }}</td>
                                <td class="text-end mono-num">${{ number_format($item->price ?? 0, 2) }}</td>
                                <td class="text-end mono-num">
                                    ${{ number_format($item->subtotal ?? (($item->quantity ?? 0) * ($item->price ?? 0)), 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; color:var(--muted); padding:18px;">No returned items found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="totals-wrap">
                    <div class="note">{{ $saleReturn->note ?? 'This document confirms the items listed above have been returned and processed against the original sale.' }}</div>
                    <div class="totals">
                        <div class="row"><span class="lbl">Total</span><span class="val">${{ number_format($saleReturn->total ?? 0, 2) }}</span></div>
                        <div class="row"><span class="lbl">Tax</span><span class="val">${{ number_format($saleReturn->tax ?? 0, 2) }}</span></div>
                        <div class="row grand"><span class="lbl">Grand Total</span><span class="val">${{ number_format($saleReturn->grand_total ?? 0, 2) }}</span></div>
                        <div class="row"><span class="lbl">Paid / Refunded</span><span class="val">${{ number_format($saleReturn->paid ?? 0, 2) }}</span></div>
                        <div class="row balance"><span class="lbl">Balance</span><span class="val">${{ number_format($balance, 2) }}</span></div>
                    </div>
                </div>

                <div class="sign">
                    <div class="line"></div>
                    Authorized Signature
                </div>
            </div>
        </div>
    </div>