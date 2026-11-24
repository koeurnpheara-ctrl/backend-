@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4 mb-lg-5">
        <div>
            <div class="eyebrow mb-2">Store overview</div>
            <h1 class="page-title mb-1">Good day, {{ $admin->name }}</h1>
            <p class="text-muted-custom mb-0">Here is what is happening with your store.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.products.create') }}" class="btn btn-forest">
                <i class="bi bi-plus-lg me-1"></i> Add product
            </a>
            <div class="small text-muted-custom d-none d-lg-block">{{ now()->format('l, F j, Y') }}</div>
        </div>
    </div>

    <div class="row g-3 g-xl-4 mb-4">
        <div class="col-6 col-xl-3">
            <section class="card-clean p-3 p-lg-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="metric-label">Products</span>
                    <span class="metric-icon"><i class="bi bi-box-seam"></i></span>
                </div>
                <div class="metric-value">{{ number_format($metrics['products']) }}</div>
                <div class="small text-muted-custom mt-1">{{ number_format($metrics['low_stock']) }} low in stock</div>
            </section>
        </div>
        <div class="col-6 col-xl-3">
            <section class="card-clean p-3 p-lg-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="metric-label">Customers</span>
                    <span class="metric-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="metric-value">{{ number_format($metrics['customers']) }}</div>
                <div class="small text-muted-custom mt-1">Registered customers</div>
            </section>
        </div>
        <div class="col-6 col-xl-3">
            <section class="card-clean p-3 p-lg-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="metric-label">Orders</span>
                    <span class="metric-icon"><i class="bi bi-receipt"></i></span>
                </div>
                <div class="metric-value">{{ number_format($metrics['orders']) }}</div>
                <div class="small text-muted-custom mt-1">All-time orders</div>
            </section>
        </div>
        <div class="col-6 col-xl-3">
            <section class="card-clean p-3 p-lg-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="metric-label">Revenue</span>
                    <span class="metric-icon"><i class="bi bi-currency-dollar"></i></span>
                </div>
                <div class="metric-value">${{ number_format((float) $metrics['revenue'], 2) }}</div>
                <div class="small text-muted-custom mt-1">From delivered orders</div>
            </section>
        </div>
    </div>

    <div class="row g-3 g-xl-4">
        <div class="col-12 col-xl-8">
            <section class="card-clean h-100">
                <div class="d-flex justify-content-between align-items-center px-3 px-lg-4 py-3 border-bottom">
                    <div>
                        <h2 class="h5 mb-1" style="font-family:Georgia,serif">Recent orders</h2>
                        <div class="small text-muted-custom">The latest activity from your customers</div>
                    </div>
                    <span class="badge rounded-pill text-bg-light">{{ $recentOrders->count() }} recent</span>
                </div>
                @if ($recentOrders->isEmpty())
                    <div class="text-center px-4 py-5">
                        <div class="empty-icon mx-auto mb-3"><i class="bi bi-bag"></i></div>
                        <h3 class="h6 mb-1">No orders yet</h3>
                        <p class="small text-muted-custom mb-0">Orders will appear here when customers place them.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th class="ps-3 ps-lg-4">Order</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th class="text-end pe-3 pe-lg-4">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td class="ps-3 ps-lg-4 fw-semibold">{{ $order->order_number }}</td>
                                    <td>{{ $order->customer->name ?? 'Guest' }}</td>
                                    <td><span class="status-pill status-{{ strtolower($order->status) }}">{{ $order->status }}</span></td>
                                    <td class="text-end pe-3 pe-lg-4">${{ number_format((float) $order->total, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="card-clean h-100">
                <div class="px-3 px-lg-4 py-3 border-bottom">
                    <h2 class="h5 mb-1" style="font-family:Georgia,serif">Stock to watch</h2>
                    <div class="small text-muted-custom">Active products with five or fewer left</div>
                </div>
                @if ($lowStockProducts->isEmpty())
                    <div class="text-center px-4 py-5">
                        <div class="empty-icon mx-auto mb-3"><i class="bi bi-check2"></i></div>
                        <h3 class="h6 mb-1">All clear</h3>
                        <p class="small text-muted-custom mb-0">No active products are low in stock.</p>
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($lowStockProducts as $product)
                            <li class="list-group-item px-3 px-lg-4 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold small">{{ $product->name }}</div>
                                    <div class="text-muted-custom small">{{ $product->category ?: 'Uncategorized' }}</div>
                                </div>
                                <span class="badge rounded-pill text-bg-warning">{{ $product->quantity }} left</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
@endsection
