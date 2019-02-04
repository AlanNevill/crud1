<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Our_Animals</title>
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

      <div id="carouselExampleIndicators" class="carousel carousel-fade" data-ride="carousel" data-interval="3000">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner embed-responsive" role="listbox">
          <div class="carousel-item active">
              <img class="img-fluid w-100" src=/images/facilities/animals/sheep-dog-1000-compressor.jpg alt="First slide">
              <!--<div class="carousel-caption">
                  <h3>First slide</h3>
                  <p>animals/sheep dog 1000</p>
              </div>-->
          </div>
          <div class="carousel-item">
              <img class="lazy img-fluid w-100" src=/images/facilities/animals/hens-1000-compressor.jpg alt="Second slide">
              <!--<div class="carousel-caption">
                  <h3>Second slide</h3>
                  <p>animals/hens 1000</p>
              </div>-->
          </div>
          <div class="carousel-item">
              <img class="lazy img-fluid w-100" src=/images/facilities/animals/herding-1000-compressor.jpg alt="Fith slide">
              <!--<div class="carousel-caption">
                  <h3>Fourth slide</h3>
                  <p>animals/herding 1000</p>
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
          Our Ryeland sheep are family pets who like to be petted and hand fed. The ewes are named after flowers and the boys after herbs.
        </p>
        <p>
          Maisy our border collie is 6 years old and has helped many children to overcome their fear of dogs. She adores having a ball thrown for her.
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