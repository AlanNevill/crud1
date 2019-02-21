<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Cowslip</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Holiday cottages in Saundersfoot, Pembrokeshire. A perfect family holiday and pets welcome." />
  <meta name="google-site-verification" content="a4KYq1XTIP3MdKDfpEZPIJjlyX8gvfMnMdIiDBmvq3w" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

  <!-- Latest compiled and minified Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

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
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
      </ol>
      <div class="carousel-inner embed-responsive" role="listbox">
        <div class="carousel-item active">
          <img class="img-fluid w-100" src=/images/cowslip/IMG_0063-1000-compressor.jpg alt="Lounge slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/cowslip/Cowslip-double-room-1-1000-compressor.jpg alt="Main bedroom slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/cowslip/Cowslip-twin-room-5-1000-compressor.jpg alt="Twin bedroom slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/cowslip/IMG_1139-1000-compressor.jpg alt="Kitchen slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/cowslip/Cowslip-garden-1-1000-compressor.jpg alt="Back garden slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/cowslip/Cowslip-garden-3-1000-compressor.jpg alt="Back garden 2 slide">
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
        Cowslip cottage has two bedrooms and one bathroom. It has been sympathetically renovated to provide a comfortable holiday retreat with stylish furnishings in keeping with the traditional beams and cottage style windows.
      </p>
      <p>
        Reached via a private enclosed garden it has an open-plan lounge and kitchen/diner. The kitchen includes dishwasher, fridge with an ice box, microwave, hob and oven. There is also a comprehensive range of cook and tableware. The lounge is equipped with flat screen TV, DVD player and a selection of books.
      </p>
      <p>
        A pretty double bedroom with fresh white linen sheets provides ample hanging and storage space. This charming room boasts views over our lake and surrounding meadows.
      </p>
      <p>
        A pretty double bedroom, with fresh white linen, provides views over the lake and ample hanging and storage space.
      </p>
      <p>
        Cowslip’s twin bedroom with its pretty views is equipped with ample storage and provides two single beds, which may be exchanged for wooden cots.
      </p>
      <p>
        The modern bathroom contains a walk-in shower, sink, heated towel rail and w.c.
      </p>
      <p>
        Enjoy al fresco dinning in the secluded sunny garden with comfortable patio furniture and BBQ.
      </p>
      <p>
        Cowslip has central heating. Towels and bed linen are provided. All electricity, heating and Wi-Fi are included in the rental.
      </p>
      <p>
        Pets are welcome at Cowslip however smoking is not permitted inside the property.
      </p>
    </section>

    <div class="row">
    <div class="col text-white">
      <a type="button" class="btn btn-success" href="newBooking.php">Availiability</a>
    </div>
  </div>

      <br />

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