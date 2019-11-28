<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Facilities</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />
  <meta name="description" content="Holiday cottages in Saundersfoot, Pembrokeshire. A perfect family holiday and pets welcome." />
  <meta name="google-site-verification" content="a4KYq1XTIP3MdKDfpEZPIJjlyX8gvfMnMdIiDBmvq3w" />

  <?php include '../include/MGF_header.html'; ?>        <!-- favicon + bootstrap & fork awesome style sheets -->

  <!-- Place your stylesheet here-->
  <link rel="stylesheet" href="../css/MGF.css" />

  <!-- load the Google tracking code for MGF.co.Uk -->
  <?php include '../include/googleTrackingCoUk.html';  ?>

</head>

<body>
  <!-- load the MGF menu -->
  <?php include '../include/MGF_menu.html';  ?>

  <!-- main content container -->
  <main role="main" class="container text-center">

    <div id="carouselExampleIndicators" class="carousel carousel-fade" data-ride="carousel" data-interval="3000">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
      </ol>
      <div class="carousel-inner embed-responsive" role="listbox">
        <div class="carousel-item active">
          <img class="img-fluid w-100" src=../images/facilities/Play-area-14-1000-compressor.jpg alt="First slide">
          <!--<div class="carousel-caption">
              <h3>First slide</h3>
              <p>Play area 14 1000</p>
          </div>-->
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=../images/facilities/Beach-hut-2-1000-compressor.jpg alt="Second slide">
          <!--<div class="carousel-caption">
              <h3>Second slide</h3>
              <p>Beach hut 2 100</p>
          </div>-->
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=../images/facilities/DSC01047-1000-compressor.jpg alt="Third slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=../images/facilities/lake-3-1000-compressor.jpg alt="Fourth slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=../images/facilities/Chris-Morrell-4-1000-compressor.jpg alt="Fith slide">
          <!--<div class="carousel-caption">
              <h3>Fith slide</h3>
              <p>Chris Morrell 4 1000</p>
          </div>-->
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
        Our farm is a relaxing, welcoming holiday destination for couples and families of all ages.
      </p>
      <p>
        Enjoy the books, board games, puzzles and toys from the beach hut. Take leisurely walks around the 23 acres of rolling meadows, follow woodland trails around the fishing lakes or explore the nearby coastal footpaths that caters for all levels of fitness. Pet and feed our friendly sheep and chickens and throw a ball for Maisy the dog. Following a fun filled day at the blue flag beaches or the award winning local attractions such as Folly Farm and Barafundle Bay, a warm welcome awaits you back on the farm..
      </p>
      <p>
        For more details please see the pages below.
      </p>
      <div class="row justify-content-center">
        <div class="col-11 col-md-5">
          <ul class="text-left">
            <li><a href="facilitiesBeachHut.html">The Beach Hut</a></li>
            <li><a href="facilitiesPlayArea.html">Childrens play area</a></li>
            <li><a href="facilitiesBabies.html">Babies &amp; Toddlers</a></li>
            <li><a href="facilitiesAnimals.html">The Animals</a></li>
            <li><a href="facilitiesLakesWoods.html">The lakes &amp; nature trails</a></li>
          </ul>
        </div>
      </div>
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