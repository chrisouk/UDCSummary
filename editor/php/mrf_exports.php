<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd"> 
<html> 
<head> 
<title>Mrf Exports</title> 
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/> 
 
<link rel="stylesheet" href="../udcedit.css" type="text/css" /> 
<script language="javascript" src="udcedit.js" type="text/javascript" ></script> 
<script language="javascript" src="php.default.js" type="text/javascript" ></script> 
</head> 
 
 
<body> 
    <div id="pagecontainer"> 
        <div id="titleimagecontainer">&nbsp;</div> 
    	<form id="udcform" name="udcform" method="post" action="exportbsi.php" accept-charset="UTF-8">
            <div style="width: 800px;">
                Action 
                <select id="action" name="action">
                    <!--option value="upload" >Upload</option>
                    <option value="compare" >Compare</option>
                    <option value="update">Update</option-->
                    <option value="bsi">MRF Export</option>
                    <option value="encode">Encode</option>
                    <option value="new">Populate New</option>
                    <option value="mod">Populate Modifies</option>
                    <option value="can">Populate Cancels</option>
                    <option value="simple">Simple</option>
                    <option value="popext">Populate Extended</option>                    
                    <option value="extended">Extended</option>                    
                </select>
            </div > 
            <div style="width: 800px;">
                Min Rec No <input type="text" id="minrecno" name="minrecno" />
                &nbsp;&nbsp;Max Rec No <input type="text" id="maxrecno" name="maxrecno" /><br />
                Date <input type="text" id="opdate" name="opdate" />
                &nbsp;&nbsp;Edition <input type="text" id="edition" name="edition" /><br />
            </div>
            <div style="width: 800px;">
                <textarea style="width: 800px; height: 500px;" id="tags" name="tags"></textarea>
            </div>
            <div style="width: 800px; text-align: center;">
                <input type="submit" value="Submit">
            </div>            
      	</form> 
    </div> 
</body> 
</html> 
 