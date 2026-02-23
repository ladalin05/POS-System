<div class="modal-body p-3">

    @php
        $tz = 'Asia/Phnom_Penh';
        $rows = collect($items ?? ($form->products ?? []));
        $hasPrice = $rows->first(fn($r) => !is_null(data_get($r, 'price')) || !is_null(data_get($r, 'unit_cost')));
        $subTotal = 0.0;
        foreach ($rows as $r) {
            $qty = (float) data_get($r, 'quantity', 0);
            $price = (float) (data_get($r, 'price') ?? data_get($r, 'unit_cost') ?? 0);
            $subTotal += $qty * $price;
        }
        $taxRate = (float) ($form->tax_rate ?? 0);
        $tax = $subTotal * $taxRate;
        $grand = $subTotal + $tax;
    @endphp

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
            width: 46px;
            height: 46px;
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
            color: var(--ink);
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
                bottom: 50mm;
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
                    @php $img = $form?->branch?->logo ? asset($form->branch->logo) : asset('assets/images/no_image.png'); @endphp
                    <img class="photo" src="{{ $img }}" alt="Logo">
                    <h4>{{ $form->branch->name ?? '—' }}</h4>
                </div>
                <div class="inv-title">INVOICE</div>
            </div>

            <div class="inv-top">
                <div class="box">
                    <div class="meta">
                        <div>
                            <div class="lbl">Invoice #</div>
                            <div class="val">{{ $form->reference_no ?? $form->id }}</div>
                        </div>
                        <div>
                            <div class="lbl">Date</div>
                            <div class="val">
                                {{ optional($form->date)->timezone($tz)->format('d/m/Y') ?? now($tz)->format('d/m/Y') }}
                            </div>
                        </div>
                        <div>
                            <div class="lbl">Branch</div>
                            <div class="val">{{ $form->branch->name ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="lbl">Warehouse</div>
                            <div class="val">{{ $form->warehouse->name ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="inv-table">
                <thead>
                    <tr>
                        <th style="width:52px;">No</th>
                        <th>Product</th>
                        <th style="width:90px;" class="text-end">{{ __('global.net_unit_cost') }}</th>
                        <th style="width:90px;" class="text-end">{{ __('global.quantity') }}</th>
                        <th style="width:90px;" class="text-end">{{ __('global.discount') }}</th>
                        <th style="width:90px;" class="text-end">{{ __('global.subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $r)
                        @php

                            $pname = data_get($r, 'product.name') ?? data_get($r, 'name', '—');
                            $pcode = data_get($r, 'product.code') ?? data_get($r, 'code', '');
                            $net_unit_cost = data_get($r, 'net_unit_cost', '');
                            $qty = (float) data_get($r, 'quantity', 0);
                            $discount = data_get($r, 'discount', '');
                            $subtotal = data_get($r, 'subtotal', '');
                          @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div class="val">
                                    {{ $pname }}
                                    @if($pcode)<span class="muted">({{ $pcode }})</span>@endif
                                </div>
                            </td>
                            <td class="text-end">{{ $net_unit_cost }}</td>
                            <td class="text-end">{{ number_format($qty, 2) }}</td>
                            <td class="text-end">{{ $discount }}</td>
                            <td class="text-end">{{ $subtotal }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted" style="text-align:center; padding:16px;">No items</td>
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
                
                    <div class="row"><span>TOTAL</span><span>{{ number_format($form->total, 2) }}</span></div>
                    <div class="row"><span>order_discount</span><span>{{ number_format($form->order_discount, 2) }}</span></div>
                    <div class="row"><span>grand_total</span><span>{{ number_format($form->grand_total, 2) }}</span></div>
                </div>
            @endif

            <div class="sign">Authorised Sign</div>
        </div>
    </div>
</div>

<div class="modal-footer no-print">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.close') }}</button>
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="ph ph-printer me-1"></i> {{ __('global.print') }}
    </button>
</div>