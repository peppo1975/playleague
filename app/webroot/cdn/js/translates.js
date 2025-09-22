var tmp_lang = getCookie('lang');

if(tmp_lang == undefined) {
	var userLang = navigator.language || navigator.userLanguage; 
	var lang     = userLang.substr(0,2);
	setCookie('lang', lang, 30);
} else {
	lang = tmp_lang;
}

var serviceURL    = "http://www.visitmatera.com/"+lang+"/mobile/";
var filesUrl   	  = "http://www.visitmatera.com";
var offset_height = 0;

/** Cookie functions */
function setCookie(c_name,value,exdays)
{
  var exdate=new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var c_value=escape(value) + 
	((exdays==null) ? "" : ("; expires="+exdate.toUTCString()));
  document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name)
{
 var i,x,y,ARRcookies=document.cookie.split(";");
 for (i=0;i<ARRcookies.length;i++)
 {
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
  {
   return unescape(y);
  }
 }
}

/** Translate static string */
var translates = new Array;
//IT
	translates['it.Eventi'] = 'Eventi';
	translates['it.Luoghi'] = 'Home';
	translates['it.Info']   = 'Info';
//EN
	translates['en.Eventi'] = 'Events';
	translates['en.Luoghi'] = 'Home';
	translates['en.Info']   = 'Info';
/*****/