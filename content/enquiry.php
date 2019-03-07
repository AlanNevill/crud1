<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Enquiry</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />
  <meta name="description" content="Holiday cottages in Saundersfoot, Pembrokeshire. A perfect family holiday and pets welcome." />
  <meta name="google-site-verification" content="a4KYq1XTIP3MdKDfpEZPIJjlyX8gvfMnMdIiDBmvq3w" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <!-- Fork-awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fork-awesome@1.1.5/css/fork-awesome.min.css" integrity="sha256-P64qV9gULPHiZTdrS1nM59toStkgjM0dsf5mK/UwBV4=" crossorigin="anonymous">

  <!-- Project specific stylesheet here-->
  <link rel="stylesheet" href="../css/crud1.css" />

  <!-- load the Google tracking code for MGF.co.Uk -->
  <?php include '../include/googleTrackingCoUk.html';  ?>

  <!-- Google recaptcha -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
  <!-- load the MGF menu -->
  <?php include '../include/MGF_menu.html';  ?>

  <!-- main content container -->
  <main role="main" class="container">

      <!-- <div class="col-xl-8 offset-xl-2 py-5">  -->

        <h3>Enquiry form</h3>

        <p class="lead">Please submit your booking enquiry or question below.</p>

        <!-- We're going to place the form here in the next step -->
        <form id="enquiryForm" class="needs-validation" novalidate="" onsubmit="postEnquiry(event)">

            <div class="form-row">
              <div class="col-md-6 mb-3">
                <label for="first_name">First name *</label>
                <input id="first_name" type="text" name="first_name" class="form-control" placeholder="Please enter your first name *" maxlength="50" required>
                <div class="invalid-feedback">Input appears to be missing or invalid</div>

              </div>
              <div class="col-md-6 mb-3">
                <label for="last_name">Last name *</label>
                <input id="last_name" type="text" name="last_name" class="form-control" placeholder="Please enter your last name *" maxlength="50" required>
                <div class="invalid-feedback">Input appears to be missing or invalid</div>

              </div>
            </div>

            <div class="form-row">
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label for="email_to">Email *</label>
                  <input id="email_to" type="email" name="email_to" class="form-control" placeholder="Please enter your email *" maxlength="50" required>
                  <div class="invalid-feedback">Please provide a valid email address</div>

                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label for="telephone">Contact telephone (optional)</label>
                  <input id="telephone" type="telephone" name="telephone" class="form-control" maxlength="50">
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <label for="enquiry">Enquiry *</label>
                  <textarea id="enquiry" name="enquiry" class="form-control" placeholder="Enquiry *" rows="4"  maxlength="500" required></textarea>
                  <div class="invalid-feedback">Enquiry is empty</div>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-xs-6 mb-3">
                <div class="g-recaptcha" data-sitekey="6Lc3-pUUAAAAAH_KDrFr1C45TgN05n2dQ_-YcOji"></div>
              </div>
              <div class="col-xs-6 ml-auto">
                <p class="text-muted"><strong>*</strong> These fields are required.</p>
              </div>

            </div>
          </div>
          <div id="output1"></div>
        
          <button type="submit" class="btn btn-primary mb-3">Submit enquiry</button>
        </form> 

      <!-- </div> -->
      
    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>


  </main> <!-- end of inner container -->

  <!-- include the footer -->
  <?php include  '../include/MGF_footer.html';  ?>

  <!-- inlcude the bootstrap, jquery and date libraries -->
  <?php include  '../include/MGF_libs.html'; ?>
 
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js" integrity="sha256-dHf/YjH1A4tewEsKUSmNnV05DDbfGN3g7NMq86xgGh8=" crossorigin="anonymous"></script> -->

  <!-- site javascipt -->
  <script src="../js/classes.js"></script>

  <!-- site javascipt -->
  <script src="../js/crud1.js"></script>

  <!-- page javascipt -->
  <script src="../js/enquiry.js"></script>


</body>
</html>