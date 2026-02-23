<div class="row">
    <div class="col-md-4">
        @if($product->image)
            <img src="{{ asset($product->image) }}" class="img-fluid img-thumbnail">
        @else
            <div class="text-muted">No image</div>
        @endif
    </div>
    <div class="col-md-8">
        <table class="table table-sm">
            <tr><th>Name</th><td>{{ $product->name }}</td></tr>
            <tr><th>Code</th><td>{{ $product->code }}</td></tr>
            <tr><th>Type</th><td>{{ $product->type }}</td></tr>
            <tr><th>Category</th><td>{{ $product->category->name ?? '-' }}</td></tr>
            <tr><th>Unit</th><td>{{ $product->unit->name ?? '-' }}</td></tr>
            <tr><th>Cost</th><td>{{ $product->cost }}</td></tr>
            <tr><th>Price</th><td>{{ $product->price }}</td></tr>
            <tr><th>Alert Quantity</th><td>{{ $product->alert_quantity }}</td></tr>
            <tr><th>Details</th><td>{{ $product->product_details }}</td></tr>
        </table>
    </div>
</div>
