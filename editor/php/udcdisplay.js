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
    var scrollvalue = document.getElementById('scrollvalue').value;
    if (lang != null)
    {
        langid = (lang.selectedIndex+1);
        //alert(lang.selectedIndex);
    }
    else
    {
        //alert("No language defined");
    }
    
    if (langid==3)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Cargando...</div>';    
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

		var passstring = "getrecord.php?id=" + recordid + "&lang=" + langid + "&scrollvalue=" + scrollvalue;;
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

// function saves scroll position
function fScroll(val)
{
    var hidScroll = document.getElementById('scrollvalue');
    hidScroll.value = val.scrollTop;
    //alert("Scroll now " + hidScroll.value);
}

var httpObject = null;
