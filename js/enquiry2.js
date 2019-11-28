// enquiry.js
'use strict';

// onSubmit function for the form defined as onsubmit="postEnquiry(event)"
function postEnquiry(event) {
  $('#output1').empty();

  event.preventDefault();
  event.stopPropagation();

  // call validate on the form
  let enquiryForm = document.getElementById('enquiryForm');
  let formIsValid = enquiryForm.checkValidity();
  enquiryForm.classList.add('was-validated');

  /// form is invalid
  if (!formIsValid) {
    // inject an alert to message div into the form warning the user that some fields are invalid
    $('#output1').html(
      `
      <div class="alert alert-warning alert-dismissable fade show" role="alert">
        <div class="row">
          <div class="col-xs-1">
            <i class="fa fa-lg fa-exclamation-triangle" aria-hidden="true"></i>
          </div>
          <div class="col-xs-10">&nbsp 
            Please amend the fields outlined in red and try again.
          </div>
          <div class="col-xs-1 ml-auto">
            <button type="button" class="btn close" data-dismiss="alert" aria-hidden="true">&times;</button>
          </div>
        </div>
      </div>
      `
    );

    // end processing
    return;
  }

  /// form is valid
  // clear any is-invalid classes and mark all fields as valid before posting to server
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

  /// submit to common_ajax.php/method: "EnquiryResponseEmail"
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
      if (!isJSON(data)) {
        // invalid JSON returned; report error & return
        $('#output1')
          .empty()
          .append('ERROR - common_ajax.php/EnquiryResponseEmail: did not return JSON data');
        return;
      }

      // reset the captcha so that user can resubmit if there was a validation error
      grecaptcha.reset();

      let funcReturn = JSON.parse(data);

      // the enquiry was successfully emailed
      if (funcReturn.success === true) {
        // inject an alert into output1 div in the form confirmin successful email
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
        // end processing
        return;
      }

      /// server validation failed
      $(enquiryForm).removeClass('was-validated');

      // iterate the fields adding class 'is-invalid' on invalid fields determined by the server
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

      // console.log(`success=false, message: ${funcReturn.message}`);

      // inject an alert to output1 div in the form with the validation message returned from the server
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
    } // end of $.post function
  ); // end of $.post
} /// end of form on submit


/// document ready
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
