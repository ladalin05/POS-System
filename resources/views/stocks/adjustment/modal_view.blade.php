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

    .inv-logo {
        width: 46px;
        height: 46px;
        object-fit: contain;
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

    /* Table like the screenshot: thin blue grid */
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

    /* Totals card (blue footer look) */
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

    /* Payment block with ticks */
    .pay {
        margin-top: 16px;
    }

    .ticks {
        display: flex;
        gap: 18px;
        margin-top: 8px;
        color: var(--ink);
    }

    .tick i {
        display: inline-block;
        width: 10px;
        height: 10px;
        border: 2px solid var(--ink);
        border-radius: 50%;
        margin-right: 6px;
        vertical-align: -2px;
    }

    /* Signature line right bottom */
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

    .photo {
        width: 90px;
        height: 90px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    /* Print only the sheet */
    @media print {

        /* 1) Use the whole physical page */
        @page {
            size: A4;
            margin: 0;
        }

        /* remove browser page margins */
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        /* 2) Only print the invoice area */
        body * {
            visibility: hidden !important;
        }

        #invoice-print-area,
        #invoice-print-area * {
            visibility: visible !important;
        }

        /* 3) Make the invoice fill the paper */
        #invoice-print-area {
            position: fixed !important;
            /* escape any narrow ancestors */
            inset: 0 !important;
            /* top/right/bottom/left = 0 */
            width: 210mm !important;
            /* full A4 width */
            height: 297mm !important;
            /* full A4 height */
            max-width: none !important;
            margin: 0 !important;
            padding: 10mm !important;
            /* inner safe margin, adjust if you want tighter */
            box-sizing: border-box;
            transform: none !important;
        }

        /* 4) Let content stretch */
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

        /* 5) Table header repeats and avoids ugly breaks */
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

        /* 6) Keep colors visible in print */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hide screen-only controls */
        .no-print {
            display: none !important;
        }

        .sign {
            position: absolute;
            /* take it out of flow */
            bottom: 50mm;
            /* distance from bottom edge */
            right: 15mm;
            /* distance from right edge */
            width: 180px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            /* muted */
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
        <!-- header -->
        <div class="inv-head">
            <div class="inv-brand">
                @php
                    $img = $form?->branch->logo ? asset($form?->branch->logo) : asset('assets/images/no_image.png');
                @endphp

                <img class="photo" src="{{ $img }}" alt="Photo">
                <h4>{{ $form->branch->name ?? '—' }}</h4>
            </div>

            <div class="inv-title">INVOICE</div>
        </div>


        <!-- top boxes -->
        <div class="inv-top">
            <!-- <div class="box">
                <h6>Invoice to:</h6>
                <div class="lane">
                    <div class="lbl">Name</div>
                    <div class="val">{{ $form->to_name ?? $form->customer_name ?? '—' }}</div>

                    <div class="lbl">Address</div>
                    <div class="val">{{ $form->to_address ?? $form->customer_address ?? '—' }}</div>

                    <div class="lbl">Phone</div>
                    <div class="val">{{ $form->to_phone ?? $form->customer_phone ?? '—' }}</div>
                </div>
            </div> -->
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

        <!-- items table -->
        <table class="inv-table">
            <thead>
                <tr>
                    <th style="width:52px;">No</th>
                    <th>product</th>
                    <th style="width:90px;" class="text-end">{{ __('global.qoh') }}</th>
                    <th style="width:90px;" class="text-end">{{ __('global.type') }}</th>
                    <th style="width:90px;" class="text-end">{{ __('global.quantity') }}</th>
                    <th style="width:90px;" class="text-end">{{ __('global.unit') }}</th>
                    <th style="width:90px;" class="text-end">{{ __('global.new_qoh') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)


                    @php
                        $pname = data_get($r, 'product.name') ?? data_get($r, 'name', '—');
                        $pcode = data_get($r, 'product.code') ?? data_get($r, 'code', '');
                        $qoh = data_get($r, 'qoh', '');
                        $type = data_get($r, 'type', '');
                        $qty = (float) data_get($r, 'quantity', 0);
                        $product_unit_code = data_get($r, 'product_unit_code', '');
                        $new_qoh = data_get($r, 'new_qoh', '');

                      @endphp

                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="val">
                                {{ $pname }}
                                @if($pcode)<span class="muted">({{ $pcode }})</span>@endif
                            </div>
                        </td>
                        <td class="text-end">{{ $qoh }}</td>
                        <td class="text-end">{{ $type }}</td>
                        <td class="text-end">{{ number_format($qty, 2) }}</td>
                        <td class="text-end">{{ $product_unit_code }}</td>
                        <td class="text-end">{{ $new_qoh }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted" style="text-align:center; padding:16px;">No items</td>
                    </tr>
                @endforelse

                {{-- add blank rows to mimic the look --}}
                @for ($k = max(0, 10 - $rows->count()); $k > 0; $k--)
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        {{-- totals card --}}
        @if($hasPrice)
            <div class="totals">
                <div class="row"><span>Sub Total</span><span>{{ number_format($subTotal, 2) }}</span></div>
                <div class="row"><span>Tax</span><span>{{ number_format($tax, 2) }}</span></div>
                <div class="row"><span>TOTAL</span><span>{{ number_format($grand, 2) }}</span></div>
            </div>
        @endif

        <!-- payment info -->
        <!-- <div class="box pay">
            <div class="lbl" style="margin-bottom:6px;">Payment Info:</div>
            <div class="lane" style="grid-template-columns:150px 1fr;">
                <div class="lbl">Account #</div>
                <div class="val">{{ $form->account_no ?? '—' }}</div>
                <div class="lbl">A/C Name</div>
                <div class="val">{{ $form->account_name ?? '—' }}</div>
                <div class="lbl">Bank Details</div>
                <div class="val">{{ $form->bank_details ?? 'Add your bank details.' }}</div>
            </div>
            <div class="ticks">
                <span class="tick"><i></i> Phone</span>
                <span class="tick"><i></i> Address</span>
                <span class="tick"><i></i> Mail</span>
                <span class="tick"><i></i> Website</span>
            </div>
        </div> -->

        <!-- signature -->
        <div class="sign">Authorised Sign</div>
    </div>
</div>

{{-- screen actions --}}
<div class="no-print" style="display:flex; justify-content:flex-end; gap:10px; margin-top:14px;">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.close') }}</button>
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="ph ph-printer me-1"></i> Print
    </button>
</div>