<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Babies</title>
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

  <main role="main" class="container text-center">
    <!-- main content container -->

    <div id="carouselExampleIndicators" class="carousel carousel-fade" data-ride="carousel" data-interval="3000">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1" ></li>
      </ol>
      <div class="carousel-inner embed-responsive" role="listbox">
        <div class="carousel-item active">
          <img class="img-fluid w-100" src=/images/facilities/babies/image1-compressor.jpeg alt="Full size wooden cot">
        </div>
        <div class="carousel-item">
          <img class="img-fluid w-100" src=/images/facilities/babies/image2-compressor.jpeg alt="Single beds with bed guard">
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

    <section>
      <p class="lead">
        Our very welcome small guests have suitable toys and a choice of mosses basket, cot, toddler bed or single bed with safety bed guard.
        All with the appropriate bedlinen.
      </p>
      <p>
        Bedrooms have night lights; baby alarm and blackout blinds are also available.
      </p>
      <p>
          For meal times; high chairs, booster seats, cutlery, plates and beakers.
      </p>
      <p>
        Bathrooms have safety mats, step ups, potty, nappy bucket, changing mat and bath toys.
      </p>
    </section>
  </main> <!-- end of inner container -->

  <!-- include the footer -->
  <?php include  '../include/MGF_footer.html';  ?>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include  '../include/MGF_libs.html'; ?>

  <!-- Lazy loading libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.plugins.min.js"></script>

  <!-- site javascipt -->
  <script src="../js/crud1.js"></script>

  <!-- Lazy load images when document ready -->
  <script>
    $(function() {
      // Lazy load images
      $(function () {
        $('.lazy').Lazy();
      });
    });
  </script>

</body>
</html>