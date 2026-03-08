<style>
    body { background-color: #f8f9fa; }
    .card { transition: transform 0.2s; }
    .table-primary { background-color: #4e73df !important; }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1);
    }
    .remove_row:hover { background-color: #fff5f5; color: #dc3545; }
</style>
<form action="{{ $action }}" method="POST" id="productStockForm" enctype="multipart/form-data" onsubmit="handleProductSubmit(event)" >
    @csrf

    <div class="card-body p-4">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold text-secondary small uppercase">Warehouse</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-location-dot"></i></span>
                    <select name="warehouse_id" class="form-select border-start-0 shadow-none" required>
                        <option value="">Select Warehouse</option>
                        @foreach (getWarehouse() as $id => $name )
                            <option value="{{ $id }}" {{$id == $form?->warehouse_id ? 'selected' : ''}}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-secondary small">Responsible Person</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user"></i></span>
                    <select name="respon_person_id" class="form-select border-start-0 shadow-none" required>
                        <option value="">Select Person</option>
                        @foreach (getUsers() as $id => $name_en )
                            <option value="{{ $id }}" {{$id == $form?->respon_person_id ? 'selected' : ''}}>{{ $name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <hr class="text-muted opacity-25 my-4">

        <div class="row mb-4">
            <div class="col-md-12">
                <label class="form-label fw-bold text-dark">Add Products to Batch</label>
                <select id="product_select" class="form-select form-select-lg shadow-sm border-primary-subtle">
                    <option value="">Select Products</option>
                    @foreach (getProducts() as $product)
                        <option value="{{$product->id}}" data-name="{{$product->product_name}}" data-sku="{{$product->sku}}" >{{$product->product_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="table-responsive rounded-3 border shadow-sm custom-table-wrapper">
            <div class="table-scroll-container">
                <table class="table align-middle mb-0" id="product_table">
                    <thead>
                        <tr class="table-light-head">
                            <th class="ps-3">Product</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Alert Qty</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @if($form->product)
                            <tr id="row_{{$form->product_id}}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="product-img-container me-3" style="width:35px; height:35px;" >
                                            <img src="{{asset('assets/images/no_image.png')}}" class="img-thumbnail" style="width:35px; height:35px;" />
                                        </div>
                                        <span class="fw-bold text-dark text-nowrap">{{$form->product->product_name}}</span>
                                        <input type="hidden" name="products[{{$form->product_id}}][product_id]" value="{{$form->product_id}}">
                                    </div>
                                </td>
                                <td class="text-muted">{{$form->product->sku}}</td>
                                <td class="text-center">
                                        <input type="number" id="qty-{{$form->product_id}}" name="products[{{$form->product_id}}][qty]" value="{{(int) $form->stock}}" style="width: 50px" min="1">
                                </td>
                                <td class="text-center">
                                        <input type="number" id="alert-qty-{{$form->product_id}}" name="products[{{$form->product_id}}][alert_qty]" value="{{(int) $form->alert_quantity}}" style="width: 50px" min="1">
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button"
                                        class="btn btn-outline-secondary btn-sm remove_row"
                                        data-id="{{$form->product_id}}">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="custom-status-bar">
                <div class="status-grey"></div>
                <div class="status-orange"></div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end align-items-center mt-4">
            <button type="submit" class="btn btn-primary btn-sm px-5 shadow-sm rounded-pill">
                <i class="bi bi-check2-circle me-2"></i>Save
            </button>
        </div>
    </div>
</form>
<script>

    function handleProductSubmit(e) {
        e.preventDefault();
        ajaxSubmit('#productStockForm');
    }

    $(document).ready(function() {
        

        function toggleEmptyState() {
            if ($('#product_table tbody tr:not(#empty_state)').length > 0) {
                $('#empty_state').hide();
            } else {
                $('#empty_state').show();
            }
        }

        $(document).on('change', '#product_select', function() {

            let id = $(this).val();
            let name = $(this).find(':selected').data('name');
            let sku = $(this).find(':selected').data('sku') || 'N/A';
            let category = $(this).find(':selected').data('category') || 'General';
            let img = $(this).find(':selected').data('img') || 'https://via.placeholder.com/40';

            if (id == '') return;

            // Prevent duplicates
            if ($('#row_' + id).length) {
                $(this).addClass('is-invalid');
                setTimeout(() => $(this).removeClass('is-invalid'), 2000);
                return;
            }

            let row = `
            <tr id="row_{{$form->product_id}}">
                <td class="ps-4">
                    <div class="d-flex align-items-center">
                        <div class="product-img-container me-3" style="width:35px; height:35px;" >
                            <img src="{{asset('assets/images/no_image.png')}}" class="img-thumbnail" style="width:35px; height:35px;" />
                        </div>
                        <span class="fw-bold text-dark text-nowrap">${name}</span>
                        <input type="hidden" name="products[{{$form->product_id}}][product_id]" value="{{$form->product_id}}">
                    </div>
                </td>
                <td class="text-muted">${sku}</td>
                <td class="text-center">
                        <input type="number" id="qty-{{$form->product_id}}" name="products[{{$form->product_id}}][qty]" value="1" style="width: 50px" min="1">
                </td>
                <td class="text-center">
                        <input type="number" id="alert-qty-{{$form->product_id}}" name="products[{{$form->product_id}}][alert_qty]" value="1" style="width: 50px" min="1">
                </td>
                <td class="text-end pe-4">
                    <button type="button"
                        class="btn btn-outline-secondary btn-sm remove_row"
                        data-id="{{$form->product_id}}">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </td>
            </tr>`;

            if($('#empty_state').length) $('#empty_state').hide();

            $('#product_table tbody').append(row);

            $(this).val('');
        });


        // remove row
        $(document).on('click','.remove_row',function(){
            let id = $(this).data('id');

            $('#row_'+id).remove();

            if($('#product_table tbody tr').length == 0){
                $('#empty_state').show();
            }
        });
    });
</script>