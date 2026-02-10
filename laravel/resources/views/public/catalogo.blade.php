<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catálogo de Productos') }}
        </h2>
    </x-slot>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="container">
                <div class="row" id="products-container">
                    @foreach($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($product->description, 50) }}</p>
                                <div class="mt-auto">
                                    <span class="h5 mb-0">{{ $product->price }}€</span>
                                    <span class="text-muted small float-end">Stock: {{ $product->stock }}</span>
                                </div>

                                <button onclick="openReviewsModal('{{ $product->id }}', '{{ $product->name }}')"
                                    class="btn btn-outline-primary btn-sm mt-3 w-100">
                                    ⭐ Ver Valoraciones
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="reviewsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Comentarios</h5>
                    <button type="button" class="btn-close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">

                    <div id="reviewsList" class="mb-4" style="max-height: 300px; overflow-y: auto;">
                        <p class="text-center text-muted">Cargando comentarios...</p>
                    </div>

                    <hr>

                    <h6 class="fw-bold">Deja tu opinión:</h6>
                    <form id="reviewForm">
                        <input type="hidden" id="modalProductId">

                        <div class="mb-2">
                            <label>Puntuación:</label>
                            <select id="reviewRating" class="form-select form-select-sm">
                                <option value="5">⭐⭐⭐⭐⭐ - Excelente</option>
                                <option value="4">⭐⭐⭐⭐ - Muy bueno</option>
                                <option value="3">⭐⭐⭐ - Normal</option>
                                <option value="2">⭐⭐ - Regular</option>
                                <option value="1">⭐ - Malo</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <textarea id="reviewComment" class="form-control" rows="2" placeholder="Escribe tu comentario aquí..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100">Enviar Comentario</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/reviews_api.js') }}"></script>
</x-app-layout>