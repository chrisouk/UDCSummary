function getHTTPObject()
{
	//alert("getHTTPobject");
	if (window.ActiveXObject) 
	{
		//alert("MSXML");
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	else if (window.XMLHttpRequest) 
	{
		//alert("XMLHttpRequest");
		return new XMLHttpRequest();
	}
	else 
	{
		alert("Your browser does not support AJAX.");
		return null;
	}
}

function postVars()
{
	var url = "getdata.php";
	var params = "";
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		httpObject.open("POST", url, true);
		httpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpObject.setRequestHeader("Content-length", params.length);
		httpObject.setRequestHeader("Connection", "close");
		httpObject.send(params);
		httpObject.onreadystatechange = setVars;		
	}
	else
	{
		//alert("Failed to get broader category");
	}
}

function EditForm()
{
	//alert("EditForm");
	
	var transcaption = document.getElementById('transcaption');
	var transappnote =	document.getElementById('transappnote');
	var transscopenote = document.getElementById('transscopenote');
	var transverbalexamples = document.getElementById('transverbalexamples');
	var mycomments = document.getElementById('mycomments');
	var notation = document.getElementById('notation');
	
	//alert("vars done");
	
	document.getElementById('SubmitSave').style.display = "";
	document.getElementById('EditCancel').style.display = "";
	document.getElementById('notationedit').value = notation.innerHTML;
	document.getElementById('successbox').style.display = "none";
	document.getElementById('errorbox').style.display = "none";

	//alert("caption");
	transcaption.style.backgroundColor = "white";
	transcaption.removeAttribute('readOnly');
	//alert("appnote");
	transappnote.style.backgroundColor = "white";
	transappnote.removeAttribute('readOnly');
	//alert("scopenote");
	transscopenote.style.backgroundColor = "white";
	transscopenote.removeAttribute('readOnly');
	//alert("verbals");
	transverbalexamples.style.backgroundColor = "white";
	transverbalexamples.removeAttribute('readOnly');
	//alert("done");
	mycomments.style.backgroundColor = "white";
	mycomments.removeAttribute('readOnly');
	
	// Now make the edit fields visible
	//alert("links");
	
	var finished = false;
	var linkid = 1;
	
	while(!finished)
	{
		var elemname = "editlink_" + linkid;
		//alert(elemname);
		linkid++;
		if (document.getElementById(elemname) == null)
		{
			finished = true;
		} 
		else
		{
			document.getElementById(elemname).style.display = "";
		}
	}
    		
	return false;
}

function CancelEditForm()
{
	document.getElementByID('SearchButton').click();
}

function SetNotation()
{
	//alert("SetNotation");
	var notation = document.getElementById('notationedit').value;
	//alert("Notation = " + notation);
	document.getElementById('notation').value = notation;
	//alert("Success");
}

function showElement(elementID, show)
{
	//alert(elementID);
	
	var el = document.getElementById(elementID);
	if (el)
	{
		if (show)
			el.style.display = "";
		else
			el.style.display = "none";
	}
}

function editExample(exampleNotation)
{
	//alert(exampleNotation);
	var exnotation = decodeURIComponent(exampleNotation);
    //alert(exnotation);
	/*alert(html_entity_decode(exnot3, "ENT_COMPAT"));
	var exnot = decodeURI(exampleNotation);
	alert("decodeURI " + exnot);
	var exnot2 = decodeURI(exnot);
	alert("decodeURI2 " + exnot2);
	exnot = html_entity_decode(exnot, "ENT_COMPAT");
	alert("html entity " + exnot);
	*/
	
	var i=0;
	var exampleline = "";
	var notindex = 1;
	var exampleString = document.getElementById('examplestring').value;
	
	//alert(exampleString);
	
	while(i < exampleString.length)
	{
		var iSep = exampleString.indexOf("@", i);
		if (iSep == -1)	
		{
			exampleLine = exampleString.substr(i,exampleString.length - i);
			finished = true;
		}
		else
		{
			exampleLine = exampleString.substr(i,iSep-i);
		}

		//alert(exampleLine);
				
		if (exampleLine.length > 0)
		{
		    var notation = "";
		    var description = "";
		    var encoded = "";

			var iLastSepPos = 0;
			var iField = 0;
			var bMatch = false;
			var iSepPos = exampleLine.indexOf("#", iLastSepPos);
			while (iSepPos != -1)
			{
			    switch(iField)
			    {
			        case 0:
                        notation = exampleLine.substr(iLastSepPos, iSepPos - iLastSepPos);
                        //alert("Original " + notation);
                        notation = decodeURIComponent(notation);
                        //alert("decodeURI " + notation);
                        if (notation == exnotation)
                        {
                            bMatch = true;
                        }
                        break;
                    case 1:
                        description = exampleLine.substr(iLastSepPos, iSepPos - iLastSepPos);
                        //alert("Original Desc: " + description);
                        //####
                        description = decodeURIComponent(description);
                        description = html_entity_decode(description, "ENT_COMPAT");
                        description = description.replace(/\$\$4\$\$/gi, '\'');                        
                        //alert("Decoded Desc: " + description);
                        break;
                    case 2:
                        encoded = exampleLine.substr(iLastSepPos, iSepPos - iLastSepPos);
                        break;
                }
                iLastSepPos = iSepPos + 1;
				iField++;  
                iSepPos = exampleLine.indexOf("#", iLastSepPos);
            }
			
			if (iLastSepPos < (exampleLine.length-1))
				encoded = exampleLine.substr(iLastSepPos, exampleLine.length - iLastSepPos);
				
			//alert(notation + "|" + description + "|" + encoded + "|");
            
            if (bMatch)
            {
				//alert("match");
                //####
	            //document.getElementById('examplenotation').value = decodeURIComponent(notation);
	            //document.getElementById('exampledescription').value = decodeURIComponent(description);
	            //document.getElementById('exampleencoded').value = encoded;
	            document.getElementById('examplenotation').value = notation;
	            document.getElementById('exampledescription').value = decodeURIComponent(description);
	            document.getElementById('exampleencoded').value = encoded;
	            
	            var extable = document.getElementById('extable');
	            if (extable != null)
	            {
	            	//alert("extable");
	            	extable.rows[notindex].cells[0].style.backgroundColor = "#F4CEF1";
	            	extable.rows[notindex].cells[1].style.backgroundColor = "#F4CEF1";
	            	extable.rows[notindex].cells[2].style.backgroundColor = "#F4CEF1";
                    document.getElementById('editcancelexample').style.display = "block";    
	            }
	        }
	        else
	        {
	        	var editlinkname = "editlink_" + notindex;
	        	var editlink = document.getElementById(editlinkname);
	        	if (editlink != null)
	        	{
	        		editlink.style.display="none";
	        	}
	        }
		}
		
		i=iSep+1;
		notindex++;
	}
}

function addExample(controlcommand)
{
	var examples = document.getElementById('examplestring').value;
	var examplenotation = document.getElementById('examplenotation').value;
	var exampledescription = document.getElementById('exampledescription').value;
	var exampleencoded = document.getElementById('exampleencoded').value;
	
	if (controlcommand == 'none')
	{
		examplenotation = '';
	}
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		//alert("examplestring = " + examples);
		//alert("examplenotation = " + examplenotation);
		//alert("description = " + exampledescription);

		var newnotation = htmlentities(examplenotation);
		examples = htmlentities(examples);
        examples = examples.replace('+','%2B');
        examples = examples.replace('\'','%27');
        
		//alert("examplestring = " + examples);
		//alert("newnotation = " + newnotation);
		//alert("EscapedNotation = " + escape(newnotation));
		//alert("description = " + exampledescription);
		//alert("desc = " + escape(htmlentities(exampledescription)));
		
        // Plus characters are not translated correctly - we have to do this manually
        newnotation = newnotation.replace('+','%2B');
        newnotation = newnotation.replace('\'', '%27');
        exampledescription = exampledescription.replace('+', '%2B');
        exampledescription = exampledescription.replace('\'', '%27');
        
//		var plusPos = newnotation.indexOf("+", 0);
//		while(plusPos != -1)
//		{
//			//alert("Plus substitution");
//			var tempNotation = newnotation.substr(0, plusPos);
//			//alert("First temp = " + tempNotation);
//			tempNotation += "%2B";
//			//alert("Second temp = " + tempNotation);
//			//alert("Length = " + newnotation.length);
//			//alert("plusPos = " + plusPos);
//			tempNotation += newnotation.substr(plusPos+1, newnotation.length - plusPos - 1);
//			//alert("tempNotation = " + tempNotation);
//			newnotation = tempNotation;
//			plusPos = newnotation.indexOf("+", 0);		
//		}
		
		var passstring = "addexample.php?examplestring=" + escape(examples) + "&notation=" + escape(newnotation) + "&description=" + 
  						 escape(htmlentities(exampledescription)) + "&encoded=" + escape(exampleencoded);
		//alert(passstring);

		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setExamples;
        		
	}
	else
	{
		alert("Failed to get example");
	}
}

function deleteExample(srcnotation)
{
	//alert(refnotation);

	var examples = document.getElementById('examplestring').value;
	
	//alert(refs);
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "deleteexample.php?examplestring=" + escape(examples) + "&examplenotation=" + escape(srcnotation);
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setExamples;		
	}
	else
	{
		alert("Failed to delete example - could not create AJAX transport");
	}
}

// Change the value of the outputText field
function fieldstat(fieldid, checked)
{
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "fieldstat.php?fieldid=" + fieldid + "&operation=" + checked;
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setFieldStat;		
	}
	else
	{
		alert("Failed to change fieldstat - could not create AJAX transport");
	}
}

function refreshform()
{
    document.udcform.submit();
}

function setFieldStat()
{
	if (httpObject.readyState == 4)
	{
	   //alert(httpObject.responseText);
    }
}

// Change the value of the outputText field
function setExamples()
{
	setReplyText('examples');
}

function setReplyText(elementID)
{
	if (httpObject.readyState == 4)
	{	
		var responseText = httpObject.responseText;
		//alert("Response=" + responseText);

		var exmarker = responseText.indexOf("exampleentry", 0);
		if (exmarker != -1)
        {
            //alert(responseText.substr(exmarker));
        }

		responseText = responseText.replace(/\+/g," ");
        exmarker = responseText.indexOf("exampleentry", 0);
		if (exmarker != -1)
        {
            //alert(responseText.substr(exmarker));
        }
		//alert("replace response = " + responseText);
		//alert("http response = " + responseText);
		responseText = unescape(responseText);
        exmarker = responseText.indexOf("exampleentry", 0);
		if (exmarker != -1)
        {
            //alert(responseText.substr(exmarker));
        }
        //alert("unescape response = " + responseText);
		responseText = unescape(responseText);
        exmarker = responseText.indexOf("exampleentry", 0);
		if (exmarker != -1)
        {
            //alert(responseText.substr(exmarker));
        }
        //alert("unescape response2 = " + responseText);
				
        responseText = responseText.replace(/\$\$1\$\$/gi, '+');
        responseText = responseText.replace(/\$\$3\$\$/gi, '\"');
        //responseText = responseText.replace(/\$\$3\$\$/gi, '\'');
  		//alert("$ response = " + responseText);

        var errorString = "";
		
		if (responseText.length > 1)
		{
			if (responseText.substr(0,1) == "*")
			{
				// Extract the error message before the table
				var endmarker = responseText.indexOf("*", 1);
				if (endmarker != -1)
				{
					errorString = responseText.substr(1, endmarker-1);
					responseText = responseText.substr(endmarker+1, responseText.length - endmarker);
				}
			}
			else
			{
				// If this response is language encoded, extract it
				var langmarker = responseText.indexOf("~", 1);
				if (langmarker != -1)
				{
					var langvalue = responseText.substr(0, langmarker);
					responseText = responseText.substr(langmarker+1, responseText.length - langmarker);
					
					// Does this equal the current language?
					var langcombo = document.getElementById("language");
					if (langcombo != null)
					{
						var currentlang = langcombo.options[langcombo.selectedIndex].value;
						//alert("Current = " + currentlang + ", lang = " + langvalue);
						if (currentlang != langvalue)
						{
							//alert("Setting grey text area");
							document.getElementById(elementID).style.color = "#882222";
						}
						else
						{
							//alert("Setting black text area");
							document.getElementById(elementID).style.color = "black";
						}
					}
					else
					{
						//alert("Language is null");
					}
				}
				else
				{
					//alert("No language element");
				}
			}
		}
	
		document.getElementById(elementID).innerHTML = responseText;
		if (errorString.length > 0)
		{
			alert(errorString);
		}
        
        document.body.style.cursor='auto';	   

	}
}

function GetLanguage(langstring)
{
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
		else if(langname.indexOf('Chinese') >= 0)
			langid = 27;
			
        //langid = (lang.selectedIndex+1);
        //alert(langname + " " + langid);
    }
    else
    {
        //alert("No language defined");
    }
    
    return langid;
}

function openrecord(recordid)
{
	//alert("GetRecord: " + recordid);
	
    document.body.style.cursor='wait';

    var lang = document.getElementById('selectedlang');
    var langid = GetLanguage(lang);

    //alert("Lang ID = " + langid);
        
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

		var passstring = "rightpane.php?notation=" + recordid + "&lang=" + langid + "&encode=Y";
        //alert(passstring);
		httpObject.onreadystatechange = setRecord;		
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
	}
	else
	{
        document.body.style.cursor='auto';	   
		alert("Failed to get classmark record");
	}    
}

function setRecord()
{
	setReplyText('rightpane');
    document.body.style.cursor='auto';
}

function acknowledge(id)
{
    var cb = document.getElementById('cb_' + id);
    if (cb != null)
    {
        if (cb.checked == 1)
        {
            //alert("Box is ticked");
            cb.value = 'Y';
        }
        else
        {
            //alert("Box is not ticked");
            cb.value = 'N';    
        }
    }
}

var httpObject = null;
