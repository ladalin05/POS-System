{{-- resources/views/sales/payment/index-styled.blade.php --}}
<input type="hidden" name="sale_id" value="{{ $sale->id ?? '' }}">

<style>
  /* Container & header */
  .payment-view {
    font-family: "Helvetica Neue", Arial, sans-serif;
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 6px 22px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #e6e9ee;
  }

  /* top bar */
  .payment-view__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    background: linear-gradient(180deg, #f6f9fc 0%, #eef5fb 100%);
    border-bottom: 1px solid #dfe7ef;
  }

  .payment-view__title {
    font-weight: 700;
    font-size: 14px;
    color: #222b45;
    letter-spacing: .2px;
  }

  .payment-view__actions {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  /* Print button */
  .btn-print {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: 4px;
    background: #f7f9fb;
    border: 1px solid #d0dbe8;
    font-size: 13px;
    color: #2b6cb0;
    cursor: pointer;
  }

  .btn-print svg {
    width: 14px;
    height: 14px;
    fill: currentColor;
  }

  /* Close icon */
  .btn-close-styled {
    margin-left: 8px;
    width: 28px;
    height: 28px;
    display: inline-grid;
    place-items: center;
    border-radius: 50%;
    background: transparent;
    border: none;
    color: #7a8aa3;
    cursor: pointer;
  }

  .btn-close-styled:hover {
    background: rgba(0, 0, 0, 0.03);
    color: #222b45;
  }

  /* Table area */
  .table-wrap {
    padding: 0;
    overflow-x: auto;
  }

  /* Table */
  .table-clean {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    color: #34495e;
  }

  .table-clean thead th {
    background: linear-gradient(180deg, #2f90d2 0%, #1f6fb0 100%);
    color: #fff;
    font-weight: 700;
    padding: 10px 12px;
    text-align: left;
    border-bottom: 0;
    font-size: 13px;
  }

  .table-clean tbody tr {
    border-bottom: 1px solid #eef3f7;
    background: #fff;
  }

  .table-clean tbody td {
    padding: 12px;
    vertical-align: middle;
    border-right: none;
  }

  /* No-data row */
  .table-clean tbody tr.no-data td {
    text-align: center;
    color: #7b8a9a;
    padding: 18px;
    font-style: italic;
    background: #fbfdff;
  }

  /* Attachment link */
  .attachment-link {
    text-decoration: none;
    color: #0b66b3;
    font-weight: 600;
  }

  /* Pagination area */
  .pagination-wrap {
    padding: 10px 16px;
    border-top: 1px solid #eef3f7;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }

  .pagination {
    display: inline-flex;
    gap: 6px;
    align-items: center;
  }

  .pagination a,
  .pagination span {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 13px;
    border: 1px solid transparent;
    color: #1f6fb0;
    text-decoration: none;
    background: #f7fbff;
  }

  .pagination .active {
    background: #1f6fb0;
    color: #fff;
    border-color: #1f6fb0;
  }





  /* Responsive tweaks */
  @media (max-width: 720px) {
    .payment-view__top {
      padding: 10px;
    }

    .table-clean thead th,
    .table-clean tbody td {
      padding: 10px 8px;
      font-size: 12px;
    }
  }
</style>

<div class="payment-view" role="region" aria-label="View payments">
  <div class="payment-view__top">
    <div class="payment-view__title">
      VIEW PAYMENTS
      @if(!empty($sale->reference_no))
        <span style="font-weight:600;color:#56718a;">(SALE REFERENCE: {{ $sale->reference_no }})</span>
      @elseif(!empty($sale->id))
        <span style="font-weight:600;color:#56718a;">(SALE ID: {{ $sale->id }})</span>
      @endif
    </div>

    <div class="payment-view__actions">
      <button class="btn-print" onclick="window.print()" title="Print">
        <!-- print icon -->
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M19 8h-1V3H6v5H5c-1.1 0-2 .9-2 2v6h4v4h10v-4h4v-6c0-1.1-.9-2-2-2zM8 5h8v3H8V5zm8 14H8v-5h8v5z" />
        </svg>
        Print
      </button>

      <button class="btn-close-styled" type="button" aria-label="Close"
        onclick="(function(){ const el=event.target; const modal = el.closest('.modal'); if(modal) bootstrap?.Modal?.getInstance(modal)?.hide(); else { /* fallback close */ el.closest('.payment-view')?.remove(); } })()">
        <!-- simple X -->
        <svg width="12" height="12" viewBox="0 0 24 24" aria-hidden="true">
          <path
            d="M18.3 5.71L12 12l6.3 6.29-1.42 1.42L10.59 13.41 4.29 19.71 2.88 18.29 9.17 12 2.88 5.71 4.29 4.29 10.59 10.59 16.88 4.29z" />
        </svg>
      </button>
    </div>
  </div>

  <div class="table-wrap">
    <table class="table-clean" role="table" aria-describedby="payments-list">
      <thead>
        <tr>
          <th>Date</th>
          <th>Reference No</th>
          <th>Amount (USD)</th>
          <th>Discount</th>
          <th>Paid by</th>
        </tr>
      </thead>

      <tbody>
        @if($payments->isEmpty())
          <tr class="no-data">
            <td colspan="8">No data available</td>
          </tr>
        @else
          @foreach($payments as $p)


            <tr>
              <td>{{ $p->date?->format('Y-m-d H:i') }}</td>
              <td>{{ $p->reference_no }}</td>
              <td>{{ number_format($p->amount_usd ?? 0, 2) }}</td>
              <td>{{ $p->discount }}</td>
              <td>{{ $p->cashAccount->name }}</td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
  </div>

  <div class="pagination-wrap" role="navigation" aria-label="Pagination">
    <div style="flex:1"></div>
    <div>
      {{-- keep your existing pagination links --}}
      {!! $payments->links('pagination::simple-default') !!}
    </div>
  </div>
</div>