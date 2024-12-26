<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd"> 
<html> 
<head> 
<title>Mrf Updates</title> 
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/> 
 
<link rel="stylesheet" href="../udcedit.css" type="text/css" /> 
<script language="javascript" src="udcedit.js" type="text/javascript" ></script> 
<script language="javascript" src="php.default.js" type="text/javascript" ></script> 
</head> 
 
 
<body> 
    <div id="pagecontainer"> 
        <div id="titleimagecontainer">&nbsp;</div> 
    	<form id="udcform" name="udcform" method="post" action="upload_updates.php" accept-charset="UTF-8">
            <div style="width: 800px;">
                Action 
                <select id="action" name="action">
                    <!--option value="upload" >Upload</option>
                    <option value="compare" >Compare</option>
                    <option value="update">Update</option-->
                    <option value="reconcile">Reconcile</option>
                </select>
            </div > 
            <div style="width: 800px;">
                Clear table before upload <input type="checkbox" id="cleartable" name="cleartable" value=""/>
            </div>
            <div style="width: 800px;">
                <textarea style="width: 800px; height: 500px;" id="updatetext" name="updatetext"></textarea>
            </div>
            <div style="width: 800px; text-align: center;">
                <input type="submit" value="Submit">
            </div>            
      	</form> 
    </div> 
</body> 
</html> 
 