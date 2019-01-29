// testHarness2.js
'use strict';

// var clsbookingMaint = new clsbookingMaint();

$(document).ready(function () {

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
  const _clsDeviceIdCookie = new clsDeviceIdCookie();

  // // Get the client's fingerprint id
  // var fingerprint = client.getFingerprint();

  // let userAgentString = client.getUserAgent();

  // let output1Str = `Fingerprint: ${fingerprint}`;

  // $('#info').empty().append(output1Str);

  // $('#output1').empty().append(`userAgentStr: ${userAgentString}`);

  // $.post(
  //   "include/testHarness2_ajax.php", 
  //   {'method':'DeviceId_insert', 'deviceId':fingerprint, 'userAgentString':userAgentString},
  //   function (data, textStatus, jqXHR) {
  //     if (isJSON(data)) {
  //       let funcReturn = JSON.parse(data);
  //       if (funcReturn.success === true) {
  //         console.log(`success=true, message: ${funcReturn.message}`);
  //       }
  //       else {console.log(`success=false, message: ${funcReturn.message}`);}
  //     }
  //   },
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