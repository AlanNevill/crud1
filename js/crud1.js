
// when document ready
$(function() {
  
  // get the page title and use it to select the menu <a> element with the same id then add the active class and remove the page link and repalce with #
  let title = $("#" + $(this).attr('title'));
  $(title).addClass("active");
  $(title).attr("href", "#");

  $('[data-toggle="tooltip"]').tooltip();

});


