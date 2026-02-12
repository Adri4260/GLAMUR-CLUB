<x-app-layout>
    <div class="container py-5">

        <a href="{{ route('catalogo') }}" class="btn btn-outline-dark mb-4">
            ← Volver al Catálogo
        </a>

        <div class="row g-5">

            <div class="col-md-5">
                <div class="card shadow-sm border-0 sticky-top" style="top: 2rem;">
                    @php
                    $imagePath = Str::replace('/public/', '', $product->image);
                    $imagePath = Str::startsWith($imagePath, 'img/') ? $imagePath : 'img/'.$imagePath;
                    $imgUrl = file_exists(public_path($imagePath)) ? asset($imagePath) : asset('img/prod1.jpg');
                    @endphp
                    <img src="{{ $imgUrl }}" class="card-img-top" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-dark text-uppercase">{{ $product->category }}</span>
                            @if($product->reviews->count() > 0)
                            <span class="text-warning fw-bold">
                                ★ {{ round($product->reviews->avg('rating'), 1) }} / 5
                            </span>
                            @endif
                        </div>

                        <h1 class="display-6 fw-bold" style="font-family: 'Playfair Display', serif;">{{ $product->name }}</h1>
                        <p class="h3 text-primary fw-bold my-3">{{ $product->price }}€</p>

                        <p class="text-muted" style="line-height: 1.8;">
                            {{ $product->description }}
                        </p>

                        <div class="d-grid mt-4">
                            <button class="btn btn-primary btn-lg">Añadir al Carrito</button>
                        </div>
                        <div class="text-center mt-3 small text-muted">
                            Stock disponible: {{ $product->stock }} unidades
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <h3 class="mb-4 pb-2 border-bottom" style="font-family: 'Playfair Display', serif;">
                    Opiniones de Clientes ({{ $product->reviews->count() }})
                </h3>

                <div class="card mb-5 bg-light border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">¿Has probado este producto?</h5>

                        @auth
                        <form id="reviewFormPage"> <input type="hidden" id="pageProductId" value="{{ $product->id }}">

                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Tu Puntuación</label>
                                <div class="rating-css">
                                    <select id="pageRating" class="form-select w-auto">
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                        <option value="3">⭐⭐⭐ (3/5)</option>
                                        <option value="2">⭐⭐ (2/5)</option>
                                        <option value="1">⭐ (1/5)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Tu Opinión</label>
                                <textarea id="pageComment" class="form-control" rows="3" placeholder="Cuéntanos qué te ha parecido el aroma, la duración..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-dark px-4">Publicar Opinión</button>
                        </form>
                        @else
                        <div class="text-center py-3">
                            <p class="mb-2">Necesitas iniciar sesión para dejar una valoración.</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm">Iniciar Sesión</a>
                        </div>
                        @endauth
                    </div>
                </div>

                <div id="reviewsListContainer">
                    @forelse($product->reviews as $review)
                    <div class="d-flex mb-4 p-3 border rounded bg-white shadow-sm review-item">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                {{ substr($review->user->name ?? 'A', 0, 1) }}
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'Usuario Anónimo' }}</h6>
                                <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                            </div>
                            <div class="text-warning small my-1">
                                {{ str_repeat('⭐', $review->rating) }}
                            </div>
                            <p class="mb-0 text-secondary" style="line-height: 1.5;">
                                {{ $review->comment }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted bg-light rounded border border-dashed">
                        <p class="mb-0">No hay opiniones todavía.</p>
                        <small>¡Sé el primero en valorar este producto!</small>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reviewFormPage');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const productId = document.getElementById('pageProductId').value;
                    const rating = document.getElementById('pageRating').value;
                    const comment = document.getElementById('pageComment').value;
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    fetch('/api/reviews', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                rating: rating,
                                comment: comment
                            })
                        })
                        .then(res => {
                            if (res.status === 401) throw new Error('Inicia sesión primero.');
                            if (!res.ok) throw new Error('Error al guardar.');
                            return res.json();
                        })
                        .then(data => {
                            alert('¡Opinión publicada! La página se recargará.');
                            window.location.reload(); // Recarga simple para ver el nuevo comentario
                        })
                        .catch(err => alert(err.message));
                });
            }
        });
    </script>
</x-app-layout>