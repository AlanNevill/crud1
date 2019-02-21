<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>DeviceId</title>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

  <!-- Latest compiled and minified Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- Fork-awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fork-awesome@1.1.5/css/fork-awesome.min.css" integrity="sha256-P64qV9gULPHiZTdrS1nM59toStkgjM0dsf5mK/UwBV4=" crossorigin="anonymous">

  <link rel="stylesheet" href="../css/crud1.css" />
  <link rel="stylesheet" href="../css/DeviceId_maint.css" />

</head>

<body onload="showDeviceId()">

  <main role="main" class="container">

    <h5 id="title">DeviceId maintenance</h5><span></span>

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
                </tr>
            </thead>
            <tbody id="tbodyDeviceId"></tbody>
        </table>
    </div>
    
    <!-- error & warning messages -->
    <div id="output1" class="text-white bg-dark"></div> 

    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include  '../include/MGF_libs.html'; ?>

  <script src="../js/bootstable.js"></script>  

  <!-- page javascipt -->
  <script src="../js/DeviceId_maint.js"></script>

</body>
</html>
