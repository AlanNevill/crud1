// cookieFuncs.js

// creates or overwrites a cookie
function createCookie(name,value,days) {
    let expires = ""; // if days is zero the cookie expires at the end of the session
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expires = "; expires="+date.toGMTString();
	}
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	let nameEQ = name + "=";
	let ca = document.cookie.split(';'); 
	for(let i=0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0)===' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

// erase cookie by setting the expiry date in the past
function eraseCookie(name) {
	createCookie(name,"",-1);
}