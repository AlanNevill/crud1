<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Cornflower</title>
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
        <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
      </ol>
      <div class="carousel-inner embed-responsive" role="listbox">
        <div class="carousel-item active">
          <img class="img-fluid w-100" src=../images/cornflower/Cornflower-living-room-7-1000-compressor.jpg alt="First slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=../images/cornflower/Cornflower-lounge-2016-04-24a-1000-compressor.jpg alt="Second slide">
        </div>
        <div class="carousel-item">
            <img class="lazy img-fluid w-100" src=../images/cornflower/Cornflower-bedroom-2-1000-compressor.jpg alt="Third slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=../images/cornflower/Cornflower-twin-room-1-1000-compressor.jpg alt="Fourth slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=../images/cornflower/IMG_0044-1000-compressor.jpg alt="Fifth slide">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=../images/cornflower/Cornflower-exterior-1000-compressor.jpg alt="Sixth slide">
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
          Cornflower is a tranquil haven with two bedrooms and one bathroom. Renovated throughout and furnished to a high standard. The high A frame ceiling and cottage style windows add to the ambiance.
        </p>
        <p>
          Reached by an enclosed private garden. The living area has comfortable sofas, flat screen TV and DVD player. Books, toys and games may be borrowed from the beach hut.
        </p>
        <p>
          The kitchen includes dishwasher, fridge with an ice box, microwave, hob and oven. There is also a comprehensive range of cook and tableware.
        </p>
        <p>
          The fresh, soothing double bedroom overlooks the lake and meadows. There is generous hanging and storage space. A twin bedroom provides two full sized single beds and ample storage.
        </p>
        <p>
            The modern bathroom has a bath with overhead power shower, heated towel rail, sink and w.c.
        </p>
        <p>
            Enjoy al fresco dining in the sunny garden with patio furniture and BBQ.
        </p>
        <p>
          Cornflower has central heating. Towels and bed linen are provided. All electricity, heating and Wi-Fi are included in the rental.
        </p>
        <p>
            Dogs are not allowed in Cornflower and smoking is not permitted inside the property.
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