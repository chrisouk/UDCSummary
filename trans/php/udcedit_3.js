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
	var editormycomments = document.getElementById('editormycomments');
    
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
	
    //alert("comments");
	    
	editormycomments.style.backgroundColor = "white";
	editormycomments.removeAttribute('readOnly');
	
	// Now make the edit fields visible
	//alert("editormycomments");
	
	var finished = false;
	var linkid = 1;
	
	while(!finished)
	{
		var elemname = "example_" + linkid;
		//alert(elemname);
		linkid++;
		if (document.getElementById(elemname) == null)
		{
			finished = true;
		} 
		else
		{
			document.getElementById(elemname).removeAttribute('readonly');
            document.getElementById(elemname).style.backgroundColor = 'white';
		}
	}
    		
    //alert('Successful');
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
		else if(langname.indexOf('Chinese') >= 0)			langid = 27;
			
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
	//alert("openrecord: " + recordid);
	
    document.body.style.cursor='wait';

    var lang = document.getElementById('selectedlang');
    var langid = GetLanguage(lang);
    var scrollvalue = document.getElementById('scrollvalue').value;

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

		var passstring = "rightpane.php?notation=" + recordid + "&lang=" + langid + "&encode=Y&scrollvalue=" + scrollvalue;
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

// function saves scroll position
function fScroll(val)
{
    var hidScroll = document.getElementById('scrollvalue');
    hidScroll.value = val.scrollTop;
    //alert("Scroll now " + hidScroll.value);
}

function showSubMenus(choice)
{
    document.body.style.cursor='wait';

    httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "getSubMenus.php?notation=" + choice;
		httpObject.onreadystatechange = setParText;		
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
	}
	else
	{
        document.body.style.cursor='auto';	   
		alert("Failed to get classmark record");
	}    
}
              
function setParText()
{
	if (httpObject.readyState == 4)
	{	
		var responseText = httpObject.responseText;
        //alert(responseText);
        
		responseText = unescape(responseText);
		responseText = responseText.replace(/\+/g," ");
	
		document.getElementById('choicepar').innerHTML = responseText;
        
        document.body.style.cursor='auto';	   
	}
}

function resetSubMenus(choice)
{
    document.body.style.cursor='wait';

    httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "resetSubMenus.php?notation=" + escape(choice);
        //alert(passstring);
		httpObject.onreadystatechange = resetSubMenuComplete;		
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
	}
	else
	{
        document.body.style.cursor='auto';	   
		alert("Failed to get classmark record");
	}    
}

function resetSubMenuComplete()
{
    if (httpObject.readyState == 4)
	{	
		var responseText = httpObject.responseText;
        //alert("Response=" + responseText);
		responseText = unescape(responseText);
        //alert("Response=" + responseText);
        responseText = responseText.replace(/\+/g," ");
        //alert("Response=" + responseText);
        responseText = responseText.replace("$$1$$","%2B");
        //alert("Response=" + responseText);
		window.location='edittag.php?notation=' + responseText;
        document.body.style.cursor='auto';	   
	}
}

function menuchoose()
{
    var choice = document.getElementById('menuchoice');
    if (choice != null)
    {
        var value = choice.value;
        switch(value)
        {
            case '0':
            case '1':
            case '2':
            case '3':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
                showSubMenus(value);
                break;
            default:
                resetSubMenus(value);
                break;
        }
    }
}

function ajaxFormat(value)
{
	//alert("AFB: " + value);
    value = value.replace(/\"/gi, "@@3@@");
    value = value.replace(/\+/gi, "@@1@@");		
	//alert("AFA: " + value);

	return value;
}

function ajaxUnformat(value)
{
	//alert("AUB: " + value);
    value = value.replace(/@@3@@/gi, '\"');
    value = value.replace(/@@1@@/gi, '+');		
	//alert("AUA: " + value);

	return value;
}

function markTreeRecord(notation, equals, caption)
{
	var item_count = 1;
	var item = document.getElementById("leftlistitem_" + item_count);

	//alert("Checking nodetag for notation [" + notation + "]");

	while(item != null)
	{
		var item_html = item.innerHTML;
		var nodetag = item_html.indexOf('nodetag');
		if (nodetag != -1)
		{
			//alert("Found nodetag: " + nodetag);
			var end_nodetag = item_html.indexOf('<span style', nodetag);
			if (end_nodetag != -1)
			{
				var node_notation = item_html.substr(nodetag+9, end_nodetag-(nodetag+9));
				//alert("Found notation: " + node_notation);
				node_notation = node_notation.replace(/<span class=\"auxiliary\">/gi,'');
				node_notation = node_notation.replace(/<span class=\"specialauxiliary\">/gi,'');
				node_notation = node_notation.replace(/\<\/span\>/gi,'');
				node_notation = node_notation.replace(/&nbsp;/gi,'');
				//alert("End notation: " + node_notation);
				if (node_notation == notation)
				{
					//alert("Found notation [" + node_notation + "]");
					
					if (equals == 'Y')
					{
						//alert("Equals");
						item_html = item_html.replace('redsquare.gif', 'greensquare.gif');
						item_html = item_html.replace('color: #AA0000', 'color: #000000');
					}
					else
					{
						//alert("Does not equal");
						item_html = item_html.replace('greensquare.gif', 'redsquare.gif');
						item_html = item_html.replace('color: #000000', 'color: #AA0000');						
					}
					var caption_section = item_html.indexOf("color");
					if (caption_section != -1)
					{
						//alert("Found caption section");
						var start_caption = item_html.indexOf('>', caption_section);
						var end_caption = item_html.indexOf('<', caption_section);
						if (start_caption != -1 && end_caption > start_caption)
						{
							item_html = item_html.substr(0, start_caption) + '>' + caption + item_html.substr(end_caption);
						}
						//alert("Node html = " + item_html);
						item.innerHTML = item_html;
					}
				}
			}
			else
			{
				//alert('No end of nodetag detected');
			}
		}
		else
		{
			//alert("No nodetags at all!");
		}
		//alert(item_count + ": " + item.innerHTML);
		item_count++;
		item = document.getElementById("leftlistitem_" + item_count);
	}
}

function setRecordReply()
{
	if (httpObject.readyState == 4)
	{
		var notation = "";
		var equals = "N";
		var caption = "";
		
		var reply = httpObject.responseText;
		//alert("Reply=" + reply);
		reply = ajaxUnformat(reply);
		//alert("Unformatted=" + reply);
		var iLastSepPos = 0;
		var iField = 0;
		var iSepPos = reply.indexOf("#", iLastSepPos);
		
		while (iSepPos != -1)
		{
		    switch(iField)
		    {
		        case 0:
                    notation = reply.substr(iLastSepPos, iSepPos - iLastSepPos);
                    break;
                case 1:
                    equals = reply.substr(iLastSepPos, iSepPos - iLastSepPos);
                    break;
            }
		    
		    iField++;
		    iLastSepPos = iSepPos+1;
		    iSepPos = reply.indexOf("#", iLastSepPos);
        }

		if (iLastSepPos != -1)
		{
			caption = reply.substr(iLastSepPos);
		}

	    //alert("Notation = " + notation);
	    //alert("Equals = " + equals);
	    //alert("Caption = " + caption);
	    
	    markTreeRecord(notation, equals, caption);
		
		openrecord(notation);
	}
}

function saveUpdates(language_id)
{
	//alert("SaveUpdates");
	
	var notation = ajaxFormat(document.getElementById('searchterm').value);
	//alert("Notation = " + notation);
	var id = ajaxFormat(document.getElementById('mfnvalue').value);
	//alert("ID = " + id);
	var caption = ajaxFormat(document.getElementById('transcaption').value);	
	//alert("Caption = " + caption);
	var including = ajaxFormat(document.getElementById('transverbalexamples').value);
	//alert("Including = " + including);
	var scope_note = ajaxFormat(document.getElementById('transscopenote').value);
	//alert("Scope Note = " + scope_note);
	var app_note = ajaxFormat(document.getElementById('transappnote').value);
	//alert("App Note = " + app_note);
	var comments_for_user = ajaxFormat(document.getElementById('mycomments').value);
	//alert("App Note = " + app_note);
	var comments_for_editor = ajaxFormat(document.getElementById('editormycomments').value);
	//alert("App Note = " + app_note);
	
	var xml = "<?xml version=\"1.0\"?><fields>" + language_id + "|||||" + notation + "|||||" + id + "|||||" + caption + "|||||" + including + "|||||" + scope_note + "|||||" + app_note + "|||||" + comments_for_user + "|||||" + comments_for_editor + "|||||"; 

	var example_count = 1;
	var examples = '';
	var example_var = document.getElementById('example_' + example_count);
	
	//alert("Looking for example_" + example_count);
	while(example_var != null)
	{
		//alert("Found example " + example_count);
		
		var example_input = ajaxFormat(example_var.value);
		
		//alert("EX:" + example_input);
		
		examples = examples + example_input + "|||||";
		
		//alert("Examples = " + examples);
		example_count++;
		example_var = document.getElementById('example_' + example_count);
		//alert("Looking for example_" + example_count);
	}
	
	xml = xml + (example_count-1)  + "|||||" + examples + "</fields>";

	//alert("Sending: " + xml);

	httpObject = getHTTPObject();
	if (httpObject != null)
	{
		httpObject.open("POST", "saverecord.php", true);
		//Send the proper header information along with the request
		httpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpObject.setRequestHeader("Content-length", xml.length);
		httpObject.setRequestHeader("Connection", "close");
		httpObject.onreadystatechange = setRecordReply;	
		httpObject.send("content=" + xml);
	}
}

var httpObject = null;
