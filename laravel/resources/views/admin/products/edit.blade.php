<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 text-dark mb-0">Editar Producto</h2>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Volver al listado</a>
        </div>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nombre del Producto</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">SKU</label>
                        <input type="text" value="{{ $product->sku }}" class="form-control bg-light" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Precio (€)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Stock</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Categoría</label>
                        <input type="text" name="category" value="{{ old('category', $product->category) }}" class="form-control">
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancelar</a>

                    <button type="submit" class="btn btn-primary px-4">
                        💾 Guardar Cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>