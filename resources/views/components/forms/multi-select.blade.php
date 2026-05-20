@props([
    'name',
    'label'       => null,
    'options'     => [],
    'placeholder' => 'Select options',
    'value'       => [],
    'required'    => false,
    'id'          => null,
])

@php
    $inputId     = $id ?? $name;
    $oldValues   = old($name, $value);
    $oldValues   = is_array($oldValues) ? $oldValues : [];
    $oldValues   = array_map('strval', $oldValues);
@endphp

<div class="form-field">
    @if($label)
        <label class="form-label">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    {{-- Hidden inputs submitted with form --}}
    <div id="{{ $inputId }}-hidden-inputs">
        @foreach($oldValues as $v)
            <input type="hidden" name="{{ $name }}[]" value="{{ $v }}">
        @endforeach
    </div>

    <div class="cmulti-wrap" id="cmulti-{{ $inputId }}">
        <div class="cmulti-trigger {{ empty($oldValues) ? 'placeholder' : '' }}"
             onclick="cmultiToggle('{{ $inputId }}')">

            {{-- Tags for pre-selected (old()) values --}}
            @if(empty($oldValues))
                <span class="cmulti-label">{{ $placeholder }}</span>
            @else
                @foreach($oldValues as $v)
                    @if(isset($options[$v]))
                        <span class="cmulti-tag">
                            {{ $options[$v] }}
                            <span class="cmulti-tag-remove"
                                  onclick="cmultiRemoveTag('{{ $inputId }}', '{{ $v }}', event)">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="3"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </span>
                        </span>
                    @endif
                @endforeach
            @endif

            <span class="cmulti-chevron">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </span>
        </div>

        <div class="cmulti-dropdown">
            @forelse($options as $optId => $optName)
                <div class="cmulti-option {{ in_array((string)$optId, $oldValues) ? 'selected' : '' }}"
                     data-value="{{ $optId }}"
                     data-label="{{ $optName }}"
                     onclick="cmultiPick('{{ $inputId }}', this)">
                    <span class="cmulti-checkbox"></span>
                    <span>{{ $optName }}</span>
                </div>
            @empty
                <div class="cmulti-empty">No options available</div>
            @endforelse
        </div>
    </div>

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>


@once
<style>
.cmulti-wrap {
    position: relative;
    width: 100%;
    user-select: none;
    font-size: 0.875rem;
}
.cmulti-trigger {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    min-height: 46px;
    padding: 5px 12px;
    cursor: pointer;
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    color: #1e293b;
    transition: border-color .15s, box-shadow .15s;
}
.cmulti-trigger:hover { border-color: #94a3b8; }
.cmulti-trigger.open {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
}
.cmulti-label { color: #94a3b8; font-size: 0.875rem; }
.cmulti-chevron {
    display: flex;
    align-items: center;
    color: #64748b;
    transition: transform .2s;
    margin-left: auto;
    flex-shrink: 0;
}
.cmulti-trigger.open .cmulti-chevron { transform: rotate(180deg); }

.cmulti-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ede9fe;
    color: #4f46e5;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 6px;
    white-space: nowrap;
}
.cmulti-tag-remove {
    cursor: pointer;
    display: flex;
    align-items: center;
    color: #7c3aed;
    line-height: 1;
}
.cmulti-tag-remove:hover { color: #dc2626; }

.cmulti-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0; right: 0;
    z-index: 200;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
    padding: 4px 0;
    max-height: 220px;
    overflow-y: auto;
}
.cmulti-wrap.open .cmulti-dropdown { display: block; }

.cmulti-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 0.85rem;
    color: #1e293b;
    transition: background .1s;
}
.cmulti-option:hover { background: #f8fafc; }
.cmulti-option.selected { color: #4f46e5; }

.cmulti-checkbox {
    width: 16px;
    height: 16px;
    border: 1.5px solid #cbd5e1;
    border-radius: 4px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
}
.cmulti-option.selected .cmulti-checkbox {
    background: #4f46e5;
    border-color: #4f46e5;
}
.cmulti-option.selected .cmulti-checkbox::after {
    content: '';
    display: block;
    width: 9px;
    height: 5px;
    border-left: 2px solid #fff;
    border-bottom: 2px solid #fff;
    transform: rotate(-45deg) translateY(-1px);
}

.cmulti-empty {
    padding: 10px 12px;
    font-size: 0.82rem;
    color: #94a3b8;
    text-align: center;
}

.form-error { font-size: 0.78rem; color: #ef4444; margin-top: 4px; }
</style>

<script>
function cmultiToggle(id) {
    const wrap   = document.getElementById('cmulti-' + id);
    const isOpen = wrap.classList.contains('open');

    document.querySelectorAll('.cmulti-wrap.open').forEach(w => {
        w.classList.remove('open');
        w.querySelector('.cmulti-trigger').classList.remove('open');
    });

    if (!isOpen) {
        wrap.classList.add('open');
        wrap.querySelector('.cmulti-trigger').classList.add('open');
    }
}

function cmultiPick(id, el) {
    el.classList.toggle('selected');
    cmultiSync(id);
}

function cmultiRemoveTag(id, value, event) {
    event.stopPropagation();
    const wrap   = document.getElementById('cmulti-' + id);
    const option = wrap.querySelector(`.cmulti-option[data-value="${value}"]`);
    if (option) option.classList.remove('selected');
    cmultiSync(id);
}

function cmultiSync(id) {
    const wrap      = document.getElementById('cmulti-' + id);
    const trigger   = wrap.querySelector('.cmulti-trigger');
    const chevron   = trigger.querySelector('.cmulti-chevron');
    const selected  = Array.from(wrap.querySelectorAll('.cmulti-option.selected'));
    const container = document.getElementById(id + '-hidden-inputs');

    // Rebuild hidden inputs
    container.innerHTML = '';
    selected.forEach(opt => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = id + '[]';
        input.value = opt.dataset.value;
        container.appendChild(input);
    });

    // Rebuild trigger tags
    trigger.querySelectorAll('.cmulti-tag, .cmulti-label').forEach(el => el.remove());

    if (selected.length === 0) {
        trigger.classList.add('placeholder');
        const label = document.createElement('span');
        label.className   = 'cmulti-label';
        label.textContent = trigger.dataset.placeholder || 'Select options';
        trigger.insertBefore(label, chevron);
    } else {
        trigger.classList.remove('placeholder');
        selected.forEach(opt => {
            const tag = document.createElement('span');
            tag.className = 'cmulti-tag';
            tag.innerHTML = `${opt.dataset.label}
                <span class="cmulti-tag-remove"
                      onclick="cmultiRemoveTag('${id}', '${opt.dataset.value}', event)">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="3"
                         stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </span>`;
            trigger.insertBefore(tag, chevron);
        });
    }

    // Dispatch change event for external listeners
    wrap.dispatchEvent(new CustomEvent('cmulti:change', {
        detail: { values: selected.map(o => o.dataset.value) }
    }));
}

function cmultiGetSelected(id) {
    const wrap = document.getElementById('cmulti-' + id);
    return Array.from(wrap.querySelectorAll('.cmulti-option.selected'))
                .map(el => el.dataset.value);
}

function cmultiSetOptions(id, options) {
    const wrap      = document.getElementById('cmulti-' + id);
    const dropdown  = wrap.querySelector('.cmulti-dropdown');
    dropdown.innerHTML = '';

    const entries = Object.entries(options);
    if (entries.length === 0) {
        dropdown.innerHTML = '<div class="cmulti-empty">No options available</div>';
        return;
    }

    entries.forEach(([optId, optName]) => {
        const div = document.createElement('div');
        div.className        = 'cmulti-option';
        div.dataset.value    = optId;
        div.dataset.label    = optName;
        div.onclick          = function () { cmultiPick(id, this); };
        div.innerHTML        = `<span class="cmulti-checkbox"></span><span>${optName}</span>`;
        dropdown.appendChild(div);
    });
}

function cmultiReset(id) {
    const wrap = document.getElementById('cmulti-' + id);
    if (!wrap) return;
    wrap.querySelectorAll('.cmulti-option').forEach(o => o.classList.remove('selected'));
    cmultiSync(id);
}

document.addEventListener('click', e => {
    if (!e.target.closest('.cmulti-wrap')) {
        document.querySelectorAll('.cmulti-wrap.open').forEach(w => {
            w.classList.remove('open');
            w.querySelector('.cmulti-trigger').classList.remove('open');
        });
    }
});
</script>
@endonce