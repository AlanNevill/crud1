// datefns-test.js
'use strict';

var datefns = require('date-fns');
var moment = require('moment');

let today = moment();

console.log('today a: ' + today);

let a = moment(today, 'YYYY-MM-DD').add(2, 'days').format('ddd DD MMM');

console.log('moment a: ' + a);

let c = new Date("2020-07-30");
console.log('datefns c: ' + c);

let b = datefns.format(datefns.addDays(c, 2), 'iii dd LLL');


console.log('datefns b: ' + b);

