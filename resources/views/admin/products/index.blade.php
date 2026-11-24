@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <div class="eyebrow mb-2">Store inventory</div>
            <h1 class="page-title mb-1">Products</h1>
            <p class="text-muted-custom mb-0">Create and update the goods in your storefront.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-forest">
            <i class="bi bi-plus-lg me-1"></i> Add product
        </a>
    </div>

    <section class="card-clean">
        <div class="p-3 p-lg-4 border-bottom">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2">
                <div class="col-sm-8 col-lg-5">
                    <label class="visually-hidden" for="product-search">Search products</label>
                    <input id="product-search" name="q" value="{{ $search }}" class="form-control" placeholder="Search name or category">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search me-1"></i> Search</button>
                </div>
                @if ($search !== '')
                    <div class="col-auto">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-link text-secondary">Clear</a>
                    </div>
                @endif
            </form>
        </div>

        @if ($products->isEmpty())
            <div class="text-center px-4 py-5">
                <div class="empty-icon mx-auto mb-3"><i class="bi bi-box-seam"></i></div>
                <h2 class="h6 mb-1">{{ $search !== '' ? 'No matching products' : 'No products yet' }}</h2>
                <p class="small text-muted-custom mb-3">{{ $search !== '' ? 'Try another search.' : 'Add your first product to start building your inventory.' }}</p>
                @if ($search === '')
                    <a href="{{ route('admin.products.create') }}" class="btn btn-forest btn-sm">Add your first product</a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th class="ps-3 ps-lg-4">Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-end pe-3 pe-lg-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="ps-3 ps-lg-4">
                                <div class="d-flex align-items-center gap-3">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" alt="" width="44" height="44" class="rounded object-fit-cover">
                                    @else
                                        <span class="empty-icon" style="width:44px;height:44px"><i class="bi bi-image"></i></span>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        @if ($product->description)
                                            <div class="small text-muted-custom">{{ \Illuminate\Support\Str::limit($product->description, 48) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category ?: '—' }}</td>
                            <td>${{ number_format((float) $product->price, 2) }}</td>
                            <td>{{ number_format($product->quantity) }}</td>
                            <td><span class="status-pill status-{{ $product->status }}">{{ $product->status }}</span></td>
                            <td class="text-end pe-3 pe-lg-4 text-nowrap">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary" aria-label="Edit {{ $product->name }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Delete this product? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Delete {{ $product->name }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 p-lg-4">{{ $products->links('pagination::bootstrap-5') }}</div>
        @endif
    </section>
@endsection
