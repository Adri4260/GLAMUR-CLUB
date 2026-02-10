<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Importar Productos (Excel/CSV)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                <div style="background-color: #d1e7dd; color: #0f5132; padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div style="background-color: #f8d7da; color: #842029; padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="file">
                            Selecciona tu archivo Excel o CSV:
                        </label>
                        <input type="file" name="file" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                    </div>

                    <br>
                    <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;">
                        CONFIRMAR Y SUBIR
                    </button>
                </form>

                <hr class="my-8 border-gray-300">

                <h3 class="font-bold text-lg mb-4 text-gray-800">Opción 2: Importar Valoraciones</h3>

                <form action="{{ route('import.reviews') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="file_reviews">
                            Archivo CSV de Valoraciones:
                        </label>
                        <input type="file" name="file_reviews" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                    </div>

                    <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;">
                        SUBIR VALORACIONES
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>