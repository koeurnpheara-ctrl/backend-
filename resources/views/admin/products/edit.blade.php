@extends('layouts.admin')

@section('title', 'Edit product')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}" class="small text-decoration-none text-muted-custom"><i class="bi bi-arrow-left me-1"></i> Back to products</a>
        <div class="eyebrow mt-4 mb-2">Store inventory</div>
        <h1 class="page-title mb-1">Edit product</h1>
        <p class="text-muted-custom mb-0">Update the details for {{ $product->name }}.</p>
    </div>
    @include('admin.products.form', ['action' => route('admin.products.update', $product), 'method' => 'PUT'])
@endsection
