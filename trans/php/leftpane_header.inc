<div class="leftcontainer">
    <div class="searchbox searchkeywordbox">
        <?php echo "<input type=\"hidden\" name=\"searchchoice\" id=\"searchchoice\" value=\"" . $notation . "\">\n"; ?>
        <div class="editleftcolumn searchcolumn">
            <div class="editlabel searchlabel rightmargin5">Notation</div>
            <div class="editvalue editpadding noborder">
                <input type="text" id="searchnotation" name="searchnotation" class="textinput" style="width: 210px;" value="<?php echo $notationsearch; ?>" />
            </div>
            <div style="float: left; line-height: 20px; vertical-align: middle; margin: -1px;">
                &nbsp;
                <input class="inputbutton" type="button" id="submitnotationsearch" name="submitnotationsearch" value="Search" onclick="return searchnotation();" />&nbsp;&nbsp;&nbsp;<a href="advanced_search.php"><img src="../images/button-advanced.jpg" border="0" style="vertical-align: middle; margin-top: -2px;"/></a>
            </div>
        </div>
        <div class="editleftcolumn searchcolumn">
            <div class="editlabel searchlabel rightmargin5">Caption</div>
            <div class="editvalue editpadding noborder">
                <input type="text" id="searchcaption" name="searchcaption" class="textinput" style="width: 210px;" value="<?php echo $captionsearch; ?>" />
            </div>
            <div style="float: left; line-height: 20px; vertical-align: middle; margin: -1px;">
                &nbsp;
                <input class="inputbutton" type="button" id="submitcaptionsearch" name="submitcaptionsearch" value="Search" onclick="return searchcaption();" />
            </div>
        </div>
        <div class="editleftcolumn">
            <div class="editlabel searchlabel rightmargin5">Browse</div>
            <div class="editvalue noborder extendededit">
                <?php
                        echo "<div style=\"float: left; width: 100px;\"><select class=\"menuchoice\" id=\"menuchoice\" onchange=\"menuchoose(); return false;\">\n";
                echo "<option value=\"\"> </option>\n";
                #echo "<option value=\"%2B,%20/\""; if ($chosenmenu == "+, /") echo " selected"; echo ">Table 1a</option>\n";
                #echo "<option value=\":,%20::,%20[]\""; if ($chosenmenu == ":, ::, []") echo " selected"; echo ">Table 1b</option>\n";
                #echo "<option value=\"*,%20A/Z\""; if ($chosenmenu == "*, A/Z") echo " selected"; echo ">Table 1h</option>\n";
				echo "<option value=\"--\""; if ($chosenmenu == "--") echo " selected"; echo ">Auxiliary signs</option>\n";
                echo "<option value=\"=...\""; if ($chosenmenu == "=...") echo " selected"; echo ">=...</option>\n";
                echo "<option value=\"(0...)\""; if ($chosenmenu == "(0...)") echo " selected"; echo ">(0...)</option>\n";
                echo "<option value=\"(1/9)\""; if ($chosenmenu == "(1/9)") echo " selected"; echo ">(1/9)</option>\n";
                echo "<option value=\"(=...)\""; if ($chosenmenu == "(=...)") echo " selected"; echo ">(=...)</option>\n";
                echo "<option value=\"%22...%22\""; if ($chosenmenu == "\"...\"") echo " selected"; echo ">\"...\"</option>\n";
                echo "<option value=\"-0...\""; if ($chosenmenu == "-0...") echo " selected"; echo ">-0...</option>\n";
                echo "<option value=\"-1/-9,.01/.09,%20`0/`9\""; if ($chosenmenu == "-1/-9,.01/.09, `0/`9") echo " selected"; echo ">Spec Aux</option>\n";
                echo "<option value=\"0\""; if ($chosenmenu == "0") echo " selected"; echo ">0</option>\n";
                echo "<option value=\"1\""; if ($chosenmenu == "1") echo " selected"; echo ">1</option>\n";
                echo "<option value=\"2\""; if ($chosenmenu == "2") echo " selected"; echo ">2</option>\n";
                echo "<option value=\"3\""; if ($chosenmenu == "3") echo " selected"; echo ">3</option>\n";
                echo "<option value=\"5\""; if ($chosenmenu == "5") echo " selected"; echo ">5</option>\n";
                echo "<option value=\"6\""; if ($chosenmenu == "6") echo " selected"; echo ">6</option>\n";
                echo "<option value=\"7\""; if ($chosenmenu == "7") echo " selected"; echo ">7</option>\n";
                echo "<option value=\"8\""; if ($chosenmenu == "8") echo " selected"; echo ">8</option>\n";
                echo "<option value=\"9\""; if ($chosenmenu == "9") echo " selected"; echo ">9</option>\n";
                echo "</select></div><div id=\"choicepar\">";
            if (isset($_SESSION['submenu']))
            {
            echo $_SESSION['submenu'];
            }
            else
            {
            echo "&nbsp;";
            }
            echo "</div>\n";
        ?>
    </div>
</div>
<!--div class="editleftcolumn">
    <div class="editlabel searchlabel rightmargin5">&nbsp;</div>
    <div class="editvalue noborder extendededit">
        <?php
            echo "<div id=\"choicepar\">";
            if (isset($_SESSION['submenu']))
            {
                echo $_SESSION['submenu'];
            }
            else
            {
                echo "&nbsp;";
            }

            echo "</div>\n";
        ?>
    </div>
</div-->
<div class="editleftcolumn">
    <div class="editlabel searchlabel rightmargin5">&nbsp;</div>
    <div class="editvalue editpadding noborder">
        <input type="checkbox" name="exstats" value="exstats" onchange="javascript:fieldstat(9, this.checked); return false;" <?php  if (strpos($sessionstats, "9") !== FALSE) echo "checked"; ?> /> Show unfinished only&nbsp;&nbsp;
        <input type="checkbox" name="lastrevs" value="lastrevs" onchange="javascript:showlastrevs('1112', this.checked); return false;" <?php if (!empty($showlastrevs)) { echo "checked"; } ?> /> Show MRF2011 changes
    </div>
</div>
</div>