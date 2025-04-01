<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mapa de Check-ins - {{ $date }}</title>
  <!-- Leaflet CSS sin integridad para descartar problemas -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      margin: 0;
      padding: 0;
    }
    header, footer {
      text-align: center;
      padding: 1rem;
      background-color: #f1f1f1;
    }
    .map-container {
      width: 100%;
      height: 80vh;
    }
  </style>
</head>
<body>
  <header>
    <h1>Check-ins del Día: {{ $date }}</h1>
    <p>Ubicaciones de los técnicos que hicieron check-in</p>
  </header>

  <div class="map-container" id="map"></div>

  <footer>
    <p>&copy; {{ date('Y') }} Distribuidora Jadi. Todos los derechos reservados.</p>
  </footer>

  <!-- Cargar Leaflet JS sin atributos de integridad -->
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script>
    // Verifica que la variable L esté definida
    if (typeof L === 'undefined') {
      console.error('Leaflet no se cargó correctamente.');
    } else {
      console.log('Leaflet cargado correctamente.');
    }
    
    // Los check-ins se pasan desde el controlador como JSON
    const checkins = @json($checkins);

    // Inicializar el mapa centrado en Guatemala (ajusta las coordenadas según convenga)
    const map = L.map('map').setView([14.6349, -90.5069], 8);

    // Capa base de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Agregar un marcador por cada check-in
    checkins.forEach(function(checkin) {
      if (checkin.latitude && checkin.longitude) {
        const marker = L.marker([checkin.latitude, checkin.longitude]).addTo(map);
        marker.bindPopup(
          `<strong>Técnico:</strong> ${checkin.technician_name}<br>
           <strong>Hora:</strong> ${checkin.checked_in_at}`
        );
      }
    });
  </script>
</body>
</html>
