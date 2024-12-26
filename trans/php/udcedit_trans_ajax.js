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

/*
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
*/

function EditForm()
{
	var transcaption = document.getElementById('transcaption');
	var transappnote =	document.getElementById('transappnote');
	var transscopenote = document.getElementById('transscopenote');
	var transverbalexamples = document.getElementById('transverbalexamples');
	var mycomments = document.getElementById('mycomments');
	var notation = document.getElementById('notation');
	var editormycomments = document.getElementById('editormycomments');

	document.getElementById('SubmitSave').style.display = "";
	document.getElementById('EditCancel').style.display = "";
	document.getElementById('notationedit').value = notation.innerHTML;
	document.getElementById('successbox').style.display = "none";
	document.getElementById('errorbox').style.display = "none";

	transcaption.style.backgroundColor = "white";
	transcaption.removeAttribute('readOnly');

	transappnote.style.backgroundColor = "white";
	transappnote.removeAttribute('readOnly');

	transscopenote.style.backgroundColor = "white";
	transscopenote.removeAttribute('readOnly');

	transverbalexamples.style.backgroundColor = "white";
	transverbalexamples.removeAttribute('readOnly');

	mycomments.style.backgroundColor = "white";
	mycomments.removeAttribute('readOnly');

	// Make the edit fields visible

	var finished = false;
	var linkid = 1;

	while(!finished)
	{
		var elemname = "example_" + linkid;

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

	return false;
}

function CancelEditForm()
{
	var notation = document.getElementById('savedsearchterm');
    if (notation != null)
    {
        return openrecord(notation.value);
    }

    return false;
}

function SetNotation()
{
	var notation = document.getElementById('notationedit').value;
	document.getElementById('notation').value = notation;
    return false;
}

function showElement(elementID, show)
{
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
	var exnotation = decodeURIComponent(exampleNotation);
	var exampleline = "";
	var notindex = 1;
	var exampleString = document.getElementById('examplestring').value;

    var i=0;
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
                        notation = decodeURIComponent(notation);
                        if (notation == exnotation)
                        {
                            bMatch = true;
                        }
                        break;
                    case 1:
                        description = exampleLine.substr(iLastSepPos, iSepPos - iLastSepPos);
                        description = decodeURIComponent(description);
                        description = html_entity_decode(description, "ENT_COMPAT");
                        description = description.replace(/\$\$4\$\$/gi, '\'');
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

            if (bMatch)
            {
	            document.getElementById('examplenotation').value = notation;
	            document.getElementById('exampledescription').value = decodeURIComponent(description);
	            document.getElementById('exampleencoded').value = encoded;

	            var extable = document.getElementById('extable');
	            if (extable != null)
	            {
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
		var newnotation = htmlentities(examplenotation);
		examples = htmlentities(examples);
        examples = examples.replace('+','%2B');
        examples = examples.replace('\'','%27');

        // Plus characters are not translated correctly - we have to do this manually
        newnotation = newnotation.replace('+','%2B');
        newnotation = newnotation.replace('\'', '%27');
        exampledescription = exampledescription.replace('+', '%2B');
        exampledescription = exampledescription.replace('\'', '%27');

		var passstring = "addexample.php?examplestring=" + escape(examples) + "&notation=" + escape(newnotation) + "&description=" +
  						 escape(htmlentities(exampledescription)) + "&encoded=" + escape(exampleencoded);

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
	var examples = document.getElementById('examplestring').value;

	httpObject = getHTTPObject();
	if (httpObject != null)
	{
		var passstring = "deleteexample.php?examplestring=" + escape(examples) + "&examplenotation=" + escape(srcnotation);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setExamples;
	}
	else
	{
		alert("Failed to delete example - could not create AJAX transport");
	}
}

function fieldstat(fieldid, checked)
{
	httpObject = getHTTPObject();
	if (httpObject != null)
	{
		var passstring = "fieldstat.php?fieldid=" + fieldid + "&operation=" + checked;
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setFieldStat;
	}
	else
	{
		alert("Failed to change fieldstat - could not create AJAX transport");
	}
}

function showlastrevs(auditdate, checked)
{
	httpObject = getHTTPObject();
	if (httpObject != null)
	{
		var passstring = "showlastrevs.php?auditdate=" + auditdate + "&operation=" + checked;
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
		var exmarker = responseText.indexOf("exampleentry", 0);

		responseText = responseText.replace(/\+/g," ");
        exmarker = responseText.indexOf("exampleentry", 0);
		responseText = decodeURIComponent(responseText);
        exmarker = responseText.indexOf("exampleentry", 0);
		responseText = decodeURIComponent(responseText);
        exmarker = responseText.indexOf("exampleentry", 0);
        responseText = responseText.replace(/\$\$1\$\$/gi, '+');
        responseText = responseText.replace(/\$\$3\$\$/gi, '\"');

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
						if (currentlang != langvalue)
						{
							document.getElementById(elementID).style.color = "#882222";
						}
						else
						{
							document.getElementById(elementID).style.color = "black";
						}
					}
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

function openrecord(recordid)
{
    document.body.style.cursor='wait';

    var langid = document.getElementById('targetlanguage').value;
    var scrollvalue_element = document.getElementById('scrollvalue');
    var scrollvalue = 0;

    if (scrollvalue_element != null)
    {
        scrollvalue = scrollvalue_element.value;
    }
    else
    {
        alert('No scroll value');
    }

    httpObject = getHTTPObject();
	if (httpObject != null)
	{
        recordid = recordid.replace("\"", "%22");
        recordid = recordid.replace("+", "%2B");

		var passstring = "rightpane.php?notation=" + recordid + "&lang=" + langid + "&encode=Y&scrollvalue=" + scrollvalue;
		httpObject.onreadystatechange = setRecord;
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
	}
	else
	{
        document.body.style.cursor='auto';
		alert("Failed to get classmark record");
	}

    return false;
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
}

function leftpane_searchnotation()
{
    document.body.style.cursor='wait';
    var notation = document.getElementById('searchterm').value;
    return dosearch(notation, 'submitnotationsearch');
}

function searchnotation()
{
    document.body.style.cursor='wait';
    var notation = document.getElementById('searchnotation').value;
    document.getElementById('searchcaption').value = '';
    return dosearch(notation, 'submitnotationsearch');
}

function searchcaption()
{
    document.body.style.cursor='wait';
    var caption = document.getElementById('searchcaption').value;
    document.getElementById('searchnotation').value = '';
    return dosearch(caption, 'submitcaptionsearch');
}

function dosearch(term, type)
{
    var leftpane = document.getElementById('leftpane');
    if (leftpane != null)
    {
    	leftpane.innerHTML = '<div style="width: 100px; height: 50px; margin-left: auto; margin-right: auto; margin-top: 200px;"><img src="../images/wait.gif"></div>';
    }

    clearchoice = true;
    clearmenuchoice = true;
    clearsearches = false;

    httpObject = getHTTPObject();
	if (httpObject != null)
	{
		var passstring = "search=" + encodeURIComponent(term) + "|ajaxcall#Y|resetsearch#Y|" + type + "#Y";
		httpObject.open("POST", "leftpane.php", true);
		httpObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
		httpObject.setRequestHeader("Content-length", passstring.length);
		httpObject.setRequestHeader("Connection", "close");
		httpObject.onreadystatechange = setAndClearLeftPane;
		httpObject.send(passstring);
	}
	else
	{
        document.body.style.cursor='auto';
		alert("Failed to browse classmark record");
	}

    return false;
}

function browsetree(notation, clearsubmenus, clearselection, clearsearch)
{
    document.body.style.cursor='wait';

    var leftpane = document.getElementById('leftpane');
    if (leftpane != null)
    {
    	leftpane.innerHTML = '<div style="width: 100px; height: 50px; margin-left: auto; margin-right: auto; margin-top: 200px;"><img src="../images/wait.gif"></div>';
    }

    httpObject = getHTTPObject();
	if (httpObject != null)
	{
		clearchoice = clearsubmenus;
		clearmenuchoice = clearselection;
		clearsearches = clearsearch;

		var passstring = "search=" + encodeURIComponent(notation) + "|ajaxcall#Y|resetsearch#Y|notation#Y";
        httpObject.open("POST", "leftpane.php", true);
		httpObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
		httpObject.setRequestHeader("Content-length", passstring.length);
		httpObject.setRequestHeader("Connection", "close");
		httpObject.onreadystatechange = setAndClearLeftPane;
		httpObject.send(passstring);
	}
	else
	{
        document.body.style.cursor='auto';
		alert("Failed to browse classmark record");
	}
}

function setAndClearLeftPane()
{
	if (httpObject.readyState == 4)
	{
		var responseText = httpObject.responseText;

		responseText = decodeURIComponent(responseText);
		responseText = responseText.replace(/\+/g," ");
		responseText = ajaxUnformat(responseText);

		if (responseText == '')
		{
			var leftpane = document.getElementById('leftpane');
		    if (leftpane != null)
		    {
		    	leftpane.innerHTML = '<div style="width: 100px; height: 50px; margin-left: auto; margin-right: auto; margin-top: 200px; font-size: 17px; color: #cbcbcb;">No Results</div>';
		    }
		}
		else
		{
            document.getElementById('leftpane').innerHTML = responseText;
		}

		if (clearchoice == true)
		{
            document.getElementById('choicepar').innerHTML = '&nbsp;';
		}

		if (clearmenuchoice == true)
		{
            document.getElementById('menuchoice').selectedIndex = 0;
		}

		if (clearsearches == true)
		{
			document.getElementById('searchcaption').value = '';
			document.getElementById('searchnotation').value = '';
		}

	    document.body.style.cursor='auto';
	}
}

function setLeftPane()
{
	if (httpObject.readyState == 4)
	{
		var responseText = httpObject.responseText;

		responseText = decodeURIComponent(responseText);
		responseText = responseText.replace(/\+/g," ");
		responseText = ajaxUnformat(responseText);

		document.getElementById('leftpane').innerHTML = responseText;

        document.body.style.cursor='auto';
	}
}

function generateSubMenus(choice)
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

		responseText = decodeURIComponent(responseText);
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
		var passstring = "leftpane.php?notation=" + escape(choice) + "&resetsearch=Y";
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
		responseText = decodeURIComponent(responseText);
        responseText = responseText.replace(/\+/g," ");
        responseText = responseText.replace("$$1$$","%2B");

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
//                generateSubMenus(value);
  //              break;
            default:
                browsetree(value, true, false, true);
                break;
        }
    }
}

function ajaxFormat(value)
{
    value = value.replace(/\"/gi, "@@###@@");
    value = value.replace(/\+/gi, "@@#@@");
    value = value.replace(/</, "@@#####@@");
    value = value.replace(/>/, "@@######@@");

	return value;
}

function ajaxUnformat(value)
{
    value = value.replace(/@@###@@/gi, '"');
    value = value.replace(/@@####@@/gi, '%22');
    value = value.replace(/@@#@@/gi, '+');
    value = value.replace(/@@#####@@/, '<');
    value = value.replace(/@@######@@/, '>');

	return value;
}

function markTreeRecord(notation, equals, caption)
{
    //alert('marktreerecord');

	var item_count = 1;
	var item = document.getElementById("leftlistitem_" + item_count);

	while(item != null)
	{
		var item_html = item.innerHTML;
		var nodetag = item_html.indexOf('<strong>');
		if (nodetag != -1)
		{
			var end_nodetag = item_html.indexOf('</strong>', nodetag);
			if (end_nodetag != -1)
			{
				var node_notation = item_html.substr(nodetag+8, end_nodetag-(nodetag+8));
				node_notation = node_notation.replace(/&nbsp;/gi,'');
                //alert(notation + " vs " + node_notation);
				if (node_notation == notation)
				{
					if (equals == 'Y')
					{
						item_html = item_html.replace('redsquare.gif', 'greensquare.gif');
						item_html = item_html.replace('color: #AA0000', 'color: #000000');
					}
					else
					{
						item_html = item_html.replace('greensquare.gif', 'redsquare.gif');
						item_html = item_html.replace('color: #000000', 'color: #AA0000');
					}

					var caption_section = item_html.indexOf("color");
					if (caption_section != -1)
					{
						var start_caption = item_html.indexOf('>', caption_section);
						var end_caption = item_html.indexOf('<', caption_section);
						if (start_caption != -1 && end_caption > start_caption)
						{
							item_html = item_html.substr(0, start_caption) + '>' + caption + item_html.substr(end_caption);
						}
						item.innerHTML = item_html;
					}
				}
			}
		}
        else
        {
            alert("Item " + item_count + " has no notation");
        }

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
		var debug = "";

		var reply = httpObject.responseText;
		reply = ajaxUnformat(reply);
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
                case 2:
                    caption = reply.substr(iLastSepPos, iSepPos - iLastSepPos);
                    break;
            }

		    iField++;
		    iLastSepPos = iSepPos+1;
		    iSepPos = reply.indexOf("#", iLastSepPos);
        }

		if (iLastSepPos != -1)
		{
			debug = reply.substr(iLastSepPos);
		}

	    markTreeRecord(notation, equals, caption);

		openrecord(notation);
	}
}

function saveUpdates(language_id)
{
    //alert('saveUpdates');

	var notation = encodeURIComponent(ajaxFormat(document.getElementById('searchterm').value));
	var id = encodeURIComponent(ajaxFormat(document.getElementById('mfnvalue').value));
	var caption = encodeURIComponent(ajaxFormat(document.getElementById('transcaption').value));
	var including = encodeURIComponent(ajaxFormat(document.getElementById('transverbalexamples').value));
	var scope_note = encodeURIComponent(ajaxFormat(document.getElementById('transscopenote').value));
	var app_note = encodeURIComponent(ajaxFormat(document.getElementById('transappnote').value));
	var comments_for_user = encodeURIComponent(ajaxFormat(document.getElementById('mycomments').value));
    //var comments_for_editor = encodeURIComponent(ajaxFormat(document.getElementById('editormycomments').value));

	var xml = "<?xml version=\"1.0\"?><fields>" + language_id + "|||||" + notation + "|||||" + id + "|||||" + caption + "|||||" + including + "|||||" + scope_note + "|||||" + app_note + "|||||" + comments_for_user + "||||| |||||";
    //alert('XML = ' + xml);

	var example_count = 1;
	var examples = '';
	var example_var = document.getElementById('example_' + example_count);
    var example_seq = document.getElementById('seq_' + example_count);

	while(example_var != null && example_seq != null)
	{
		var example_input = ajaxFormat(example_var.value);
		var example_seq_input = ajaxFormat(example_seq.value);

        if (example_input.trim().length > 0)
        {
            examples = examples + example_input + "|" + example_seq_input + "|||||";
        }

		example_count++;
		example_var = document.getElementById('example_' + example_count);
        example_seq = document.getElementById('seq_' + example_count);
	}

	xml = xml + (example_count-1)  + "|||||" + examples + "</fields>";

	httpObject = getHTTPObject();
	if (httpObject != null)
	{
		httpObject.open("POST", "saverecord.php", true);
		httpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpObject.setRequestHeader("Content-length", xml.length);
		httpObject.setRequestHeader("Connection", "close");
		httpObject.onreadystatechange = setRecordReply;
		httpObject.send("content=" + xml);
	}

    return false;
}

var httpObject = null;
var clearchoice = false;
var clearmenuchoice = false;
var clearsearches = false;
