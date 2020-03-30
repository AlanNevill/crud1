<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Cornflower</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />
  <meta name="description" content="Holiday cottages in Saundersfoot, Pembrokeshire. A perfect family holiday and pets welcome." />
  <meta name="google-site-verification" content="a4KYq1XTIP3MdKDfpEZPIJjlyX8gvfMnMdIiDBmvq3w" />
  
  <?php include '../include/MGF_header.html'; ?>        <!-- favicon + bootstrap & fork awesome style sheets -->

  <!-- Site stylesheet -->
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
        <li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
      </ol>
      <div class="carousel-inner embed-responsive">
        <div class="carousel-item active">
          <img class="d-block w-100" src= "../images/cornflower/IMG_20200229_123649_1000.jpg" alt="Main bedroom">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src= "../images/cornflower/IMG_20200229_124031_A_1000.jpg" alt="Second slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../images/cornflower/IMG_20200229_133152_1000.jpg" alt="Second bedroom">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../images/cornflower/IMG_20200229_135944_1000.jpg" alt="Lounge 1">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../images/cornflower/IMG_20200229_140330_1000.jpg" alt="Lounge 2">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../images/cornflower/IMG_20200229_135007_1000.jpg" alt="Kitchen">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="../images/cornflower/IMG_0044-1000.jpg" alt="Bathroom">
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
        Cornflower has central heating. Towels and bed linen are provided. All electricity, heating and streaming Wi-Fi are included in the rental.
      </p>
      <p>
        Dogs are not allowed in Cornflower and smoking is not permitted inside the property.
      </p>
    </section>

    <div class="row">
      <div class="col text-white">
        <a type="button" class="btn btn-success" href="newBooking.php">Availability</a>
      </div>
    </div>

    <br />

  </main> <!-- end of inner container -->

  <!-- include the footer -->
  <?php include  '../include/MGF_footer.html';  ?>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include  '../include/MGF_libs.html'; ?>

  <!-- Lazy loading libraries -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.plugins.min.js"></script> -->

  <!-- site javascript -->
  <script src="../js/crud1.js"></script>

</body>
</html>