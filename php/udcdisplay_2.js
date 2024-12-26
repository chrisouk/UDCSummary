/**
*
*  UTF-8 data encode / decode
*  http://www.webtoolkit.info/
*
**/
 
var Utf8 = {
 
	// public method for url encoding
	encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) {
 
			var c = string.charCodeAt(n);
 
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
 
		return utftext;
	},
 
	// public method for url decoding
	decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) {
 
			c = utftext.charCodeAt(i);
 
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
 
		}
 
		return string;
	}
 
}

function getHTTPObject()
{
	if (window.ActiveXObject) 
	{
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	else if (window.XMLHttpRequest) 
	{
		return new XMLHttpRequest();
	}
	else 
	{
		alert("Your browser does not support AJAX.");
		return null;
	}
}

function openrecord(recordid)
{
    document.body.style.cursor='wait';

    var lang = document.getElementById('selectedlang');
    var langid = 1;
    if (lang != null)
    {
    	var langname = lang.options[lang.selectedIndex].text;
   		if (langname == 'English [en]')
			langid = 1;
		else if(langname == 'Dutch [nl]')
			langid = 2; 
		else if(langname == 'Spanish [es]')
			langid = 3;
		else if(langname == 'French [fr]')
			langid = 4;
		else if(langname == 'Swedish [sv]')
			langid = 5;
		else if(langname == 'German [de]')
			langid = 6;
		else if(langname == 'Croatian [hr]')
			langid = 7;
		else if(langname == 'Russian [ru]')
			langid = 8;
		else if(langname == 'Slovenian [sl]')
			langid = 9;
		else if(langname == 'Finnish [fi]')
			langid = 10;
		else if(langname == 'Italian [it]')
			langid = 11;
		else if(langname == 'Georgian [ka]')
			langid = 12;
		else if(langname == 'Polish [pl]')
			langid = 13;
		else if(langname == 'Romanian [ro]')
			langid = 14;
		else if(langname == 'Czech [cs]')
			langid = 15;
		else if(langname == 'Hungarian [hu]')
			langid = 16;
			
        //langid = (lang.selectedIndex+1);
        //alert(langname + " " + langid);
    }
    else
    {
        //alert("No language defined");
    }
    
    if (langid==2)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Laden...</div>';    
    }
    else if (langid==3)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Cargando...</div>';    
    }
    else if (langid==4)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Chargement...</div>';    
    }
    else if (langid==5)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Laddar...</div>';    
    }
    else if (langid==6)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Laden...</div>';    
    }
    else if (langid==7)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">U&#269;itava se...</div>';    
    }
    else if (langid==8)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">&#1047;&#1072;&#1075;&#1088;&#1091;&#1078;&#1072;&#1077;&#1090;&#1089;&#1103;...</div>';    
    }
    else if (langid==12)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">&#4329;&#4304;&#4322;&#4309;&#4312;&#4320;&#4311;&#4309;&#4304;...</div>';
    }
    else if (langid==13)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">&#322;adowanie...</div>';
    }
    else if (langid==14)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">se &#238;ncarc&#259;...</div>';
    }
    else if (langid==15)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Na&#269;&#237;t&#225;n&#237; dat...</div>';
    }
    else if (langid==16)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Bet&#246;lt&#233;sse...</div>';
    }

    else 
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Loading...</div>';
    }
    
    httpObject = getHTTPObject();
	if (httpObject != null) 
	{
	    /*
        recordid = recordid.replace("«","%AB");
        recordid = recordid.replace("»", "%BB");
        */
        //recordid = escape(recordid);

        recordid = recordid.replace("\"", "%22");
        recordid = recordid.replace("+", "%2B");

		var passstring = "getrecord.php?id=" + recordid + "&lang=" + langid;
        //alert(passstring);
		httpObject.onreadystatechange = setRecord;		
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
	}
	else
	{
		alert("Failed to get classmark record");
	}    
}

function setRecord()
{
	setReplyText('recordbox');
    document.body.style.cursor='auto';
}

function setReplyText(elementID)
{
	if (httpObject.readyState == 4)
	{	
		var responseText = httpObject.responseText;
		document.getElementById(elementID).innerHTML = responseText;
	}
}

var httpObject = null;
