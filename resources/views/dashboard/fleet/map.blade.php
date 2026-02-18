<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mapa de Flota en Tiempo Real (Google Maps)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(empty($googleMapsKey))
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Google Maps API Key no configurada</p>
                            <p>Por favor, configura la clave en <a href="{{ route('admin.settings') }}" class="underline font-bold">Configuraciones</a> para habilitar el mapa.</p>
                        </div>
                    @endif
                    <div id="map" style="height: 600px; width: 100%; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($googleMapsKey))
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&callback=initMap" async defer></script>

    <script>
        var map;
        var markers = {};

        function initMap() {
            // Initialize the map centered on a default location
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 40.416775, lng: -3.703790 },
                zoom: 6,
                styles: [
                    {
                        "featureType": "administrative",
                        "elementType": "labels.text.fill",
                        "stylers": [{ "color": "#444444" }]
                    }
                ]
            });

            updateLocations();
            // Auto-refresh every 30 seconds
            setInterval(updateLocations, 30000);
        }

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
                    var position = { lat: lat, lng: lng };

                    if (markers[userId]) {
                        // Update existing marker
                        markers[userId].setPosition(position);
                        markers[userId].infoWindow.setContent(`<b>${driverName}</b><br>Velocidad: ${speed} km/h`);
                    } else {
                        // Create new marker
                        var marker = new google.maps.Marker({
                            position: position,
                            map: map,
                            title: driverName,
                            icon: {
                                url: "http://maps.google.com/mapfiles/ms/icons/truck.png"
                            }
                        });

                        var infoWindow = new google.maps.InfoWindow({
                            content: `<b>${driverName}</b><br>Velocidad: ${speed} km/h`
                        });

                        marker.addListener('click', function() {
                            infoWindow.open(map, marker);
                        });

                        marker.infoWindow = infoWindow;
                        markers[userId] = marker;
                    }
                });
            })
            .catch(error => console.error('Error fetching locations:', error));
        }
    </script>
    @endif
</x-app-layout>
