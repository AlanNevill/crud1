// testHarness2.js
'use strict';

// var clsbookingMaint = new clsbookingMaint();

$(document).ready(function() {
  // let dateSatCookie = readCookie('dateSatCookie');
  // if (dateSatCookie == null) {
  //     $('info').empty().append('dateSatCookie not found');
  // }
  // else $('info').empty().append(dateSatCookie);
  // if (clsbookingMaint.cottageNum) {
  //     $('#output1').empty().append('Read cottageNum: ' + clsbookingMaint.cottageNum);
  // }
  // if (sessionStorage.getItem("bookingMaint")) {
  //     // Restore the contents of bookingMaint from session storage
  //     const bookingMaint = JSON.parse(sessionStorage.getItem("bookingMaint"));
  //     $('#output1').empty().append(bookingMaint.cottageNum);
  // }
  // Create a new ClientJS object
  // const _clsDeviceIdCookie = new clsDeviceIdCookie();
  // // Get the client's fingerprint id
  // var fingerprint = client.getFingerprint();
  // let userAgentString = client.getUserAgent();
  // let output1Str = `Fingerprint: ${fingerprint}`;
  // $('#info').empty().append(output1Str);
  // $('#output1').empty().append(`userAgentStr: ${userAgentString}`);
  // $.post(
  //   "include/common_ajax.php",
  //   {
  //     method: "EnquiryResponseEmail",
  //     first_name: "FirstName", // required
  //     last_name: "LastName", // required
  //     email_to: "alannevill@gmail.com", // required
  //     telephone: "07986990062", // not required
  //     enquiry: "Test enquiry 01"
  //   },
  //   function(data, textStatus, jqXHR) {
  //     if (isJSON(data)) {
  //       let funcReturn = JSON.parse(data);
  //       if (funcReturn.success === true) {
  //         console.log(`success=true, message: ${funcReturn.message}`);
  //         $("#output1")
  //           .empty()
  //           .append(`success=true, message: ${funcReturn.message}`);
  //       } else {
  //         console.log(`success=false, message: ${funcReturn.message}`);
  //         $("#output1")
  //           .empty()
  //           .append(`success=false, message: ${funcReturn.message}`);
  //       }
  //     } else {
  //       $("#output1")
  //         .empty()
  //         .append(
  //           "ERROR - common_ajax.php/EnquiryResponseEmail: did not return JSON data"
  //         );
  //     }
  //   }
  // ); // end of $.post
  // alert(`Fingerprint: ${fingerprint}`);
  // Print the 32bit hash id to the console
  // console.log(fingerprint);
  // console.log(client.getUserAgent());
  // setCookie('deviceId',fingerprint,1);
  // checkCookie();
  // setCookie("test1","test1 value",1);
  // setCookie("test2","test2 value",1);
  // var ca = document.cookie;
  // var cookieValue=getCookie("test2");
  // var userName = getCookie(fingerprint);
  // let testString ="{(({_}),)}";
  // let myIsJson = isJSON(testString);
  // alert("testString: " + testString + "\nisJSON returned: " + myIsJson + "\nJSON.parse: " );
});

function confirmDialog(message, handler) {
  $(`<div class="modal fade" id="myModal" role="dialog"> 
     <div class="modal-dialog"> 
       <!-- Modal content--> 
        <div class="modal-content"> 
           <div class="modal-body" style="padding:10px;"> 
             <h4 class="text-center">${message}</h4> 
             <div class="text-center"> 
               <a class="btn btn-danger btn-yes">yes</a> 
               <a class="btn btn-default btn-no">no</a> 
             </div> 
           </div> 
       </div> 
    </div> 
  </div>`).appendTo('body');

  //Trigger the modal
  $('#myModal').modal({
    backdrop: 'static',
    keyboard: false
  });

  //Pass true to a callback function
  $('.btn-yes').click(function() {
    handler(true);
    $('#myModal').modal('hide');
  });

  //Pass false to callback function
  $('.btn-no').click(function() {
    handler(false);
    $('#myModal').modal('hide');
  });

  //Remove the modal once it is closed.
  $('#myModal').on('hidden.bs.modal', function() {
    $('#myModal').remove();
  });
}

confirmDialog('Is this working?', ans => {
  if (ans) {
    console.log('yes');
  } else {
    console.log('no');
  }
});
