<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Home</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />
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

  <!-- container used within outer container to give white border -->
  <main class="container text-center">

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
      <div class="carousel-inner embed-responsive" role="listbox">
        <div class="carousel-item active">
          <img class="img-fluid w-100" src=/images/home/IMG_1270-1000-compressor.jpg alt="Cowslip cottage">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/home/Cowslip-garden-1-1000-compressor.jpg alt="Cowslip cottage">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/home/Cornflower-exterior-1000-compressor.jpg alt="Cornflower cottage">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/home/Meadowswett-exterior-3-1000-compressor.jpg alt="Meadowsweet cottage">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/home/newLambs1000-compressor.jpg alt="New lambs">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src=/images/home/Play-area-9-1000-compressor.jpg alt="Childrens play area">
        </div>
        <div class="carousel-item">
          <img class="lazy img-fluid w-100" src="/images/home/Sunset 4 1000.jpg" alt="Sunset over the farm">
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
        Meadow Green Farm nestles in 23 acres of rolling green meadows in the Pembrokeshire National Park, on the outskirts of the pretty and bustling seaside village of Saundersfoot.
      <p />
      <p>
        There are three contemporary self-catering cottages to provide you with an enchanting holiday. Each is fully stocked to provide a home away from home, and have their own private sunny gardens with tables, chairs and BBQs.
      <p />
      <p>
        Everyone is well catered for at Meadow Green Farm: for couples a quiet haven of tranquillity. For families we have a trampoline, swings, a playhouse as well as numerous outdoor and indoor toys, books and board games to keep children of all ages entertained. Animal lovers may bring their own dogs, pet our friendly sheep and collect eggs daily from our free-range chickens. For those who love the outdoors, our two lakes may be fished, and you are free to explore our meadows and woods, discovering the many footpaths across the land.
      <p />
      <p>
        Meadow Green Farm is within walking distance of Saundersfoot village, many picturesque beaches and the stunning coastal path. Indeed, we are just a short drive to numerous blue flag beaches including Barafundle Bay and fantastic seaside resorts such as Tenby, Pendine and Laugharne. As well as family attractions including Folly Farm, Tenby Dinosaur Park, Pembroke Castle and Oakwood Theme Park.
      </p>

      <h3 class="text-center">We look forward to welcoming you to Meadow Green Farm</h3>
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
