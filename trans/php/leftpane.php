<?php
	if (!isset($_SESSION))
		session_start();

	session_cache_expire("120");

    require_once("checksession.php");
    checksession();
        
    function ShowLeftPane()
    {
        # Did the user request a specific classmark notation?
        $notation = "";
        $captionsearch = "";
        $notationsearch = "";

        if (isset($_GET['notation']))
        {
            $notation = $_GET['notation'];
        }
        else if (isset($_POST['notation']))
        {
            $notation = $_POST['search'];
        }

        if (isset($_GET['tag']))
        {
            $notation = $_GET['tag'];
        }

        if (isset($_POST['submitnotationsearch']))
        {
            # New search, so unset the old search results
            unset($_SESSION['search_results']);
            $notationsearch = trim($_POST['search']);            
        }
        else if (isset($_POST['submitcaptionsearch']))
        {
            # New search, so unset the old search results
            unset($_SESSION['search_results']);
            $captionsearch = trim($_POST['search']);            
        }

        #echo "NotationSearch = [" . $notationsearch . "]<br>\n";
        #echo "CaptionSearch = [" . $captionsearch . "]<br>\n";

        if (isset($_POST['searchterm']))
        {
            $notation = substr($_POST['searchterm'], 0, 1);
            switch($notation)
            {
                case "0":
                case "1":
                case "2":
                case "3":
                case "5":
                case "6":
                case "7":
                case "8":
                case "9":
                    break;
                case "4":
                    $notation = "";
                    break;
                case "+":
                case "/":
                    $notation = "+, /";
                    break;
                case ":":
                case "[":
                    $notation = ":, ::, []";
                    break;
                case "*":
                case "A":
                    $notation = "*, A/Z";
                    break;                                    
                case "\"":
                    $notation = "\"...\"";
                    break;
                case "=":
                    $notation = "=...";
                    break;
                case "(":
                    if (substr($_POST['searchterm'],0,2) == "(0")
                    {
                        $notation = "(0...)";
                    }
                    else if (substr($_POST['searchterm'],0,2) == "(=")
                    {
                        $notation = "(=...)";
                    }
                    else
                    {
                        $notation = "(1/9)";
                    }
                    break;
                case "-":
                    $notation = "-0...";
                    break;
                    
                default:
                    $notation = $_POST['searchterm'];
                    break;
            }
        }

		include_once("displaybranch.php");

        echo GetHierarchyBranch($notation, $captionsearch, $notationsearch, $_SESSION['deflang']);
    }

    if (isset($_GET['ajaxcall']))
    {
    	ShowLeftPane();
    }
    else if (isset($_POST['search']))
    {
    	$args = explode("|", $_POST['search']);
    	foreach($args as $arg)
    	{
    		$arg_items = explode("#", $arg, 2);
    		if (count($arg_items) == 2)
    		{
    			#echo $arg_items[0] . '=' . $arg_items[1] . "<br>\n";
    			$_POST[$arg_items[0]] = $arg_items[1];
    		}
    		else
    		{
    			#echo 'Search=' . urlencode($arg) . "<br>\n";
    			$_POST['search'] = $arg;
    		}
    	}
    	ShowLeftPane();
    }
    else
    {
    	echo "";
    }
?>