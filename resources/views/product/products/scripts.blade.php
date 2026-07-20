
<script>
    const cselWrapperId   = n => `csel-${n}`;
    const cselHiddenId    = n => `${n}-value`;
    const cmultiWrapperId = n => `cmulti-${n}`;
    const cmultiOptId     = n => `cmulti-${n}-options`;
    const cmultiHiddenId  = n => `${n}-hidden`;

    /* ═══════════════════════════════════════════════════════════════
    SINGLE SELECT
    ═══════════════════════════════════════════════════════════════ */
    function cselToggle(id) {
        const wrap = document.getElementById(cselWrapperId(id));
        if (!wrap) return;
        const isOpen = wrap.classList.contains('open');

        document.querySelectorAll('.csel-wrap.open').forEach(w => {
            w.classList.remove('open');
            w.querySelector('.csel-trigger')?.classList.remove('open');
        });

        if (!isOpen) {
            wrap.classList.add('open');
            wrap.querySelector('.csel-trigger')?.classList.add('open');
            const si = wrap.querySelector('.csel-search input');
            if (si) { si.value = ''; si.focus(); }
            wrap.querySelectorAll('.csel-option').forEach(o => o.style.display = 'flex');
            wrap.querySelector('.csel-no-results')?.remove();
        }
    }

    function cselPick(id, value, el) {
        const wrap   = document.getElementById(cselWrapperId(id));
        const hidden = document.getElementById(cselHiddenId(id));
        if (!wrap || !hidden) return;

        hidden.value = value;

        const trigger = wrap.querySelector('.csel-trigger');
        trigger.querySelector('span').textContent = el.querySelector('span').textContent.trim();
        trigger.classList.remove('placeholder');

        wrap.querySelectorAll('.csel-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        wrap.classList.remove('open');
        trigger.classList.remove('open');

        hidden.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function cselFilter(id, term) {
        const q    = term.toLowerCase().trim();
        let   any  = false;
        const wrap = document.getElementById(cselWrapperId(id));
        if (!wrap) return;

        wrap.querySelectorAll('.csel-option').forEach(opt => {
            const match = opt.querySelector('span').textContent.toLowerCase().includes(q);
            opt.style.display = match ? 'flex' : 'none';
            if (match) any = true;
        });

        let empty = wrap.querySelector('.csel-no-results');
        if (!any && !empty) {
            empty = document.createElement('div');
            empty.className   = 'csel-empty csel-no-results';
            empty.textContent = 'No results found';
            wrap.querySelector('.csel-options')?.appendChild(empty);
        } else if (any && empty) { empty.remove(); }
    }

    function cselSetOptions(id, options) {
        const wrap = document.getElementById(cselWrapperId(id));
        if (!wrap) return;

        const container = document.getElementById(`csel-${id}-options`)
                    || wrap.querySelector('.csel-options');
        if (!container) return;

        container.innerHTML = '';
        const entries = Object.entries(options);
        if (entries.length === 0) {
            container.innerHTML = '<div class="csel-empty">No options available</div>';
            return;
        }
        entries.forEach(([val, label]) => {
            const div         = document.createElement('div');
            div.className     = 'csel-option';
            div.dataset.value = val;
            div.onclick       = function () { cselPick(id, val, this); };
            div.innerHTML     = `<span>${label}</span>
                <svg class="csel-check" width="14" height="14" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>`;
            container.appendChild(div);
        });
    }

    function cselReset(id, placeholder) {
        const wrap   = document.getElementById(cselWrapperId(id));
        const hidden = document.getElementById(cselHiddenId(id));
        if (!wrap) return;
        if (hidden) hidden.value = '';

        const trigger = wrap.querySelector('.csel-trigger');
        if (trigger) {
            trigger.querySelector('span').textContent = placeholder ?? 'Select an option';
            trigger.classList.add('placeholder');
            trigger.classList.remove('open');
        }
        wrap.querySelectorAll('.csel-option').forEach(o => o.classList.remove('selected'));
        wrap.classList.remove('open');
    }

    /* ═══════════════════════════════════════════════════════════════
    MULTI SELECT (used elsewhere, kept intact)
    ═══════════════════════════════════════════════════════════════ */
    const cmultiState = {};

    function cmultiToggle(id) {
        const wrap = document.getElementById(cmultiWrapperId(id));
        if (!wrap) return;
        const isOpen = wrap.classList.contains('open');

        document.querySelectorAll('.cmulti-wrap.open').forEach(w => {
            w.classList.remove('open');
            w.querySelector('.cmulti-trigger')?.classList.remove('open');
        });

        if (!isOpen) {
            wrap.classList.add('open');
            wrap.querySelector('.cmulti-trigger')?.classList.add('open');
            const si = wrap.querySelector('.cmulti-search input');
            if (si) { si.value = ''; si.focus(); }
            wrap.querySelectorAll('.cmulti-option').forEach(o => o.style.display = 'flex');
            wrap.querySelector('.cmulti-no-results')?.remove();
        }
    }

    function cmultiToggleOption(id, value) {
        if (!cmultiState[id]) cmultiState[id] = new Set();
        cmultiState[id].has(value)
            ? cmultiState[id].delete(value)
            : cmultiState[id].add(value);

        cmultiRenderTags(id);
        cmultiSyncOptions(id);
        cmultiRenderHidden(id);
    }

    function cmultiSyncOptions(id) {
        const wrap = document.getElementById(cmultiWrapperId(id));
        if (!wrap) return;
        const selected = cmultiState[id] || new Set();
        wrap.querySelectorAll('.cmulti-option').forEach(opt => {
            const sel = selected.has(opt.dataset.value);
            opt.classList.toggle('selected', sel);
            const chk = opt.querySelector('.cmulti-check');
            if (chk) chk.style.opacity = sel ? '1' : '0';
        });
    }

    function cmultiRenderTags(id) {
        const wrap = document.getElementById(cmultiWrapperId(id));
        if (!wrap) return;
        const trigger = wrap.querySelector('.cmulti-trigger');
        if (!trigger) return;
        const ph = trigger.querySelector('.cmulti-placeholder');

        trigger.querySelectorAll('.cmulti-tag').forEach(t => t.remove());

        const selected = cmultiState[id] || new Set();
        if (selected.size === 0) {
            if (ph) ph.style.display = 'inline';
        } else {
            if (ph) ph.style.display = 'none';
            selected.forEach(val => {
                const safe = val.replace(/'/g, "\\'");
                const tag  = document.createElement('span');
                tag.className = 'cmulti-tag';
                tag.innerHTML = `${val} <button type="button" onclick="event.stopPropagation();cmultiToggleOption('${id}','${safe}')">×</button>`;
                trigger.insertBefore(tag, trigger.lastElementChild);
            });
        }
    }

    function cmultiRenderHidden(id) {
        const container = document.getElementById(cmultiHiddenId(id));
        if (!container) return;
        container.innerHTML = '';
        (cmultiState[id] || new Set()).forEach(val => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = `${id}[]`;
            inp.value = val;
            container.appendChild(inp);
        });
    }

    function cmultiFilter(id, term) {
        const q         = term.toLowerCase().trim();
        let   any       = false;
        const container = document.getElementById(cmultiOptId(id));
        if (!container) return;

        container.querySelectorAll('.cmulti-option').forEach(opt => {
            const match = opt.querySelector('span').textContent.toLowerCase().includes(q);
            opt.style.display = match ? 'flex' : 'none';
            if (match) any = true;
        });

        let empty = container.querySelector('.cmulti-no-results');
        if (!any && !empty) {
            empty = document.createElement('div');
            empty.className   = 'cmulti-empty cmulti-no-results';
            empty.textContent = 'No results found';
            container.appendChild(empty);
        } else if (any && empty) { empty.remove(); }
    }

    function cmultiGetSelected(id) {
        return Array.from(cmultiState[id] || new Set());
    }

    function cmultiSetOptions(id, values) {
        const container = document.getElementById(cmultiOptId(id));
        if (!container) return;

        container.innerHTML = '';
        cmultiState[id] = new Set();
        cmultiRenderTags(id);
        cmultiRenderHidden(id);

        if (!values || values.length === 0) {
            container.innerHTML = '<div class="cmulti-empty">No options available</div>';
            return;
        }
        values.forEach(val => {
            const div         = document.createElement('div');
            div.className     = 'cmulti-option';
            div.dataset.value = val;
            div.onclick       = () => cmultiToggleOption(id, val);
            div.innerHTML     = `<span>${val}</span>
                <svg class="cmulti-check" style="opacity:0" width="14" height="14" viewBox="0 0 24 24"
                    fill="none" stroke="#4f46e5" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>`;
            container.appendChild(div);
        });
    }

    /* Close all dropdowns on outside click */
    document.addEventListener('click', e => {
        if (!e.target.closest('.csel-wrap')) {
            document.querySelectorAll('.csel-wrap.open').forEach(w => {
                w.classList.remove('open');
                w.querySelector('.csel-trigger')?.classList.remove('open');
            });
        }
        if (!e.target.closest('.cmulti-wrap')) {
            document.querySelectorAll('.cmulti-wrap.open').forEach(w => {
                w.classList.remove('open');
                w.querySelector('.cmulti-trigger')?.classList.remove('open');
            });
        }
    });

    /* ═══════════════════════════════════════════════════════════════
    ATTRIBUTE VALUES MAP
    ═══════════════════════════════════════════════════════════════ */
    const ATTRIBUTE_VALUES = {
        Color:    ['Red','Blue','Green','Black','White','Yellow','Orange','Purple','Pink','Grey'],
        Size:     ['XS','S','M','L','XL','XXL','3XL','4XL'],
        Material: ['Cotton','Polyester','Wool','Leather','Silk','Linen','Nylon','Denim'],
        Weight:   ['100g','250g','500g','1kg','2kg','5kg','10kg'],
    };

    /* ═══════════════════════════════════════════════════════════════
    DOMContentLoaded
    ═══════════════════════════════════════════════════════════════ */
    document.addEventListener('DOMContentLoaded', function () {

        /* Auto-slug from product name */
        const productNameInput = document.getElementById('product_name');
        const slugInput        = document.getElementById('slug');
        if (productNameInput && slugInput) {
            productNameInput.addEventListener('input', function () {
                slugInput.value = this.value
                    .toLowerCase().trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-');
            });
        }

        /* Category → Sub-Category via AJAX */
        const categoryHidden = document.getElementById(cselHiddenId('category_id'));
        if (categoryHidden) {
            categoryHidden.addEventListener('change', function () {
                const categoryId = this.value;
                cselReset('sub_category_id', 'Select Sub Category');
                if (!categoryId) return;

                fetch("{{ route('get-subcategory') }}?category_id=" + encodeURIComponent(categoryId))
                    .then(r => r.json())
                    .then(data => cselSetOptions('sub_category_id', data))
                    .catch(() => cselSetOptions('sub_category_id', {}));
            });
        }

        /* ── Variant Attribute → populate plain checkbox list ── */
        const attrHidden = document.getElementById(cselHiddenId('variant_attribute'));
        if (attrHidden) {
            attrHidden.addEventListener('change', function () {
                const selectedAttr = this.value;
                const valuesWrap   = document.getElementById('variant-values-wrap');
                const valuesList   = document.getElementById('variant-values-list');

                if (!selectedAttr) {
                    valuesWrap.style.display = 'none';
                    valuesList.innerHTML = '';
                    return;
                }

                const allValues = ATTRIBUTE_VALUES[selectedAttr] || [];

                /* Build checkbox rows */
                valuesList.innerHTML = '';
                allValues.forEach(val => {
                    const uid = `vv_${val.replace(/[^a-z0-9]/gi, '_')}`;
                    const row = document.createElement('div');
                    row.className = 'variant-row';
                    row.innerHTML = `
                        <input type="checkbox" id="${uid}" value="${val}" class="variant-value-cb">
                        <label for="${uid}">${val}</label>`;
                    valuesList.appendChild(row);
                });

                valuesWrap.style.display = 'block';
            });
        }
    });

    /* ═══════════════════════════════════════════════════════════════
    PRODUCT TYPE TOGGLE
    ═══════════════════════════════════════════════════════════════ */
    function toggleProductType() {
        const selected = document.querySelector('input[name="product_type"]:checked').value;
        document.getElementById('single-product-section').style.display   = selected === 'Single'   ? 'block' : 'none';
        document.getElementById('variable-product-section').style.display = selected === 'Variable' ? 'block' : 'none';
    }

    /* ═══════════════════════════════════════════════════════════════
    GENERATE VARIANT TABLE
    ═══════════════════════════════════════════════════════════════ */
    function generateVariants() {
        const attr      = document.getElementById(cselHiddenId('variant_attribute')).value;
        const checked   = [...document.querySelectorAll('.variant-value-cb:checked')].map(c => c.value);
        const tableBody = document.getElementById('variant-table-body');
        tableBody.innerHTML = '';

        if (!attr || checked.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">
                Please select an attribute and at least one value.</td></tr>`;
            return;
        }

        checked.forEach(val => {
            const sku = 'SKU-' + Math.floor(100000 + Math.random() * 900000);
            tableBody.innerHTML += `
                <tr>
                    <td><input type="text"   name="variant_name[]"  class="form-control form-control-sm" value="${attr} - ${val}" readonly></td>
                    <td><input type="text"   name="variant_sku[]"   class="form-control form-control-sm" value="${sku}"></td>
                    <td><input type="number" name="variant_qty[]"   class="form-control form-control-sm" placeholder="0" min="0"></td>
                    <td><input type="number" name="variant_price[]" class="form-control form-control-sm" step="0.01" placeholder="0.00" min="0"></td>
                </tr>`;
        });
    }

    /* ═══════════════════════════════════════════════════════════════
    IMAGE UPLOAD & PREVIEW
    ═══════════════════════════════════════════════════════════════ */
    const imageInput       = document.getElementById('imageInput');
    const uploadBox        = document.getElementById('uploadBox');
    const previewContainer = document.getElementById('imagePreview');
    let   selectedFiles    = [];

    uploadBox.addEventListener('click', () => imageInput.click());
    imageInput.addEventListener('change', function () { addFiles(this.files); });

    function addFiles(files) {
        Array.from(files).forEach(file => selectedFiles.push(file));
        updatePreview();
        updateInputFiles();
    }

    function updatePreview() {
        previewContainer.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'preview-card';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="preview">
                    <button type="button" onclick="removeImage(${index})" class="delete-btn">
                        <i class="fa-solid fa-xmark"></i>
                    </button>`;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
        updateInputFiles();
    }

    function updateInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        imageInput.files = dt.files;
    }

    /* ═══════════════════════════════════════════════════════════════
    GENERATORS
    ═══════════════════════════════════════════════════════════════ */
    function generateSKU() {
        document.getElementById('sku-input').value = 'SKU-' + Math.floor(100000 + Math.random() * 900000);
    }

    function generateBarcode() {
        document.getElementById('barcode-input').value = Math.floor(100000000000 + Math.random() * 900000000000);
    }
</script>