// classes.js
'use strict';

/* global _VERSION */
const _VERSION = "v0.7";

// class to get the deviceId (fingerprint) & userAgentString and store in db and session cookie
class clsDeviceIdCookie {
  constructor() {
    // create an instance of the ClientJS class to get the fingerprint and userAgentString
    const _clsClientJS = new ClientJS();
    
    this._fingerprint     = _clsClientJS.getFingerprint();// Get the client's fingerprint
    this._userAgentString = _clsClientJS.getUserAgent();  // Get the client's userAgentString


    // call the cookie function to set the session cookie 'deviceId' which dbFuncs.php will read
    setCookie('deviceId', this._fingerprint, 0);


    // insert a row into DeviceId table if does not already exist
    $.post(
      "include/common_ajax.php", 
      {'method':'DeviceId_insert', 'deviceId': this._fingerprint, 'userAgentString': this._userAgentString},
      function (data, textStatus, jqXHR) {
        if (isJSON(data)) {
          let funcReturn = JSON.parse(data);
          if (funcReturn.success === true) {
            console.log(`success=true, message: ${funcReturn.message}`);
          }
          else {console.log(`success=false, message: ${funcReturn.message}`);}
        }
      },
    ); // end of $.post


    // write message into ProcessLog table
    // $.post(
    //   "include/common_ajax.php", 
    //   {'method':'ProcessLog_insert', 
    //    'MessType':'I', 
    //    'Application':'classes.clsDeviceIdCookie',
    //    'Routine':'classes>clsDeviceIdCookie>constructor',
    //    'ErrorMess':null,
    //    'Remarks':`DeviceId: ${this._fingerprint}, userAgentString: ${this._userAgentString}`
    //   },
    // ); // end of $.post
    
  } // end of constructor

  // getters
  get deviceId() {
    return this._fingerprint;
  }
  
  get userAgentString() {
    return this._userAgentString;
  }

}


// class to retrieve and store values in seesion storage variable named 'bookingMaint'
class clsbookingMaint {
  constructor() {
    
    if (sessionStorage.getItem("bookingMaint")) {
      // Restore the contents of the text field from session storage
      const _bookingMaint = JSON.parse(sessionStorage.getItem("bookingMaint"));
      this._dateSat = _bookingMaint.dateSat;
      this._cottageNum = _bookingMaint.cottageNum;
    }

  } // end of constructor

  get dateSat() {
    return this._dateSat;
  }

  get cottageNum() {
    return this._cottageNum;
  }

  set dateSat(dateSat) {
    if (Object.prototype.toString.call(new Date(dateSat)) === "[object Date]") {
      this._dateSat = dateSat;
      this.save();
    } else {
      alert("dateSat is not a valid date: " + dateSat);
    }
  }

  set cottageNum(cottageNum) {
    let cottageNumInt = parseInt(cottageNum);
    if (Number.isInteger(cottageNumInt)) {
      this._cottageNum = Math.abs(cottageNumInt);
      this.save();
    } else {
      alert("cottageNum is not a valid integer: " + cottageNum);
    }
  }

  // update session storage key bookingMaint
  save() {
    let bookingMaint = { dateSat: this._dateSat, cottageNum: this._cottageNum };
    sessionStorage.setItem("bookingMaint", JSON.stringify(bookingMaint));
  }

  // remove bookingMaint session storage
  remove() {
    sessionStorage.removeItem('bookingMaint');
  }
} // end of class clsbookingMaint


function isJSON(myTestStr){
  try {
    // let myJSON = JSON.stringify(myTestStr);
    let json = JSON.parse(myTestStr);       // error thrown if not json
    if (typeof myTestStr == 'string')       // check for 0 length string
      if(myTestStr.length === 0)
        return false;
    
  } catch (e) {
      return false;
  }
  return true;
}


// cookie functions
function setCookie(name,value,days) {
  let expires = "";
  if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days*24*60*60*1000));
      expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0)===' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

function eraseCookie(name) {   
  document.cookie = name+'=; Max-Age=-99999999;';  
}

function checkCookie() {
  var user = getCookie("username");
  if (user !== "" && user != null) {
    alert("Welcome again " + user);
  } else {
    user = prompt("Please enter your name:", "");
    if (user !== "" && user != null) {
      setCookie("username", user, 365);
    }
  }
}