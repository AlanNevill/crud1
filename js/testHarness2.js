// testHarness2.js

var clsbookingMaint = new clsbookingMaint();


$(document).ready(function () {

    // let dateSatCookie = readCookie('dateSatCookie');

    // if (dateSatCookie == null) {
    //     $('info').empty().append('dateSatCookie not found');
        
    // }
    // else $('info').empty().append(dateSatCookie);

    if (clsbookingMaint.cottageNum) {
        $('#output1').empty().append('Read cottageNum: ' + clsbookingMaint.cottageNum);

    }

    // if (sessionStorage.getItem("bookingMaint")) {
    //     // Restore the contents of bookingMaint from session storage
    //     const bookingMaint = JSON.parse(sessionStorage.getItem("bookingMaint"));
    //     $('#output1').empty().append(bookingMaint.cottageNum);
    // }


});