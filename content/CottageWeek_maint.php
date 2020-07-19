<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <title>Cottage Week</title>

  <link rel="stylesheet" href="../css/CottageWeek_maint.css" /> <!-- Page specific stylesheet -->

  <?php 
    include ('../include/MGF_header.html'); # favicon.ico + bootstrap.css & fork awesome.css style sheets
    include ('../include/dbFuncs.php');     # open db connection and instantiate dbFuncs class
  ?>  

</head>

<body class="m-2">

  <main role="main">

    <!-- debug info div for validation and error messages -->
    <section id="info" class="text-white bg-success">INFO - 
      <?php
        echo $dbFuncs->getHostAndDb();
      ?>
    </section>

    <!-- error & warning messages -->
    <div id="output1" class="bg-warning"></div> 

    <div><h5 id="title">CottageWeek maintenance</h5></div>

    <!-- form to allow user to select options -->
    <form  id="formGet">
      <div class="form-row form-inline">
        <div class="form-group col-2">
          <label for="cottageNum">Cottage:</label>
          <select id="cottageNum" name="messType" class="custom-select">
            <option value="-1" selected disabled hidden>Select</option>
            <option value="1">Cornflower</option>
            <option value="2">Cowslip</option>
            <option value="3">Meadowsweet</option>
          </select>
        </div>
      </div>
    </form>

    <!-- table of ProcessLog rows -->
    <div class="table-responsive">
      <table id="tblCottageWeek" class="table table-light table-bordered table-striped table-hover">
        <caption class="d-sm-none"><small>Scroll right to view details</small></caption>
        <thead class="thead-dark">
          <tr>
            <th>DateSat</th>
            <th>Short breaks</th>
            <th>Day rent</th>
            <th>Week rent</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
    

    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include '../include/MGF_libs.html'; ?>

  <script src="../js/bootstable.js"></script>  

  <!-- class functions -->
  <script src="../js/classes.js"></script>

  <!-- page javascript -->
  <script src="../js/CottageWeek_maint.js"></script>

</body>
</html>
