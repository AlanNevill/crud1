// bookingMaint.js

var objDateSat, cottageNum, trIdNum, rentalVal;

var booCottageNum = false;
var booDateSat = false;

// Create a new clsDeviceIdCookie class which set up the deviveId cookie 
const _clsDeviceIdCookie = new clsDeviceIdCookie();

// load bookingMaint from session storage
const _clsbookingMaint = new clsbookingMaint();

$(document).ready(function() {
	// hide the booking insert form until a dateSat & cottageNum have been input
	$(".insertForm").hide();

	// autoNumeric for rental field in New Booking Form
	// rentalVal = new AutoNumeric('#Rental > input', AutoNumeric.getPredefinedOptions().British);
	rentalVal = new AutoNumeric(
		"#Rental",
		AutoNumeric.getPredefinedOptions().numericPos
	);
	rentalVal.options.maximumValue("9999.99");
	rentalVal.options.currencySymbol("£");
	rentalVal.options.digitGroupSeparator(",");

	// set up functions which add and remove class 'loading' when ajax starts or stops. See crud1.css
	let body = $("body");

	$(document).on({
		ajaxStart: function() {
			body.addClass("loading");
		},
		ajaxStop: function() {
			body.removeClass("loading");
		}
	});

	// show current global version on title click or double click
	$("#title").on("dblclick click", function() {
		$(this)
			.next()
			.html(`${_VERSION}-${_clsDeviceIdCookie.deviceId}`)
			.toggle();
	});

	// update the number of remaining chars in the Notes field of the new booking form
	$("textarea").keyup(function() {
		if (this.value.length > $(this).attr("maxlength")) {
			return false;
		}
		$(this)
			.next()
			.html(
				"Remaining characters : " +
					($(this).attr("maxlength") - this.value.length)
			);
	});

	// get the selected dateSat when selection changes
	$("#wcDateSat").change(function() {
		if ($("#wcDateSat").val() === "-1") {
			$("#output1").html("Selected date is not valid");
			objDateSat = null;
			booDateSat = false;
			showFrmNewBooking();
		} else {
			// a valid date has been selected so save in global variable
			objDateSat = moment($("#wcDateSat").val(), "YYYY-MM-DD");
			booDateSat = true;
			showFrmNewBooking();
		}
	});

	// get the selected cottageNum when selection changes
	$("#cottageNum").change(function() {
		// debugger;

		if (cottageNum === "-1") {
			$("#output1").html("Invalid cottage number: " + cottageNum);
			booCottageNum = false;
			showFrmNewBooking();
		} else {
			// cottageNum is a valid selection
			cottageNum = $("#cottageNum").val();
			booCottageNum = true;
			showFrmNewBooking();
		}
	});

	function showFrmNewBooking() {
		// clear any new booking messages when dateSat or cottageNum change
		$("#newBookingInfo").empty();
		$("#output1").empty();

		if (booDateSat && booCottageNum) {
			// put the date into the frmNewBooking in case the user does an insert
			$("#frmNewBooking input[name=dateSat]").val(
				moment(objDateSat, "YYYY-MM-DD").format("YYYY-MM-DD")
			);

			//  set up the firstNight & lastNight date selection drop down options
			$("firstNight").empty();
			$("lastNight").empty();

			// debugger;

			for (let i = 0; i < 7; i++) {
				document.frmNewBooking.firstNight.options[i] = new Option(
					moment(objDateSat, "YYYY-MM-DD")
						.add(i, "days")
						.format("ddd DD MMM"),
					moment(objDateSat, "YYYY-MM-DD")
						.add(i, "days")
						.format("YYYY-MM-DD"),
					false,
					false
				);
				document.frmNewBooking.lastNight.options[i] = new Option(
					moment(objDateSat, "YYYY-MM-DD")
						.add(i, "days")
						.format("ddd DD MMM"),
					moment(objDateSat, "YYYY-MM-DD")
						.add(i, "days")
						.format("YYYY-MM-DD"),
					false,
					false
				);
			}

			// set defaults: lastNight to next Friday, 7 nights,
			$("#lastNight option:eq(6)").prop("selected", true);
			$("#frmNewBooking #nights").text("7 nights");

			// put the cottageNum into the frmNewBooking in case the user does an insert
			$("#frmNewBooking input[name=cottageNum]").val(cottageNum);

			// show the new CottageWeek_data section
			$(".CottageWeek_data").show();

			// refresh the existing bookings list for this date and cottage
			$("#submitButton").click();

			// show the new booking form but with add button disabled
      $(".insertForm").show();
      
		} else {
			$(".insertForm").hide();
		}
	}

	// called by function showFrmNewBooking()
	// debug: user clicks submit button on get form
	$("#submitButton").click(function(e) {
		e.preventDefault();

		// date or cottage number has changed so empty the bookings table and clear any output1 messages
		$("#tbodyBookings").empty();
		$("#output1").empty();

		// if dateSat and cottage number are both true get
		// 1. CottageWeek data to display as a single info line
		// 2. any existing bookings from CottageBook table
		if (booDateSat && booCottageNum) {
			// 1. get the CottageWeek row
			// var dateSat = moment(objDateSat).format('YYYY-MM-DD');
			$.post("include/bookingMaint_ajax.php", {
				method: "cottageWeek_get",
				dateSat: objDateSat._i,
				cottageNum: cottageNum
			}).done(function(returnArray) {

        if (isJSON(returnArray)) {
          
          returned = JSON.parse(returnArray);
          if (returned.success) {
            // put the CottageWeekRow data into the dom section CottageWeek_data
            cottageWeekRow = returned.cottageWeekRow;

            $("#DayRent").html("Rent / day £" + cottageWeekRow.RentDay);
            if (!cottageWeekRow.bShortBreaksAllowed) {
              $("#DayRent").css("text-decoration","line-through");
            }
            $("#WeekRent").html("Rent / week £" + cottageWeekRow.RentWeek);

            let allowed = cottageWeekRow.bShortBreaksAllowed ? "" : "*not* ";
            let shortBreak = `Short breaks are ${allowed}allowed this week`;
            $("#bShortBreaksAllowed").html(shortBreak);

            // put the weekly rent into the new booking form
            $("#frmNewBooking #Rental").val(cottageWeekRow.RentWeek);
            rentalVal.set(cottageWeekRow.RentWeek);

          } else {
            $("#output1")
              .empty()
              .append(returned.cottageWeekRow);
          }
        }
        else {$("#output1").append('Error occurred - See ErrorLog');}
			});

			// 2. get any existing bookings from CottageBook
			$.post("include/bookingMaint_ajax.php", $("#formGet").serialize())
				.done(function(response) {
          
          if (isJSON(response)) {
              
            let returnArray = JSON.parse(response);

            if (returnArray.success && returnArray.cottageBookCount > 0) {
              let tblBookings = CottageBook2Table(returnArray.cottageBookRows);
              $("#tbodyBookings").append(tblBookings); // populate the existing bookings table
            }

            $("#output1").append(returnArray.errorMess); // always display the returned message
          }
          else { $("#output1").append('Error occurred - See ErrorLog'); }
				})
				.always(function() {
					// if trIdNum points to a newly inserted booking row then set its background color
					$(trIdNum).css("background-color", "#a8cb17");
					tridNum = null;

				})
				.fail(error =>
					$("#output1")
						.empty()
						.append(error.statusText)
				); // end of $.post
		} else {
			// don't POST as one or both of the dateSat and cottageNum have not been selected
			$("#output1")
				.empty()
				.append("<h3>Please select both a date and cottage number</h3>");
		}
    }); // end of #submitButton.click function


	// firstNight or lastNight selection changes
	$("#firstNight, #lastNight").on("change", function() {
		// clear any messages and disable the submit button
		$("#output1").empty();
		$("#submitNewBooking").prop("disabled", true);

		// sanity check proposed booking dates in UI & update the number of nights field on the form
		if ($("#lastNight").val() < $("#firstNight").val()) {
			$("#output1").append(
				"<h5>The last night cannot be before the arrival date</h5>"
			);
			return;
		}

		// if the date check succeed then enable the new booking button
		$("#submitNewBooking").prop("disabled", false);

		// Update number of nights field in the new booking form
		let c = dateFns.differenceInDays($("#lastNight").val(), $("#firstNight").val()) + 1;
		$("#frmNewBooking #nights").text(c + (c > 1 ? " nights" : " night"));

		// update the rental field
		if (c === 7) {
			// $('#frmNewBooking #Rental').val(cottageWeek.cottageWeekRow[0].RentWeek);
			rentalVal.set(cottageWeekRow.RentWeek);
		} else {
			// $('#frmNewBooking #Rental').val(cottageWeek.cottageWeekRow[0].RentDay * c);
			rentalVal.set(cottageWeekRow.RentDay * c);
		}
	});

	// user clicks submit button on frmNewBooking form
	// TODO Move checking and insert into PHP as one post with validation on Rental field max and min values
	$("#submitNewBooking").click(function(e) {
		e.preventDefault();

		// disable the submit button
		$("#submitNewBooking").attr("disabled", true);

		$("#outpu1").empty();

		// unformat the rental field before posting to DB
		$("#Rental").val(rentalVal.getNumericString());

		// ajax post to method=insert
		$.post("include/bookingMaint_ajax.php", $("#frmNewBooking").serialize())
			.done(function(data) {
				// check for date clashes with existing bookings
				// debugger;
				const returnArray = JSON.parse(data);

				// process any errors returned
				if (!returnArray.success) {
					switch (returnArray.messSeverity) {
						// 2 types of warning
						case "W":
							if (returnArray.clashCount > 0) {
								$("#output1")
									.empty()
									.append(
										"<h5>The proposed booking clashes with " +
											returnArray.clashCount +
											" existing booking" +
											(returnArray.clashCount > 1 ? "s" : "") +
											"</h5>"
									);
							}
							// must be a warning about the rental value greater then 9,999.99
							else {
								$("#output1")
									.empty()
									.append("<h5>The rental is greater the £9,999.99</h5>");
							}
							break;
						// any error
						case "E":
							// TODO insert line breaks
							$("#output1")
								.empty()
								.append(
									"<h5>Error has occurred - contact support" +
										returnArray.errorMess
								);
							break;
					} // end of switch
				}
				// method=insert returned success, so process the new booking
				else {
					// debugger;
					// set trIdNum to the IdNum of the newly inserted row so that when the table is refreshed the row can be highlighted
					trIdNum = "#" + returnArray.insertedIdNum;

					// reinstate the Rental field formatting by triggering the focus event
					rentalVal.set($("#Rental").val());

					// requery the database in case someone else has removed or inserted and get the rows in the correct date order
					// TODO temp disable
					// $('#submitButton').click();

					// Remove class=highlight from any previously inserted row in the table
					$("#tbodyBookings > tr").removeClass("highlight");

					// get the new booking form data into an array
					let frmNewBookingSer = $("#frmNewBooking").serializeArray();

          // define template substitution variables
          let ArriveDate,IdNum,FirstNight,LastNight,numNights,Rental,BookingRef,Notes,provisional,confirmed;

          ArriveDate  = moment(frmNewBookingSer[3].value).format("YYYY-MM-DD");
          FirstNight  = moment(frmNewBookingSer[3].value).format("ddd DD MMM");
          LastNight   = moment(frmNewBookingSer[4].value).format("ddd DD MMM");
          numNights   = dateFns.differenceInDays(LastNight,FirstNight) + 1;
          Rental      = rentalVal.getFormatted();
          BookingRef  = returnArray.bookingRef;
          Notes       = frmNewBookingSer[7].value;
          IdNum       = returnArray.insertedIdNum;
          provisional = frmNewBookingSer[6].value === 'P' ? 'selected' : '';
          confirmed   = frmNewBookingSer[6].value === 'C' ? 'selected' : '';

          let newBookingTemplate = `
          <tr class='highlight' firstNight='${ArriveDate}' IdNum=${IdNum}>
          <td>${FirstNight}</td>
          <td>${LastNight}</td>
          <td>${numNights}</td>
          <td>${Rental}</td>
          <td>${BookingRef}</td>
          <td>${Notes}</td>
          <td><select class='custom-select bookingStatus' 
            data-toggle='tooltip' data-placement='auto' 
            title='(C)onfirmed or (P)rovision booking'>
              <option value='P' ${provisional}>P</option>
              <option value='C' ${confirmed}>C</option>
          </select></td>
          <td><button type='button' class='btn remove' ></button></td>
          </tr>
          `;
      
					// add the row to the existing bookings table rows
					$(newBookingTemplate).appendTo($("#tblBookings"));

					// sort the bookings by the tr attribute firstNight so that the inserted row is in the correct position
					let tbody = $("#tbodyBookings");

					let rows = $("#tbodyBookings").find("tr").get();
					rows.sort(function(a, b) {
						let keyA = $(a).attr("firstNight");
						let keyB = $(b).attr("firstNight");
						if (keyA < keyB) return -1;
						if (keyA > keyB) return 1;
						return 0;
					});
					$.each(rows, function(index, row) {
						tbody.append(row);
					});

					// TODO clear down the new booking form
					// showFrmNewBooking();

					// display message for 5 seconds
					$("#newBookingInfo")
						.empty()
						.append("Booking made")
						.fadeOut(5000);
				}
      })
      
			// always re-enable the submit new booking button
			.always(() => $("#submitNewBooking").removeAttr("disabled"))

			.fail(error =>
				$("#newBookingInfo")
					.empty()
					.append(error.statusText)
      ); // end of $.post method=insert
      
	}); // end of #submitNewBooking.click

	// Remove a booking by defining a delegate on the table body which fires on click of any remove button
	$("#tbodyBookings").delegate(".remove", "click", function() {
		
    $("btn .remove").attr("disabled", true); // disable any remaining row remove buttons
    
    $('btn .remove').tooltip('disable'); // disable the delete tooltip during delete

		// find the table row of the deleted booking and remove with fade out
		let removeTR = $(this).closest("tr");

		// post the delete to the PHP URL with the IdNum extracted from the tr
		$.post("include/bookingMaint_ajax.php", {
			method: "CottageBook_delete",
			IdNum: removeTR.attr("IdNum")
    })
    .done(function(response) {
      let returnArray = JSON.parse(response);

			// don't refresh the bookings from the DB so just remove the row from the table
			if (returnArray.success) {
        // remove the row from the table
				removeTR.fadeOut(500, function() { $(this).remove(); } );

        // display a confirm message for 5 seconds
        $("#newBookingInfo")
          .empty()
          .append(returnArray.errorMess)
          .fadeOut(5000);
        }
        else {
          // remove any previous message & display the error message
          $("#output1").empty().append(returnArray.errorMess);
          alert("an error has occured\n\n" + returnArray.errorMess);
        }
      }) // end of .done
    .always(() => 
        // re-enable the row remove button(s)
        $("btn .remove").removeAttr("disabled")

    ); // end of $.post method: "CottageBook_delete"

    $('btn .remove').tooltip('enable'); // re-enable the delete tooltip

  }); // end of remove button click event
    
  
  // define update bookingStatus delegate on the existing booking table body which fires on click of any select change event where class bookingStatus
  $("#tbodyBookings").delegate(".bookingStatus", "change", function() {

    // clear any previous messages
    $("#output1").empty();

    // disable any remaining row update class=bookingStatus selects
		$("select .bookingStatus").attr("disabled", true);

		// find the table row of the bookingStatus update
		let updateTR = $(this).closest("tr");

		// post the update to the PHP URL with the IdNum extracted from the tr
		$.post("include/bookingMaint_ajax.php", {
			method: "cottageBook_updStatus",
      IdNum: updateTR.attr("IdNum"),
      BookingStatus : this.value
    })
    .done(function(response) {

      let returnArray = JSON.parse(response);

			// if success
			if (returnArray.success) {

        $("#newBookingInfo")
          .empty()
          .append("The booking status was changed")
          .fadeOut(5000);
      }
      else { // returnArray.success == false
        // remove any previous messages & output the error message
        $("#output1").empty().append(returnArray.errorMess);
        alert("An error has occured\n\n" + returnArray.errorMess);
      }
    })
    .always(() => 
      // re-enable the rows class=bookingStatus select(s)
      $("select .bookingStatus").removeAttr("disabled")

    ); // end of $.post method: "CottageBook_delete"

  }); // end of remove button click event
    

	// check for values in session storage; if found then display the DateSat and CottageNum
	if (
		typeof _clsbookingMaint.cottageNum  !== "undefined" &&
		typeof _clsbookingMaint.dateSat     !== "undefined"
  )
  {
		cottageNum = _clsbookingMaint.cottageNum;
		booCottageNum = true;
		$("#cottageNum [value=" + cottageNum + "]").attr("selected", "selected");

		objDateSat = moment(_clsbookingMaint.dateSat, "YYYY-MM-DD");
		$("#wcDateSat [value=" + _clsbookingMaint.dateSat + "]").attr(
			"selected",
			"selected"
		);
		booDateSat = true;

		// get the existing bookings if any and show the new booking form
    showFrmNewBooking();
    
    // initialise the tooltips
    $('[data-toggle="tooltip"]').tooltip();
  }
  
}); // end of (document).ready


// TODO provide lookup function by booking ref
// generate a Char(4) booking reference
function bookingRef() {
	return Math.floor((1 + Math.random()) * 0x10000)
		.toString(16)
		.substring(1)
		.toUpperCase();
}


// prevent the enter key submitting the new booking form
$("#frmNewBooking").on("keyup keypress", function(e) {
	const keyCode = e.keyCode || e.which;
	if (keyCode === 13) {
		e.preventDefault();
		return false;
	}
});


function CottageBook2Table(cottageBookRows)
{
  var ArriveDate,IdNum,FirstNight,LastNight,numNights,Rental,BookingRef,Notes,provisional,confirmed;
  var existingBookingRows = '';

  cottageBookRows.forEach(row => {
    // format the substitution variables
    ArriveDate  = row.FirstNight;
    IdNum       = row.IdNum;
    FirstNight  = dateFns.format(new Date(row.FirstNight),'ddd D MMM');
    LastNight   = dateFns.format(new Date(row.LastNight) ,'ddd D MMM');
    numNights   = row.numNights;
    Rental      = '£' + row.Rental.toLocaleString();
    BookingRef  = row.BookingRef;
    Notes       = row.Notes;
    provisional = row.BookingStatus==='P' ? 'selected' : '';
    confirmed   = row.BookingStatus==='C' ? 'selected' : '';

    let bookingTemplate = `
      <tr firstNight='${ArriveDate}' IdNum=${IdNum}>
      <td>${FirstNight}</td>
      <td>${LastNight}</td>
      <td>${numNights}</td>
      <td>${Rental}</td>
      <td>${BookingRef}</td>
      <td>${Notes}</td>
      <td><select class='custom-select bookingStatus' 
        >
          <option value='P' ${provisional}>P</option>
          <option value='C' ${confirmed}>C</option>
      </select></td>
      <td><button type='button' class='btn remove'></button></td>
      </tr>
    `;

    existingBookingRows += bookingTemplate;  
  });

  return existingBookingRows;
}
