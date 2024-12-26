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
//	alert("GetRecord: " + recordid);
	
    document.body.style.cursor='wait';

    var lang = document.getElementById('selectedlang');
    var langid = 1;
    if (lang != null)
    {
    	var langname = lang.options[lang.selectedIndex].text;
   		if (langname.indexOf('English') >= 0)
			langid = 1;
		else if(langname.indexOf('Dutch') >= 0)
			langid = 2; 
		else if(langname.indexOf('Spanish') >= 0)
			langid = 3;
		else if(langname.indexOf('French') >= 0)
			langid = 4;
		else if(langname.indexOf('Swedish') >= 0)
			langid = 5;
		else if(langname.indexOf('German') >= 0)
			langid = 6;
		else if(langname.indexOf('Croatian') >= 0)
			langid = 7;
		else if(langname.indexOf('Russian') >= 0)
			langid = 8;
		else if(langname.indexOf('Slovenian') >= 0)
			langid = 9;
		else if(langname.indexOf('Finnish') >= 0)
			langid = 10;
		else if(langname.indexOf('Italian') >= 0)
			langid = 11;
		else if(langname.indexOf('Georgian') >= 0)
			langid = 12;
		else if(langname.indexOf('Polish') >= 0)
			langid = 13;
		else if(langname.indexOf('Romanian') >= 0)
			langid = 14;
		else if(langname.indexOf('Czech') >= 0)
			langid = 15;
		else if(langname.indexOf('Hungarian') >= 0)
			langid = 16;
		else if(langname.indexOf('Ukrainian') >= 0)
			langid = 17;
		else if(langname.indexOf('Hindi') >= 0)
			langid = 18;
		else if(langname.indexOf('Norwegian') >= 0)
			langid = 19;
		else if(langname.indexOf('Estonian') >= 0)
			langid = 20;
		else if(langname.indexOf('Armenian') >= 0)
			langid = 21;
		else if(langname.indexOf('Serbian') >= 0)
			langid = 22;
		else if(langname.indexOf('Portuguese') >= 0)
			langid = 23;
		else if(langname.indexOf('Catalan') >= 0)
			langid = 24;
		else if(langname.indexOf('Greek') >= 0)
			langid = 25;			
		else if(langname.indexOf('Turkish') >= 0)
			langid = 26;			
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
    else if (langid==10)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Ladataan...</div>';
    }
    else if (langid==11)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Caricamento...</div>';
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
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Bet&#246;lt&#233;s...</div>';
    }
    else if (langid==17)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">&#1047;&#1072;&#1074;&#1072;&#1085;&#1090;&#1072;&#1078;&#1077;&#1085;&#1085;&#1103;...</div>';
    }
    else if (langid==20)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Laadimine...</div>';
    }
    else if (langid==22)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">&#1059;&#1095;&#1080;&#1090;&#1072;&#1074;&#1072; &#1089;&#1077;...</div>';
    }
    else if (langid==23)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Carregando...</div>';    
    }
    else if (langid==24)
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Carregant...</div>';    
    }
    else 
    {
        document.getElementById('recordbox').innerHTML = '<div class=\"wait\">Loading...</div>';
    }
    
    httpObject = getHTTPObject();
	if (httpObject != null) 
	{
	    /*
        recordid = recordid.replace("�","%AB");
        recordid = recordid.replace("�", "%BB");
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
