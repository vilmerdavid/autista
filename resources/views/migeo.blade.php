<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <title>Geo en vivo</title>
    <script type="text/javascript" src="{{ asset('mdb/js/jquery.min.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4bcJ39miYRDXIr4ux3484nqQP1XqS9Bw&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
    <style type="text/css">
      #right-panel {
        font-family: "Roboto", "sans-serif";
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select,
      #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }

      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      #map {
        height: 100%;
        float: left;
        width: 63%;
        height: 100%;
      }

      #right-panel {
        float: right;
        width: 34%;
        height: 100%;
      }

      .panel {
        height: 100%;
        overflow: auto;
      }

      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: "Roboto", "sans-serif";
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
      <link rel="stylesheet" href="{{ asset('mdb/css/bootstrap.min.css') }}">
    <script>
      (function(exports) {
        "use strict";
        
        var lat={{ $geo->lat}};
        var lng={{ $geo->lng}};
        var LatLngOrigin = {lat: lat, lng: lng};
        var LatLngDestination={lat:-1.049779, lng:-78.587351}

        var directionsService;
        var directionsRenderer;

        function initMap() {
          var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 4,
            center: LatLngOrigin
          });
          directionsService = new google.maps.DirectionsService();
          directionsRenderer = new google.maps.DirectionsRenderer({
            
            map: map,
            panel: document.getElementById("right-panel")
          });
          directionsRenderer.addListener("directions_changed", function() {
            computeTotalDistance(directionsRenderer.getDirections());
          });
          displayRoute(
            directionsService,
            directionsRenderer
          );

          document
            .getElementById("mode")
            .addEventListener("change", function() {
              displayRoute(directionsService, directionsRenderer);
            });

        }

        function displayRoute( service, display) {
          var selectedMode = document.getElementById("mode").value;

          service.route(
            {
              origin: LatLngOrigin,
              destination: LatLngDestination,
              travelMode: google.maps.TravelMode[selectedMode],
              avoidTolls: true
            },
            function(response, status) {
              if (status === "OK") {
                display.setDirections(response);
              } else {
                console.log("No se pudieron mostrar las indicaciones debido a:" + status);
              }
            }
          );
        }

        setInterval(function(){
          $.get("{{ route('obtenerLatLng') }}",{}, function(json) {
            var ltx=parseFloat(json.latitude);
            var lnx=parseFloat(json.longitude);
            var LatLng = {lat:ltx, lng:lnx};
              
              if(JSON.stringify(LatLng)!=JSON.stringify(LatLngOrigin)){
                LatLngOrigin=LatLng;
                displayRoute(
                  directionsService,
                  directionsRenderer
                );
              }
          });
        },2000);



        function computeTotalDistance(result) {
          var total = 0;
          var myroute = result.routes[0];

          for (var i = 0; i < myroute.legs.length; i++) {
            total += myroute.legs[i].distance.value;
          }

          total = total / 1000;
          document.getElementById("total").innerHTML = total + " km";
        }

        exports.computeTotalDistance = computeTotalDistance;
        exports.displayRoute = displayRoute;
        exports.initMap = initMap;
      })((this.window = this.window || {}));
    </script>
  </head>
  <body>
    <div id="floating-panel">
      <b>Modo de viaje: </b>
      <select id="mode">
        <option value="WALKING">Caminando</option>
        <option value="DRIVING">Conducción</option>
        <option value="BICYCLING">Montar en bicicleta</option>
        <option value="TRANSIT">Tránsito</option>
      </select>
    </div>
    <div id="map"></div>
    <div id="right-panel" class="card">
      <a href="" class="btn btn-primary btn-block my-2">Actualizar mapa</a>
      <p>Distancia total: <span id="total"></span></p>
    </div>
  </body>
</html>