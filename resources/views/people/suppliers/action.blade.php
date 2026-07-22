
<style>
    .action-toggle {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    .action-toggle:hover {
        background-color: #f1f1f1;
    }

    .dropdown-menu .dropdown-item {
        display: flex;
        align-items: center;
        font-size: 14px;
        padding: 8px 16px;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="dropdown">
    <button type="button" class="btn btn-sm btn-light border action-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li>
            <a href="{{ route('people.suppliers.update', ['id' => $row->id]) }}" class="dropdown-item">
                <i class="ph ph-pencil-simple me-2 text-success"></i>
                Edit
            </a>
        </li>
        <li>
            <button type="button" class="dropdown-item text-danger data_remove" data-url="{{ route('people.suppliers.delete', ['id' => $row->id]) }}">
                <i class="fa fa-trash me-2"></i>
                Delete
            </button>
        </li>
    </ul>
</div>

