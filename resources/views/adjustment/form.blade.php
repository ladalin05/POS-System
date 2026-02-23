<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>
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
  box-shadow: 0 0 0 3px rgba(59,130,246,0.25);
}


#quickAddSuggestions {
  margin-top: 2px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  background: #fff;
  box-shadow: 0 4px 8px rgba(0,0,0,.08);
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
    <!-- Content area -->
    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('adjustment.save', $form?->id) }}" novalidate
                enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.date') }}" name="date" type="datetime-local" value="{{ $form?->date
                                ? $form->date->timezone('Asia/Phnom_Penh')->format('Y-m-d\TH:i')
                                : now('Asia/Phnom_Penh')->format('Y-m-d\TH:i') }}"
                            :required="true" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.reference_no') }}" name="reference_no"
                            value="{{ $form?->reference_no }}" :required="false" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.select label="{{ __('global.branch') }}" name="branch_id" :options="$branches"
                            :required="true" :selected="$form?->branch_id" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.select label="{{ __('global.warehouse') }}" name="warehouse_id"
                            :options="$warehouses" :required="true" :selected="$form?->warehouse_id" />
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('global.attachment') }}</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="document"
                                    accept=".pdf,.doc,.docx,.jpg,.png" />
                            </div>
                        </div>
                    </div>
                </div>

                  <div class="position-relative">
                <i class="ph ph-barcode"></i><input id="quickAdd" type="text" class="form-control" placeholder="{{ __('Please add products to order list') }}">
                <div id="quickAddSuggestions" class="list-group position-absolute w-100" style="z-index:1000; display:none;"></div>
            </div>

                <!-- Products Section -->
                <div class="row mt-4">

                    <div class="col-12">
                       
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>{{ __('global.product_name') }}</th>
                                        <th>{{ __('global.qoh') }}</th>
                                        <th>{{ __('global.type') }}</th>
                                        <th>{{ __('global.quantity') }}</th>
                                        <th>{{ __('global.unit') }}</th>
                                        <th>{{ __('global.new_qoh') }}</th>
                                        <th>{{ __('global.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="productRows">
                                    @if($form && $form->products->count() > 0)
                                    
                                        @foreach($form->products as $index => $product)
                                            
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">
                                                    <select name="products[{{ $index }}][product_id]" class="form-select"
                                                        required>
                                                        <option value="">{{ __('global.select_product') }}</option>
                                                        @foreach($products as $prod)
                                                            <option value="{{ $prod['id'] }}" {{ $product->product_id == $prod['id'] ? 'selected' : '' }}>
                                                                {{ $prod['name'] }} ({{ $prod['code'] }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][qoh]"
                                                        class="form-control qoh-input" min="0" value="{{ $product->qoh }}"
                                                        required onchange="calculateNewQOH(this)">
                                                </td>
                                                <td>
                                                    <select name="products[{{ $index }}][type]" class="form-select type-input"
                                                            required onchange="calculateNewQOH(this)">
                                                        <option value="">{{ __('global.select_type') }}</option>

                                                        <option value="add" {{ in_array($product->type, ['add','addition']) ? 'selected' : '' }}>
                                                            {{ __('global.add') }}
                                                        </option>

                                                        
                                                        <option value="subtract" {{ in_array($product->type, ['subtract','subtraction']) ? 'selected' : '' }}>
                                                            {{ __('global.subtract') }}
                                                        </option>
                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="number" name="products[{{ $index }}][quantity]"
                                                        class="form-control quantity-input" min="1"
                                                        value="{{ $product->quantity }}" required
                                                        onchange="calculateNewQOH(this)">
                                                </td>
                                              <td>
                                                  <select name="products[{{ $index }}][product_unit_id]"
                                                          class="form-select unit-select"
                                                          required
                                                          onchange="syncUnitCode(this)">
                                                      <option value="">{{ __('global.select_unit') }}</option>
                                                  </select>
                                                  <input type="hidden" name="products[{{ $index }}][product_unit_code]" class="unit-code-input">
                                              </td>

                                                <td>
                                                    <input type="text" name="products[{{ $index }}][new_qoh]" class="form-control bg-light new-qoh"
                                                        value="{{ $product->new_qoh }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" onclick="removeProductRow(this)"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="ph-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="emptyRow">
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="ph-package"></i> {{ __('global.please_add_products') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Note Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">{{ __('global.note') }}</label>
                            <div class="border rounded">
                                <textarea name="note" class="form-control border-0" rows="4"
                                    placeholder="{{ __('global.note') }}">{{ $form?->note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('adjustment.index') }}"
                        class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>


<script>
$(function () {
  // ---------------- CONFIG ----------------
  var URL_PRODUCTS       = "{{ route('adjustment.ajaxProducts') }}";
  var URL_PRODUCT_UNITS  = "{{ route('adjustment.ajaxProductUnits') }}";
  // ----------------------------------------

  var PRODUCTS = [];       // cached array from ajaxProducts
  var loadingProducts = false;

  // ====== 1) Load products once ======
  function loadProducts(callback) {
    if (loadingProducts) return;
    loadingProducts = true;

    $.getJSON(URL_PRODUCTS, { q: '', limit: 1000 }, function (list) {
      PRODUCTS = Array.isArray(list) ? list : [];
    })
    .always(function () {
      loadingProducts = false;
      if (typeof callback === 'function') callback();
    });
  }

  // Build product <option>s from PRODUCTS
  function productOptions(selectedId) {
    var html = '<option value="">{{ __("global.select_product") }}</option>';
    $.each(PRODUCTS, function (_, p) {
      var sel = (String(p.id) === String(selectedId)) ? 'selected' : '';
      html += '<option value="'+p.id+'" '+sel+'>'+escapeHtml(p.name)+' ('+escapeHtml(p.code)+')</option>';
    });
    return html;
  }

  // ====== 2) Utilities ======
  function escapeHtml(s) {
    s = String(s || '');
    return s.replace(/[&<>"']/g, function (m) {
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]);
    });
  }

  function productById(id) {
    for (var i=0;i<PRODUCTS.length;i++) {
      if (String(PRODUCTS[i].id) === String(id)) return PRODUCTS[i];
    }
    return null;
  }

  function productQoh(id) {
    var p = productById(id);
    var q = p ? parseFloat(p.quantity || 0) : 0;
    return isNaN(q) ? 0 : q;
  }

  function nextIndex() {
    var maxIdx = -1;
    $('#productRows > tr').each(function () {
      var $tr = $(this);
      if ($tr.attr('id') === 'emptyRow') return;
      var $first = $tr.find('[name^="products["]').first();
      if ($first.length) {
        var m = ($first.attr('name') || '').match(/^products\[(\d+)\]/);
        if (m) {
          var idx = parseInt(m[1],10);
          if (!isNaN(idx) && idx > maxIdx) maxIdx = idx;
        }
      }
    });
    return maxIdx + 1;
  }

  function syncUnitCode(selectEl) {
    var $row  = $(selectEl).closest('tr');
    var code  = $(selectEl).find('option:selected').data('code') || '';
    $row.find('.unit-code-input').val(code || '');
  }

  // ====== 3) Add / Remove Row ======
  function addRow() {
    var $tbody = $('#productRows');
    $('#emptyRow').remove();

    var idx = nextIndex();
    var rowHtml =
      '<tr>' +
        '<td>' +
          '<select name="products['+idx+'][product_id]" class="form-select product-select" required>' +
            productOptions(null) +
          '</select>' +
        '</td>' +
        '<td>' +
          '<input type="number" name="products['+idx+'][qoh]" class="form-control qoh-input" min="0" required>' +
        '</td>' +
        '<td>' +
          '<select name="products['+idx+'][type]" class="form-select type-input" required>' +
            '<option value="">{{ __("global.select_type") }}</option>' +
            '<option value="add">{{ __("global.add") }}</option>' +
            '<option value="subtract">{{ __("global.subtract") }}</option>' +
          '</select>' +
        '</td>' +
        '<td>' +
          '<input type="number" name="products['+idx+'][quantity]" class="form-control quantity-input" min="1" required>' +
        '</td>' +
        '<td>' +
          '<select name="products['+idx+'][product_unit_id]" class="form-select unit-select" required>' +
            '<option value="">{{ __("global.select_unit") }}</option>' +
          '</select>' +
          '<input type="hidden" name="products['+idx+'][product_unit_code]" class="unit-code-input">' +
        '</td>' +
        '<td>' +
          '<input type="number" name="products['+idx+'][new_qoh]" class="form-control bg-light new-qoh" readonly>' +
        '</td>' +
        '<td>' +
          '<button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="ph ph-trash"></i></button>' +
        '</td>' +
      '</tr>';

    $tbody.append(rowHtml);
  }

  function removeRow(btn) {
    $(btn).closest('tr').remove();
    var $tbody = $('#productRows');
    if ($tbody.children('tr').length === 0) {
      $tbody.append(
        '<tr id="emptyRow">' +
          '<td colspan="7" class="text-center py-4 text-muted">' +
            '<i class="ph-package"></i> {{ __("global.please_add_products") }}' +
          '</td>' +
        '</tr>'
      );
    }
  }

  // ====== 4) Per-product units loader ======
  function loadProductUnits(productId, $unitSelect, selectedId) {
    if (!productId) {
      $unitSelect.html('<option value="">{{ __("global.select_unit") }}</option>');
      return;
    }
    $.getJSON(URL_PRODUCT_UNITS, { product_id: productId }, function (units) {
      var html = '<option value="">{{ __("global.select_unit") }}</option>';
      $.each(units, function (_, u) {
        var sel = (selectedId && String(selectedId) === String(u.id)) ? 'selected' : '';
        html += '<option value="'+u.id+'" data-code="'+escapeHtml(u.code)+'" '+sel+'>'+escapeHtml(u.name)+'</option>';
      });
      $unitSelect.html(html);
      if (selectedId) {
        // ensure hidden code reflects the selected unit
        syncUnitCode($unitSelect.get(0));
      }
    });
  }

  // ====== 5) QOH calculation ======
  function recalcRow($tr) {
    var qoh  = parseFloat($tr.find('.qoh-input').val() || 0) || 0;
    var qty  = parseFloat($tr.find('.quantity-input').val() || 0) || 0;
    var type = String($tr.find('.type-input').val() || '').toLowerCase();
    var newQ = qoh;

    if (qty > 0) {
      if (type === 'add' || type === 'addition' || type === '+') newQ = qoh + qty;
      if (type === 'subtract' || type === 'subtraction' || type === '-') newQ = qoh - qty;
    }
    if (newQ < 0) newQ = 0;
    $tr.find('.new-qoh').val(newQ);
  }

  // ====== 6) Quick Add (simple) ======
  var typingTimer = null;
  $('#quickAdd').on('input', function () {
    var term = $.trim($(this).val());
    clearTimeout(typingTimer);
    if (!term) {
      $('#quickAddSuggestions').hide().empty();
      return;
    }
    typingTimer = setTimeout(function () {
      $.getJSON(URL_PRODUCTS, { q: term, limit: 8 }, function (list) {
        var arr = Array.isArray(list) ? list : [];
        if (!arr.length) {
          $('#quickAddSuggestions').hide().empty();
          return;
        }
        var html = '';
        $.each(arr, function (i, p) {
          html += '<button type="button" class="list-group-item list-group-item-action" '+
                  'data-id="'+p.id+'" data-name="'+escapeHtml(p.name)+'" data-code="'+escapeHtml(p.code)+'">'+
                  escapeHtml(p.name)+' <small class="text-muted">('+escapeHtml(p.code)+')</small></button>';
        });
        $('#quickAddSuggestions').html(html).show();
      });
    }, 180);
  });

  // click a suggestion → add to table
  $('#quickAddSuggestions').on('click', '.list-group-item', function () {
    var pid = $(this).data('id');
    $('#quickAdd').val('');
    $('#quickAddSuggestions').hide().empty();

    // ensure we have a free row
    var $targetSelect = $('#productRows .product-select').filter(function(){
      return !$(this).val();
    }).first();
    if (!$targetSelect.length) {
      addRow();
      $targetSelect = $('#productRows .product-select').last();
    }

    // set product
    $targetSelect.val(String(pid)).trigger('change');

    // focus quantity (optional)
    var $tr = $targetSelect.closest('tr');
    var $qty = $tr.find('.quantity-input');
    if ($qty.length && !$qty.val()) {
      $tr.find('.type-input').val('add'); // default add
      $qty.val('1').trigger('input').focus().select();
      recalcRow($tr);
    }
  });

  // clicking outside closes suggestions
  $(document).on('click', function (e) {
    if ($(e.target).closest('.position-relative').length === 0) {
      $('#quickAddSuggestions').hide().empty();
    }
  });

  // ====== 7) Events ======
  // add row button (if you have one; else call addRow() wherever you need)
  $(document).on('click', '.btn-add-row', function(){ addRow(); });

  // remove row
  $(document).on('click', '.btn-remove-row', function(){ removeRow(this); });

  // product change → fill QOH + load product units
  $(document).on('change', '.product-select', function () {
    var pid  = $(this).val();
    var $tr  = $(this).closest('tr');
    var $qoh = $tr.find('.qoh-input');

    // fill qoh from cached product list
    var q = productQoh(pid);
    $qoh.val(q);

    // load that product's units
    var $unit = $tr.find('.unit-select');
    loadProductUnits(pid, $unit, null);

    recalcRow($tr);
  });

  // unit change → sync hidden code
  $(document).on('change', '.unit-select', function () {
    syncUnitCode(this);
  });

  // quantity/type/qoh inputs → recalc
  $(document).on('input change', '.qoh-input, .quantity-input, .type-input', function () {
    recalcRow($(this).closest('tr'));
  });

  // before submit: coerce selects to numeric ids & ensure unit code synced
  $('form').on('submit', function () {
    $('.unit-select').each(function(){ syncUnitCode(this); });
  });

  // ====== 8) Initial hydrate (edit page support) ======
  // Load products, then hydrate existing product selects and their units
  loadProducts(function () {
    // fill product selects with options (keep existing selected via data-selected)
    $('#productRows .product-select').each(function () {
      var sel = $(this).attr('data-selected');
      $(this).html(productOptions(sel));
    });

    // for each existing row: load product units with data-selected
    $('#productRows .unit-select').each(function () {
      var $unit = $(this);
      var selectedUnitId = $unit.data('selected');   // put this in Blade for edit
      var pid = $unit.closest('tr').find('.product-select').val();
      if (pid) {
        loadProductUnits(pid, $unit, selectedUnitId);
        // set QOH from product cache too
        var q = productQoh(pid);
        $unit.closest('tr').find('.qoh-input').val(q);
      }
    });

    // recompute existing rows
    $('#productRows tr').each(function () { recalcRow($(this)); });
  });

});
</script>




</x-app-layout>