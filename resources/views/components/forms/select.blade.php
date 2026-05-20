@props([
    'name',
    'label'       => null,
    'options'     => [],
    'placeholder' => 'Select an option',
    'value'       => null,
    'required'    => false,
    'id'          => null,
    'searchable'  => true,
])

@php
    $inputId  = $id ?? $name;
    $oldValue = old($name, $value);
    $selected = collect($options)->first(fn($label, $key) => (string)$key === (string)$oldValue);
@endphp

<div class="form-field">
    @if($label)
        <label class="form-label">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    <input type="hidden" name="{{ $name }}" id="{{ $inputId }}-value" value="{{ $oldValue ?? '' }}">

    <div class="csel-wrap" id="csel-{{ $inputId }}">
        <div class="csel-trigger {{ empty($oldValue) ? 'placeholder' : '' }}"
             onclick="cselToggle('{{ $inputId }}')">
            <span>{{ $selected ?? $placeholder }}</span>
            <span class="csel-chevron">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </span>
        </div>

        <div class="csel-dropdown">
            @if($searchable && count($options) > 5)
                <div class="csel-search">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" placeholder="Search..."
                           oninput="cselFilter('{{ $inputId }}', this.value)">
                </div>
            @endif

            <div class="csel-options">
                @forelse($options as $optId => $optName)
                    <div class="csel-option {{ (string)$oldValue === (string)$optId ? 'selected' : '' }}"
                         data-value="{{ $optId }}"
                         onclick="cselPick('{{ $inputId }}', '{{ $optId }}', this)">
                        <span>{{ $optName }}</span>
                        <svg class="csel-check" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                @empty
                    <div class="csel-empty">No options available</div>
                @endforelse
            </div>
        </div>
    </div>

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>


@once
<style>
.csel-wrap {
    position: relative;
    width: 100%;
    user-select: none;
    font-size: 0.875rem;
}
.csel-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 46px;
    padding: 0 12px;
    cursor: pointer;
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    color: #1e293b;
    transition: border-color .15s, box-shadow .15s;
}
.csel-trigger.placeholder span { color: #94a3b8; }
.csel-trigger:hover { border-color: #94a3b8; }
.csel-trigger.open {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
}
.csel-chevron {
    display: flex;
    align-items: center;
    color: #64748b;
    transition: transform .2s;
    flex-shrink: 0;
}
.csel-trigger.open .csel-chevron { transform: rotate(180deg); }
.csel-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    z-index: 200;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
}
.csel-wrap.open .csel-dropdown { display: block; }
.csel-search {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.csel-search input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 0.8rem;
    color: #1e293b;
    background: transparent;
}
.csel-options { max-height: 200px; overflow-y: auto; padding: 4px 0; }
.csel-option {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 0.85rem;
    color: #1e293b;
    transition: background .1s;
}
.csel-option:hover { background: #f8fafc; }
.csel-option.selected { color: #4f46e5; font-weight: 600; }
.csel-check { opacity: 0; color: #4f46e5; flex-shrink: 0; }
.csel-option.selected .csel-check { opacity: 1; }
.csel-empty {
    padding: 10px 12px;
    font-size: 0.82rem;
    color: #94a3b8;
    text-align: center;
}
.form-error { font-size: 0.78rem; color: #ef4444; margin-top: 4px; }
</style>

<script>
function cselToggle(id) {
    const wrap = document.getElementById('csel-' + id);
    const isOpen = wrap.classList.contains('open');

    document.querySelectorAll('.csel-wrap.open').forEach(w => {
        w.classList.remove('open');
        w.querySelector('.csel-trigger').classList.remove('open');
    });

    if (!isOpen) {
        wrap.classList.add('open');
        wrap.querySelector('.csel-trigger').classList.add('open');
        const searchInput = wrap.querySelector('.csel-search input');
        if (searchInput) searchInput.focus();
    }
}

function cselPick(id, value, el) {
    const wrap = document.getElementById('csel-' + id);

    document.getElementById(id + '-value').value = value;

    const trigger = wrap.querySelector('.csel-trigger');
    trigger.querySelector('span').textContent = el.querySelector('span').textContent.trim();
    trigger.classList.remove('placeholder');

    wrap.querySelectorAll('.csel-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');

    wrap.classList.remove('open');
    trigger.classList.remove('open');

    // Fire change event so external listeners (e.g. category → subcategory) work
    document.getElementById(id + '-value').dispatchEvent(new Event('change'));
}

function cselFilter(id, term) {
    const q = term.toLowerCase().trim();
    let any = false;

    document.querySelectorAll('#csel-' + id + ' .csel-option').forEach(opt => {
        const match = opt.querySelector('span').textContent.toLowerCase().includes(q);
        opt.style.display = match ? 'flex' : 'none';
        if (match) any = true;
    });

    const wrap = document.getElementById('csel-' + id);
    let empty = wrap.querySelector('.csel-no-results');
    if (!any && !empty) {
        empty = document.createElement('div');
        empty.className = 'csel-empty csel-no-results';
        empty.textContent = 'No results found';
        wrap.querySelector('.csel-options').appendChild(empty);
    } else if (any && empty) {
        empty.remove();
    }
}

function cselSetOptions(id, options) {
    const wrap = document.getElementById('csel-' + id);
    const container = wrap.querySelector('.csel-options');
    container.innerHTML = '';

    const entries = Object.entries(options);

    if (entries.length === 0) {
        container.innerHTML = '<div class="csel-empty">No options available</div>';
        return;
    }

    entries.forEach(([optId, optName]) => {
        const div = document.createElement('div');
        div.className = 'csel-option';
        div.dataset.value = optId;
        div.onclick = function () { cselPick(id, optId, this); };
        div.innerHTML = `<span>${optName}</span>
            <svg class="csel-check" width="14" height="14" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>`;
        container.appendChild(div);
    });
}

function cselReset(id, placeholder) {
    const wrap = document.getElementById('csel-' + id);
    if (!wrap) return;

    document.getElementById(id + '-value').value = '';

    const trigger = wrap.querySelector('.csel-trigger');
    trigger.querySelector('span').textContent = placeholder ?? 'Select an option';
    trigger.classList.add('placeholder');
    trigger.classList.remove('open');

    wrap.querySelectorAll('.csel-option').forEach(o => o.classList.remove('selected'));
    wrap.classList.remove('open');
}

document.addEventListener('click', e => {
    if (!e.target.closest('.csel-wrap')) {
        document.querySelectorAll('.csel-wrap.open').forEach(w => {
            w.classList.remove('open');
            w.querySelector('.csel-trigger').classList.remove('open');
        });
    }
});
</script>
@endonce