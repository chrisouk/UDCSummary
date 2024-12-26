<?php
    if (!isset($_SESSION))
    {
        session_start();
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC Summary</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<!--meta http-equiv="Content-Type" content="text/html; charset=utf-8"/-->
<link rel="stylesheet" href="../reset.css" type="text/css">
<link rel="stylesheet" href="../udc1000.css" type="text/css" />

</head>

<body>
	<div id="centercontainer" class="debugbkg">
		<div id="titlebox" class="debugbkg"></div>
		<div id="menubox" class="debugbkg">
            <ul class="menu">
                <li><a href="index.php">TOP</a></li>
                <li><a href="index.php?tag=--">SIGNS</a></li>
                <li><a href="index.php?tag=---">AUXILIARIES</a></li>
                <li><a href="index.php?tag=0">0</a></li>
                <li><a href="index.php?tag=1">1</a></li>
                <li><a href="index.php?tag=2">2</a></li>
                <li><a href="index.php?tag=3">3</a></li>
				<li><a class="vacant" href="#" title="vacant">4</a></li>
                <li><a href="index.php?tag=5">5</a></li>
                <li><a href="index.php?tag=6">6</a></li>
                <li><a href="index.php?tag=7">7</a></li>
                <li><a href="index.php?tag=8">8</a></li>
                <li><a href="index.php?tag=9">9</a></li>
            </ul>
            <ul class="rightmenu">
                <li><a href="../translation.htm" title="Under Development">TRANSLATIONS</a></li>
                <li><a href="#" title="Under Development">MAPPINGS</a></li>
                <li><a href="../exports.htm" title="Data Exports">EXPORTS</a></li>
                <li><a href="#" title="Under Development">ABC INDEX</a></li>
                <li><a href="#" title="Under Development">GUIDE</a></li>
                <li><a href="../about.htm" title="About the UDC Summary">ABOUT</a></li>
            </ul>
        </div>
		<p>&nbsp;</p>
		<div id="aboutcontainer">
			<h1>UDC Summary Export Request</h1>
			<p style="margin-bottom: 10px">
				Your request for an export of UDC Summary Data has been received and will be processed shortly<?php if(isset($_SESSION['goodemail'])) : ?>, responses will be sent to <strong><?php echo $_SESSION['goodemail']; ?></strong>.<?php else: ?>.<?php endif; ?>
            </p>
            <p style="margin-bottom: 40px">
                For any enquiries, please email <a href="mailto:udcs@udcc.org">udcs@udcc.org</a>.
            </p>

            <p style="margin-bottom: 40px">
                <a href="https://udcsummary.info">Return to the UDC Summary page</a>.
            </p>
        </div>
		<div id="footer">
			<div class="footersectionleft">
				This UDC Summary (UDCS) provides a selection of around 2,600 classes from the whole scheme which comprises more than 70,000 entries.
				Please send questions and suggestions to <a href="mailto:udcs@udcc.org?subject=UDC Summary Enquiry">udcs@udcc.org</a>.
			</div>
			<div class="footermiddle">
				<a href="http://www.udcc.org"><img src="../images/udclogowhite.png" border="0"></a>
			</div>
			<div class="footerright">
				The data provided in this Summary is released under the
				<a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">Creative Commons Attribution Share Alike 3.0 license</a>
			</div>
			<div class="footercc">
				<a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank"><img src="../images/cclogo.jpg" style="margin-top: 7px;"></a>
			</div>
		</div>
	</div>
</body>
</html>
