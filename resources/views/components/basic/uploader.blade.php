@props([
    'inputName',
    'url' => '',
    'path' => '',
    'accept' => 'image/*',
    'removable' => false,
    'layout' => 'block',
    'width'  => '160px',
    'height' => '160px',
    'shape' => 'rounded',
    'caption' => '',
    'folder' => '',
    'filenameHint' => '',
])

@php
    $hasImage = !empty($url);

    $radius = match ($shape) {
        'circle' => '50%',
        'square' => '0',
        default  => '14px',
    };
@endphp

<div class="uploader-item uploader-layout-{{ $layout }} {{ $hasImage ? 'upload-done' : '' }}"
     data-storage-path="{{ $path }}"
     data-folder="{{ $folder }}"
     data-filename="{{ $filenameHint }}"
     style="--uploader-w: {{ $width }}; --uploader-h: {{ $height }}; --uploader-radius: {{ $radius }};">

    <input type="file" class="d-none uploader-file" accept="{{ $accept }}">
    <input type="hidden" name="{{ $inputName }}" class="uploader-path" value="{{ $url }}">

    <div class="uploader-preview-box">
        <div class="uploader-spinner" style="display:none;">
            <div class="uploader-spinner-ring"></div>
        </div>

        <img src="{{ $url ?: '#' }}" class="uploader-preview {{ $hasImage ? '' : 'hidden' }}" alt="">
        <div class="uploader-placeholder {{ $hasImage ? 'hidden' : '' }}">
            <i class="fa fa-image"></i>
            <span>Add image</span>
        </div>

        <div class="uploader-hover-veil">
            <i class="fa fa-camera"></i>
        </div>
    </div>

    <div class="uploader-actions">
        @if($removable)
            <button type="button" class="uploader-btn uploader-btn-danger uploader-remove-row" title="Delete">
                <i class="fa fa-trash"></i>
            </button>
        @else
            <button type="button" class="uploader-btn uploader-btn-danger uploader-remove-image"
                    style="{{ $hasImage ? '' : 'display:none' }}">
                <i class="fa fa-times"></i> <span>Remove</span>
            </button>
        @endif
        <button type="button" class="uploader-btn uploader-btn-select">
            <i class="fa fa-upload"></i>
            <span>{{ $hasImage ? 'Change' : 'Select' }}</span>
        </button>
    </div>

    @if($caption)
        <div class="uploader-caption">{{ $caption }}</div>
    @endif
</div>

@once
<style>
    /* ── Tokens ────────────────────────────────────────────────────── */
    .uploader-item {
        --uploader-w: 160px;
        --uploader-h: 160px;
        --uploader-radius: 14px;
        --uploader-ink: #1f2430;
        --uploader-muted: #6b7280;
        --uploader-line: #d9d7d1;
        --uploader-accent: #2f6f5e;
        --uploader-accent-soft: #e6f0ed;
        --uploader-danger: #c0392b;

        position: relative;
        display: inline-flex;
        flex-direction: column;
        gap: 0.55rem;
        font-size: 0.85rem;
        color: var(--uploader-ink);
    }

    .uploader-item.uploader-layout-flex {
        flex-direction: row;
        align-items: flex-start;
        gap: 0.9rem;
    }
    .uploader-item.uploader-layout-flex .uploader-actions {
        flex-direction: column;
        align-items: flex-start;
        padding-top: 0.15rem;
    }

    .uploader-preview-box {
        position: relative;
        width: var(--uploader-w);
        height: var(--uploader-h);
        border-radius: var(--uploader-radius);
        border: 1.5px dashed var(--uploader-line);
        background: #fafaf8;
        overflow: hidden;
        flex-shrink: 0;
        transition: border-color .15s ease, background .15s ease;
    }
    .uploader-item.upload-done .uploader-preview-box {
        border-style: solid;
        border-color: var(--uploader-line);
    }

    .uploader-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .uploader-preview.hidden { display: none; }

    .uploader-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        color: var(--uploader-muted);
    }
    .uploader-placeholder i { font-size: 1.4rem; opacity: 0.6; }
    .uploader-placeholder span { font-size: 0.72rem; }
    .uploader-placeholder.hidden { display: none; }

    .uploader-hover-veil {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(31, 36, 48, 0.45);
        color: #fff;
        font-size: 1.1rem;
        opacity: 0;
        transition: opacity .15s ease;
        pointer-events: none;
    }
    .uploader-item.upload-done .uploader-preview-box:hover .uploader-hover-veil {
        opacity: 1;
    }

    .uploader-spinner {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(250, 250, 248, 0.9);
    }
    .uploader-spinner-ring {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2.5px solid var(--uploader-accent-soft);
        border-top-color: var(--uploader-accent);
        animation: uploader-spin 0.7s linear infinite;
    }
    @keyframes uploader-spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) {
        .uploader-spinner-ring { animation: none; }
    }

    .uploader-actions {
        display: flex;
        justify-content: end;
        gap: 0.4rem;
    }

    .uploader-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.7rem;
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1;
        border-radius: 7px;
        border: 1px solid var(--uploader-line);
        background: #fff;
        color: var(--uploader-ink);
        cursor: pointer;
        transition: background .15s ease, border-color .15s ease, color .15s ease;
    }
    .uploader-btn:hover { background: #f4f3f0; }
    .uploader-btn:disabled { opacity: 0.55; cursor: not-allowed; }

    .uploader-btn-select {
        border-color: var(--uploader-accent);
        color: var(--uploader-accent);
    }
    .uploader-btn-select:hover { background: var(--uploader-accent-soft); }

    .uploader-btn-danger {
        color: var(--uploader-danger);
        border-color: #ecd3ce;
    }
    .uploader-btn-danger:hover { background: #fbeceb; }

    .uploader-caption {
        font-size: 0.72rem;
        color: var(--uploader-muted);
        max-width: calc(var(--uploader-w) * 1.6);
    }

    .uploader-item.uploading .uploader-preview-box { border-color: var(--uploader-accent); }
    .uploader-item.upload-error .uploader-preview-box { border-color: var(--uploader-danger); border-style: solid; }
</style>

<script>
    (function ($) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        });

        const ROUTES = {
            store: '{{ route('uploads.store') }}',
            destroy: '{{ route('uploads.destroy') }}',
        };

        function uploadFile(file, folder, filenameHint) {
            const fd = new FormData();
            fd.append('file', file);
            fd.append('folder', folder || '');
            fd.append('filename', filenameHint || '');

            return $.ajax({
                url: ROUTES.store,
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
            }).then((res) => res.data)
              .catch((jqXHR) => {
                  const message = jqXHR.responseJSON?.message || `Upload failed (${jqXHR.status}).`;
                  throw new Error(message);
              });
        }

        function deleteFile(path) {
            if (!path) return Promise.resolve();

            return $.ajax({
                url: ROUTES.destroy,
                type: 'DELETE',
                data: JSON.stringify({ path }),
                contentType: 'application/json',
            }).catch((jqXHR) => {
                console.error('uploader: failed to delete file', path, jqXHR.status);
            });
        }

        function setItemState(item, state) {
            item.classList.remove('uploading', 'upload-done', 'upload-error');
            if (state) item.classList.add(state);

            const spinner = item.querySelector('.uploader-spinner');
            if (spinner) {
                spinner.style.display = (state === 'uploading') ? 'flex' : 'none';
            }
        }

        /* ── Select / trigger file picker ─────────────────────────────── */
        document.addEventListener('click', function (e) {
            const selectBtn = e.target.closest('.uploader-btn-select');
            if (selectBtn) {
                selectBtn.closest('.uploader-item').querySelector('.uploader-file').click();
                return;
            }

            const clearBtn = e.target.closest('.uploader-remove-image');
            if (clearBtn) {
                const item = clearBtn.closest('.uploader-item');
                const path = item.dataset.storagePath;

                if (path && !confirm('Remove this image?')) return;
                if (path) deleteFile(path);

                item.dataset.storagePath = '';
                item.querySelector('.uploader-path').value = '';

                const preview = item.querySelector('.uploader-preview');
                preview.src = '#';
                preview.classList.add('hidden');
                item.querySelector('.uploader-placeholder').classList.remove('hidden');

                clearBtn.style.display = 'none';
                setItemState(item, null);
                item.querySelector('.uploader-btn-select span').textContent = 'Select';
                return;
            }

            const removeRowBtn = e.target.closest('.uploader-remove-row');
            if (removeRowBtn) {
                const item = removeRowBtn.closest('.uploader-item');
                const path = item.dataset.storagePath;
                const doRemove = () => {
                    if (path) deleteFile(path);
                    item.remove();
                };
                path ? (confirm('Delete this image?') && doRemove()) : doRemove();
                return;
            }

            const addBtn = e.target.closest('.uploader-add');
            if (addBtn) {
                const group = addBtn.closest('.uploader-group');
                const template = group.querySelector('.uploader-item-template');
                const clone = template.content.cloneNode(true);
                group.querySelector('.uploader-items').appendChild(clone);
            }
        });

        /* ── Handle the actual file upload ────────────────────────────── */
        document.addEventListener('change', async function (e) {
            const fileInput = e.target.closest('.uploader-file');
            if (!fileInput) return;

            const file = fileInput.files[0];
            if (!file) return;

            const item = fileInput.closest('.uploader-item');
            const group = fileInput.closest('.uploader-group');

            // Guard against the JS value `undefined` ending up in FormData
            // as the literal string "undefined".
            const folder = item.dataset.folder || group?.dataset.folder || '';
            const filenameHint = item.dataset.filename || '';

            if (!folder) {
                console.warn('uploader: no folder set on this uploader-item or its group; backend will use a default.');
            }

            const pathInput = item.querySelector('.uploader-path');
            const preview = item.querySelector('.uploader-preview');
            const placeholder = item.querySelector('.uploader-placeholder');
            const selectBtn = item.querySelector('.uploader-btn-select');
            const clearBtn = item.querySelector('.uploader-remove-image');
            const oldPath = item.dataset.storagePath;

            setItemState(item, 'uploading');
            selectBtn.disabled = true;

            try {
                const result = await uploadFile(file, folder, filenameHint);
                console.log(result);

                if (oldPath && oldPath !== result.path) {
                    deleteFile(oldPath);
                }

                pathInput.value = result.public_url;
                item.dataset.storagePath = result.path;
                preview.src = result.public_url;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                if (clearBtn) clearBtn.style.display = 'inline-flex';
                selectBtn.querySelector('span').textContent = 'Change';
                setItemState(item, 'upload-done');
            } catch (err) {
                console.error('Upload failed', err);
                alert(err.message || 'Upload failed. Please try again.');
                setItemState(item, oldPath ? 'upload-done' : 'upload-error');
            } finally {
                selectBtn.disabled = false;
                fileInput.value = '';
            }
        });

        /* ── Block submit while any uploader on the form is mid-upload ──── */
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form.matches('form')) return;

            if (form.querySelector('.uploader-item.uploading')) {
                e.preventDefault();
                alert('Please wait — some images are still uploading.');
            }
        });
    })(jQuery);
</script>
@endonce