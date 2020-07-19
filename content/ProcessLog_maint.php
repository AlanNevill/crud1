<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <title>ProcessLog</title>

  <link rel="stylesheet" href="../css/ProcessLog_maint.css" /> <!-- Page specific stylesheet -->

  <?php 
    include ('../include/MGF_header.html'); # favicon.ico + bootstrap.css & fork awesome.css style sheets
    include ('../include/dbFuncs.php');     # open db connection and instantiate dbFuncs class
  ?>  

</head>

<body onload="showProcessLog2()" class="m-2">

  <main role="main">

    <!-- debug info div for validation and error messages -->
    <section id="info" class="text-white bg-success">INFO - 
      <?php
        echo $dbFuncs->getHostAndDb();
      ?>
    </section>

    <!-- error & warning messages -->
    <div id="output1" class="bg-warning"></div> 

    <div><h5 id="title">ProcessLog maintenance</h5></div>

    <!-- form to allow user to select options -->
    <form  id="formGet">
      <div class="form-row">
        <div class="form-group col-2">
          <label for="messType" class="mb-0">Mess Type</label>
          <select id="messType" name="messType" class="custom-select">
            <option value="-1" selected disabled hidden>Select</option>
            <option value="1">Ignore</option>
            <option value="2">Errors</option>
            <option value="3">Warnings</option>
            <option value="4">Information</option>
            <option value="5">Errors & Warnings</option>

          </select>
        </div>
        <div class="form-group col-2">
          <label for="alarmRaised" class="mb-0">Alarm Raised</label>
          <select id="alarmRaised" name="alarmRaised" class="custom-select">
            <option value="-1" selected disabled hidden>Select</option>
            <option value="1">Ignore</option>
            <option value="2">Yes</option>
            <option value="3">No</option>
          </select>
        </div>
        <div class="form-group col-2">
          <label for="limitNum" class="mb-0">Limit Number</label>
          <select id="limitNum" name="limitNum" class="custom-select">
            <option value="-1" selected disabled hidden>Select</option>
            <option value="1">Ignore</option>
            <option value="2">Yes</option>
            <option value="3">No</option>
          </select>
        </div>
      </div>
    </form>

    <!-- table of ProcessLog rows -->
    <div class="table-responsive">
      <table id="tblDeviceId" class="table table-dark table-bordered table-striped table-hover">
        <caption class="d-sm-none"><small>Scroll right to view details</small></caption>
        <thead class="thead-light">
          <tr>
            <th>IdNum (PK)</th>
            <th>Mess Date Time</th>
            <th>Mess Type</th>
            <th>Application</th>
            <th>Routine</th>
            <th>User Id</th>
            <th>Error Mess</th>
            <th>Remarks</th>
            <th>Alarm Raised</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tbodyProcessLog"></tbody>
      </table>
    </div>
    

    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include '../include/MGF_libs.html'; ?>

  <script src="../js/bootstable.js"></script>  

  <!-- page javascript -->
  <script src="../js/ProcessLog_maint.js"></script>

</body>
</html>
