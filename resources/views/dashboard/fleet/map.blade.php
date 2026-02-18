<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mapa de Flota en Tiempo Real') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="map" style="height: 600px; width: 100%; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize the map centered on a default location (e.g., Spain or center of operations)
            var map = L.map('map').setView([40.416775, -3.703790], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var markers = {};

            function updateLocations() {
                fetch('/api/v1/dashboard/fleet/locations', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    data.forEach(location => {
                        var lat = parseFloat(location.lat);
                        var lng = parseFloat(location.lng);
                        var driverName = location.driver_name;
                        var userId = location.user_id;
                        var speed = location.speed || 0;

                        if (markers[userId]) {
                            // Update existing marker
                            markers[userId].setLatLng([lat, lng]);
                            markers[userId].getPopup().setContent(`<b>${driverName}</b><br>Velocidad: ${speed} km/h`);
                        } else {
                            // Create new marker
                            markers[userId] = L.marker([lat, lng])
                                .addTo(map)
                                .bindPopup(`<b>${driverName}</b><br>Velocidad: ${speed} km/h`);
                        }
                    });
                })
                .catch(error => console.error('Error fetching locations:', error));
            }

            // Initial load
            updateLocations();

            // Auto-refresh every 30 seconds
            setInterval(updateLocations, 30000);
        });
    </script>
</x-app-layout>
