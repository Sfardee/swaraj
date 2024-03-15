<?php 
if (isset($_GET['auth']) && $_GET['auth'] == 'NnIUn5AeYXzMHqsFDn5eEte1H6mPl0yu7Swuhdb5') {
?>
   <!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt0Yi7X7jPIEoj1YPhJun7QoGjsAmwzds&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
    <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <script>
      let map;

      function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
          center: { lat: -34.397, lng: 150.644 },
          zoom: 8,
        });
      }
    </script>
  </head>
  <body>
    <div id="map"></div>
    <br><br>
    <img src="https://maps.googleapis.com/maps/api/staticmap?center=40.714728,-73.998672&zoom=12&size=400x400&maptype=roadmap&key=AIzaSyCt0Yi7X7jPIEoj1YPhJun7QoGjsAmwzds">
  </body>
</html>
<?php } else {
  echo "It's working";
}
