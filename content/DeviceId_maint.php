<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />

  <title>DeviceId</title>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

  <!-- Latest compiled and minified Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- Fork-awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fork-awesome@1.1.5/css/fork-awesome.min.css" integrity="sha256-P64qV9gULPHiZTdrS1nM59toStkgjM0dsf5mK/UwBV4=" crossorigin="anonymous">

  <!-- <link rel="stylesheet" href="../css/MGF.css" /> -->
  <link rel="stylesheet" href="../css/DeviceId_maint.css" />

  <?php 
    // include ('../include/MGF_header.html'); # favicon.ico + bootstrap.css & fork awesome.css style sheets
    include ('../include/dbFuncs.php');     # open db connection and instantiate dbFuncs class
  ?>  

</head>

<body onload="showDeviceId()" class="m-2">

  <main role="main" class="container-fluid">

    <!-- debug info div for validation and error messages -->
    <section id="info" class="text-white bg-success">INFO - 
      <?php
        echo $dbFuncs->getHostAndDb();
      ?>
    </section>

    <!-- error & warning messages -->
    <div id="output1" class="bg-warning"></div> 

    <h2 id="title">DeviceId maintenance</h2><span></span>

    <p class="d-sm-none"><small>Scroll right to view details</small></p>

    <!-- table of DeviceId rows -->
    <div class="table-responsive">
        <table id="tblDeviceId" class="table table-light table-bordered table-striped table-hover">
            <caption class="d-sm-none"><small>Scroll right to view details</small></caption>
            <thead class="thead-light">
                <tr>
                  <th>DeviceId (PK)</th>
                  <th>DeviceDesc</th>
                  <th>UserAgentString</th>
                  <th name="buttons">edit delete</th>
                </tr>
            </thead>
            <tbody id="tbodyDeviceId"></tbody>
        </table>
    </div>
    

    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include  '../include/MGF_libs.html'; ?>

  <!-- table editing library -->
  <script src="../js/bootstable.js"></script>  

  <!-- page javascript -->
  <script src="../js/DeviceId_maint.js"></script>

</body>
</html>
