<x-app-layout>
    <x-basic.breadcrumb></x-basic.breadcrumb>

    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('expense.add_expense.save', $form?->id) }}" novalidate
                enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.date') }}" name="date" type="datetime-local" value="{{ $form?->date
    ? $form->date->timezone('Asia/Phnom_Penh')->format('Y-m-d\TH:i')
    : now('Asia/Phnom_Penh')->format('Y-m-d\TH:i') }}" :required="true" />
                    </div>

                    <div class="col-md-4">
                        <x-basic.form.text label="{{ __('global.reference_no') }}" name="reference_no"
                            value="{{ $form?->reference_no }}" :required="false" />
                    </div>

                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.branch') }}" name="branch_id" :options="$branches"
                            :selected="$form?->branch_id" :required="true" />
                    </div>

                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.warehouse') }}" name="warehouse_id"
                            :options="$warehouses" :selected="$form?->warehouse_id" :required="true" />
                    </div>

                    <div class="col-md-4">
                        <x-basic.form.select label="{{ __('global.paid_by') }}" name="paid_by" :options="$cash"
                            :selected="$form?->paid_by" :required="true" />
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('global.attachment') }}</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="document"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">{{ __('global.expense') }}</h5>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addItemRow()">
                                <i class="ph-plus"></i> {{ __('global.add_expense') }}
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="min-width:220px">{{ __('global.expense') }}</th>
                                        <th style="min-width:120px">{{ __('global.code') }}</th>
                                        <th>{{ __('global.description') }}</th>
                                        <th style="min-width:120px" class="text-end">{{ __('global.unit_cost') }}</th>
                                        <th style="min-width:120px" class="text-end">{{ __('global.quantity') }}</th>
                                        <th style="min-width:140px" class="text-end">{{ __('global.subtotal') }}</th>
                                        <th style="width:80px">{{ __('global.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    @php
                                        $hasItems = $form && $form->relationLoaded('items') ? $form->items->count() > 0 : false;
                                    @endphp

                                    @if($hasItems)
                                        @foreach($form->items as $idx => $it)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $it->id }}">
                                                    <input type="hidden" name="items[{{ $idx }}][expense_category_id]"
                                                        value="{{ $it->expense_category_id }}">
                                                    <input type="text" name="items[{{ $idx }}][expense_name]"
                                                        class="form-control" value="{{ $it->expense_name }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="items[{{ $idx }}][expense_code]"
                                                        class="form-control" value="{{ $it->expense_code }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="items[{{ $idx }}][description]"
                                                        class="form-control" value="{{ $it->description }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="items[{{ $idx }}][unit_cost]"
                                                        class="form-control text-end unit-cost" value="{{ $it->unit_cost }}"
                                                        oninput="recalcRow(this)">
                                                </td>
                                                <td>
                                                    <input type="text" name="items[{{ $idx }}][quantity]"
                                                        class="form-control text-end quantity" value="{{ $it->quantity }}"
                                                        oninput="recalcRow(this)">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control text-end subtotal bg-light"
                                                        value="{{ number_format((float) $it->subtotal, 2, '.', '') }}" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeItemRow(this)">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    @else
                                        <tr id="emptyRow">
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="ph-package"></i> {{ __('global.please_add_items') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">{{ __('global.grand_total') }}</th>
                                        <th class="text-end">
                                            <input type="text" id="grandTotal" class="form-control text-end bg-light"
                                                value="0.00" readonly>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Note --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <x-basic.form.textarea label="{{ __('global.note') }}" name="note" rows="4">
                            {{ $form?->note }}
                        </x-basic.form.textarea>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('expense.add_expense.index') }}"
                        class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>

    @push('scripts')
        <script>
            let EXP_CAT_BY_ID = {};

            function loadExpenseCategories() {
                $.ajax({
                    url: "{{ route('expense.add_expense.expense_categories') }}",
                    method: "GET",
                    dataType: "json",
                    success: function (data) {
                        EXP_CAT_BY_ID = {};
                        data.forEach(cat => { EXP_CAT_BY_ID[cat.id] = cat; });
                        $("select.exp-cat").each(function () {
                            const $sel = $(this);
                            const current = $sel.val();
                            $sel.empty().append(`<option value="">Select</option>`);
                            data.forEach(cat => {
                                $sel.append(`<option value="${cat.id}">${cat.name}</option>`);
                            });
                            if (current) $sel.val(current);
                        });
                    }
                });
            }

            function onCategoryChange(sel) {
                const id = $(sel).val();
                const row = $(sel).closest("tr");
                const idx = $(sel).data("idx");
                const cat = EXP_CAT_BY_ID[id];
                if (cat) {
                    row.find(`input[name="items[${idx}][expense_name]"]`).val(cat.name);
                    row.find(`input[name="items[${idx}][expense_code]"]`).val(cat.code || '');
                    row.find(`input[name="items[${idx}][description]"]`).val(cat.description || cat.name || '');
                    row.find(`input[name="items[${idx}][expense_category_id]"]`).val(cat.id);
                } else {
                    row.find(`input[name="items[${idx}][expense_category_id]"]`).val('');
                }
            }

            $(document).ready(function () {
                loadExpenseCategories();
            });



            function fmt(n) {
                return (Math.round((Number(n) || 0) * 100) / 100).toFixed(2);
            }
            function recalcRow(el) {
                const row = el.closest('tr');
                const cost = parseFloat(row.querySelector('.unit-cost')?.value) || 0;
                const qty = parseFloat(row.querySelector('.quantity')?.value) || 0;
                const sub = row.querySelector('.subtotal');
                if (sub) sub.value = fmt(cost * qty);
                recalcGrand();
            }
            function recalcGrand() {
                let total = 0;
                document.querySelectorAll('#itemRows .subtotal').forEach(inp => {
                    total += parseFloat(inp.value || '0');
                });
                const gt = document.getElementById('grandTotal');
                if (gt) gt.value = fmt(total);
            }


            function getNextItemIndex() {
                const used = new Set();
                document.querySelectorAll('#itemRows [name^="items["]').forEach(el => {
                    const m = el.name.match(/^items\[(\d+)]/);
                    if (m) used.add(parseInt(m[1], 10));
                });
                let i = 0;
                while (used.has(i)) i++;
                return i;
            }
            let itemIndex = getNextItemIndex();



            function removeItemRow(btn) {
                btn.closest('tr').remove();
                const tbody = document.getElementById('itemRows');
                const hasDataRows = tbody.querySelectorAll('tr:not(#emptyRow)').length > 0;
                if (!hasDataRows) {
                    const r = document.createElement('tr');
                    r.id = 'emptyRow';
                    r.innerHTML = `<td colspan="7" class="text-center py-4 text-muted">
                                        <i class="ph-package"></i> {{ __('global.please_add_items') }}
                                    </td>`;
                    tbody.appendChild(r);
                }
                recalcGrand();
            }

            document.addEventListener('DOMContentLoaded', recalcGrand);

            async function ensureCategoriesLoaded() {
                if (Object.keys(EXP_CAT_BY_ID).length) return;
                await $.ajax({
                    url: "{{ route('expense.add_expense.expense_categories') }}",
                    method: "GET",
                    dataType: "json",
                    success: function (data) {
                        EXP_CAT_BY_ID = {};
                        data.forEach(cat => { EXP_CAT_BY_ID[cat.id] = cat; });
                    }
                });
            }

            async function addItemRow() {
                await ensureCategoriesLoaded();

                const tbody = document.getElementById('itemRows');
                const emptyRow = document.getElementById('emptyRow');
                if (emptyRow) emptyRow.remove();

                const idx = getNextItemIndex(); // <- fresh, reliable

                const tr = document.createElement('tr');
                tr.innerHTML = `
                            <td>
                                <input type="hidden" name="items[${idx}][expense_category_id]" value="">
                                <select class="form-select mt-2 exp-cat" onchange="onCategoryChange(this)" data-idx="${idx}">
                                    <option value="">{{ __('global.select') }}</option>
                                    ${Object.values(EXP_CAT_BY_ID).map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                                </select>
                            </td>
                            <td><input type="text" name="items[${idx}][expense_code]" class="form-control"></td>
                            <td><input type="text" name="items[${idx}][description]" class="form-control"></td>
                            <td><input type="text" name="items[${idx}][unit_cost]" class="form-control text-end unit-cost" value="0" oninput="recalcRow(this)"></td>
                            <td><input type="text" name="items[${idx}][quantity]" class="form-control text-end quantity" value="1" oninput="recalcRow(this)"></td>
                            <td><input type="text" class="form-control text-end subtotal bg-light" value="0.00" readonly></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </td>
                        `;
                tbody.appendChild(tr);
                recalcGrand();
            }



            // Initial grand total for edit mode
            document.addEventListener('DOMContentLoaded', recalcGrand);
        </script>
    @endpush

</x-app-layout>