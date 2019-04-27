// enquiry.js
'use strict';

// window.addEventListener(
//   "load",
//   function() {
//     // Fetch all the forms we want to apply custom Bootstrap validation styles to
//     var forms = document.getElementsByClassName("needs-validation");

//     // Loop over them and prevent submission
//     var validation = Array.prototype.filter.call(forms, function(form) {
//       form.addEventListener(
//         "submit",
//         function(event) {
//           if (form.checkValidity() === false) {
//             // form is invalid
//             event.preventDefault();
//             event.stopPropagation();
//           } // form is valid so submit to server for validation and processing is valid
//           else {
//             postEnquiry();
//           }

//           form.classList.add("was-validated");
//         },
//         false
//       );
//     });
//   },
//   false
// );

// onSumit function for the form post
function postEnquiry(event) {
  $('#output1').empty();

  event.preventDefault();
  event.stopPropagation();

  //  call validate on the form
  let enquiryForm = document.getElementById('enquiryForm');
  let formIsValid = enquiryForm.checkValidity();
  enquiryForm.classList.add('was-validated');

  // process depending on form validity
  if (formIsValid) {
    // clear any is-invalid classes and mark all fields as valid before server validation
    $('#first_name')
      .removeClass('is-invalid')
      .addClass('is-valid');
    $('#last_name')
      .removeClass('is-invalid')
      .addClass('is-valid');
    $('#email_to')
      .removeClass('is-invalid')
      .addClass('is-valid');
    $('#enquiry')
      .removeClass('is-invalid')
      .addClass('is-valid');

    // submit to common_ajax.php/method: "EnquiryResponseEmail"
    $.post(
      '../include/common_ajax.php',
      {
        method: 'EnquiryResponseEmail',
        first_name: $('#first_name').val(), // required
        last_name: $('#last_name').val(), // required
        email_to: $('#email_to').val(), // required
        telephone: $('#telephone').val(), // not required
        enquiry: $('#enquiry').val(), // required
        captcha: grecaptcha.getResponse()
      },
      function(data, textStatus, jqXHR) {
        // process the return data
        if (isJSON(data)) {
          let funcReturn = JSON.parse(data);

          // enquiry was successfully sent
          if (funcReturn.success === true) {
            // reset the captcha
            grecaptcha.reset();

            // inject an alert to message div into the form
            $('#output1').html(
              `
              <div class="alert alert-success alert-dismissable fade show" role="alert">
                <div class="row">
                  <div class="col-xs-1">
                    <i class="fa fa-lg fa-check" aria-hidden="true"></i>
                  </div>
                  <div class="col-xs-10">
                    &nbspThank you for your enquiry. A confirmation email has been sent to you.
                  </div>
                  <div class="col-xs-1 ml-auto">
                    <button type="button" class="btn close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  </div>
                </div>
              </div>
              `
            );

            // server validation failed
          } else {
            // iterate the fields setting class is-invalid on invalid fields
            if (!funcReturn.first_nameValid) {
              $('#first_name').addClass('is-invalid');
            }
            if (!funcReturn.last_nameValid) {
              $('#last_name').addClass('is-invalid');
            }
            if (!funcReturn.email_toValid) {
              $('#email_to').addClass('is-invalid');
            }
            if (!funcReturn.enquiryValid) {
              $('#enquiry').addClass('is-invalid');
            }

            $(enquiryForm).removeClass('was-validated');
            enquiryForm.checkValidity(); // TODO - check if this is needed

            console.log(`success=false, message: ${funcReturn.message}`);

            // inject an alert to message div into the form
            $('#output1').html(
              `
              <div class="alert alert-warning alert-dismissable fade show" role="alert">
                <div class="row">
                  <div class="col-xs-1">
                    <i class="fa fa-lg fa-exclamation-triangle" aria-hidden="true"></i>
                  </div>
                  <div class="col-xs-11 ml-auto">
                    <button type="button" class="btn close" data-dismiss="alert" aria-hidden="true">&times;
                    </button>
                  </div>
                  <div class="col-xs-12">
                    ${funcReturn.message}
                  </div>
                </div>
              </div>
              `
            );
          }
        } else {
          // invalid JSON returned
          $('#output1')
            .empty()
            .append('ERROR - common_ajax.php/EnquiryResponseEmail: did not return JSON data');
        }
      }
    ); // end of $.post
  } else {
    // form is invalid

    // inject an alert to message div into the form
    $('#output1').html(
      `
      <div class="alert alert-warning alert-dismissable fade show" role="alert">
        <div class="row">
          <div class="col-xs-1">
            <i class="fa fa-lg fa-exclamation-triangle" aria-hidden="true"></i>
          </div>
          <div class="col-xs-10">&nbsp 
            Please amend the fields outlined in red and re-send.
          </div>
          <div class="col-xs-1 ml-auto">
            <button type="button" class="btn close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        </div>
      </div>
      `
    );
  }
} // end of form on submit

// remove any 'is-invalid' class when user starts amending
$('input').keyup(function() {
  $(this).removeClass('is-invalid');
});

// document ready
$(document).ready(function() {
  // set up functions which add and remove class 'loading' when ajax starts or stops. See MGF.css
  let body = $('body');
  $(document).on({
    ajaxStart: function() {
      body.addClass('loading');
    },
    ajaxStop: function() {
      body.removeClass('loading');
    }
  });
}); // end of document ready
