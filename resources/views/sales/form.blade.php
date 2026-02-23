<x-app-layout>
  <style>
    .position-relative {
      margin-bottom: 20px;
      position: relative;
      width: 100%;
    }

    .position-relative i.ph-barcode {
      position: absolute;
      top: 50%;
      left: 10px;
      transform: translateY(-50%);
      font-size: 18px;
      color: #6b7280;
      pointer-events: none;
    }

    #quickAdd {
      width: 100%;
      height: 44px;
      padding: 0 12px 0 38px;
      font-size: 14px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      outline: none;
      transition: all 0.2s ease;
    }

    #quickAdd:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
    }

    #quickAddSuggestions {
      margin-top: 2px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      background: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, .08);
      overflow: hidden;
      display: none;
    }

    #quickAddSuggestions .list-group-item {
      padding: 10px 14px;
      font-size: 14px;
      color: #374151;
      cursor: pointer;
      transition: background 0.15s;
    }

    #quickAddSuggestions .list-group-item:hover {
      background: #f3f4f6;
    }

    #quickAddSuggestions .active {
      background: #3b82f6;
      color: #fff;
    }
  </style>

  <x-basic.breadcrumb />
  <div class="content">
    <x-basic.card :title="$title">
      <x-basic.form action="{{ route('purchases.save', $form?->id) }}" novalidate enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
          <div class="col-md-6">
            <x-basic.form.text label="{{ __('global.date') }}" name="date" type="datetime-local"
              value="{{ $form?->date ? $form->date->timezone('Asia/Phnom_Penh')->format('Y-m-d\TH:i') : now('Asia/Phnom_Penh')->format('Y-m-d\TH:i') }}"
              :required="true" />
          </div>

          <div class="col-md-6">
            <x-basic.form.text label="{{ __('global.reference_no') }}" name="reference_no"
              value="{{ $form?->reference_no }}" />
          </div>

          <div class="col-md-6">
            <x-basic.form.select label="{{ __('global.branch') }}" name="branch_id" :options="$branches"
              :selected="$form?->branch_id" :required="true" />
          </div>

          <div class="col-md-6">
            <x-basic.form.select label="{{ __('global.warehouse') }}" name="warehouse_id" :options="$warehouses"
              :selected="$form?->warehouse_id" :required="true" />
          </div>

          <div class="col-md-6">
            <x-basic.form.text label="SI Reference No" name="si_reference_no" value="{{ $form?->si_reference_no }}" />
          </div>

          <div class="col-md-6">
            <x-basic.form.select label="{{ __('global.supplier') }}" name="supplier_id" :options="$suppliers"
              :selected="$form?->supplier_id" :required="true" />
          </div>
        </div>

        {{-- Quick add input --}}
        <div class="position-relative">
          <i class="ph ph-barcode"></i>
          <input id="quickAdd" type="text" class="form-control"
            placeholder="{{ __('Please add products to order list') }}">
          <div id="quickAddSuggestions" class="list-group position-absolute w-100" style="z-index:1000; display:none;">
          </div>
        </div>

        {{-- Items Grid --}}
        <div class="table-responsive">
          <table class="table table-bordered align-middle" id="itemsTable">
            <thead class="table-primary">
              <tr>
                <th style="width: 30%">{{ __('Product (Code - Name)') }}</th>
                <th style="width: 12%">{{ __('Unit') }}</th>
                <th class="text-end" style="width: 14%">{{ __('Net Unit Cost') }}</th>
                <th class="text-end" style="width: 10%">{{ __('Quantity') }}</th>
                <th class="text-end" style="width: 10%">{{ __('Discount') }}</th>
                <th class="text-end" style="width: 14%">{{ __('Subtotal (USD)') }}</th>
                <th style="width: 10%">{{ __('global.action') }}</th>
              </tr>
            </thead>
            <tbody id="itemRows">
              @php $rows = $form?->items ?? collect(); @endphp

              @forelse($rows as $i => $r)
                <tr>
                  <td>
                    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $r->id }}">
                    <select name="items[{{ $i }}][product_id]" class="form-select item-product" required>
                      <option value="">{{ __('global.select_product') }}</option>
                      @foreach($products as $p)
                        <option value="{{ $p->id }}" @selected($r->product_id == $p->id)>{{ $p->code }} — {{ $p->name }}
                        </option>
                      @endforeach
                    </select>
                  </td>

                  <td>
                    <select name="items[{{ $i }}][unit_id]" class="form-select item-unit"
                      data-selected="{{ $r->unit_id ?? '' }}">
                      <option value="">{{ __('global.select_unit') }}</option>
                    </select>
                  </td>

                  <td>
                    <input type="text" class="form-control text-end item-cost" name="items[{{ $i }}][net_unit_cost]"
                      value="{{ $r->net_unit_cost }}" required>
                  </td>
                  <td>
                    <input type="text" class="form-control text-end item-qty" name="items[{{ $i }}][quantity]"
                      value="{{ $r->quantity }}" required>
                  </td>
                  <td>
                    <input type="text" class="form-control text-end item-discount" name="items[{{ $i }}][discount]"
                      value="{{ $r->discount ?? 0 }}">
                  </td>
                  <td class="text-end">
                    <input type="text" class="form-control text-end item-subtotal" name="items[{{ $i }}][subtotal]"
                      value="{{ $r->subtotal }}" readonly tabindex="-1">
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeRow(this)">
                      <i class="ph ph-trash"></i>
                    </button>
                  </td>
                </tr>
              @empty
                <tr id="emptyRow">
                  <td colspan="7" class="text-center text-muted py-4">
                    <i class="ph ph-package"></i> {{ __('global.please_add_products') }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Order-level fields --}}
        <div class="row g-3 mt-2">
          <div class="col-md-4">
            <label class="form-label">{{ __('Order Tax') }}</label>
            <div class="input-group">
              <select id="orderTaxPreset" class="form-select">
                <option value="0">{{ __('No Tax') }}</option>
                <option value="0.05">VAT 5%</option>
                <option value="0.10">VAT 10%</option>
              </select>
              <input type="number" step="0.01" min="0" class="form-control text-end" id="orderTax" name="order_tax"
                value="{{ $form?->order_tax ?? 0 }}">
            </div>
          </div>

          <div class="col-md-4">
            <x-basic.form.text label="{{ __('Discount (5/5%)') }}" name="order_discount"
              value="{{ $form?->order_discount ?? 0 }}" class="text-end" />
          </div>

          <div class="col-md-6">
            <label class="form-label">{{ __('global.attachment') }}</label>
            <input type="file" class="form-control" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
          </div>
        </div>

        {{-- Note --}}
        <div class="mt-3">
          <label class="form-label">{{ __('global.note') }}</label>
          <textarea name="note" rows="3" class="form-control">{{ $form?->note }}</textarea>
        </div>

        {{-- Totals footer --}}
        <div class="row mt-4">
          <div class="col-lg-8"></div>
          <div class="col-lg-4">
            <table class="table table-sm">
              <tr>
                <th class="text-end">{{ __('Items') }} :</th>
                <td class="text-end" id="itemsCount">0</td>
              </tr>
              <tr>
                <th class="text-end">{{ __('Total') }} :</th>
                <td class="text-end" id="totalVal">0.00</td>
              </tr>
              <tr>
                <th class="text-end">{{ __('Order Discount') }} :</th>
                <td class="text-end" id="orderDiscountVal">0.00</td>
              </tr>
              <tr>
                <th class="text-end">{{ __('Order Tax') }} :</th>
                <td class="text-end" id="orderTaxVal">0.00</td>
              </tr>
              <tr class="table-primary">
                <th class="text-end">{{ __('Grand Total') }} :</th>
                <td class="text-end fw-bold" id="grandTotalVal">0.00</td>
              </tr>
            </table>
          </div>
        </div>

        <div class="text-end">
          <a href="{{ route('purchases.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
          <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
        </div>
      </x-basic.form>
    </x-basic.card>
  </div>

  <script>
    const T_SELECT_PRODUCT = "{{ __('global.select_product') }}";
    const T_PLEASE_ADD = "{{ __('global.please_add_products') }}";

    let PRODUCTS = [];
    let rowIndex = 0;
    let loadingProducts = false;
    let suggIndex = -1;
    const PRODUCT_UNITS_CACHE = {}; // productId => units array

    const fmt2 = (n) => Number(n || 0).toFixed(2);
    const toNum = (v, d = 0) => { const n = parseFloat(String(v).replace(/[, ]/g, '')); return isFinite(n) ? n : d; };
    const escapeHtml = (s) => (s ?? '').toString().replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));

    function setAddButtonEnabled(enabled) {
      const btn = document.getElementById('btnAddRow');
      if (!btn) return;
      btn.disabled = !enabled;
      btn.classList.toggle('disabled', !enabled);
    }




    function loadProducts() {
      if (loadingProducts) return;
      loadingProducts = true;

      $.ajax({
        url: "{{ route('purchases.ajaxProductUnits') }}", // <-- return list (no product_id)
        method: "GET",
        dataType: "json",
        success: function (res) {
          // controller returns array of product meta objects (each may include .units)
          PRODUCTS = Array.isArray(res) ? res : (res.data || []);
          // build unit cache if any product includes units
          PRODUCTS.forEach(p => {
            if (Array.isArray(p.units) && p.units.length) {
              PRODUCT_UNITS_CACHE[String(p.id)] = p.units;
            }
          });

          hydrateAllProductSelects();
          
          hydrateExistingUnitSelects();
        },
        error: function (xhr) {
          console.error("Failed to load products+units", xhr.responseText);
        },
    
      });
    }


    function loadProducts() {
      if (loadingProducts) return;
      loadingProducts = true;
      $.ajax({
        url: "{{ route('purchases.ajaxProducts') }}",
        method: "GET",
        dataType: "json",
        success: function (res) {
          PRODUCTS = Array.isArray(res) ? res : [];
          hydrateAllProductSelects();
        },
        error: function (xhr) {
          console.error("Failed to load products", xhr.responseText);
        },
        // complete() { loadingProducts = false; }
      });
    }

    function productOptionsHtml(filterText = "") {
      const needle = filterText.trim().toLowerCase();
      let list = PRODUCTS;
      if (needle) {
        list = PRODUCTS.filter(p =>
          (p.code || '').toLowerCase().includes(needle) ||
          (p.name || '').toLowerCase().includes(needle)
        );
      }
      return list.map(p => `<option value="${p.id}">${escapeHtml(p.code)} — ${escapeHtml(p.name)}</option>`).join('');
    }

    function hydrateAllProductSelects() {
      document.querySelectorAll('#itemRows select.item-product').forEach(sel => {
        const keepVal = sel.value;
        const q = sel.getAttribute('data-q') || "";
        sel.innerHTML = '<option value="">' + T_SELECT_PRODUCT + '</option>' + productOptionsHtml(q);
        if (keepVal) sel.value = keepVal;

        // bind change to populate units for this row
        sel.removeEventListener('change', sel._changeHandler);
        sel._changeHandler = function () {
          const tr = sel.closest('tr');
          const unitSel = tr.querySelector('.item-unit');
          const pid = sel.value ? parseInt(sel.value, 10) : null;
          populateUnitsForSelect(unitSel, pid, null);
        };
        sel.addEventListener('change', sel._changeHandler);

        // if row already has selected product, trigger units population
        if (keepVal) {
          const tr = sel.closest('tr');
          const unitSel = tr.querySelector('.item-unit');
          const existing = unitSel ? unitSel.getAttribute('data-selected') : null;
          populateUnitsForSelect(unitSel, keepVal, existing);
        }
      });
    }

    function addRow(prefillQuery = "") {
      const tbody = document.getElementById('itemRows');
      if (!tbody) return;
      $('#emptyRow').remove();
      const opts = '<option value="">' + T_SELECT_PRODUCT + '</option>' + productOptionsHtml(prefillQuery);

      const tr = document.createElement('tr');
      tr.innerHTML = `
    <td>
      <select name="items[${rowIndex}][product_id]" class="form-select item-product" data-q="${escapeHtml(prefillQuery)}" required>
        ${opts}
      </select>
    </td>
    <td>
      <select name="items[${rowIndex}][unit_id]" class="form-select item-unit" data-selected="">
        <option value="">{{ __('global.select_unit') }}</option>
      </select>
    </td>
    <td><input type="text" name="items[${rowIndex}][net_unit_cost]" class="form-control text-end item-cost" value="0"></td>
    <td><input type="text" name="items[${rowIndex}][quantity]" class="form-control text-end item-qty" value="1"></td>
    <td><input type="text" name="items[${rowIndex}][discount]" class="form-control text-end item-discount" value="0"></td>
    <td class="text-end"><input type="text" class="form-control text-end item-subtotal" name="items[${rowIndex}][subtotal]" value="0.00" readonly tabindex="-1"></td>
    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-row"><i class="ph ph-trash"></i></button></td>
  `;
      tbody.appendChild(tr);
      rowIndex++;
      bindRowInputs(tr);

      // if prefillQuery exactly matches product code, select it and load units
      if (prefillQuery) {
        const sel = tr.querySelector('.item-product');
        if (sel) {
          const exact = PRODUCTS.find(p => (p.code || '').toLowerCase() === prefillQuery.trim().toLowerCase());
          if (exact) {
            sel.value = String(exact.id);
            const unitSel = tr.querySelector('.item-unit');
            populateUnitsForSelect(unitSel, exact.id, null);
          }
        }
      }

      recalcRow(tr);
    }

    function addRowFromProductId(productId) {
      addRow("");
      const lastRow = document.querySelector('#itemRows tr:last-child');
      const sel = lastRow?.querySelector('select.item-product');
      if (sel) {
        sel.innerHTML = '<option value="">' + T_SELECT_PRODUCT + '</option>' + productOptionsHtml("");
        sel.value = String(productId);
        const unitSel = lastRow.querySelector('.item-unit');
        populateUnitsForSelect(unitSel, productId, null);
      }
      recalcRow(lastRow);
    }

    function removeRow(btn) {
      const tr = btn.closest('tr');
      if (tr) tr.remove();
      if (!document.querySelector('#itemRows tr')) {
        const empty = document.createElement('tr');
        empty.id = 'emptyRow';
        empty.innerHTML = '<td colspan="7" class="text-center text-muted py-4"><i class="ph ph-package"></i> ' + T_PLEASE_ADD + '</td>';
        document.getElementById('itemRows').appendChild(empty);
      }
      recalcFooter();
    }

    function bindRowInputs(tr) {
      tr.querySelectorAll('.item-cost,.item-qty,.item-discount').forEach(inp => {
        inp.addEventListener('input', () => recalcRow(tr));
      });
      const del = tr.querySelector('.btn-remove-row');
      if (del) del.addEventListener('click', () => removeRow(del));

      // product change -> populate units handled in hydrateAllProductSelects
      const prodSel = tr.querySelector('.item-product');
      const unitSel = tr.querySelector('.item-unit');
      if (prodSel) {
        prodSel.addEventListener('change', function () {
          const pid = prodSel.value ? parseInt(prodSel.value, 10) : null;
          populateUnitsForSelect(unitSel, pid, null);
        });
      }

      if (unitSel) {
        unitSel.addEventListener('change', () => {
          // optional: when user changes unit we could recompute price per base / previews
          recalcRow(tr);
        });
      }
    }

    function recalcRow(tr) {
      const cost = toNum(tr.querySelector('.item-cost')?.value, 0);
      const qty = toNum(tr.querySelector('.item-qty')?.value, 0);
      const disc = toNum(tr.querySelector('.item-discount')?.value, 0);
      const sub = Math.max(0, (cost * qty) - disc);
      const out = tr.querySelector('.item-subtotal');
      if (out) out.value = fmt2(sub);
      recalcFooter();
    }

    function recalcFooter() {
      const rows = Array.from(document.querySelectorAll('#itemRows tr')).filter(tr => tr.id !== 'emptyRow');
      const count = rows.length;
      let total = 0;
      rows.forEach(tr => total += toNum(tr.querySelector('.item-subtotal')?.value, 0));

      const ordDisc = toNum(document.querySelector('input[name="order_discount"]')?.value, 0);
      const ordTax = toNum(document.getElementById('orderTax')?.value, 0);
      const grand = Math.max(0, total - ordDisc + ordTax);

      document.getElementById('itemsCount').innerText = count;
      document.getElementById('totalVal').innerText = fmt2(total);
      document.getElementById('orderDiscountVal').innerText = fmt2(ordDisc);
      document.getElementById('orderTaxVal').innerText = fmt2(ordTax);
      document.getElementById('grandTotalVal').innerText = fmt2(grand);
    }

    // Suggestion box functions
    function ensureSuggestionBox() {
      let box = document.getElementById('quickAddSuggestions');
      if (box) return box;
      const input = document.getElementById('quickAdd');
      if (!input) return null;
      box = document.createElement('div');
      box.id = 'quickAddSuggestions';
      box.className = 'list-group position-absolute w-100';
      box.style.zIndex = '1000';
      box.style.display = 'none';
      box.style.maxHeight = '260px';
      box.style.overflowY = 'auto';
      input.after(box);
      return box;
    }

    function findMatches(q) {
      const needle = (q || '').trim().toLowerCase();
      if (!needle) return [];
      return PRODUCTS.filter(p =>
        (p.code || '').toLowerCase().includes(needle) ||
        (p.name || '').toLowerCase().includes(needle)
      ).slice(0, 50);
    }

    function renderSuggestions(matches) {
      const box = ensureSuggestionBox();
      if (!box) return;
      if (!matches.length) {
        box.style.display = 'none';
        box.innerHTML = '';
        suggIndex = -1;
        return;
      }
      box.innerHTML = matches.map((p, i) => `
    <button type="button"
      class="list-group-item list-group-item-action d-flex justify-content-between align-items-center ${i === suggIndex ? 'active' : ''}"
      data-id="${p.id}">
      <span>${escapeHtml(p.code)} — ${escapeHtml(p.name)}</span>
    </button>
  `).join('');
      box.style.display = 'block';

      box.querySelectorAll('button').forEach((btn, i) => {
        btn.addEventListener('mouseenter', () => { suggIndex = i; highlightSuggestion(); });
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          addRowFromProductId(id);
          hideSuggestions();
          const input = document.getElementById('quickAdd');
          if (input) input.value = '';
        });
      });
    }

    function highlightSuggestion() {
      const box = document.getElementById('quickAddSuggestions');
      if (!box) return;
      box.querySelectorAll('button').forEach((btn, i) => {
        btn.classList.toggle('active', i === suggIndex);
      });
    }

    function hideSuggestions() {
      const box = document.getElementById('quickAddSuggestions');
      if (!box) return;
      box.style.display = 'none';
      suggIndex = -1;
    }

    function debounce(fn, wait = 150) {
      let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
    }

    // ------------------ product-units AJAX ------------------
    function loadProductUnits(productId) {
      return new Promise((resolve) => {
        if (!productId) return resolve([]);
        if (PRODUCT_UNITS_CACHE[productId]) return resolve(PRODUCT_UNITS_CACHE[productId]);

        $.ajax({
          url: "{{ route('purchases.ajaxProductUnits') }}",
          method: "GET",
          data: { product_id: productId },
          dataType: "json",
        }).done(function (res) {
          PRODUCT_UNITS_CACHE[productId] = Array.isArray(res) ? res : [];
          resolve(PRODUCT_UNITS_CACHE[productId]);
        }).fail(function () {
          PRODUCT_UNITS_CACHE[productId] = [];
          resolve([]);
        });
      });
    }

    function populateUnitsForSelect(sel, productId, selectedUnitId = null) {
      if (!sel) return;
      sel.disabled = true;
      sel.innerHTML = `<option value="">{{ __('global.select_unit') }}</option>`;
      if (!productId) { sel.disabled = false; return; }

      loadProductUnits(productId).then(units => {
        if (!units || units.length === 0) {
          sel.innerHTML = `<option value="">{{ __('global.select_unit') }}</option>`;
          sel.disabled = false;
          if (selectedUnitId) sel.value = selectedUnitId;
          return;
        }

        const opts = units.map(u => {
          const text = (u.unit_code ? u.unit_code + ' ' : '') + u.unit_name + (u.qty && u.qty != 1 ? ` (${u.qty})` : '');
          return `<option value="${u.unit_id}" data-qty="${u.qty}" ${selectedUnitId && selectedUnitId == u.unit_id ? 'selected' : ''}>${escapeHtml(text)}</option>`;
        }).join('');
        sel.innerHTML = `<option value="">{{ __('global.select_unit') }}</option>` + opts;
        sel.disabled = false;
        if (selectedUnitId) sel.value = selectedUnitId;
      });
    }

    // Quick add setup
    function setupQuickAdd() {
      const input = document.getElementById('quickAdd');
      if (!input) return;
      ensureSuggestionBox();

      input.addEventListener('input', debounce((e) => {
        if (!PRODUCTS.length) return;
        const q = e.target.value;
        const matches = findMatches(q);
        suggIndex = -1;
        renderSuggestions(matches);
      }, 120));

      input.addEventListener('keydown', (e) => {
        const box = document.getElementById('quickAddSuggestions');
        const visible = box && box.style.display !== 'none';
        if (e.key === 'ArrowDown' && visible) {
          e.preventDefault();
          const count = box.querySelectorAll('button').length;
          suggIndex = (suggIndex + 1 + count) % count;
          highlightSuggestion();
        } else if (e.key === 'ArrowUp' && visible) {
          e.preventDefault();
          const count = box.querySelectorAll('button').length;
          suggIndex = (suggIndex - 1 + count) % count;
          highlightSuggestion();
        } else if (e.key === 'Enter') {
          e.preventDefault();
          if (visible && suggIndex >= 0) {
            const btnEl = box.querySelectorAll('button')[suggIndex];
            if (btnEl) btnEl.click();
          } else {
            const q = input.value || "";
            const exact = PRODUCTS.find(p => (p.code || '').toLowerCase() === q.trim().toLowerCase());
            if (exact) addRowFromProductId(exact.id); else addRow(q);
            input.value = '';
            hideSuggestions();
          }
        } else if (e.key === 'Escape') {
          hideSuggestions();
        }
      });

      document.addEventListener('click', (e) => {
        const box = document.getElementById('quickAddSuggestions');
        if (!box) return;
        if (!box.contains(e.target) && e.target !== input) hideSuggestions();
      });
    }

    function seedRowIndexFromDom() {
      let max = -1;
      document.querySelectorAll('#itemRows [name^="items["]').forEach(el => {
        const m = el.name.match(/^items\[(\d+)\]\[/);
        if (m) max = Math.max(max, parseInt(m[1], 10));
      });
      rowIndex = max + 1;
    }

    $(function () {
      seedRowIndexFromDom();
      document.querySelectorAll('#itemRows tr').forEach(tr => { if (tr.id !== 'emptyRow') bindRowInputs(tr); });
      loadProducts();
      setupQuickAdd();
      $('#orderTax').on('input', recalcFooter);
      $('input[name="order_discount"]').on('input', recalcFooter);
      $('#orderTaxPreset').on('change', function () {
        const v = toNum(this.value, 0);
        $('#orderTax').val(fmt2(v));
        recalcFooter();
      });
      recalcFooter();
    });
  </script>

</x-app-layout>