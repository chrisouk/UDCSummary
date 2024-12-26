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

function openrecord(recordid, navpos, clearpath)
{
    document.body.style.cursor = 'wait';
    var lang = document.getElementById('selectedlang');
    var langid = document.getElementById('if_lang').value;
    var loading = document.getElementById('if_loading').value;
    document.getElementById('recordbox').innerHTML = '<div class=\"wait\">' + loading + '...</div>';
    httpObject = getHTTPObject();
    if (httpObject != null)
    {
        recordid = recordid.replace("\"", "%22");
        recordid = recordid.replace("+", "%2B");
        var passstring = "getrecord.php?id=" + recordid + "&lang=" + langid;
        if (navpos != -1)
        {
            passstring = passstring + "&navpos=" + navpos;
        }
        else if (clearpath == true)
        {
            passstring = passstring + "&clearpath=true";
        }
        httpObject.onreadystatechange = setRecord;
        httpObject.open("GET", passstring, true);
        httpObject.send(null)
    }
    else
    {
        alert("Failed to get classmark record")
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