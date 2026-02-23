{{-- resources/views/pos/today_sale.blade.php --}}

<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                &times;
            </button>
            <h4 class="modal-title">
                TODAY'S SALE
            </h4>

            <button type="button"
                    class="btn btn-default btn-sm pull-right no-print"
                    onclick="window.print()">
                <i class="fa fa-print"></i> Print
            </button>
        </div>

        <div class="modal-body" id="today-sale-print-area">
            <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                <tbody>
                <tr>
                    <th style="width: 50%;">Cash in hand:</th>
                    <td class="text-right">
                        {{ number_format($cash_in_hand ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <th>Product:</th>
                    <td class="text-right">
                        {{ number_format($product_sale ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <th>Discount:</th>
                    <td class="text-right">
                        {{ number_format($sale_discount ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <th>Sale:</th>
                    <td class="text-right">
                        {{ number_format($total_sale ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <th>Return:</th>
                    <td class="text-right">
                        {{ number_format($sale_return ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <th>Expense:</th>
                    <td class="text-right">
                        {{ number_format($expense ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <th>Total Cash:</th>
                    <td class="text-right">
                        <strong>{{ number_format($total_cash ?? 0, 2) }}</strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="modal-footer no-print">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                Close
            </button>
        </div>
    </div>
</div>


