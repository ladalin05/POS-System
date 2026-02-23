<!-- payments/modal.blade.php — PARTIAL RETURNED BY CONTROLLER -->
<!-- NOTE: data-balance, data-rate-usd, and data-rate-khr added for safe numeric reading -->


<style>
    :root {
        --bg: #ffffff;
        --muted: #6b6b6b;
        --accent: #1c7ed6;
        --border: #d7d7d7;
    }

    .muted {
        color: var(--muted);
        font-size: 13px;
    }

    .required {
        color: #c0392b;
        font-weight: 600;
    }

    /* Form grid */
    .grid {
        display: block;
        gap: 12px;
    }

    .field {
        margin-bottom: 12px;
    }

    .field label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        font-size: 13px;
    }

    .field input[type="text"],
    .field input[type="number"],
    .field input[type="datetime-local"],
    .field textarea,
    .field select {
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: 3px;
        font-size: 14px;
        background: #fff;
    }

    .two-col {
        display: flex;
        gap: 12px;
    }

    .two-col .field {
        flex: 1;
    }

    .fullwidth {
        width: 100%;
    }

    .actions {
        margin-top: 14px;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 5px;
        background: #fff;
        cursor: pointer;
    }

    .btn .primary {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }
</style>



<form id="paymentForm" method="POST" action="{{ route('payment.save') }}" enctype="multipart/form-data" novalidate
    data-balance="{{ $balance ?? 0 }}" data-rate-usd="{{ $rate_usd ?? 1 }}" data-rate-khr="{{ $rate_khr ?? 0 }}">
    @csrf

    <input type="hidden" name="sale_id" value="{{ $sale->id ?? '' }}">

    <div class="grid">

        <div class="field">
            <label for="date">Date <span class="required">*</span></label>
            <input id="date" name="date" type="datetime-local"
                value="{{ now('Asia/Phnom_Penh')->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="field">
            <label for="reference_no">Reference No</label>
            <input id="reference_no" name="reference_no" type="text" placeholder="Reference number">
        </div>

        <!-- single amount field (value set to sale balance) -->
        <div class="field fullwidth">
            <label for="amount">Amount <span class="required">*</span></label>
            <input id="amount" name="amount" type="text" value="{{ $balance ?? '' }}" readonly required>
        </div>

        <div class="field">
            <label for="discount">Discount</label>
            <input id="discount" name="discount" type="text" value="">
        </div>

        <div class="two-col">

            <div class="field">
                <label>Amount: {{ $amount_usd ?? '' }} (USD)</label>
                <input id="amount_usd" name="amount_usd" type="text" value="">
            </div>

            <div class="field">
                <label>Rate (USD)</label>
                <input id="rate_usd" name="rate_usd" type="text" value="{{ $rate_usd ?? '' }}" readonly>
            </div>
        </div>

        <div class="two-col">
            <div class="field">
                <label>Amount: {{ $amount_kh ?? '' }} (KHR)</label>
                <input id="amount_khr" name="amount_khr" type="text" value="">
            </div>
            <div class="field">
                <label>Rate(KH)</label>
                <input id="rate_khr" name="rate_khr" type="text" value="{{ $rate_khr ?? '' }}">
            </div>
        </div>

        <div class="field fullwidth">
            <label for="paying_by">Paying by <span class="required">*</span></label>
            <select id="paying_by" name="paying_by" required>
                <option value="">Choose...</option>
                @foreach ($paid_by as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="field fullwidth">
            <label for="attachment">Attachment</label>
            <input id="attachment" name="attachment" type="file" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <div class="field fullwidth">
            <label for="note">Note</label>
            <textarea id="note" name="note" rows="4" placeholder="Optional note..."></textarea>
        </div>
    </div>

    <div class="actions">
        <button type="submit" class="btn primary">Save payment</button>
        <button type="button" class="btn" data-bs-dismiss="modal" id="payment-modal-cancel">Cancel</button>
    </div>
</form>



<script>
    (function () {

        // Utility: safe parse from string -> number (strip commas, currency symbols)
        function cleanNumber(str, fallback = 0) {
            if (str === undefined || str === null) return fallback;
            const cleaned = String(str).replace(/[^\d\.\-]+/g, '');
            const n = parseFloat(cleaned);
            return Number.isFinite(n) ? n : fallback;
        }

        // Initialize single form node
        function initPaymentFormNode(form) {
            try {
                if (!form) return;

                // read numeric values from form data attributes (safe from Blade quoting issues)
                const balance = cleanNumber(form.getAttribute('data-balance'), 0);
                const rateUsd = cleanNumber(form.getAttribute('data-rate-usd'), 1);
                const rateKhr = cleanNumber(form.getAttribute('data-rate-khr'), 0);

                const discountInput = form.querySelector('#discount');
                const amountUsdInput = form.querySelector('#amount_usd');
                // const amountKhrInput = form.querySelector('#amount_khr');
                const amountMain = form.querySelector('#amount');

                if (!discountInput) {
                    // not the correct form instance — nothing to do
                    return;
                }

                function updateAmounts() {
                    let discount = cleanNumber(discountInput.value, 0);

                    if (discount > balance) {
                        discount = balance;
                        discountInput.value = String(balance);
                    }

                    const newUsd = balance - discount;


                    if (amountUsdInput) amountUsdInput.value = newUsd.toFixed(2);
                    if (amountMain) amountMain.value = newUsd.toFixed(2);
                }

                // Remove previous listener if present, then attach
                discountInput.removeEventListener('input', updateAmounts);
                discountInput.addEventListener('input', updateAmounts);

                // Set initial values right away
                updateAmounts();

            } catch (err) {
                console.error('initPaymentFormNode error:', err);
            }
        }

        // Initialize any existing form(s) on page load
        document.querySelectorAll('form#paymentForm').forEach(form => initPaymentFormNode(form));

        // Watch for dynamic injection (modal loaded by AJAX)
        const observer = new MutationObserver(mutations => {
            for (const m of mutations) {
                for (const node of m.addedNodes) {
                    if (node.nodeType !== 1) continue;
                    // check the node itself
                    if (node.matches && node.matches('form#paymentForm')) {
                        initPaymentFormNode(node);
                    }
                    // check if it contains the form
                    const nested = node.querySelector && node.querySelector('form#paymentForm');
                    if (nested) initPaymentFormNode(nested);
                }
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });

    })();
</script>