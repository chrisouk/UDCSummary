<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC Record Editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="../udcedit.css" type="text/css">

<script language="javascript" type="text/javascript">
<!--

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

function editAuxGroup()
{
	//alert("checkBroader");
	var auxgroupname = document.getElementById('newgroupname').value;
	//alert("Broader = " + broadcategory);
	
	while(auxgroupname.length > 0 && auxgroupname.substr(auxgroupname.length-1, 1) == " ") 
		auxgroupname = auxgroupname.substr(0, auxgroupname.length-1);
		
	if (auxgroupname == "")
	{
		return false;
	}
	
	httpObject = getHTTPObject();
	if (httpObject != null) 
	{
		var passstring = "getAuxCategory.php?category=" + escape(auxgroupname);
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

// Change the value of the outputText field
function setAuxGroup()
{
	setValueText('editid', 'newgroupname');
}

function setValueText(elementID, elementText)
{
	if (httpObject.readyState == 4)
	{	
		var responseText = httpObject.responseText;
		//alert("Response=" + responseText);
		
		var catid = 0;
		var catname = "";
		
		if (responseText.length > 1)
		{
			// Extract the error message before the table
			var endmarker = responseText.indexOf("*", 0);
			if (endmarker != -1)
			{
				catid = responseText.substr(0, endmarker);
				catname = responseText.substr(endmarker+1, responseText.length - endmarker);
			}
		}
	
		//alert("Response = " + httpObject.responseText);
		document.getElementById(elementID).value = catid;
		document.getElementById(elementText).value = catname;
	}
}

var httpObject = null;

//-->
</script>

<script language="javascript">
<!--

var state = 'none';

function showhide(id){
if (document.getElementById){
obj = document.getElementById(id);
if (obj.style.display == "none"){
obj.style.display = "";
} else {
obj.style.display = "none";
}
}
} 
//-->
</script>


</head>


<body>

	<form name="form1" method="post" action="auxcats.php">
		<div id="searchbox">
			<div class="editleftcolumn">
				<div class="editlabel fixedwidthlabel"><strong>Edit Group</strong></div>
				<div class="editvalue">
					<select id="auxgroups" name="auxgroups" class="editcombo">#1#</select>
					<input type="submit" name="getgroups" id="getgroups" value="Get">
					<input type="submit" name="editgroup" id="editgroup" value="Edit" onClick="setAuxGroup();">
					<input type="submit" name="delgroup" id="delgroup" value="Delete">
				</div>
			</div>
			<div class="editleftcolumn">
				<div class="editlabel fixedwidthlabel"><strong>Add Group&nbsp;&nbsp;</strong></div>
				<div class="editvalue">
					<input type="hidden" id="editid" name="editid">
					<input name="newgroupname" id="newgroupname" type="text" size="37">
					<input type="submit" name="addgroup" value="Add">
				</div>
			</div>
		</div>
	</form>

</body>
</html>
