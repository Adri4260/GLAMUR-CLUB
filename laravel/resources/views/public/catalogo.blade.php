<x-app-layout>
    <section class="catalog-section" style="padding-top: 2rem;">
        <div class="container">
            <div class="catalog-header text-center mb-5">
                <h1 class="display-4 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--color-primary);">Catálogo de Productos</h1>
                <p class="text-muted">Explora nuestra colección completa de perfumes y productos de belleza</p>
            </div>

            <div class="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">

                @foreach($products as $product)
                <div class="product-card" style="background: var(--bg-card); border: 1px solid var(--color-border); border-radius: 8px; overflow: hidden; transition: transform 0.3s ease;">

                    <div class="product-image" style="position: relative; height: 300px; overflow: hidden;">
                        @php
                        $imagePath = Str::replace('/public/', '', $product->image);
                        $imagePath = Str::startsWith($imagePath, 'img/') ? $imagePath : 'img/'.$imagePath;
                        // Imagen por defecto si falla
                        $imgUrl = file_exists(public_path($imagePath)) ? asset($imagePath) : asset('img/prod1.jpg');
                        @endphp
                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">

                        <span class="badge bg-dark" style="position: absolute; top: 10px; left: 10px; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px;">
                            {{ $product->category }}
                        </span>
                    </div>

                    <div class="product-info p-3">
                        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: 0.5rem;">{{ $product->name }}</h3>
                        <p class="text-muted small" style="height: 40px; overflow: hidden;">{{ Str::limit($product->description, 60) }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price" style="font-size: 1.25rem; font-weight: 700; color: var(--color-primary);">{{ $product->price }}€</span>
                            <span class="stock text-muted small">Stock: {{ $product->stock }}</span>
                        </div>

                        <div class="mt-3 d-grid gap-2">
                            <button class="btn btn-primary" style="background: var(--color-primary); border: none;">Añadir al Carrito</button>

                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-dark btn-sm">
                                ⭐ Ver Valoraciones
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    <div class="modal fade" id="reviewsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--color-accent);">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="modalTitle" style="font-family: 'Playfair Display', serif;">Comentarios</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <div id="reviewsList" class="mb-4" style="max-height: 300px; overflow-y: auto;">
                        <p class="text-center text-muted">Cargando comentarios...</p>
                    </div>
                    <hr style="border-color: var(--color-border);">
                    <h6 class="fw-bold mb-3">Deja tu opinión:</h6>
                    <form id="reviewForm">
                        <input type="hidden" id="modalProductId">
                        <div class="mb-3">
                            <select id="reviewRating" class="form-select mb-2" style="background: var(--bg-body); color: var(--text-primary); border-color: var(--color-border);">
                                <option value="5">⭐⭐⭐⭐⭐ - Excelente</option>
                                <option value="4">⭐⭐⭐⭐ - Muy bueno</option>
                                <option value="3">⭐⭐⭐ - Normal</option>
                                <option value="2">⭐⭐ - Regular</option>
                                <option value="1">⭐ - Malo</option>
                            </select>
                            <textarea id="reviewComment" class="form-control" rows="3" placeholder="Escribe tu comentario..." required style="background: var(--bg-body); color: var(--text-primary); border-color: var(--color-border);"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar Comentario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>