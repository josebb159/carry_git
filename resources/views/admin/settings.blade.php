<x-app-layout>
    <x-slot name="header">
        {{ __('Configuraciones del Sistema') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="Maps_API_KEY" class="block text-sm font-medium text-gray-700">Google Maps API KEY</label>
                                <input type="password" name="Maps_API_KEY" id="Maps_API_KEY" value="{{ $settings['Maps_API_KEY'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <p class="mt-2 text-xs text-gray-500">Esta clave se utiliza para el monitoreo de flotas en tiempo real.</p>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
