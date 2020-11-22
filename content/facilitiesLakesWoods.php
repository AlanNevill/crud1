<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Lakes_and_woods</title>
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
  <main role="main" class="container">

    <div id="carouselExampleIndicators" class="carousel carousel-fade" data-ride="carousel" data-interval="3000">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
      </ol>
      <div class="carousel-inner embed-responsive" role="listbox">
        <div class="carousel-item active">
          <img class="d-block w-100" alt="Wild water swimming"
            srcset="../images/facilities/lakesWoods/VID_20200724_142139.webp"
            src="../images/facilities/lakesWoods/VID_20200724_142139.jpg">
        </div>
        <div class="carousel-item">
          <img class="img-fluid w-100" src=../images/facilities/lakesWoods/IMG_1298-1000-compressor.jpg alt="Second slide">
        </div>
        <div class="carousel-item">
          <img class="img-fluid w-100" src=../images/facilities/lakesWoods/IMG_9025-1000-compressor.jpg alt="Third slide">
        </div>
        <div class="carousel-item">
          <img class="img-fluid w-100" src=../images/facilities/lakesWoods/DSC03576-1000-compressor.jpg alt="Fourth slide">
        </div>
        <div class="carousel-item">
          <img class="img-fluid w-100" src=../images/facilities/lakesWoods/lake-4-1000-compressor.jpg alt="Fifth slide">
        </div>
        <div class="carousel-item">
          <img class="img-fluid w-100" src=../images/facilities/lakesWoods/trail-2-1000-compressor.jpg alt="Sixth slide">
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
      <h3>Wild water swimming</h3>
        <p>Our lakes are fresh water fed and are safe for comptent swimmers. Children must always be accompanied by an adult.</p>
      <h3>Fishing</h3>
        <p>The farm includes two lakes which you may fish or feed with bread. Perch and roach have been caught. Bring your own rods; we have nets in the beach hut for children.</p>
      <h3>Nature trails</h3>
        <p>We have numerous footpaths that you may meander along. Cross the bubbling stream, discover the extensive wild life. Take a picnic and relax.</p>
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