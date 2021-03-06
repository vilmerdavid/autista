@extends('layouts.app')

@section('content')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
  .map-container{
    overflow:hidden;
    padding-bottom:56.25%;
    position:relative;
    height:0;
  }
  .map-container iframe{
    left:0;
    top:0;
    height:100%;
    width:100%;
    position:absolute;
  }
</style>

<style>
  /* Always set the map height explicitly to define the size of the div
   * element that contains the map. */
  #map {
    height: 100%;
  }
  /* Optional: Makes the sample page fill the window. */
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
</style>

<style>
  .highcharts-figure .chart-container {
    width: 300px;
    height: 200px;
    float: left;
  }

  .highcharts-figure, .highcharts-data-table table {
    width: 600px;
    margin: 0 auto;
  }

  .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
  }
  .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
  }
  .highcharts-data-table th {
    font-weight: 600;
      padding: 0.5em;
  }
  .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
      padding: 0.5em;
  }
  .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
  }
  .highcharts-data-table tr:hover {
      background: #f1f7ff;
  }

  @media (max-width: 600px) {
    .highcharts-figure, .highcharts-data-table table {
      width: 100%;
    }
    .highcharts-figure .chart-container {
      width: 300px;
      float: none;
      margin: 0 auto;
    }

  }

  #container {
      height: 400px; 
  }

  .highcharts-figure, .highcharts-data-table table {
      min-width: 310px; 
      max-width: 500px;
      margin: 1em auto;
  }

  .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
  }
  .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
  }
  .highcharts-data-table th {
    font-weight: 600;
      padding: 0.5em;
  }
  .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
      padding: 0.5em;
  }
  .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
  }
  .highcharts-data-table tr:hover {
      background: #f1f7ff;
  }
</style>

<link rel="stylesheet" href="{{ asset('confirm/dist/jquery-confirm.min.css') }}">
<script src="{{ asset('confirm/dist/jquery-confirm.min.js') }}"></script>

 <div class="container-fluid">
   <div class="row">
     <div class="col-md-6">
      <div class="card">
        <div class="card-header   light-blue accent-1">
          <h4><strong>Temperatura</strong> <small id="fecha_temp"></small> </h4>
             
        </div>
        <div class="card-body" id="table">
            <figure class="highcharts-figure">
              <div id="container-speed" class="chart-container"></div>
             
          </figure>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header  light-blue accent-1">
          <h4><strong>Frecuencia cardíaca</strong> <small id="text_fecha_frecuencia"></small></h4>
        </div>
        <div class="card-body">
          <figure class="highcharts-figure-pulso">
            <div id="container"></div>
        </figure>
        </div>
      </div>
     </div>
     <div class="col-md-6">
      <div class="card">
        <div class="card-header  light-blue accent-1">
          <h4><strong>Geolocalización</strong> <small id="fecha_geo"></small></h4>
        </div>
        <div class="card-body">
          <!--Google map-->
          <div id="map" class="z-depth-1-half map-container" style="height: 500px">
            
          </div>
        </div>
      </div>
     </div>
   </div>
 </div>

 <script>
   var gaugeOptions = {
      chart: {
          type: 'solidgauge'
      },

      title: null,

      pane: {
          center: ['50%', '85%'],
          size: '140%',
          startAngle: -90,
          endAngle: 90,
          background: {
              backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
              innerRadius: '60%',
              outerRadius: '100%',
              shape: 'arc'
          }
      },

      exporting: {
          enabled: false
      },

      tooltip: {
          enabled: false
      },

      // the value axis
      yAxis: {
          stops: [
              [0.7, '#55BF3B'], // green
              [0.6, '#DDDF0D'], // yellow
              [0.9, '#DF5353'] // red
          ],
          lineWidth: 0,
          tickWidth: 0,
          minorTickInterval: null,
          tickAmount: 2,
          title: {
              y: -70
          },
          labels: {
              y: 16
          }
      },

      plotOptions: {
          solidgauge: {
              dataLabels: {
                  y: 5,
                  borderWidth: 0,
                  useHTML: true
              }
          }
      }
  };

// The speed gauge
  var chartSpeed = Highcharts.chart('container-speed', Highcharts.merge(gaugeOptions, {
      yAxis: {
          min: 0,
          max: 55,
          title: {
              text: 'TEMPERATURA'
          }
      },

      credits: {
          enabled: false
      },

      series: [{
          name: 'Temperatura',
          data: [0],
          dataLabels: {
              format:
                  '<div style="text-align:center">' +
                  '<span style="font-size:25px">{y}</span><br/>' +
                  '<span style="font-size:12px;opacity:0.4">°C</span>' +
                  '</div>'
          },
          tooltip: {
              valueSuffix: ' °C'
          }
      }]

  }));


  setInterval(function () {
    
      var point,
          newVal,
          inc;

      if (chartSpeed) {
          point = chartSpeed.series[0].points[0];      
          $.get( "{{ route('temperaturas') }}", function( data ) {
                newVal=parseInt(data.temperatura)
                point.update(newVal);    
                $('#fecha_temp').html(data.fecha)            
          });
      }

  }, 2000);

// ----------
  Highcharts.chart('container', {

  chart: {
      type: 'gauge',
      alignTicks: false,
      plotBackgroundColor: null,
      plotBackgroundImage: null,
      plotBorderWidth: 0,
      plotShadow: false
  },

  title: {
      text: null
  },

  pane: {
      startAngle: -150,
      endAngle: 150
  },

  yAxis: [{
      min: 0,
      max: 200,
      lineColor: '#339',
      tickColor: '#339',
      minorTickColor: '#339',
      offset: -25,
      lineWidth: 2,
      labels: {
          distance: -20,
          rotation: 'auto'
      },
      tickLength: 5,
      minorTickLength: 5,
      endOnTick: false
  }],
  exporting: {
          enabled: false
      },

  series: [{
      name: 'Frecuancia cardíaca',
      data: [0],
      dataLabels: {
          formatter: function () {
              var kmh = this.y;
              return '<span style="color:#339">' + kmh + ' pulsos por minuto</span><br/>';
          },
          backgroundColor: {
              linearGradient: {
                  x1: 0,
                  y1: 0,
                  x2: 0,
                  y2: 1
              },
              stops: [
                  [0, '#DDD'],
                  [1, '#FFF']
              ]
          }
      },
     
      tooltip: {
          valueSuffix: ' pulsos por minuto'
      }
  }]

  },
  // Add some life
  function (chart) {
    setInterval(function () {
        if (chart.axes) { // not destroyed
            var point = chart.series[0].points[0];
            
            $.get( "{{ route('pulsos') }}", function( data ) {
                newVal = parseInt(data.pulso);
                point.update(newVal);
                $('#text_fecha_frecuencia').html(data.fecha)

            });
        }
    }, 2000);

  });

// -------



 </script>
 
 <script>

   var lat={{ $geo->lat}};
   var lng={{ $geo->lng}};
   var marker;
   var myLatLng = {lat: lat, lng: lng};

   function initMap() {
      

      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: myLatLng
      });

      marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Última posición'
      });
    }

    var marker;

    // every 10 seconds
    setInterval(function(){
      $.get("{{ route('obtenerLatLng') }}",{}, function(json) {
          var LatLng = new google.maps.LatLng(json.latitude, json.longitude);
          marker.setPosition(LatLng);
          $('#fecha_geo').html(json.fecha)
      });
    },2000);

    function updateMarker() {
      
    }



 </script>


<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4bcJ39miYRDXIr4ux3484nqQP1XqS9Bw&callback=initMap">
</script>
@endsection
