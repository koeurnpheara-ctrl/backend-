<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="card-clean p-3 p-md-4">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">Please correct the highlighted fields.</div>
    @endif

    <div class="row g-3">
        <div class="col-md-8">
            <label for="name" class="form-label">Product name <span class="text-danger">*</span></label>
            <input id="name" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" maxlength="255" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label for="category" class="form-label">Category</label>
            <input id="category" name="category" value="{{ old('category', $product->category) }}" class="form-control @error('category') is-invalid @enderror" maxlength="100" placeholder="e.g. Home decor">
            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="4" maxlength="5000" class="form-control @error('description') is-invalid @enderror" placeholder="Describe this product">{{ old('description', $product->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-4">
            <label for="price" class="form-label">Price (USD) <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price ?? '0.00') }}" class="form-control @error('price') is-invalid @enderror" required>
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-sm-4">
            <label for="quantity" class="form-label">Quantity in stock <span class="text-danger">*</span></label>
            <input id="quantity" name="quantity" type="number" min="0" step="1" value="{{ old('quantity', $product->quantity ?? 0) }}" class="form-control @error('quantity') is-invalid @enderror" required>
            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-4">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="active" @selected(old('status', $product->status ?: 'active') === 'active')>Active</option>
                <option value="inactive" @selected(old('status', $product->status) === 'inactive')>Inactive</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label for="image" class="form-label">Product image</label>
            <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="form-control @error('image') is-invalid @enderror">
            <div class="form-text">JPG, PNG, or WebP; maximum 2 MB.</div>
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if ($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="Current image for {{ $product->name }}" class="mt-3 rounded" width="100">
            @endif
        </div>
    </div>

    <div class="d-flex gap-2 border-top mt-4 pt-4">
        <button type="submit" class="btn btn-forest">{{ $method === 'POST' ? 'Create product' : 'Save changes' }}</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
