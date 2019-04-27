<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Play_area</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />
  <meta name="description" content="Holiday cottages in Saundersfoot, Pembrokeshire. A perfect family holiday and pets welcome." />
  <meta name="google-site-verification" content="a4KYq1XTIP3MdKDfpEZPIJjlyX8gvfMnMdIiDBmvq3w" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <!-- Place your stylesheet here-->
  <link rel="stylesheet" href="../css/MGF.css" />

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
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
        </ol>
        <div class="carousel-inner embed-responsive" role="listbox">
          <div class="carousel-item active">
              <img class="img-fluid w-100" src=../images/facilities/playArea/Play-area-9-1000-compressor.jpg alt="First slide">
              <!--<div class="carousel-caption">
                  <h3>First slide</h3>
                  <p>playArea/Play area 9 1000</p>
              </div>-->
          </div>
          <div class="carousel-item">
              <img class="lazy img-fluid w-100" src=../images/facilities/playArea/IMG_2819-1000-compressor.jpg alt="Second slide">
              <!--<div class="carousel-caption">
                  <h3>Second slide</h3>
                  <p>playArea/IMG_2819 1000</p>
              </div>-->
          </div>
          <div class="carousel-item">
              <img class="lazy img-fluid w-100" src=../images/facilities/playArea/toy-2-1000-compressor.jpg alt="Third slide">
          </div>
          <div class="carousel-item">
              <img class="lazy img-fluid w-100" src=../images/facilities/playArea/IMG_2672-1000-compressor.jpg alt="Fourth slide">
          </div>
          <div class="carousel-item">
              <img class="lazy img-fluid w-100" src=../images/facilities/playArea/IMG_2672-1000-compressor.jpg alt="Fifth slide">
              <!--<div class="carousel-caption">
                  <h3>Fith slide</h3>
                  <p>playArea/IMG_2690 1000</p>
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

      <!--<h4 style="background-color: #00C851;">Childrens play area</h4>-->
      <section>
        <p class="lead text-left">
          There is a designated children's play area with both large and small, indoor and outdoor toys including :
        </p>
        <div class="row justify-content-center">
          <div class="col-12 col-md-5">
              <ul class="text-left">
                <li>swings</li>
                <li>playhouse</li>
                <li>small slide</li>
                <li>trampoline</li>
                <li>sandpit with buckets and spades</li>
                <li>various sit on and push along toys</li>
                <li>sports equipment</li>
                <li>fishing nets</li>
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