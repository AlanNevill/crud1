// datefns-test.js

// to run use: node --icu-data-dir=C:\Users\User\AppData\Roaming\npm\node_modules\full-icu datefns-test.js

'use strict';

var datefns = require('date-fns');
var moment = require('moment');

let today = moment();

console.log('moment today a: ' + today);

let a = moment(today, 'YYYY-MM-DD').add(2, 'days').format('ddd DD MMM');

console.log('moment a: ' + a);

let c = new Date("2020-07-30");
console.log(`new Date c: ${c}`);

let b = datefns.format(datefns.addDays(c, 2), 'iii dd LLL');


console.log('datefns b: ' + b);

const dateOptions = {weekday: 'short', day: '2-digit', month: 'short'};
console.log(`new Date.toLocaleDateString: ${new Date().toLocaleDateString("en-gb", dateOptions)}`);
console.log(`c.toLocaleDateString: ${c.toLocaleDateString("en-gb")}`);
