<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <title>bookingFind</title>

  <?php include '../include/MGF_header.html'; ?>  <!-- favicon.ico + bootstrap.css & fork awesome.css style sheets -->

</head>

<body onload="">

  <main role="main" class="container">

    <form class=form-inline>
      <!-- Default checked -->
      <div class="form-check form-check-inline ">
        <input type="radio" class="custom-control-input " id="outputTable" name="outputType" checked>
        <label class="custom-control-label mr-5" for="outputTable">Output to table</label>
      </div>

      <!-- Default unchecked -->
      <div class="form-check form-check-inline ">
        <input type="radio" class="custom-control-input" id="outputCSV" name="outputType">
        <label class="custom-control-label  mr-5" for="outputCSV">Output to CSV</label>
      </div>

      <div class="form-group">
        <label for="exampleFormControlSelect1">Example select</label>
        <select class="form-control" id="exampleFormControlSelect1">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
      </div>
    </form>

    <h5 id="title">bookingFind</h5><span>hostname: <?php echo gethostname() ?></span>

    <p class="d-sm-none"><small>Scroll right to view details</small></p>

    <!-- table of booking rows -->
    <div class="table-responsive">
      <table id="tblDeviceId" class="table table-dark table-bordered table-striped table-hover">
        <caption class="d-sm-none"><small>Scroll right to view details</small></caption>
        <thead class="thead-light">
          <tr>
            <th>IdNum (PK)</th>
            <th>MessDateTime</th>
            <th>MessType</th>
            <th>Application</th>
            <th>Routine</th>
            <th>UserId</th>
            <th>ErrorMess</th>
            <th>Remarks</th>
            <th>AlarmRaised</th>
          </tr>
        </thead>
        <tbody id="tbodyProcessLog"></tbody>
      </table>
    </div>
    
    <!-- error & warning messages -->
    <div id="output1" class="text-white bg-dark"></div> 

    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include '../include/MGF_libs.html'; ?>

  <script src="../js/bootstable.js"></script>  

  <!-- page javascipt -->
  <script src="../js/ProcessLog_maint.js"></script>

</body>
</html>
