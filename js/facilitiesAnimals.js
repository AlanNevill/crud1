// facilitiesAnimals.js
'use strict';

$(function() {
  // Lazy load images
  $(function() {
    $('.lazy').Lazy();
  });

  // Maisy age update
  let maisyAge = dateFns.distanceInWordsToNow(new Date(2008, 11, 1));
  // console.log(maisyAge);
  $('#maisyAge').html(maisyAge);
});
