@php
    /** @var \App\Models\Finance\Expense $form */
    $tz   = 'Asia/Phnom_Penh';

    // rows = Expense items (collection or array)
    $rows = collect($items ?? ($form->items ?? []));

    // compute totals defensively
    $subTotal = 0.0;
    foreach ($rows as $r) {
        $qty   = (float) data_get($r, 'quantity', 0);
        $cost  = (float) data_get($r, 'unit_cost', 0);
        $line  = (float) (data_get($r, 'subtotal') ?? ($qty * $cost));
        $subTotal += $line;
    }
    $taxRate = (float) ($form->tax_rate ?? 0); // set/keep 0 if you don't use tax
    $tax     = $subTotal * $taxRate;
    $grand   = $subTotal + $tax;

    // branch logo safe URL
    $rawLogo = data_get($form, 'branch.logo');
    if ($rawLogo && str_starts_with($rawLogo, 'public/')) {
        $rawLogo = substr($rawLogo, 7); // drop "public/"
    }
    $logoUrl = $rawLogo
        ? (preg_match('/^(https?:\/\/|\/|data:)/', $rawLogo) ? $rawLogo : asset($rawLogo))
        : asset('assets/images/logo.jpg');
@endphp

<style>
    :root{
        --ink:#001e2b; --muted:#74828f; --line:#d8e4f4; --panel:#f7fbff;
        --accent:#1f75ff; --accent-deep:#0f4fd6;
    }
    .inv-sheet{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:22px;}
    .inv-head{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e5e7eb;padding-bottom:16px}
    .inv-brand{display:flex;align-items:center;gap:10px}
    .inv-logo{width:46px;height:46px;object-fit:contain;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc}
    .inv-brand h4{margin:0;color:var(--ink);font-size:20px;font-weight:700}
    .inv-title{color:var(--accent-deep);font-weight:900;font-size:26px;letter-spacing:.5px}
    .inv-top{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:14px}
    .box{border:1px solid #e5e7eb;border-radius:10px;padding:12px;background:#fff}
    .meta{display:grid;grid-template-columns:1fr 1fr;gap:8px 16px}
    .lbl{color:var(--muted);font-size:12px}
    .val{font-weight:600;color:var(--ink)}
    .inv-table{width:100%;margin-top:14px;border-collapse:collapse}
    .inv-table th,.inv-table td{border:1px solid var(--line);padding:7px 10px}
    .inv-table th{background:#eef5ff;color:var(--ink);font-size:12.5px;font-weight:800}
    .text-end{text-align:right}
    .muted{color:var(--muted);font-size:12px}
    .totals{width:280px;margin-left:auto;margin-top:8px;border:1px solid var(--line);border-radius:10px;overflow:hidden}
    .totals .row{display:flex;justify-content:space-between;padding:9px 12px;border-bottom:1px solid var(--line)}
    .totals .row:last-child{background:#e8f1ff;font-weight:900;border-bottom:none}
    .sign{width:240px;margin-left:auto;margin-top:24px;text-align:center;color:var(--muted)}
    .sign:before{content:"";display:block;height:1px;background:#d7dbe2;margin-bottom:6px}
    .no-print-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:14px}
    @media print{
        @page{size:A4;margin:0}
        html,body{margin:0!important;padding:0!important;background:#fff!important}
        body *{visibility:hidden!important}
        #invoice-print-area,#invoice-print-area *{visibility:visible!important}
        #invoice-print-area{position:fixed!important;inset:0!important;width:210mm!important;height:297mm!important;max-width:none!important;margin:0!important;padding:10mm!important;box-sizing:border-box;transform:none!important}
        .inv-sheet{border:0!important;border-radius:0!important;padding:0!important;width:100%!important;height:100%!important}
        .inv-table{page-break-inside:auto}
        .inv-table thead{display:table-header-group}
        .inv-table tr{page-break-inside:avoid}
        *{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important}
        .no-print-actions{display:none!important}
        .sign{position:absolute;bottom:40mm;right:15mm;width:180px}
        .sign:before{background:#cbd5e0}
    }
</style>

<div id="invoice-print-area">
    <div class="inv-sheet">
        {{-- Header --}}
        <div class="inv-head">
            <div class="inv-brand">
                <img class="inv-logo" src="{{ $logoUrl }}" alt="Logo">
                <h4>{{ $form->branch->name ?? config('app.name', 'Brand') }}</h4>
            </div>
            <div class="inv-title">{{ __('global.expense') }}</div>
        </div>

        {{-- Meta --}}
        <div class="inv-top">
            <div class="box">
                <div class="meta">
                    <div>
                        <div class="lbl">{{ __('global.reference_no') }}</div>
                        <div class="val">{{ $form->reference_no ?? $form->id }}</div>
                    </div>
                    <div>
                        <div class="lbl">{{ __('global.date') }}</div>
                        <div class="val">
                            {{ optional($form->date)->timezone($tz)->format('d/m/Y H:i') ?? now($tz)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div>
                        <div class="lbl">{{ __('global.branch') }}</div>
                        <div class="val">{{ $form->branch->name ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="lbl">{{ __('global.warehouse') }}</div>
                        <div class="val">{{ $form->warehouse->name ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="lbl">{{ __('global.paying_by') }}</div>
                        <div class="val">{{ $form->cashAccount->name ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="lbl">{{ __('global.total') }}</div>
                        <div class="val">{{ number_format($form->grand_total ?? $grand, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <table class="inv-table">
            <thead>
                <tr>
                    <th style="width:52px;">#</th>
                    <th>{{ __('global.expense') }}</th>
                    <th>{{ __('global.description') }}</th>
                    <th class="text-end" style="width:100px">{{ __('global.unit_cost') }}</th>
                    <th class="text-end" style="width:90px">{{ __('global.quantity') }}</th>
                    <th class="text-end" style="width:120px">{{ __('global.subtotal') }} (USD)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                    @php
                        $name = data_get($r, 'expense_name', '—');
                        $desc = data_get($r, 'description', '');
                        $qty  = (float) data_get($r, 'quantity', 0);
                        $uc   = (float) data_get($r, 'unit_cost', 0);
                        $line = (float) (data_get($r, 'subtotal') ?? ($qty * $uc));
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $desc }}</td>
                        <td class="text-end">{{ number_format($uc, 2) }}</td>
                        <td class="text-end">{{ number_format($qty, 3) }}</td>
                        <td class="text-end">{{ number_format($line, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="muted" style="text-align:center; padding:16px;">
                            {{ __('global.no_items') ?? 'No items' }}
                        </td>
                    </tr>
                @endforelse

                {{-- Optional blank rows to pad table height --}}
                @for ($k = max(0, 10 - $rows->count()); $k > 0; $k--)
                    <tr>
                        <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals">
            <div class="row"><span>{{ __('global.subtotal') ?? 'Sub Total' }}</span><span>{{ number_format($subTotal, 2) }}</span></div>
            @if($taxRate > 0)
                <div class="row"><span>{{ __('global.tax') ?? 'Tax' }}</span><span>{{ number_format($tax, 2) }}</span></div>
            @endif
            <div class="row"><span>{{ __('global.total') ?? 'TOTAL' }}</span><span>{{ number_format($grand, 2) }}</span></div>
        </div>

        {{-- Signature --}}
        <div class="sign">{{ __('global.authorised_sign') ?? 'Authorised Sign' }}</div>
    </div>
</div>

{{-- screen buttons --}}
<div class="no-print-actions">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.close') }}</button>
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="ph ph-printer me-1"></i> {{ __('global.print') ?? 'Print' }}
    </button>
</div>
