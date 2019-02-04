<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Contact</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Holiday cottages in Saundersfoot, Pembrokeshire. A perfect family holiday and pets welcome." />
  <meta name="google-site-verification" content="a4KYq1XTIP3MdKDfpEZPIJjlyX8gvfMnMdIiDBmvq3w" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <!-- Place your stylesheet here-->
  <link rel="stylesheet" href="../css/crud1.css" />

  <!-- load the Google tracking code for MGF.co.Uk -->
  <?php include '../include/googleTrackingCoUk.html';  ?>

</head>

<body>
  <!-- load the MGF menu -->
  <?php include '../include/MGF_menu.html';  ?>

  <!-- main content container -->
  <main role="main" class="container text-center">

      <h5>Address: <strong>Meadow Green Farm, Hopshill Lane, Sundersfoot, SA69 9ED</strong></h5>
      <h5>Telephone: <strong>07739 171080</strong></h5>
      <h5>Email: <strong>meadowgreenfarm@gmail.com</strong></h5>
      <h5>Map: Satellite navigation or Google maps using our postcode SA69 9ED will bring you to our drive.</h5>

      <div id="map" style="height:500px"></div>

  </main> <!-- end of inner container -->

  <!-- include the footer -->
  <?php include  '../include/MGF_footer.html';  ?>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include  '../include/MGF_libs.html'; ?>

  <!-- site javascipt -->
  <script src="../js/crud1.js"></script>

  <script type="text/javascript">
      // function to set up the map and add listeners to keep the map centered //
      function initMap() {
          // define variables //
          var myLatLng = new google.maps.LatLng(51.70992, -4.717545),
              mapOptions = {
                  zoom: 15,
                  center: myLatLng,
                  mapTypeId: google.maps.MapTypeId.ROADMAP
              },

              map = new google.maps.Map(document.getElementById("map"), mapOptions),
              contentString = "Welcome to Meadow Green Farm!",
              infowindow = new google.maps.InfoWindow({
                  content: contentString,
                  maxWidth: 500
              }),

              marker = new google.maps.Marker({
                  position: myLatLng,
                  map: map
              });

          // add listener for marker click event //
          google.maps.event.addListener(marker, "click", function () {
              infowindow.open(map, marker);
          });

          // add listener for window resize event //
          google.maps.event.addDomListener(window, "resize", function () {
              var center = map.getCenter();
              google.maps.event.trigger(map, "resize");
              map.setCenter(center);
          });

      }

  </script>

  <!-- Google map script including the key AIzaSyAHXdAq2C_2-Rnv64m4uan5ej4DwEcEEws and callback to initMap function -->
  <script async defer
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHXdAq2C_2-Rnv64m4uan5ej4DwEcEEws&callback=initMap">
  </script>

</body>
</html>