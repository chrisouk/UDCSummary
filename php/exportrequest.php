<?php
    if (!isset($_SESSION))
    {
        session_start();
    }

    function getInputValue(array &$inputs, $field)
    {
        return (isset($inputs[$field]) ? $inputs[$field] : "");
    }

    function getExportFieldsDescription($fields)
    {
        switch($fields)
        {
            case "caption": return "Caption only";
            case "incl": return "Caption and Including";
            case "notes": return "Caption and all notes fields";
            case "all": return "Caption, notes and examples";
            default: return "No data";
        }
    }
    function checkMandatoryFields(array &$results, array &$fields, array &$errors)
    {
        foreach($fields as $field)
        {
            if (!isset($results[$field]) || trim($results[$field]) == '')
            {
                $errors[] = ucfirst($field) . " is a required field";
            }
        }
    }

    function checkResults(array &$results)
    {
        $errors = [];
        $mandatory = ['name','organization','email','language','purpose','exporttype','fields'];
        checkMandatoryFields($results, $mandatory, $errors);

        if (isset($results['printed']) && trim($results['printed']) != '' && trim($results['url']) == '')
        {
            $errors[] = "Please supply a url for the published webpage";
        }

        if (!isset($results['licence']) || $results['licence'] == '')
        {
            $errors[] = "Please agree to the CC BY-NC-ND 4.0 licence";
        }

        return $errors;
    }

    function getItems(array &$items)
    {
        $results = [];

        foreach ($items as $item)
        {
            $results[$item] = (isset($_POST[$item]) ? trim($_POST[$item]) : '');
        }

        return $results;
    }

    function storeResponse()
    {
        $_SESSION['input'] = serialize($_POST);
    }

    function checkCaptcha(array &$results, array &$errors)
    {
        $data = array(
            'secret' => "0x8Da41a07A7455F730d8Ae54bd6ac217c936eF529",
            'response' => $_POST['h-captcha-response']
        );

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);

        $responseData = json_decode($response);
        if(!$responseData->success)
        {
            $errors[] = "Please complete the Captcha";
        }
    }

    if (isset($_POST['submit']))
    {
        storeResponse();

        $items = ['name','organization','email','language','purpose','printed','url','exporttype','fields','licence'];
        $results = getItems($items);
        $errors = checkResults($results);
        checkCaptcha($results, $errors);
        if (count($errors) > 0)
        {
            $_SESSION['errors'] = serialize($errors);
            header("Location: exportrequest.php");
            exit(1);
        }
        else
        {
            unset($_SESSION['errors']);
        }

        $headers =  'From: udcs@udcc.org' . "\r\n" .
                    'Reply-To: udcs@udcc.org' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

        $all_languages = unserialize($_SESSION['all_languages']);

        $body = str_pad("Name: ", 20) . $results['name'] . "\n";
        $body .= str_pad("Organization: ", 20) . $results['organization'] . "\n";
        $body .= str_pad("Email: ", 20) . $results['email'] . "\n";
        $body .= str_pad("Language: ", 20) . (isset($all_languages[$results['language']]) ? $all_languages[$results['language']] : 'not specified') . "\n";
        $body .= str_pad("Purpose: ", 20) . $results['purpose'] . "\n";
        $body .= str_pad("Printed: ", 20) . ($results['printed'] != '' ? "YES" : "NO") . "\n";
        if ($results['printed'] != '')
        {
            $body .= str_pad("URL: ", 20) . $results['url'] . "\n";
        }

        $body .= str_pad("Export Type: ", 20) . $results['exporttype'] . "\n";
        $export_description = getExportFieldsDescription($results['fields']);
        $body .= str_pad("Fields: ", 20) . $export_description . "\n";
        $body .= str_pad("Licence: ", 20) . ($results['licence'] != '' ? "Agreed" : "Not agreed") . "\n";

        $subject = "UDC Summary Data Export Request [" . $results['name'] . "] (" . $results['organization'] . ")";

        mail("chris@udcc.org", $subject, $body, $headers);

        $_SESSION['goodemail'] = $results['email'];

        unset($_SESSION['input']);

        header('Location: exportrequestsuccess.php');
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC Summary</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<!--meta http-equiv="Content-Type" content="text/html; charset=utf-8"/-->
<link rel="stylesheet" href="../reset.css" type="text/css">
<link rel="stylesheet" href="../udc1000.css" type="text/css" />
<script src="https://www.hCaptcha.com/1/api.js?hl=en" async defer></script>
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
				UDC Summary data is available for export in a variety of formats.
                UDC Summary data exports are subject to the Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International (CC BY-NC-ND 4.0) licence.
			</p>
            <p style="margin-bottom: 40px">
                To request an export of UDC Summary data, please complete the form below.
            </p>

            <form action="exportrequest.php" method="post">
                <div style="width:70%; margin:auto; padding:30px 40px; background-color:#f0f0f0; border-top-left-radius: 20px; border-top-right-radius: 20px">
                    <?php if (isset($_SESSION['errors']) && count(unserialize($_SESSION['errors'])) > 0) : ?>
                    <div style="background-color:#ffcfcf;padding:10px; margin-bottom:10px;border-radius:5px">
                        <p>Please correct the following errors:</p>
                        <ul style="list-style:inside">
                            <?php
                                $errors = unserialize($_SESSION['errors']);
                                foreach ($errors as $error)
                                {
                                    echo "<li>" . $error . "</li>\n";
                                }
                            ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <?php
                        $inputs = (isset($_SESSION['input']) ? unserialize($_SESSION['input']) : []);

                        $selected_language = getInputValue($inputs, 'language');

                        $all_languages = [];

                        require_once("DBConnectInfo.php");

                        $dsn = 'mysql:dbname=udcsum10;host=127.0.0.1';
                        $database_user = DBUSER;
                        $database_password = DBPASS;

                        try
                        {
                            $dbc = new PDO($dsn, $database_user, $database_password);
                            $dbc->exec("SET names utf8");
//                            $dbc->exec("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
                        }
                        catch (PDOException $e)
                        {
                            $error = 'Cannot get a connection to the database.  Please email support.';
                            exit(1);
                        }

                        $sql = "select language_id, code, description, native, rtl from language order by code";
                        foreach ($dbc->query($sql) as $row)
                        {
                            $all_languages[$row[1]] = $row[2];
                            $option = $row[2] . ":" . $row[1] . ":" . $row[0] . ":" . $row[3] . ":" . $row[4];
                            $languages[] = $option;
                        }

                        asort($languages, SORT_STRING);

                        $language_options = "";

                        foreach ($languages as $option)
                        {
                            $row = explode(":", $option);
                            $lcode = $row[1];
                            $ldesc = $row[0];
                            $lid = $row[2];
                            $lnative = $row[3];
                            $rightleft = $row[4];

                            $option = "<option style=\"unicode-bidi: bidi-override; direction: ltr\" value=\"" . $lcode . "\"";
                            if ($lcode == $selected_language)
                            {
                                $option .= " selected";
                            }
                            $option .= ">" . $ldesc . " (" . $lnative . ") </option>\n";
                            $language_options .= $option;
                        }

                        $_SESSION['all_languages'] = serialize($all_languages);
                    ?>

                    <div style="margin-bottom: 10px"><div style="padding-bottom: 4px"><label>Name</label></div><input type="text" name="name" style="width:250px;" value="<?php echo getInputvalue($inputs, 'name');?>" /></div>
                    <div style="margin-bottom: 10px"><div style="padding-bottom: 4px"><label>Organization</label></div><input type="text" name="organization" style="width:400px;" value="<?php echo getInputvalue($inputs, 'organization');?>"/></div>
                    <div style="margin-bottom: 10px"><div style="padding-bottom: 4px"><label>Email</label></div><input type="email" name="email" style="width:250px;" value="<?php echo getInputvalue($inputs, 'email');?>"/></div>
                    <div style="margin-bottom: 10px"><div style="padding-bottom: 4px"><label>Re-enter email</label></div><input type="emailconfirm" name="emailconfirm" style="width:250px;" value="<?php echo getInputvalue($inputs, 'emailconfirm');?>"/></div>
                    <div style="margin-bottom: 10px"><div style="padding-bottom: 4px"><label>Language</label></div>
                        <select name="language" style="width:257px;">
                            <?php echo $language_options; ?>
                        </select>
                    </div>
                    <div style="margin-bottom: 10px"><div style="padding-bottom: 4px"><label>Purpose of use</label></div>
                        <textarea name="purpose" style="width:400px;" rows="3"><?php echo getInputvalue($inputs, 'purpose');?></textarea>
                    </div>

                    <div style="margin-bottom: 10px">
                        <input type="checkbox" id="printed" name="printed" <?php echo (getInputvalue($inputs, 'printed') != '' ? ' checked' : '');?>/>
                        <label for="printed">Will be used for printing or replicating/publishing/displaying on the web</label>
                    </div>

                    <div style="margin-bottom: 10px"><div style="padding-bottom: 4px"><label>Web URL (if applicable)</label></div><input type="text" name="url" style="width:400px;"  value="<?php echo getInputvalue($inputs, 'url');?>"/></div>

                    <div style="margin-bottom: 10px"><div style="padding-bottom: 4px"><label>Export Type</label></div>
                        <select name="exporttype" style="width:257px;">
                            <option value="text" <?php echo (getInputvalue($inputs, 'exporttype') == 'text' ? " selected" : "");?>>Text</option>
                            <option value="xls" <?php echo (getInputvalue($inputs, 'exporttype') == 'xls' ? " selected" : "");?>>Excel Spreadsheet</option>
                            <option value="xml" <?php echo (getInputvalue($inputs, 'exporttype') == 'xml' ? " selected" : "");?>>XML</option>
                            <option value="json" <?php echo (getInputvalue($inputs, 'exporttype') == 'json' ? " selected" : "");?>>JSON</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px"><div style="padding-bottom: 4px"><label>Export Fields</label></div>
                        <select name="fields" style="width:257px;">
                            <option value="caption" <?php echo (getInputvalue($inputs, 'fields') == 'caption' ? " selected" : "");?>>Caption only</option>
                            <option value="incl" <?php echo (getInputvalue($inputs, 'fields') == 'incl' ? " selected" : "");?>>Caption and Including</option>
                            <option value="notes" <?php echo (getInputvalue($inputs, 'fields') == 'notes' ? " selected" : "");?>>Caption and all notes fields</option>
                            <option value="all" <?php echo (getInputvalue($inputs, 'fields') == 'all' ? " selected" : "");?>>Caption, notes and examples</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 30px">
                        <input type="checkbox" id="licence" name="licence" <?php echo (getInputvalue($inputs, 'licence') != '' ? ' checked' : '');?>/>
                        <label for="licence">I have read and agree to the <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/" target="_blank">CC BY-NC-ND 4.0 licence</a></label>
                    </div>

                </div>
                <div style="width:70%; margin:auto; padding:30px 40px; background-color:#e8e8e8; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; margin-bottom: 40px">
                    <div style="display: flex; flex-direction: row;">
                        <div style="text-align: left; padding-top: 10px; padding-right: 20px; display: flex; flex-direction: column; flex-basis: 100%; flex: 1">
                            <div class="h-captcha" data-sitekey="5212c0b9-1c4c-43fe-8275-075e915066bb"></div>
                        </div>
                        <div style="text-align: right; padding-top: 10px; padding-bottom: 10px; display: flex; flex-direction: column; flex-basis: 100%; flex: 1; justify-content:right;">
                            <input type="submit" name="submit" value="Submit" style="width:150px;background-color:#898989;color:white;margin-top:20px;border:0px;padding:9px 14px;border-radius:3px;margin-left:auto">
                        </div>
                    </div>
                </div>
            </form>
        </div>


		<div id="rightmenucontainer">
		  <p>&gt;  <a href="#whatis">WHAT IS UDC SUMMARY?</a> </p>
		  <p>&nbsp;</p>
		  <p>&gt; <a href="#conditions">CONDITIONS OF USE</a> </p>
		  <p>&nbsp;</p>
		  <p>&gt; <a href="#editorial">EDITORIAL</a> </p>
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
