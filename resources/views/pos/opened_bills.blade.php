<style>
    /* suspended cards grid */
    .suspended-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        align-items: stretch;
    }

    /* clickable card */
    .suspend-card {
        text-decoration: none;
        width: 272px;
        /* adjust width to taste */
        display: block;
        color: #fff;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        transition: transform .12s ease, box-shadow .12s ease;
    }

    .suspend-card:hover {
        text-decoration: none;
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        color: whitesmoke;
    }

    /* inner teal block */
    .suspend-card-inner {
        background: #0f8b84;
        /* teal color */
        padding: 22px 24px;
        text-align: center;
    }

    /* title text (Khmer or customer) */
    .suspend-title {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 10px;
    }

    /* meta lines */
    .suspend-meta {
        font-size: 14px;
        line-height: 1.4;
        opacity: 0.95;
    }

    .modal-body {
        max-height: 70vh;
        /* adjust height as you like */
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* keep grid responsive inside scroll */
    .suspended-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        align-items: stretch;
        padding-bottom: 10px;
    }
</style>

<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __('SUSPENDED SALES') }}</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <p class="mb-3">{{ __('Please click the button below to open') }}</p>

            <div class="suspended-grid">
                @forelse($suspends as $suspend)
                    <a href="{{ route('pos.opened_bills_items', $suspend->id) }}" class="suspend-card"
                        data-sid="{{ $suspend->id }}">
                        <div class="suspend-card-inner">
                            <div class="suspend-title">{{ $suspend->customer_name ?? 'អតិថិជន' }}</div>

                            <div class="suspend-meta">
                                <div>{{ __('Date') }}: {{ optional($suspend->created_at)->format('d/m/Y H:i') }}</div>
                                <div>{{ __('Items') }}: {{ $suspend->items->count() }}</div>
                                <div>{{ __('Total') }}:
                                    {{ number_format($suspend->items->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1)), 2) }}
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-muted">{{ __('No suspended sales found') }}</div>
                @endforelse

                
            </div>
        </div>
    </div>
</div>