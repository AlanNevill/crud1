// classes.js

/*global _VERSION */
const _VERSION = "v0.6";


class clsbookingMaint {
  constructor() {
    
    if (sessionStorage.getItem("bookingMaint")) {
      // Restore the contents of the text field
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
      this._cottageNum = Math.abs(cottageNum);
      this.save();
    } else {
      alert("cottageNum is not a valid integer: " + cottageNum);
    }
  }

  // update session storage key bookingMaint
  save() {
    let bookingMaint = { dateSat: this._dateSat, cottageNum: this._cottageNum, };

    sessionStorage.setItem("bookingMaint", JSON.stringify(bookingMaint));
  }

  // remove bookingMaint session storage
  remove() {
    sessionStorage.removeItem('bookingMaint');
  }
}


function isJSON(myTestStr){
  try {
    let myJSON = JSON.stringify(myTestStr);
    let json = JSON.parse(myJSON);
    if (typeof myTestStr == 'string') 
      if(myTestStr.length === 0)
        return false;
    
  } catch (e) {
      return false;
  }
  return true;
}
