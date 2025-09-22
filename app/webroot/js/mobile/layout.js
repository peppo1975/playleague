if (typeof console == "undefined" || typeof console.log == "undefined") var console = { log: function() {} }; 

function isValidEmail(str) {
	var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
	return emailPattern.test(str);
}