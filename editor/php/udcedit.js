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

function copyrevisionfields(revdate, revname)
{
    //alert(revdate + "," + revname);
    
    var lastrevdate = document.getElementById("lastrevdate");
    var lastrevfields = document.getElementById("lastrevfields");
    var lastrevsource = document.getElementById("lastrevsource");
    var lastrevcomments = document.getElementById("lastrevcomment");
    
    var histrevdate = document.getElementById("revisiondate");
    var histrevfields = document.getElementById("revisionfields");
    var histrevsource = document.getElementById("revisionsource");
    var histrevcomments = document.getElementById("revisioncomments");

    if ((histrevdate.value != "") || (histrevfields.value != "") || (histrevsource.value != "") || (histrevcomments.value != ""))
    {
        var discard = confirm("Revision history is being edited - discard the input values?");
        if (discard == false)
        {
            return;
        }
    }
    
    //alert("Copying");
    
    histrevdate.value = lastrevdate.value;
    histrevfields.value = lastrevfields.value;
    histrevsource.value = lastrevsource.value;
    histrevcomments.value = lastrevcomments.value;
    
    if (lastrevdate.value != revdate)
    {
        lastrevdate.value = revdate;
    }

    if (lastrevsource.value != revname)
    {
        lastrevsource.value = revname;
    }
    
    //alert("Copying");
    
    lastrevfields.value = "";
    lastrevcomments.value = "";
}

function showPage(pageid)
{
	//alert("showPage");
    var accesspagecount = 0;
	var page1 = document.getElementById('page1access');
    if (page1 != null)
    {
        accesspagecount++;  
    }

	var page2 = document.getElementById('page2access');
    if (page2 != null)
    {
        accesspagecount++;
    }

	var page3 = document.getElementById('page3access');
    if (page3 != null)
    {
        accesspagecount++;
    }

    var linkstring = "";
	if (pageid == "page1")
	{
		document.getElementById('page2').style.display = "none";
		document.getElementById('page3').style.display = "none";
		document.getElementById('page1').style.display = "block";
        if (accesspagecount < 2)
        {
            linkstring = 'Page1<input type=\"hidden\" id=\"page1access\" value=\"Y\">'; 
        }
		else
        {
            linkstring = linkstring + 'Page1&nbsp;<input type=\"hidden\" id=\"page1access\" value=\"Y\">';
            if (page2 != null)
            {
                linkstring = linkstring + '<a href="#" onMouseDown="javascript:showPage(\'page2\');">Page2</a><input type=\"hidden\" id=\"page2access\" value=\"Y\">&nbsp;';
            }
            if (page3 != null)
            {
                linkstring = linkstring + '<a href="#" onMouseDown="javascript:showPage(\'page3\');">Page3</a><input type=\"hidden\" id=\"page3access\" value=\"Y\">';
            }
        }
        document.getElementById('pagenumber').innerHTML = linkstring;
	}
	else if (pageid == "page2")
	{
		//alert("Page2");
		
		document.getElementById('page1').style.display = "none";
		document.getElementById('page3').style.display = "none";
		document.getElementById('page2').style.display = "block";
		if (accesspagecount < 2)
        {
            linkstring = 'Page2<input type=\"hidden\" id=\"page2access\" value=\"Y\">'; 
        }
		else
        {
            if (page1 != null)
            {
                linkstring = linkstring + '<a href="#" onMouseDown="javascript:showPage(\'page1\');">Page1</a><input type=\"hidden\" id=\"page1access\" value=\"Y\">&nbsp;';
            }
            linkstring = linkstring + 'Page2<input type=\"hidden\" id=\"page2access\" value=\"Y\">&nbsp;';
            if (page3 != null)
            {
                linkstring = linkstring + '<a href="#" onMouseDown="javascript:showPage(\'page3\');">Page3</a><input type=\"hidden\" id=\"page3access\" value=\"Y\">';
            }
        }
        document.getElementById('pagenumber').innerHTML = linkstring;
	}
	else
	{
		//alert("Page3");

		document.getElementById('page1').style.display = "none";
		document.getElementById('page2').style.display = "none";
		document.getElementById('page3').style.display = "block";
        if (accesspagecount < 2)
        {
            linkstring = 'Page3<input type=\"hidden\" id=\"page3access\" value=\"Y\">'; 
        }
		else
        {
            if (page1 != null)
            {
                linkstring = linkstring + '<a href="#" onMouseDown="javascript:showPage(\'page1\');">Page1</a><input type=\"hidden\" id=\"page1access\" value=\"Y\">&nbsp;';
            }
            if (page2 != null)
            {
                linkstring = linkstring + '<a href="#" onMouseDown="javascript:showPage(\'page2\');">Page2</a><input type=\"hidden\" id=\"page2access\" value=\"Y\">&nbsp;';
            }
            linkstring = linkstring + 'Page3<input type=\"hidden\" id=\"page3access\" value=\"Y\">';
        }
        document.getElementById('pagenumber').innerHTML = linkstring;
	}
}

function EditForm()
{
	//alert("EditForm");
	
	var notation = document.getElementById('notation');
	if (notation == null)
	{
		return false;
	}
	
	//alert("Notation = " + notation.innerHTML);
	
	document.getElementById('SubmitSave').style.display = "";
	document.getElementById('EditCancel').style.display = "";
	document.getElementById('notationedit').value = notation.innerHTML;

	//alert("Notation = " + document.getElementById('notationedit').value);
	
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

function CloneRecord()
{
	//alert("CloneRecord");
	document.getElementById('mfn').innerHTML = "0";
	document.getElementById('notationedit').value = document.getElementById('notation').innerHTML;
	document.getElementById('searchterm').value = "";
    document.getElementById('EUN').value = "";
    
	document.getElementById('notation').style.display = "none";
	document.getElementById('notationeditlabel').style.display = "";
	document.getElementById('notationedit').style.display = "";
	document.getElementById('SubmitSave').style.display = "";
	document.getElementById('EditCancel').style.display = "";

	//alert("Finished");
	
	return false;

}

function NewRecord()
{
	//alert("CloneRecord");
	document.getElementById('mfn').innerHTML = "0";
	document.getElementById('notationedit').value = "";
	
	document.getElementById('notation').style.display = "none";
	document.getElementById('notationeditlabel').style.display = "";
	document.getElementById('notationedit').style.display = "";
	document.getElementById('SubmitSave').style.display = "";
	document.getElementById('EditCancel').style.display = "";

	//alert("Finished");
	
	return false;

}

function showvar(elementID)
{
	//alert(elementID);
	
	var el = document.getElementById(elementID);
	if (el)
	{
		alert(elementID + " = " + el.value);
	}
	else
	{
		alert(elementID + " could not be found");
	}
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

function checkAuxGroup()
{
	//alert("checkAuxGroup");
	var auxgroup = document.getElementById('auxgroup').value;
	//alert("Broader = " + broadcategory);
	
	var iPos = auxgroup.indexOf(' ');
	if (iPos != -1) auxgroup = auxgroup.substr(0, iPos);
	//alert("broad category = " + broadcategory);
	
	if (auxgroup == "")
	{
		return false;
	}
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "checkAuxGroup.php?auxgroup=" + escape(auxgroup);
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setAuxGroup;		
	}
	else
	{
		//alert("Failed to get broader category");
	}
}

function checkBroader()
{
	//alert("checkBroader");
	var broadcategory = document.getElementById('broader').value;
	//alert("Broader = " + broadcategory);
	var language = document.getElementById('language').value; 
	//alert("Language = " + language);
	
	var iPos = broadcategory.indexOf(' ');
	if (iPos != -1) broadcategory = broadcategory.substr(0, iPos);
	
	if (broadcategory == "")
	{
		return false;
	}
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "getBroader.php?category=" + escape(broadcategory) + "&language=" + language;
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.onreadystatechange = setBroader;		
		httpObject.send(null);
	}
	else
	{
		//alert("Failed to get broader category");
	}
}

function checkDerivedFrom()
{
	//alert("checkBroader");
	var derived = document.getElementById('derivedfrom').value;
	//alert("Broader = " + broadcategory);
	var language = document.getElementById('language').value; 
	//alert("Language = " + language);
		
    var iPos = derived.indexOf(' ');
	if (iPos != -1) derived = derived.substr(0, iPos);
	
	if (derived == "")
	{
		return false;
	}
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "getBroader.php?category=" + escape(derived) + "&language=" + language;
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.onreadystatechange = setDerivedFrom;		
		httpObject.send(null);
	}
	else
	{
		//alert("Failed to get broader category");
	}
}

function addRef()
{
	//alert("addRef");
	var refs = document.getElementById('refstring').value;
	var refnotation = document.getElementById('refnotation').value;
	//alert("Ref notation = " + refnotation);
	//alert("Getting current notation");
	var currentnotation = document.getElementById('notation').value;
	//alert("Current notation = " + currentnotation);
	var iPos = refnotation.indexOf(' ');
	if (iPos != -1) refnotation = refnotation.substr(0, iPos);
	iPos = currentnotation.indexOf(' ');
	if (iPos != -1) currentnotation = currentnotation.substr(0, iPos);
	//alert("[" + refnotation + "], [" + currentnotation + "]");
	
	if (currentnotation == refnotation)
	{
		alert("A class cannot reference itself");
		return false;
	}
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		//alert(passstring);		
		var passstring = "checkrefs.php?refstring=" + escape(refs) + "&refnotation=" + escape(refnotation);
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.onreadystatechange = setReferences;		
		httpObject.send(null);
	}
	else
	{
		alert("Failed to get reference");
	}
}

function deleteRef(refnotation)
{
	//alert(refnotation);

	var refs = document.getElementById('refstring').value;
	
	//alert(refs);
	
    httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "deleteref.php?refstring=" + escape(refs) + "&refnotation=" + escape(refnotation);
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.onreadystatechange = setReferences;		
		httpObject.send(null);
	}
	else
	{
		alert("Failed to delete reference - could not create AJAX transport");
	}
}

function editExample(exampleNotation)
{
	//alert(exampleNotation);
	
	var i=0;
	var exampleline = "";
	var finished = false;
	
	var exampleString = document.getElementById('examplestring').value;

	//alert(exampleString);
	
	while(i < exampleString.length && finished == false)
	{
		var iSep = exampleString.indexOf(";", i);
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
                        if (notation == exampleNotation)
                        {
                            bMatch = true;
                            finished = true;
                        }
                        break;
                    case 1:
                        description = exampleLine.substr(iLastSepPos, iSepPos - iLastSepPos);
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
	            document.getElementById('examplenotation').value = notation;
	            document.getElementById('exampledescription').value = description;
	            document.getElementById('exampleencoded').value = encoded;
	        }
		}
		
		i=iSep+1;
	}
}


function trim(s) 
{ 
    if (s.length == 0)
        return s;
        
    while (s.substring(0, 1) == ' ') 
    { 
        s = s.substring(1, s.length-1);
    } 

    while (s.substring(s.length-1, 1) == ' ') 
    { 
        s = s.substring(0, s.length-1);
    } 
    
    return s; 
} 

function addExample()
{
    //alert("addExample");
    
    var notations = [];
    var captions = [];
    var deletions = [];

    var newnotation = "";
    var newcaption = "";
         
    var foundnewrecord = false;
    var example_no = 0;
    
    while (foundnewrecord == false)
    {
        var example_name = 'example_notation_' + (example_no+1); 
        var example_caption = 'example_caption_' + (example_no+1);
        var example_deletion = 'example_deleted_' + (example_no+1);

        //alert(example_name);
        
        var example = document.getElementById(example_name);
        if (example == null)
        {
            foundnewrecord = true;
            
            //alert("Notation = " + newnotation);            
            //alert("Caption = " + newcaption);
                        
            if (trim(newnotation) == '' || trim(newcaption) == '')
            {
                alert('Please complete all fields in the new example before adding another example');
            }
            else
            {
                //alert("Processing " + example_name);
                var newexample = '<table class="reftable" bgcolor="#cccccc" border="0" cellpadding="2" cellspacing="1">' +
				                  '<tr><td align="left" width="15%">Notation</td><td width="75%" align="left">Description</td>' +
	                              '<td width="5%">&nbsp;</td></tr>';                

                // Add a new example row
                for (i=0; i<=example_no; i++)
                {
                    var notationinputname = "example_notation_" + (i+1);
                    var captioninputname = "example_caption_" + (i+1);
                    var deletestatename = "example_deleted_" + (i+1);
                    
                    newexample = newexample + '<tr><td width="15%" bgcolor="white" valign="top"><input type="hidden" name="' + deletestatename + '" id="' + deletestatename + '" value="';
                    if (i < example_no)
                    {
                        newexample = newexample + deletions[i];
                    }                   
                    else
                    {
                        newexample = newexample + "N";
                    } 
                    newexample = newexample + '"><textarea rows="1" class="examplenotationinput" name="' + notationinputname + '" id="' + notationinputname + '">'; 
                    if (i < example_no)
                    {
                        newexample = newexample + notations[i];
                    }                    
                    newexample = newexample + '</textarea></td><td width="75%" bgcolor="white" valign="top"';                                 
                    newexample = newexample + 'class="greytextarea">';					
                    newexample = newexample + '<textarea class="examplecaptioninput" rows="1" name="' + captioninputname + '" id="' + captioninputname + '">';
                    if (i < example_no)
                    {
                        newexample = newexample + captions[i];
                    }                    
                    newexample = newexample + '</textarea></td>';          
                    newexample = newexample + '<td width="5%" bgcolor="white">';
                    if (i < example_no)
                    {
                        newexample = newexample + '<a href="#exampleentry" onMouseDown="javascript:deleteExample(' + (i+1) + ');">Del</a></td></tr>\n';
                    }
                    else
                    {
                        newexample = newexample + '<a href="#exampleentry" onMouseDown="javascript:addExample();">Add</a></td></tr>\n';
                    }
                }
                
                newexample = newexample + '</table>';
                
                 // OK, replace the last part of the <div> containing the examples with the new caption line
                var examplediv = document.getElementById('examples');
                if (examplediv != null)
                {
                    //alert("Changing innerHTML");                        
                    examplediv.innerHTML = newexample;
                }
                else
                {
                    //alert("Couldnt find examples div");
                }                               
            }
        }  
        else
        {
            newnotation = document.getElementById(example_name).value;
            //alert("New notation for " + example_name + "= [" + newnotation + "]");
            notations[example_no] = newnotation;
            newcaption = document.getElementById(example_caption).value;
            //alert("New caption for " + example_name + " = [" + newcaption + "]");
            captions[example_no] = newcaption;
            var deletion = document.getElementById(example_deletion);
            if (deletion != null)
            {
                deletions[example_no] = deletion.value;
            }
            else
            {
                //alert("No element for [" + example_deletion + "]");
            }
            example_no++;
        }      
    }
}

function deleteExample(row_no)
{
    //alert("addExample");
    
    var notations = [];
    var captions = [];
    var deletions = [];

    var newnotation = "";
    var newcaption = "";
         
    var foundnewrecord = false;
    var example_no = 0;
    var found_examples = 0;
    
    while (foundnewrecord == false)
    {
        var example_name = 'example_notation_' + (example_no+1); 
        var example_caption = 'example_caption_' + (example_no+1);
        var example_deletion = 'example_deleted_' + (example_no+1);

        //alert(example_name);
        
        var example = document.getElementById(example_name);
        if (example == null)
        {
            foundnewrecord = true;           

            //alert("Processing " + example_name);
            var newexample = '<table class="reftable" bgcolor="#cccccc" border="0" cellpadding="2" cellspacing="1">' +
			                  '<tr><td align="left" width="15%">Notation</td><td width="75%" align="left">Description</td>' +
                              '<td width="5%">&nbsp;</td></tr>';                

            // Add a new example row
            var row_id = 1;
            for (i=0; i<=found_examples; i++)
            {
                if (i != (row_no-1))
                {
                    var notationinputname = "example_notation_" + row_id;
                    var captioninputname = "example_caption_" + row_id;
                    var deletestatename = "example_deleted_" + row_id;
                    
                    newexample = newexample + '<tr><td width="15%" bgcolor="white" valign="top"><input type="hidden" name="' + deletestatename + '" id="' + deletestatename + '" value="';
                    if (i < found_examples)
                    {
                        newexample = newexample + deletions[i];
                    }                   
                    else
                    {
                        newexample = newexample + "N";
                    } 
                    newexample = newexample + '"><textarea rows="1" class="examplenotationinput" name="' + notationinputname + '" id="' + notationinputname + '">'; 
                    if (i < found_examples)
                    {
                        newexample = newexample + notations[i];
                    }                    
                    newexample = newexample + '</textarea></td><td width="75%" bgcolor="white" valign="top"';                                 
                    newexample = newexample + 'class="greytextarea">';					
                    newexample = newexample + '<textarea class="examplecaptioninput" rows="1" name="' + captioninputname + '" id="' + captioninputname + '">';
                    if (i < found_examples)
                    {
                        newexample = newexample + captions[i];
                    }                    
                    newexample = newexample + '</textarea></td>';          
                    newexample = newexample + '<td width="5%" bgcolor="white">';
                    if (i < found_examples)
                    {
                        newexample = newexample + '<a href="#exampleentry" onMouseDown="javascript:deleteExample(' + row_id + ');">Del</a></td></tr>\n';
                    }
                    else
                    {
                        newexample = newexample + '<a href="#exampleentry" onMouseDown="javascript:addExample();">Add</a></td></tr>\n';
                    }
                    
                    row_id++;
                }
            }
            
            newexample = newexample + '</table>';
            
             // OK, replace the last part of the <div> containing the examples with the new caption line
            var examplediv = document.getElementById('examples');
            if (examplediv != null)
            {
                //alert("Changing innerHTML");                        
                examplediv.innerHTML = newexample;
            }
            else
            {
                //alert("Couldnt find examples div");
            }                               
        }  
        else
        {
            newnotation = document.getElementById(example_name).value;
            if (newnotation != "")
            {
                //alert("New notation for " + example_name + "= [" + newnotation + "]");
                notations[example_no] = newnotation;
                newcaption = document.getElementById(example_caption).value;
                //alert("New caption for " + example_name + " = [" + newcaption + "]");
                captions[example_no] = newcaption;
                var deletion = document.getElementById(example_deletion);
                if (deletion != null)
                {
                    deletions[example_no] = deletion.value;
                }
                else
                {
                    //alert("No element for [" + example_deletion + "]");
                }
                
                found_examples++;
            }
            example_no++
        }      
    }
}

/*
function addExample()
{
	var examples = document.getElementById('examplestring').value;
	var examplenotation = document.getElementById('examplenotation').value;
	var exampledescription = document.getElementById('exampledescription').value;
	var exampleencoded = document.getElementById('exampleencoded').value;
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		//var newnotation = htmlentities(examplenotation);
		//examples = htmlentities(examples);
		//alert("examplestring = " + examples);
		//alert("newnotation = " + newnotation);
		//alert("EscapedNotation = " + escape(newnotation));
		
		// Plus characters are nto translated correctly - we have to do this manually
		var plusPos = examplenotation.indexOf("+", 0);
		while(plusPos != -1)
		{
			//alert("Plus substitution");
			var tempNotation = examplenotation.substr(0, plusPos);
			//alert("First temp = " + tempNotation);
			tempNotation += "%2B";
			//alert("Second temp = " + tempNotation);
			//alert("Length = " + newnotation.length);
			//alert("plusPos = " + plusPos);
			tempNotation += examplenotation.substr(plusPos+1, newnotation.length - plusPos - 1);
			//alert("tempNotation = " + tempNotation);
			examplenotation = tempNotation;
			plusPos = examplenotation.indexOf("+", 0);		
		}
		
		var passstring = "addexample.php?examplestring=" + escape(examples) + "&notation=" + escape(examplenotation) + "&description=" + escape(exampledescription) + "&encoded=" + escape(exampleencoded);
		//alert(passstring);

		httpObject.open("GET", passstring, true);
		httpObject.onreadystatechange = setExamples;
        httpObject.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");        
		httpObject.send(null);
        		
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
		var passstring = "deleteexample.php?examplestring=" + escape(examples) + "&example_no=" + escape(srcnotation);
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.onreadystatechange = setExamples;		
        httpObject.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");                
		httpObject.send(null);
	}
	else
	{
		alert("Failed to delete example - could not create AJAX transport");
	}
}
*/

function editRevision(revisiondate)
{
	//alert("Edit Revision " + revisiondate);
	
	var i=0;
	var revisionline = "";
	var finished = false;
	
	var revisionString = document.getElementById('revisionstring').value;

	//alert(revisionString);
	
	while(i < revisionString.length && finished == false)
	{
		var iSep = revisionString.indexOf(";", i);
		if (iSep == -1)	
		{
			revisionLine = revisionString.substr(i,revisionString.length - i);
			finished = true;
		}
		else
		{
			revisionLine = revisionString.substr(i,iSep-i);
		}

		//alert(revisionLine);
				
		if (revisionLine.length > 0)
		{
		    var revdate = "";
		    var fields = "";
		    var source = "";
		    var comments = "";

			var iLastSepPos = 0;
			var iField = 0;
			var bMatch = false;
			var iSepPos = revisionLine.indexOf("#", iLastSepPos);
			while (iSepPos != -1)
			{
			    switch(iField)
			    {
			        case 0:
                        revdate = revisionLine.substr(iLastSepPos, iSepPos - iLastSepPos);
                        if (revdate == revisiondate)
                        {
                            bMatch = true;
                            finished = true;
                        }
                        break;
                    case 1:
                        fields = revisionLine.substr(iLastSepPos, iSepPos - iLastSepPos);
                        break;
                    case 2:
                        source = revisionLine.substr(iLastSepPos, iSepPos - iLastSepPos);
                        break;
                    case 3:
                        comments = revisionLine.substr(iLastSepPos, iSepPos - iLastSepPos);
                        break;
                }
                iLastSepPos = iSepPos + 1;
				iField++;  
                iSepPos = revisionLine.indexOf("#", iLastSepPos);
            }
			
			if (iLastSepPos < (revisionLine.length-1))
				comments = revisionLine.substr(iLastSepPos, revisionLine.length - iLastSepPos);
				
			//alert(revdate + "|" + fields + "|" + source + "|" + comments + "|");
          
            if (bMatch)
            {
				//alert("match");
	            document.getElementById('revisiondate').value = revdate;
	            document.getElementById('revisionfields').value = fields;
	            document.getElementById('revisionsource').value = source;
	            document.getElementById('revisioncomments').value = comments;
	        }
		}
		
		i=iSep+1;
	}
}

function addRevision()
{
	var revisionstring = document.getElementById('revisionstring').value;
    var revdate = document.getElementById('revisiondate').value;
    var revfields = document.getElementById('revisionfields').value;
    var revsource = document.getElementById('revisionsource').value;
    var revcomments = document.getElementById('revisioncomments').value;
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "addrevision.php?revisionstring=" + escape(revisionstring) + "&revdate=" + escape(revdate) + "&revfields=" + escape(revfields) + "&revsource=" + escape(revsource) + "&revcomments=" + escape(revcomments);
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setRevisions;		
	}
	else
	{
		alert("Failed to add revision - could not create AJAX transport");
	}
}

function deleteRevision(revdate)
{
	//alert(refnotation);

	var revisionstring = document.getElementById('revisionstring').value;
	
	//alert(refs);
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "deleterevision.php?revisionstring=" + escape(revisionstring) + "&revdate=" + escape(revdate);
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setRevisions;		
	}
	else
	{
		alert("Failed to delete revision - could not create AJAX transport");
	}
}

function addParDivInst()
{
	//alert("addParDivInst");	
	
	var pardivinststring = document.getElementById('pardivinststring').value;
	//alert(pardivinststring);
	var pardivinstsrcnotation= document.getElementById('pardivinstsrcnotation').value;
	//alert(pardivinstsrcnotation);
	var pardivinsttgtnotation= document.getElementById('pardivinsttgtnotation').value;
	//alert(pardivinsttgtnotation);
	var pardivinstsrcencoded = document.getElementById('pardivinstsrcencoded').value;
	//alert(pardivinstsrcencoded);
	var pardivinsttgtencoded = document.getElementById('pardivinsttgtencoded').value;
	//alert(pardivinsttgtencoded);
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "addpardivinst.php?pardivinststring=" + escape(pardivinststring) + "&srcnotation=" + escape(pardivinstsrcnotation) + "&tgtnotation=" + escape(pardivinsttgtnotation)  + "&srcencoded=" + escape(pardivinstsrcencoded) + "&tgtencoded=" + escape(pardivinsttgtencoded); 
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setParDivInst;		
	}
	else
	{
		alert("Failed to add parallel division instruction - could not create AJAX transport");
	}
}

function deletepardivinst(srcnotation)
{
	alert(srcnotation);

	var pardivinst = document.getElementById('pardivinststring').value;
	
	alert(pardivinst);
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "deletepardivinst.php?pardivinststring=" + escape(pardivinst) + "&srcnotation=" + escape(srcnotation);
		alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setParDivInst;		
	}
	else
	{
		alert("Failed to delete parallel division instruction - could not create AJAX transport");
	}
}

// Change the value of the outputText field
function setReferences()
{
	setReplyText('references');
}

// Change the value of the outputText field
function setExamples()
{
	setReplyText('examples');
}

function setRevisions()
{
	setReplyText('revisions');
}

function setBroader()
{
	setValueText('broader');
}

function setDerivedFrom()
{
	setValueText('derivedfrom');
}

function setAuxGroup()
{
	setValueText('auxgroup');
}

function setSearchTerms()
{
	setValueText('keywords');
}

// Change the value of the outputText field
function setParDivInst()
{
	setReplyText('pardivinst');
}

function checksearchterm(elementid)
{
    var element = document.getElementById(elementid);
    if (element != null)
    {
        var value = element.value;
        value.Replace('+', '%027');
        element.value = value;
    }
}

function setReplyText(elementID)
{
	if (httpObject.readyState == 4)
	{	
		var responseText = httpObject.responseText;
		//alert("Response=" + responseText);
				
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
	
		//alert("Response = " + httpObject.responseText);
		document.getElementById(elementID).innerHTML = responseText;
		if (errorString.length > 0)
		{
			alert(errorString);
		}
	}
}

function setValueText(elementID)
{
	if (httpObject.readyState == 4)
	{	
		var responseText = httpObject.responseText;
		//alert("Response=" + responseText);
		
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
				var langmarker = responseText.indexOf("#", 1);
				//alert("langmarker = " + langmarker);
				if (langmarker != -1)
				{
					var langvalue = responseText.substr(0, langmarker);
					responseText = responseText.substr(langmarker+1, responseText.length - langmarker);
					
					// Does this equal the current language?
					var langcombo = document.getElementById("language");
					if (langcombo != null)
					{
						var currentlang = langcombo.options[langcombo.selectedIndex].value;
						//alert("Current = " + currentlang + ", Returned = " + langvalue);
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
				}
			}
		}
	
		//alert("Response = " + httpObject.responseText);
		document.getElementById(elementID).value = responseText;
		if (errorString.length > 0)
		{
			alert(errorString);
		}
	}
}

function scansearchterms()
{
	var caption = escape(document.getElementById('caption').value);
	//alert(caption); 
	var verbalexamples = escape(document.getElementById('verbalexamples').value);
	//alert(verbalexamples);
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "scansearchterms.php?caption=" + caption + "&verbalexamples=" + verbalexamples;
		//alert(passstring);
		httpObject.open("GET", passstring, true);
		httpObject.send(null);
		httpObject.onreadystatechange = setSearchTerms;		
	}
	else
	{
		//alert("Failed to delete revision - could not create AJAX transport");
	}
	
}

// function saves scroll position
function fScroll(val)
{
    var hidScroll = document.getElementById('scrollvalue');
    hidScroll.value = val.scrollTop;
//    alert("Scroll now " + hidScroll.value);
}

function GotoNotation(notation)
{
    var hidScroll = document.getElementById('scrollvalue');
    //alert("edittag.php?tag=" + $notation + "&scroll=" + hidScroll.value);
    window.location = "edittag.php?tag=" + notation + "&scroll=" + hidScroll.value;
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
