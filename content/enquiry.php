<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Enquiry</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />
  <meta name="description" content="Holiday cottages in Saundersfoot, Pembrokeshire. A perfect family holiday and pets welcome." />
  <meta name="google-site-verification" content="a4KYq1XTIP3MdKDfpEZPIJjlyX8gvfMnMdIiDBmvq3w" />

  <?php include '../include/MGF_header.html'; ?>  <!-- favicon + bootstrap & fork awesome style sheets -->
  
  <link rel="stylesheet" href="../css/MGF.css" /> <!-- Project specific stylesheet here-->
  
  <?php include '../include/googleTrackingCoUk.html';  ?> <!-- load the Google tracking code for MGF.co.Uk -->

  <!-- Google recaptcha -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
  <!-- load the MGF menu -->
  <?php include '../include/MGF_menu.html';  ?>

  <!-- main content container -->
  <main role="main" class="container">

    <h3>Enquiry form</h3>

    <p class="lead">Please submit your booking enquiry or question below.</p>

    <!-- We're going to place the form here in the next step -->
    <form id="enquiryForm" class="needs-validation" novalidate onsubmit="postEnquiry(event)">

        <div class="form-row">
          <div class="form-group col-md-6 mb-3">
            <label for="first_name">First name *</label>
            <input id="first_name" type="text" name="first_name" class="form-control" placeholder="Please enter your first name *" maxlength="50" required>
            <div class="invalid-feedback">Input appears to be missing or invalid</div>

          </div>
          <div class="form-group col-md-6 mb-3">
            <label for="last_name">Last name *</label>
            <input id="last_name" type="text" name="last_name" class="form-control" placeholder="Please enter your last name *" maxlength="50" required>
            <div class="invalid-feedback">Input appears to be missing or invalid</div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6 mb-3">
            <label for="email_to">Email *</label>
            <input id="email_to" type="email" name="email_to" class="form-control" placeholder="Please enter your email *" maxlength="50" required>
            <div class="invalid-feedback">Please provide a valid email address</div>
          </div>
          <div class="form-group col-md-6 mb-3">
            <label for="telephone">Contact telephone (optional)</label>
            <input id="telephone" type="telephone" name="telephone" class="form-control" maxlength="50">
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
     
    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main> <!-- end of inner container -->

  <!-- include the footer -->
  <?php include  '../include/MGF_footer.html';  ?>

  <!-- inlcude the bootstrap, jquery and date libraries -->
  <?php include  '../include/MGF_libs.html'; ?>
 
  <!-- site javascipt -->
  <script src="../js/classes.js"></script>

  <!-- site javascipt -->
  <script src="../js/crud1.js"></script>

  <!-- page javascipt -->
  <script src="../js/enquiry.js"></script>

</body>
</html>