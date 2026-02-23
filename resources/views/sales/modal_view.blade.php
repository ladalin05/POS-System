<div class="modal-body p-3">


    <style>
        :root {
            --ink: #001e2b;
            --muted: #74828f;
            --line: #d8e4f4;
            --panel: #f7fbff;
            --accent: #1f75ff;
            --accent-deep: #0f4fd6;
        }

        .inv-sheet {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 22px;
        }

        .inv-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 16px;
        }

        .inv-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .photo {
            width: 96px;
            height: 99px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        .inv-brand h4 {
            margin: 0;
            color: var(--ink);
            font-size: 20px;
            font-weight: 700;
        }

        .inv-title {
            color: var(--accent-deep);
            font-weight: 900;
            font-size: 28px;
            letter-spacing: .5px;
        }

        .inv-top {
            display: grid;
            grid-template-columns: 1.2fr .9fr;
            gap: 16px;
            margin-top: 14px;
        }

        .box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            background: #fff;
        }

        .box h6 {
            margin: 0 0 8px 0;
            font-weight: 800;
            color: var(--ink);
            font-size: 13px;
        }

        .lane {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 8px 12px;
            color: var(--ink);
        }

        .lbl {
            color: var(--muted);
            font-size: 12px;
        }

        .val {
            font-weight: 600;
            color: var(--ink);
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 16px;
        }

        .inv-table {
            width: 100%;
            margin-top: 14px;
            border-collapse: collapse;
        }

        .inv-table th,
        .inv-table td {
            border: 1px solid var(--line);
            padding: 7px 10px;
        }

        .inv-table th {
            background: #eef5ff;
            color: white;
            font-size: 12.5px;
            font-weight: 800;
        }

        .text-end {
            text-align: right;
        }

        .muted {
            color: var(--muted);
            font-size: 12px;
        }

        .totals {
            width: 240px;
            margin-left: auto;
            margin-top: 8px;
            border: 1px solid var(--line);
            border-radius: 10px;
            overflow: hidden;
        }

        .totals .row {
            display: flex;
            justify-content: space-between;
            padding: 9px 12px;
            border-bottom: 1px solid var(--line);
        }

        .totals .row:last-child {
            background: #e8f1ff;
            font-weight: 900;
            border-bottom: none;
        }

        .sign {
            width: 240px;
            margin-left: auto;
            margin-top: 36px;
            text-align: center;
            color: var(--muted);
        }

        .sign:before {
            content: "";
            display: block;
            height: 1px;
            background: #d7dbe2;
            margin-bottom: 6px;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }

            body * {
                visibility: hidden !important;
            }

            #invoice-print-area,
            #invoice-print-area * {
                visibility: visible !important;
            }

            #invoice-print-area {
                position: fixed !important;
                inset: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 10mm !important;
                box-sizing: border-box;
                transform: none !important;
            }

            .inv-sheet {
                border: 0 !important;
                border-radius: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }

            .inv-head,
            .inv-top,
            .totals,
            .inv-table {
                width: 100% !important;
            }

            .inv-table {
                border-collapse: collapse;
                page-break-inside: auto;
            }

            .inv-table thead {
                display: table-header-group;
            }

            .inv-table tr {
                page-break-inside: avoid;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .sign {
                position: absolute;
                bottom: 10mm;
                right: 15mm;
                width: 180px;
                text-align: center;
                font-size: 12px;
                color: #6c757d;
            }

            .sign:before {
                content: "";
                display: block;
                height: 1px;
                background: #cbd5e0;
                margin-bottom: 6px;
            }
        }
    </style>

    <div id="invoice-print-area">
        <div class="inv-sheet">
            <div class="inv-head">


                <div class="inv-brand">
                    @php $img = $sale?->branch?->logo ? asset($sale->branch->logo) : asset('assets/images/logo.jpg'); @endphp
                    <img class="photo" src="{{ $img }}" alt="Logo">
                </div>


                <div class="company text-center">
                    <h1>{{ $sale->biller->name ?? '—' }}</h1>


                    <div class="sub">
                      {{ $sale->biller->address ?? '—' }}\ {{ $sale->biller->phone ?? '—' }}<br>
                        Email: {{ $sale->biller->phone ?? '—' }}
                    </div>
                </div>

                <div class="inv-brand">
                    @php $img = $sale?->branch?->logo ? asset($sale->branch->logo) : asset('assets/images/logo.jpg'); @endphp
                    <img class="photo" src="{{ $img }}" alt="Logo">
                </div>

            </div>
            <div class="inv-title text-center">INVOICE</div>

            <div class="inv-top">


                <div class="box">
                    <div class="meta">
                        <div class="info-grid">
                            <div class="label">Customer / អតិថិជន</div>
                            <div><strong>Name :</strong> {{ $sale->customer->name ?? ($sale->customer_name ?? '') }}
                            </div>
                            <div><strong>Address :</strong>
                                {{ $sale->customer->address ?? ($sale->customer_address ?? '') }}
                            </div>
                            <div><strong>Tel :</strong> {{ $sale->customer->phone ?? ($sale->customer_phone ?? '') }}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="box right-box">
                    <div class="label">Reference / ល.អ</div>
                    <div style="font-size:14px; margin-bottom:6px;"><strong>Ref:</strong>
                        {{ $sale->reference_no ?? '—' }}
                    </div>
                    <div style="font-size:14px; margin-bottom:6px;"><strong>Date:</strong>
                        {{ $sale->date ?? ($sale->created_at ?? now()) }}</div>
                    <div style="font-size:14px;"><strong>Salesman:</strong>
                        {{ $sale->biller_name ?? ($sale->biller_id ?? '') }}</div>
                </div>



            </div>

            <table class="inv-table">
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
                            <td class="text-right">${{ number_format($item->unit_price ?? ($item['unit_price'] ?? 0), 2) }}
                            </td>
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

                    @for ($k = max(0, 10 - $rows->count()); $k > 0; $k--)
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>
                    @endfor
                </tbody>
            </table>

            @if($hasPrice = true)
                <div class="totals">

                    <div class="row"><span>TOTAL</span><span>{{ number_format($sale->total, 2) }}</span></div>
                    <div class="row"><span>order_discount</span><span>{{ number_format($sale->order_discount, 2) }}</span>
                    </div>
                    <div class="row"><span>grand_total</span><span>{{ number_format($sale->grand_total, 2) }}</span></div>
                </div>
            @endif

            <div class="sign">Authorised Sign</div>
        </div>
    </div>
</div>

<div class="modal-footer no-print">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('close') }}</button>
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="ph ph-printer me-1"></i> {{ __('print') }}
    </button>
</div>