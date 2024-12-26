<?php

/**
 * @author Chris Overfield
 * @copyright 2010
 */

    $trans_chars = array("Ā" => "A","ā" => "a","Ă" => "A","ă" => "a","Ą" => "A","ą" => "a","Ć" => "C","ć" => "c","Ĉ" => "C","ĉ" => "c","Ċ" => "C","ċ" => "c","Č" => "C","č" => "c","Ď" => "D","ď" => "d","Đ" => "D",
                         "đ" => "d","Ē" => "E","ē" => "e","Ĕ" => "E","ĕ" => "e","Ė" => "E","ė" => "e","Ę" => "E","ę" => "e","Ě" => "E","ě" => "e","Ĝ" => "G","ĝ" => "g","Ğ" => "G","ğ" => "g","Ġ" => "G","ġ" => "g",
                         "Ģ" => "G","ģ" => "g","Ĥ" => "H","ĥ" => "h","Ħ" => "H","ħ" => "h","Ĩ" => "I","ĩ" => "i","Ī" => "I","ī" => "i","Ĭ" => "I","ĭ" => "i","Į" => "I","į" => "i","İ" => "I","ı" => "i","Ĵ" => "J",
                         "ĵ" => "j","Ķ" => "K","ķ" => "k","ĸ" => "k","Ĺ" => "L","ĺ" => "l","Ļ" => "L","ļ" => "l","Ľ" => "L","ľ" => "l","Ŀ" => "L","ŀ" => "l","Ł" => "L","ł" => "l","Ń" => "N","ń" => "n","Ņ" => "N",
                         "ņ" => "n","Ň" => "N","ň" => "n","ŉ" => "n","Ŋ" => "N","ŋ" => "n","Ō" => "O","ō" => "o","Ŏ" => "O","ŏ" => "o","Ő" => "O","ő" => "o","Œ" => "OE","œ" => "oe","Ŕ" => "R","ŕ" => "r","Ŗ" => "R",
                         "ŗ" => "r","Ř" => "R","ř" => "r","Ś" => "S","ś" => "s","Ŝ" => "S","ŝ" => "s","Ş" => "S","ş" => "s","Š" => "S","š" => "s","Ţ" => "T","ţ" => "t","Ť" => "T","ť" => "t","Ŧ" => "T","ŧ" => "t",
                         "Ũ" => "U","ũ" => "u","Ū" => "U","ū" => "u","Ŭ" => "U","ŭ" => "u","Ů" => "U","ů" => "u","Ű" => "U","ű" => "u","Ų" => "U","ų" => "u","Ŵ" => "W","ŵ" => "w","Ŷ" => "Y","ŷ" => "y","Ÿ" => "Y",
                         "Ź" => "Z","ź" => "z","Ż" => "Z","ż" => "z","Ž" => "Z","ž" => "z","ſ" => "s","ƀ" => "b","Ɓ" => "B","Ƃ" => "B","ƃ" => "b","Ƅ" => "B","ƅ" => "b","Ɔ" => "O","Ƈ" => "C","ƈ" => "c","Ɖ" => "D",
                         "Ɗ" => "D","Ƒ" => "F","ƒ" => "f","Ɠ" => "G","Ɩ" => "I","Ɨ" => "i","Ƙ" => "K","ƙ" => "k","ƚ" => "l","Ɲ" => "N","ƞ" => "n","Ɵ" => "O","Ơ" => "O","ơ" => "o","Ƣ" => "OI","ƣ" => "oi","Ƥ" => "P",
                         "ƥ" => "p","Ʀ" => "yr","ƫ" => "t","Ƭ" => "T","ƭ" => "t","Ʈ" => "T","Ư" => "U","ư" => "u","Ƴ" => "Y","ƴ" => "y","Ƶ" => "Z","ƶ" => "Z","Ǎ" => "A","ǎ" => "a","Ǐ" => "I","ǐ" => "i","Ǒ" => "O",
                         "ǒ" => "o","Ǔ" => "U","ǔ" => "u","Ǖ" => "U","ǖ" => "u","Ǘ" => "U","ǘ" => "u","Ǚ" => "U","ǚ" => "u","Ǜ" => "U","ǜ" => "u","ǝ" => "e","Ǟ" => "A","ǟ" => "a","Ǡ" => "A","ǡ" => "a","Ǣ" => "AE",
                         "ǣ" => "ae","Ǥ" => "G","ǥ" => "g","Ǧ" => "G","ǧ" => "g","Ǩ" => "K","ǩ" => "k","Ǫ" => "O","ǫ" => "o","Ǭ" => "O","ǭ" => "o","Ǵ" => "G","ǵ" => "g","Ƕ" => "H","Ǹ" => "N","ǹ" => "n","Ǻ" => "A",
                         "ǻ" => "a","Ǽ" => "AE","ǽ" => "ae","Ǿ" => "O","ǿ" => "o","Ȁ" => "A","ȁ" => "a","Ȃ" => "A","ȃ" => "a","Ȅ" => "E","ȅ" => "e","Ȇ" => "E","ȇ" => "e","Ȉ" => "I","ȉ" => "i","Ȋ" => "I","ȋ" => "i",
                         "Ȍ" => "O","ȍ" => "o","Ȏ" => "o","ȏ" => "o","Ȑ" => "r","ȑ" => "r","Ȓ" => "R","ȓ" => "r","Ȕ" => "U","ȕ" => "u","Ȗ" => "U","ȗ" => "u","Ș" => "S","ș" => "s","Ț" => "T","ț" => "t","Ȟ" => "H",
                         "ȟ" => "h","Ƞ" => "N","Ȥ" => "Z","ȥ" => "z","Ȧ" => "A","ȧ" => "a","Ȩ" => "E","ȩ" => "e","Ȫ" => "O","ȫ" => "o","Ȭ" => "O","ȭ" => "o","Ȯ" => "O","ȯ" => "o","Ȱ" => "O","ȱ" => "o","Ȳ" => "Y",
                         "ȳ" => "y","ȴ" => "l","ȵ" => "n","ȶ" => "t","ȷ" => "j","ȸ" => "db","ȹ" => "qp","Ⱥ" => "A","Ȼ" => "C","ȼ" =>  "c","Ƚ" => "l","Ⱦ" => "T","ȿ" => "s","ɀ" => "z","ɐ" => "a","ɓ" => "b","ɔ" => "o",
                         "ɕ" => "c","ɖ" => "d","ɗ" => "d","ɘ" => "e","ə" => "e","ɚ" => "e","ɛ" => "e","ɜ" => "e","ɝ" => "e","ɞ" => "e","ɟ" => "j","ɠ" => "g","ɡ" => "g","ɢ" => "g","ɦ" => "h","ɧ" => "h","ɨ" => "i",
                         "ɩ" => "i","ɪ" => "i","ɫ" => "l","ɬ" => "l","ɭ" => "l","ɲ" => "n","ɳ" => "n","ɴ" => "n","ɵ" => "o","ɶ" => "oe","ɾ" => "r","ʀ" => "R","ʂ" => "s","ʈ" => "t","ʉ" => "u","ʋ" => "v","ʏ" => "y",
                         "ʐ" => "z","ʑ" => "z","ʗ" => "C","ʘ" => "O","ʙ" => "b","ʚ" => "e","ʛ" => "g","ʜ" => "h","ʝ" => "j","ʟ" => "l","ʠ" => "q","ᵫ" => "ue","ᵬ" => "b","ᵭ" => "d","ᵮ" => "f","ᵯ" => "m","ᵰ" => "n",
                         "ᵱ" => "p","ᵲ" => "r","ᵵ" => "t","ᵶ" => "z","ᵻ" => "I","ᵽ" => "p","ᵾ" => "U","ᶀ" => "b","ᶁ" => "d","ᶂ" => "f","ᶃ" => "g","ᶄ" => "k","ᶅ" => "l","ᶆ" => "m","ᶇ" => "n","ᶈ" => "p","ᶉ" => "r",
                         "ᶊ" => "s","ᶌ" => "v","ᶍ" => "x","ᶎ" => "z","ᶏ" => "a","ᶑ" => "d","ᶒ" => "e","ᶖ" => "i","Ḁ" => "A","ḁ" => "a","Ḃ" => "B","ḃ" => "b","Ḅ" => "B","ḅ" => "b","Ḇ" => "B","ḇ" => "b","Ḉ" => "C",
                         "ḉ" => "c","Ḋ" => "D","ḋ" => "d","Ḍ" => "D","ḍ" => "d","Ḏ" => "D","ḏ" => "d","Ḑ" => "D","ḑ" => "d","Ḓ" => "D","ḓ" => "d","Ḕ" => "E","ḕ" => "e","Ḗ" => "E","ḗ" => "e","Ḙ" => "E","ḙ" => "e",
                         "Ḛ" => "E","ḛ" => "e","Ḝ" => "E","ḝ" => "e","Ḟ" => "F","ḟ" => "f","Ḡ" => "G","ḡ" => "g","Ḣ" => "H","ḣ" => "h","Ḥ" => "H","ḥ" => "h","Ḧ" => "H","ḧ" => "h","Ḩ" => "H","ḩ" => "h","Ḫ" => "H",
                         "ḫ" => "h","Ḭ" => "I","ḭ" => "i","Ḯ" => "I","ḯ" => "i","Ḱ" => "K","ḱ" => "k","Ḳ" => "K","ḳ" => "k","Ḵ" => "K","ḵ" => "k","Ḷ" => "L","ḷ" => "l","Ḹ" => "L","ḹ" => "l","Ḻ" => "L","ḻ" => "l",
                         "Ḽ" => "L","ḽ" => "l","Ḿ" => "M","ḿ" => "m","Ṁ" => "M","ṁ" => "m","Ṃ" => "M","ṃ" => "m","Ṅ" => "N","ṅ" => "n","Ṇ" => "N","ṇ" => "n","Ṉ" => "N","ṉ" => "n","Ṋ" => "N","ṋ" => "n","Ṍ" => "O",
                         "ṍ" => "o","Ṏ" => "O","ṏ" => "o","Ṑ" => "O","ṑ" => "o","Ṓ" => "O","ṓ" => "o","Ṕ" => "P","ṕ" => "p","Ṗ" => "P","ṗ" => "p","Ṙ" => "R","ṙ" => "r","Ṛ" => "R","ṛ" => "r","Ṝ" => "R","ṝ" => "r",
                         "Ṟ" => "R","ṟ" => "r","Ṡ" => "S","ṡ" => "s","Ṣ" => "S","ṣ" => "s","Ṥ" => "S","ṥ" => "s","Ṧ" => "S","ṧ" => "s","Ṩ" => "S","ṩ" => "s","Ṫ" => "T","ṫ" => "t","Ṭ" => "T","ṭ" => "t","Ṯ" => "T",
                         "ṯ" => "t","Ṱ" => "T","ṱ" => "t","Ṳ" => "U","ṳ" => "u","Ṵ" => "U","ṵ" => "u","Ṷ" => "U","ṷ" => "u","Ṹ" => "U","ṹ" => "u","Ṻ" => "U","ṻ" => "u","Ṽ" => "V","ṽ" => "v","Ṿ" => "V","ṿ" => "v",
                         "Ẁ" => "W","ẁ" => "w","Ẃ" => "W","ẃ" => "w","Ẅ" => "W","ẅ" => "w","Ẇ" => "W","ẇ" => "w","Ẉ" => "W","ẉ" => "w","Ẋ" => "X","ẋ" => "x","Ẍ" => "X","ẍ" => "x","Ẏ" => "Y","ẏ" => "y","Ẑ" => "Z",
                         "ẑ" => "z","Ẓ" => "Z","ẓ" => "z","Ẕ" => "Z","ẕ" => "z","ẖ" => "h","ẗ" => "t","ẘ" => "w","ẙ" => "y","ẚ" => "a","Ạ" => "A","ạ" => "a","Ả" => "A","ả" => "a","Ấ" => "A","ấ" => "a","Ầ" => "A",
                         "ầ" => "a","Ẩ" => "A","ẩ" => "a","Ẫ" => "A","ẫ" => "a","Ậ" => "A","ậ" => "a","Ắ" => "A","ắ" => "a","Ằ" => "A","ằ" => "a","Ẳ" => "A","ẳ" => "a","Ẵ" => "A","ẵ" => "a","Ặ" => "A","ặ" => "a",
                         "Ẹ" => "E","ẹ" => "e","Ẻ" => "E","ẻ" => "e","Ẽ" => "E","ẽ" => "e","Ế" => "E","ế" => "e","Ề" => "E","ề" => "e","Ể" => "E","ể" => "e","Ễ" => "E","ễ" => "e","Ệ" => "E","ệ" => "e","Ỉ" => "I",
                         "ỉ" => "i","Ị" => "I","ị" => "i","Ọ" => "O","ọ" => "o","Ỏ" => "O","ỏ" => "o","Ố" => "O","ố" => "o","Ồ" => "O","ồ" => "o","Ổ" => "O","ổ" => "o","Ỗ" => "O","ỗ" => "o","Ộ" => "O","ộ" => "o",
                         "Ớ" => "O","ớ" => "o","Ờ" => "O","ờ" => "o","Ở" => "O","ở" => "o","Ỡ" => "O","ỡ" => "o","Ợ" => "O","ợ" => "o","Ụ" => "U","ụ" => "u","Ủ" => "U","ủ" => "u","Ứ" => "U","ứ" => "u","Ừ" => "U",
                         "ừ" => "u","Ử" => "U","ử" => "u","Ữ" => "U","ữ" => "u","Ự" => "U","ự" => "u","Ỳ" => "Y","ỳ" => "y","Ỵ" => "Y","ỵ" => "y","Ỷ" => "Y","ỷ" => "y","Ỹ" => "Y","ỹ" => "y","ʻ" => "\'","‘" => "\'");

    class UDCRecord
    {
        var $id = 0;
        var $notation = "";
        var $encoded_tag = "";
        var $heading_type = "";
        var $special_aux_type = "";
        var $including = "";
        var $scope_note = "";
        var $app_note = "";
        var $info_note = "";       
        var $refs = array();
        var $examples = array();
        var $special_instructions = array();
        var $editorial_note = array();
        var $parallel_div_inst = "";
        var $index_id = "";
        var $parallel_deriv = "";
        var $intro_date;
        var $intro_source;
        var $intro_comment;
        var $cancel_date;
        var $cancel_source;
        var $cancel_comment;
        var $replaced_by = array();
        var $last_rev_date;
        var $last_rev_source;
        var $last_rev_fields = array();
        var $last_rev_comment;
        var $rev_history = array();
        var $rev_fields = array();    
        var $rev_history_fields = array();    
        var $index_terms = array();
        var $temp_work = array();
                       
        function ChangeQuotes($value, $remove_brackets)
        {
            $starttag = true;
            $finalstring = "";

            $containsbracket = false;
            $brackets_removed = 0;
                        
            for ($i=0; $i<strlen($value); $i++)
            {
                switch($value[$i])
                {
                    case '"':
                        if ($starttag)
                        {
                            $finalstring .= "«";
                            $starttag = false;
                        }
                        else
                        {
                            $finalstring .= "»";
                            $starttag = true;
                        }
                        break;
                    case "’":
                        $finalstring .= "'";
                        break;
                    case "<":
                        $containsbracket = true;
                        if (!$remove_brackets)
                        {
                            $finalstring .= "&lt;";                            
                        }
                        else
                        {
                            $brackets_removed++;
                        }
                        break;
                    case ">":
                        $containsbracket = true;
                        if (!$remove_brackets)
                        {
                            $finalstring .= "&gt;";                            
                        }
                        else
                        {
                            $brackets_removed++;
                        }
                        
                        break;
                    default:
                        $finalstring .= $value[$i];
                }
            }
            
//            if ($containsbracket)
//            {
//                echo "Bracket: [" . $value . "] is now [" . $finalstring . "]<br>\n";
//                echo "Brackets removed: " . $brackets_removed . "<br>\n"; 
//            }
            
            return $finalstring;
        }
        
        function StripField($field, &$tchars)
        {           
            for ($i=0; $i<strlen($field); $i++)
            {
                if (ord(substr($field, $i, 1)) > 127)
                {
                    foreach ($tchars as $key => $value)
                    {
                        $field = str_replace($key, $value, $field); 
                    }
                    break;
                }
            }
            
            return $field;
        }
        
        function OutputContents(&$tagarray, &$tchars)
        {
            $comparisons = array();
            
            $delims = false;
            if (array_key_exists("delims", $tagarray))
            {
                $delims = true;
            }
            
            echo "$$$<br>\n";
            //echo "Output: " . $this->notation . "<br>\n";
            foreach($tagarray as $tag => $taglabel)
            {
                switch($tag)
                {
                    case "001":
                        echo $taglabel .  " " . $this->ChangeQuotes($this->notation, false) . "<br>\n";
                        break;
                    case "002":
                        echo $taglabel . " " . $this->heading_type . "<br>\n";
                        break;
                    case "003":
                        if ($this->special_aux_type != "")
                        {
                            echo $taglabel . " " . $this->special_aux_type . "<br>\n";
                        }
                        break;
                    case "010":
                        if ($this->parallel_deriv != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->parallel_deriv, false) . "<br>\n";
                        }
                        break;
                    case "011":
                        if ($this->parallel_div_inst != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->parallel_div_inst, false) . "<br>\n";
                        }
                        break;
                    case "100":
                        echo $taglabel . " " .  $this->ChangeQuotes($this->StripField($this->caption, $tchars)) . "<br>\n";
                        break;
                    case "105":
                        if ($this->including != "")
                        {
                            echo $taglabel . " " .  $this->ChangeQuotes($this->StripField($this->including, $tchars)) . "<br>\n";                        
                        }
                        break;
                    case "110":
                        if ($this->scope_note != "")
                        {
                            echo $taglabel . " " .  $this->ChangeQuotes($this->StripField($this->scope_note, $tchars)) . "<br>\n";                        
                        }
                        break;
                    case "111":
                        if ($this->app_note != "")
                        {
                            echo $taglabel . " " .  $this->ChangeQuotes($this->StripField($this->app_note, $tchars)) . "<br>\n";
                        }
                        break;
                    case "114":
                        if ($this->info_note != "")
                        {
                            echo $taglabel . " " .  $this->ChangeQuotes($this->StripField($this->info_note, $tchars)) . "<br>\n";                                                        
                        }
                        break;
                    case "115":
                        $exstring = "";

                        foreach ($this->examples as $example)
                        {                           
                            $example_tags = explode(";", $taglabel);
                            $ex = explode("#", $example);
                            
                            switch($ex[0])
                            {
                                case "a":
                                    $exstring .= $example_tags[0] . " " . $this->ChangeQuotes($this->StripField($ex[1], $tchars), false) . "<br>\n";
                                    break;
                                case "b":
                                    $exstring .= $example_tags[1] . " " . $this->ChangeQuotes($this->StripField($ex[1], $tchars), false) . "<br>\n";
                                    break;
                                case "c":
                                default:
                                    $exstring .= $example_tags[2] . " " . $this->ChangeQuotes($this->StripField($ex[1], $tchars), false) . "<br>\n";
                                    break;
                            }
                        }
                        
                        if ($exstring != "")
                        {
                            echo $exstring;
                        }
                        break;
                    case "125":
                        foreach ($this->refs as $ref)
                        {
                            $refvalues = explode("#", $ref);
                            echo $taglabel . " " .  $this->ChangeQuotes($this->StripField($refvalues[0], $tchars), false) . "<br>\n";        
                        }
                        break;
                    case "901":
                        if ($this->intro_date != "")
                        {
                            echo $taglabel . " " . $this->intro_date . "<br>\n";
                        }
                        break;
                    case "902":
                        echo $taglabel . " " . $this->id . "<br>\n";
                        break;
                    case "903":
                        if ($this->intro_source != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->intro_source, false) . "<br>\n";
                        }
                        break;
                    case "904":
                        if ($this->intro_comment != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->intro_comment, false) . "<br>\n";
                        }
                        break;
                    case "911":
                        if ($this->cancel_date != "")
                        {
                            echo $taglabel . " " . $this->cancel_date . "<br>\n";
                        }
                        break;
                    case "912":
                        if (count($this->replaced_by) > 0)
                        {
                            foreach($this->replaced_by as $replaced_by)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($replaced_by, false) . "<br>\n";
                            }
                        }
                        break;
                    case "913":
                        if ($this->cancel_source != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->cancel_source, false) . "<br>\n";
                        }
                        break;
                    case "914":
                        if ($this->cancel_comment != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->cancel_comment, false) . "<br>\n";
                        }
                        break;
                    case "921":
                        if ($this->last_rev_date != "")
                        {
                            echo $taglabel . " " . $this->last_rev_date . "<br>\n";
                        }
                        break;
                    case "922":
                        if (count($this->last_rev_fields) > 0)
                        {
                            foreach($this->last_rev_fields as $revision_field)
                            {
                                echo $taglabel . " " . $revision_field . "<br>\n";
                            }
                        }
                        break;
                    case "923":
                        if ($this->last_rev_source != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->last_rev_source, false) . "<br>\n";
                        }
                        break;
                    case "924":
                        if ($this->last_rev_comment != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->last_rev_comment, false) . "<br>\n";
                        }
                        break;
                    case "925":
                        if (count($this->rev_history) > 0)
                        {
                            //var_dump($this->rev_history);
                            //echo "<br>\n";
                            $sortarray = array();
                            foreach($this->rev_history as $revision_date => $revision)
                            {
                                if (substr($revision_date, 0, 1) == "9")
                                {
                                    $sortarray["19" . $revision_date] = "19" . $revision_date;
                                }
                                else
                                {
                                    $sortarray["20" . $revision_date] = "20" . $revision_date;                                    
                                }
                            }
                                                       
                            sort($sortarray, SORT_NUMERIC);
                            //var_dump($sortarray);
                            
                            foreach($sortarray as $rdate => $revision_date)
                            {
                                $value = "";
                                $revision_date = substr($revision_date, 2);
                                if (array_key_exists($revision_date, $this->rev_history))
                                {
                                    $revision = $this->rev_history[$revision_date];
                                }
                               // echo "::::" . $revision_date . "=" . $revision . "<br>\n";
                                $revisions = explode("#", $revision, 2);
                                $value .= "^d" . $revision_date;
                                if (array_key_exists($revision_date, $this->rev_history_fields))
                                {
                                    $value .= "^f" . $this->rev_history_fields[$revision_date];
                                }
                                if (count($revisions) > 0 && trim($revisions[0]) != "")
                                {
                                    $value .= "^s" . $revisions[0];
                                }
                                if (count($revisions) > 1 && trim($revisions[1]) != "")
                                {
                                    $value .= "^n" . $revisions[1];
                                }
                                echo $taglabel . " " . $this->ChangeQuotes($value, false) . "<br>\n";
                            }
                        }
                        break;
                    case "951":
                        if (count($this->index_terms) > 0)
                        {
                            foreach($this->index_terms as $term)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($this->StripField($term, $tchars), false) . "<br>\n";
                            } 
                        }
                        break;
                    case "952":
                        if (count($this->special_instructions) > 0)
                        {
                            foreach($this->special_instructions as $value)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($value, false) . "<br>\n";
                            }
                        }
                        break;
                    case "955":
                        if (count($this->editorial_note) > 0)
                        {
                            foreach($this->editorial_note as $value)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($value, false) . "<br>\n";
                            }
                        }
                        break;
                    case "957":
                        if ($this->encoded_tag != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->StripField($this->encoded_tag, $tchars), false) . "<br>\n";
                        }
                        break;
                    case "999":
                        if (count($this->temp_work) > 0)
                        {
                            foreach($this->temp_work as $value)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($value, false) . "<br>\n";
                            }
                        }
                        break;
                 }
            }
            
//            foreach($comparisons as $comparison)
//            {
//                echo $comparison . "<br>\n";
//            }
            //echo "#<br>\n";
        }   
    
        function OutputExtendedContents(&$tagarray, &$tchars)
        {
            $comparisons = array();
            
            $delims = false;
            if (array_key_exists("delims", $tagarray))
            {
                $delims = true;
            }
            
            //echo "$$$<br>\n";
            //echo "Output: " . $this->notation . "<br>\n";
            /*
            foreach($tagarray as $tag => $taglabel)
            {
                switch($tag)
                {
                    case "001":
                        echo $taglabel .  " " . $this->ChangeQuotes($this->notation) . "<br>\n";
                        break;
                    case "002":
                        echo $taglabel . " " . $this->heading_type . "<br>\n";
                        break;
                    case "003":
                        if ($this->special_aux_type != "")
                        {
                            echo $taglabel . " " . $this->special_aux_type . "<br>\n";
                        }
                        break;
                    case "010":
                        if ($this->parallel_deriv != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->parallel_deriv) . "<br>\n";
                        }
                        break;
                    case "011":
                        if ($this->parallel_div_inst != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->parallel_div_inst) . "<br>\n";
                        }
                        break;
                    case "100":
                        echo $taglabel . " " . $this->StripField($this->caption, $tchars) . "<br>\n";
                        break;
                    case "105":
                        if ($this->including != "")
                        {
                            echo $taglabel . " " . $this->StripField($this->including, $tchars) . "<br>\n";                        
                        }
                        break;
                    case "110":
                        if ($this->scope_note != "")
                        {
                            echo $taglabel . " " . $this->StripField($this->scope_note, $tchars) . "<br>\n";                        
                        }
                        break;
                    case "111":
                        if ($this->app_note != "")
                        {
                            echo $taglabel . " " . $this->StripField($this->app_note, $tchars) . "<br>\n";
                        }
                        break;
                    case "114":
                        if ($this->info_note != "")
                        {
                            echo $taglabel . " " . $this->StripField($this->info_note, $tchars) . "<br>\n";                                                        
                        }
                        break;
                    case "115":
                        $exstring = "";

                        foreach ($this->examples as $example)
                        {                           
                            $example_tags = explode(";", $taglabel);
                            $ex = explode("#", $example);
                            
                            switch($ex[0])
                            {
                                case "a":
                                    $exstring = $example_tags[0] . " " . $this->ChangeQuotes($this->StripField($ex[1], $tchars));
                                    break;
                                case "b":
                                    $exstring = $example_tags[1] . " " . $this->ChangeQuotes($this->StripField($ex[1], $tchars));
                                    break;
                                case "c":
                                default:
                                    $exstring = $example_tags[2] . " " . $this->ChangeQuotes($this->StripField($ex[1], $tchars));
                                    break;
                            }
                        }
                        
                        if ($exstring != "")
                        {
                            echo $exstring . "<br>\n";
                        }
                        break;
                    case "125":
                        foreach ($this->refs as $ref)
                        {
                            echo $taglabel . " " .  $this->ChangeQuotes($this->StripField($ref, $tchars)) . "<br>\n";        
                        }
                        break;
                    case "901":
                        if ($this->intro_date != "")
                        {
                            echo $taglabel . " " . $this->intro_date . "<br>\n";
                        }
                        break;
                    case "902":
                        echo $taglabel . " " . $this->id . "<br>\n";
                        break;
                    case "903":
                        if ($this->intro_source != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->intro_source) . "<br>\n";
                        }
                        break;
                    case "904":
                        if ($this->intro_comment != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->intro_comment) . "<br>\n";
                        }
                        break;
                    case "911":
                        if ($this->cancel_date != "")
                        {
                            echo $taglabel . " " . $this->cancel_date . "<br>\n";
                        }
                        break;
                    case "912":
                        if ($this->replaced_by != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->replaced_by) . "<br>\n";
                        }
                        break;
                    case "913":
                        if ($this->cancel_source != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->cancel_source) . "<br>\n";
                        }
                        break;
                    case "914":
                        if ($this->cancel_comment != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->cancel_comment) . "<br>\n";
                        }
                        break;
                    case "921":
                        if ($this->last_rev_date != "")
                        {
                            echo $taglabel . " " . $this->last_rev_date . "<br>\n";
                        }
                        break;
                    case "922":
                        if (count($this->last_rev_fields) > 0)
                        {
                            foreach($this->last_rev_fields as $revision_field)
                            {
                                echo $taglabel . " " . $revision_field . "<br>\n";
                            }
                        }
                        break;
                    case "923":
                        if ($this->last_rev_source != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->last_rev_source) . "<br>\n";
                        }
                        break;
                    case "924":
                        if ($this->last_rev_comment != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->last_rev_comment) . "<br>\n";
                        }
                        break;
                    case "925":
                        if (count($this->rev_history) > 0)
                        {
                            //var_dump($this->rev_history);
                            //echo "<br>\n";
                            $sortarray = array();
                            foreach($this->rev_history as $revision_date => $revision)
                            {
                                if (substr($revision_date, 0, 1) == "9")
                                {
                                    $sortarray["19" . $revision_date] = "19" . $revision_date;
                                }
                                else
                                {
                                    $sortarray["20" . $revision_date] = "20" . $revision_date;                                    
                                }
                            }
                                                       
                            sort($sortarray, SORT_NUMERIC);
                            //var_dump($sortarray);
                            
                            foreach($sortarray as $rdate => $revision_date)
                            {
                                $value = "";
                                $revision_date = substr($revision_date, 2);
                                if (array_key_exists($revision_date, $this->rev_history))
                                {
                                    $revision = $this->rev_history[$revision_date];
                                }
                               // echo "::::" . $revision_date . "=" . $revision . "<br>\n";
                                $revisions = explode("#", $revision, 2);
                                $value .= "^d" . $revision_date;
                                if (array_key_exists($revision_date, $this->rev_history_fields))
                                {
                                    $value .= "^f" . $this->rev_history_fields[$revision_date];
                                }
                                if (count($revisions) > 0 && trim($revisions[0]) != "")
                                {
                                    $value .= "^s" . $revisions[0];
                                }
                                if (count($revisions) > 1 && trim($revisions[1]) != "")
                                {
                                    $value .= "^n" . $revisions[1];
                                }
                                echo $taglabel . " " . $this->ChangeQuotes($value) . "<br>\n";
                            }
                        }
                        break;
                    case "951":
                        if (count($this->index_terms) > 0)
                        {
                            foreach($this->index_terms as $term)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($this->StripField($term, $tchars)) . "<br>\n";
                            } 
                        }
                        break;
                    case "952":
                        if (count($this->special_instructions) > 0)
                        {
                            foreach($this->special_instructions as $value)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($value) . "<br>\n";
                            }
                        }
                        break;
                    case "955":
                        if (count($this->editorial_note) > 0)
                        {
                            foreach($this->editorial_note as $value)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($value) . "<br>\n";
                            }
                        }
                        break;
                    case "957":
                        if ($this->encoded_tag != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->StripField($this->encoded_tag, $tchars)) . "<br>\n";
                        }
                        break;
                    case "999":
                        if (count($this->temp_work) > 0)
                        {
                            foreach($this->temp_work as $value)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($value) . "<br>\n";
                            }
                        }
                        break;
                 }
            }
            */

            foreach($tagarray as $tag => $taglabel)
            {
                switch($tag)
                {
                    case "001":
                        echo $taglabel .  " " . $this->notation . "<br>\n";
                        break;
                    case "002":
                        echo $taglabel . " " . $this->heading_type . "<br>\n";
                        break;
                    case "003":
                        if ($this->special_aux_type != "")
                        {
                            echo $taglabel . " " . $this->special_aux_type . "<br>\n";
                        }
                        break;
                    case "010":
                        if ($this->parallel_deriv != "")
                        {
                            echo $taglabel . " " . $this->parallel_deriv . "<br>\n";
                        }
                        break;
                    case "011":
                        if ($this->parallel_div_inst != "")
                        {
                            echo $taglabel . " " . $this->parallel_div_inst . "<br>\n";
                        }
                        break;
                    case "100":
                        echo $taglabel . " " . $this->ChangeQuotes($this->caption, true) . "<br>\n";
                        break;
                    case "105":
                        if ($this->including != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->including, true) . "<br>\n";                        
                        }
                        break;
                    case "110":
                        if ($this->scope_note != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->scope_note, true) . "<br>\n";                        
                        }
                        break;
                    case "111":
                        if ($this->app_note != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->app_note, true) . "<br>\n";
                        }
                        break;
                    case "114":
                        if ($include_114 && $this->info_note != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->info_note, true) . "<br>\n";                                                        
                        }
                        break;
                    case "115":
                        foreach ($this->examples as $example)
                        {
                            $example_tags = explode(";", $taglabel);
                            $ex = explode("#", $example);
                            
                            $shortexample = false;
                            if (array_key_exists("shortexamples", $tagarray))
                            {
                                $shortexample = true;
                            }
                            
                            switch($ex[0])
                            {
                                case "a":
                                    echo $example_tags[0] . " " . $this->ChangeQuotes($ex[1], true) . "<br>\n";
                                    break;
                                case "b":
                                    echo $example_tags[1] . " " . $this->ChangeQuotes($ex[1], true) . "<br>\n";
                                    break;
                                case "c":
                                default:
                                    echo $example_tags[2] . " " . $this->ChangeQuotes($ex[1], true) . "<br>\n";
                                    break;
                            }
                        }
                        break;
                    case "125":
                        foreach ($this->refs as $ref)
                        {
                            $refvalues = explode("#", $ref);
                            echo $taglabel . " " .  $this->ChangeQuotes($refvalues[0] . " " . $refvalues[1], true) . "<br>\n";   
                        }
                        break;
                    case "903":
                        if ($this->intro_source != "")
                        {
                            echo $taglabel . " " . $this->ChangeQuotes($this->intro_source, true) . "<br>\n";
                        }
                        break;
                    case "912":
                        if (count($this->replaced_by) > 0)
                        {
                            foreach($this->replaced_by as $replaced_by)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($replaced_by, false) . "<br>\n";
                            }
                        }
                        break;
                    case "922":
                        if (count($this->last_rev_fields) > 0)
                        {
                            foreach($this->last_rev_fields as $revision_field)
                            {
                                echo $taglabel . " " . $revision_field . "<br>\n";
                            }
                        }
                        break;
                    case "952":
                        if (count($this->special_instructions) > 0)
                        {
                            foreach($this->special_instructions as $value)
                            {
                                echo $taglabel . " " . $this->ChangeQuotes($value, true) . "<br>\n";
                            }
                        }
                        break;
                 }
            }
            
//            foreach($comparisons as $comparison)
//            {
//                echo $comparison . "<br>\n";
//            }
            echo "<br>\n";
        }
    }

    function ProcessTags($tags, &$tagsarray)
    {
        $taglines = explode("\n", $tags);
        foreach($taglines as $tagline)
        {
            $tagline = trim($tagline);
            if ($tagline == "")
                continue;
                
            //echo $tagline . "<br>\n";
            
            $values = explode("=", $tagline);
            if (count($values) == 2)
            {
                //echo "[" . $values[0] . "] = [" . $values[1] . "]<br>\n";
                $key = trim($values[0]); 
                $value = trim($values[1]);
                //echo "Tag: [" . $key . "] = [" . $value . "]<br>\n";
                $tagsarray[$key] = $value;
            }
            else
            {
                echo "Bad tag: " . $tagline . "<br\n";
            }
        }
    }

    function StartTime(&$starttime)
    {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;                
    }

    function EndTime($operation, $starttime)
    {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        
        $totaltime = ($endtime - $starttime);
        echo $operation . "<br>\n";
        echo "Executed in " .$totaltime. " seconds.<br>\n";
        flush();
    }
            
    function FetchLanguageField(&$dbc, $lang_field_id, &$recordarray, &$errors, $heading_type, $min_rec_no, $max_rec_no, $mrf_format)
    {
        StartTime($starttime);
        $sql =  "select c.classmark_id, f.field_id, f.description from classmarks c " .
                "join language_fields f on c.classmark_id = f.classmark_id and f.field_id = " . $lang_field_id . " " . 
                "join tmp_export e on c.classmark_id = e.classmark_id " .
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no;
                
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
        
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $field_id = $row[1];
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    switch($field_id)
                    {
                        case 1:
                            $rec->caption = (($mrf_format) ? "^e" : "") . $row[2];
                            break;
                        case 4:
                            $rec->including = (($mrf_format) ? "^e" : "") . $row[2];
                            break;
                        case 5:
                            $rec->scope_note = (($mrf_format) ? "^e" : "") . $row[2];
                            break;
                        case 6:
                            $rec->app_note = (($mrf_format) ? "^e" : "") . $row[2];
                            break;
                        case 10:
                            $rec->info_note = (($mrf_format) ? "^e" : "") . $row[2];
                            break;
                        default:
                            break;
                    }
                    //$recordarray[$rec->id] =  $rec;
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for language_field " . $field_id);
                }
    		}
    		mysql_free_result($res);
            EndTime("Language fields " . $lang_field_id, $starttime);
    	}        
    }
    
    function FetchRecord(&$dbc, &$recordarray, &$errors, $heading_type, $specialauxarray, $min_rec_no, $max_rec_no, $mrf_format)
    {
        $res = false;
        
        $starttime = 0.0;
        
        StartTime($starttime);       
        $sql =  "select c.classmark_id, c.classmark_tag, c.parallel_deriv, h.heading_type, e.entry_id, c.classmark_enc_tag from classmarks c " .
                "join headingtypes h on c.heading_type = h.heading_type_id ".
                "join tmp_export e on c.classmark_id = e.classmark_id " .
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no;

    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
        
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $rec = new UDCRecord();
                $rec->id = $row[0];
                $rec->notation = $rec->ChangeQuotes($row[1]);
                $rec->parallel_deriv = $row[2];
                $rec->heading_type = $row[3];
                $rec->index_id = $row[4];
                $rec->encoded_tag = $row[5];
                $recordarray[$rec->id] =  $rec;
    		}
    		mysql_free_result($res);
            EndTime("Initial fetch", $starttime);
    	}
    
        StartTime($starttime);
        $sql =  "select c.classmark_id, s.special_aux_type from classmarks c " .
                "join tmp_export e on c.classmark_id = e.classmark_id " .
                "left outer join classmark_aux_types s on c.classmark_id = s.classmark_id " .
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " ";   
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);

    	if ($res)
    	{
            StartTime($starttime);    	   
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    $aux_type = $row[1];
                    if ($aux_type > 0)
                    {
                        if (array_key_exists($aux_type, $specialauxarray))
                        {
                            $rec->special_aux_type = $specialauxarray[$aux_type];
                        }
                        else
                        {
                            array_push($errors, "Unknown aux_type [" . $aux_type . "] for record [" . $rec->notation . "] (" . $rec->id . ")");
                        }
                    }                    
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for language_field " . $field_id);
                }
    		}
    		mysql_free_result($res);
            EndTime("Special Aux Type", $starttime);            
    	}

        // Language fields (except EoC)
        FetchLanguageField(&$dbc, 1, &$recordarray, &$errors, $heading_type, $min_rec_no, $max_rec_no, $mrf_format);  
        FetchLanguageField(&$dbc, 4, &$recordarray, &$errors, $heading_type, $min_rec_no, $max_rec_no, $mrf_format);    
        FetchLanguageField(&$dbc, 5, &$recordarray, &$errors, $heading_type, $min_rec_no, $max_rec_no, $mrf_format);    
        FetchLanguageField(&$dbc, 6, &$recordarray, &$errors, $heading_type, $min_rec_no, $max_rec_no, $mrf_format);    
        FetchLanguageField(&$dbc, 10, &$recordarray, &$errors, $heading_type, $min_rec_no, $max_rec_no, $mrf_format);    

        // References
        StartTime($starttime);       
                       
        $sql =  "select r.classmark_id, r.notation, f.description, d.description " .
                "from classmark_refs r join classmarks c on c.classmark_tag = r.notation " .
                "join language_fields f on f.classmark_id = c.classmark_id and f.field_id = 1 " . 
                "join tmp_export e on r.classmark_id = e.classmark_id " .
                "left outer join classmark_refs_desc d on r.classmark_id = d.classmark_id " . 
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no;

//        $sql =  "select r.classmark_id, r.notation, f.description " .
//                "from classmark_refs r join classmarks c on r.notation = c.classmark_tag and c.active = 'Y' ".
//                "join language_fields f on c.classmark_id = f.classmark_id and f.field_id = 1 " .
//                "join tmp_export e on c.classmark_id = e.classmark_id " .
//                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no;
                                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
       		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $notation = $row[1];
                $description  = $row[2];
                $annotation = $row[3];
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    if ($mrf_format)
                    {
                        $value = "^a" . $notation;
                        if ($annotation != "")
                        {
                            $value .= "^t" . $annotation;
                        }
                        array_push($rec->refs, $value);
                    }
                    else
                    {
                        array_push($rec->refs, trim($notation . "#" . $description));
                    }
                    //$recordarray[$rec->id] =  $rec;                    
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for reference " . $notation);
                }
            }
            
    		mysql_free_result($res);
            EndTime("References", $starttime);
    	}

        
        // Examples of combination
        StartTime($starttime);
        $sql =  "select x.classmark_id, c.classmark_tag, x.tag, x.field_type, f.description from example_classmarks x join classmarks c on x.classmark_id = c.classmark_id ".
                "join language_fields f on x.classmark_id = f.classmark_id and f.field_id = 2 and f.seq_no = x.seq_no " .
                "join tmp_export e on e.classmark_id = x.classmark_id " .
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " " .
                "order by e.entry_id, x.seq_no";   
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $classnotation = $row[1];
                $notation = $row[2];
                $type = $row[3];
                $description = $row[4];
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    switch($type)
                    {
                        case "a":
                            $example_notation = $classnotation . $notation;
                            if ($mrf_format)
                            {
                                array_push($rec->examples, $type . "#" . "^" . $type . $notation . "^d" .$description);
                            }
                            else
                            {
                                array_push($rec->examples, $type . "#" . $example_notation . " " .$description);    
                            }
                            break;
                        case "b":
                            $example_notation = $classnotation . ":" . $notation;
                            if ($mrf_format)
                            {
                                array_push($rec->examples, $type . "#" . "^" . $type . $notation . "^d" .$description);
                            }
                            else
                            {
                                array_push($rec->examples, $type . "#" . $example_notation . " " .$description);    
                            }
                            break;
                        case "c":
                        case "r":
                        default:
                            $example_notation = $notation;
                            if ($mrf_format)
                            {
                                array_push($rec->examples, $type . "#" . "^" . $type . $notation . "^d" .$description);
                            }
                            else
                            {
                                array_push($rec->examples, $type . "#" . $example_notation . " " .$description);    
                            }
                            break;
                    }
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for example " . $notation);
                }
            }       
    		mysql_free_result($res);
            EndTime("Examples", $starttime);            
    	}
        
        // Editorial Note and Instructions for Special Character Use 
        StartTime($starttime);
        $sql =  "select o.classmark_id, o.revision_field, o.annotation from other_annotations o join classmarks c on c.classmark_id = o.classmark_id " .
                "join tmp_export e on e.classmark_id = o.classmark_id " .
                "where o.revision_field in ('952', '955', '999') " .
                "and e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " ";
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $field = $row[1];
                $description = $row[2];
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    switch($field)
                    {
                        case "952":
                            array_push($rec->special_instructions, $description);
                            break;
                        case "955":
                            array_push($rec->editorial_note, $description);
                            break;
                        case "999":
                            array_push($rec->temp_work, $description);
                            break;
                        default:
                            break;
                    }
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for annotation " . $field);
                }
            }       
    		mysql_free_result($res);
            EndTime("Other Annotations", $starttime);            
    	}
    
        // Parallel Division Instructions
        StartTime($mtime);
        $sql =  "select c.classmark_id, c.classmark_tag, i.src_notation, i.target_notation, ia.annotation " .
                "from classmarks c join parallel_div_instructions i on c.classmark_id = i.classmark_id " .
                "join tmp_export e on e.classmark_id = c.classmark_id " .               
                "left outer join parallel_div_inst_annotations ia on c.classmark_id = ia.classmark_id " .
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " ";
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $classmark_tag = $row[1];
                $source = $row[2];
                $target = $row[3];
                $annotation = $row[4];
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    if ($mrf_format)
                    {
                        $line = "^a" . $source;
                        if ($target != "")
                        {
                            $line .= "^b" . $target;
                        }
                        if ($annotation != "")
                        {
                            $line .= "^t" . $annotation;
                        }
                    }
                    else
                    {
                        if ($target != "")
                        {
                            $line = $target . " divided as " . $source;
                        }
                        else
                        {
                            $line = $classmark_tag . " divided as " . $source;
                        }
                            
                        if ($annotation != "" && $annotation != "NULL")
                        {
                            $line .= " " . $annotation;
                        }
                    }
                    $rec->parallel_div_inst = $line;
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for parallel_div_inst " . $id);
                }
            }       
    		mysql_free_result($res);
            EndTime("Parallel Division Instructions", $starttime);
    	}
        
        // Audit history
        StartTime($mtime);
        $sql =  "select a.classmark_id, a.audit_date, a.audit_type, a.audit_source, a.audit_comment " .
                "from audit_history a join tmp_export e on e.classmark_id = a.classmark_id " . 
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " ";
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $audit_date     = $row[1];
                $audit_type     = $row[2];
                $audit_source   = $row[3];
                $audit_comment  = $row[4];
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    switch($audit_type)
                    {
                        case 'I':
                            $rec->intro_date = $audit_date;
                            $rec->intro_source = $audit_source;
                            $rec->intro_comment = $audit_comment;
                            break;
                        case 'C':
                            $rec->cancel_date = $audit_date;
                            $rec->cancel_source = $audit_source;
                            $rec->cancel_comment = $audit_comment;
                            break;
                        case 'R':
                            $rec->last_rev_date = $audit_date;
                            $rec->last_rev_source = $audit_source;
                            $rec->last_rev_comment = $audit_comment;
                            break;
                        default:
                            array_push($errors, "Unknown audit type [" . $audit_type . "] for record " . $id);
                            break;
                    }
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for audit_history ");
                }
            }       
    		mysql_free_result($res);
            EndTime("Audit History", $starttime);
    	}
        
        // Revision fields
        StartTime($mtime);
        $sql =  "select r.classmark_id, r.revision_date, r.revision_field " .
                "from revision_fields r join tmp_export e on r.classmark_id = e.classmark_id " . 
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " order by classmark_id, revision_date";
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $revision_date  = trim($row[1]);
                $revision_field = trim($row[2]);
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    array_push($rec->last_rev_fields, $revision_field);
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for revision fields ");
                }
            }       
    		mysql_free_result($res);
            EndTime("Revision Fields", $starttime);
    	}

        // Revision history
        StartTime($mtime);
        $sql =  "select r.classmark_id, r.revision_date, r.revision_source, r.revision_comment " .
                "from revision_history r join tmp_export e on e.classmark_id = r.classmark_id " . 
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " ";
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $revision_date     = $row[1];
                $revision_source   = $row[2];
                $revision_comment  = $row[3];
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    //echo $id . ": [" . $revision_date . "], [" . $revision_source . "], [" . $revision_comment . "]<br>\n";
                    $rec->rev_history[$revision_date] = $revision_source . "#" . $revision_comment;
                    //var_dump($rec->rev_history);
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for revision history ");
                }
            }       
    		mysql_free_result($res);
            EndTime("Revision History", $starttime);
    	}
        
        // Revision fields
        StartTime($mtime);
        $sql =  "select r.classmark_id, r.revision_date, r.revision_field " .
                "from revision_history_fields r join tmp_export e on r.classmark_id = e.classmark_id " . 
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " order by classmark_id, revision_date, sequence_no";
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $revision_date  = trim($row[1]);
                $revision_field = trim($row[2]);
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    if (array_key_exists($revision_date, $rec->rev_history_fields))
                    {
                        $value = $rec->rev_history_fields[$revision_date];
                        $value .= ";" . $revision_field; 
                        $rec->rev_history_fields[$revision_date] = $value;
                    }
                    else
                    {
                        $rec->rev_history_fields[$revision_date] = $revision_field; 
                    }
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for revision history fields");
                }
            }       
    		mysql_free_result($res);
            EndTime("Revision History Fields", $starttime);
    	}

        // Revision fields
        StartTime($mtime);
        $sql =  "select r.classmark_id, r.notation " .
                "from index_only_udc_notations r join tmp_export e on r.classmark_id = e.classmark_id " . 
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no;
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $notation  = trim($row[1]);
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    array_push($rec->index_terms, $notation);                    
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for index terms");
                }
            }       
    		mysql_free_result($res);
            EndTime("Index Terms", $starttime);
    	}
        
        // Replaced By
        StartTime($starttime);
        $sql =  "select r.classmark_id, r.notation from udc_replaced_by r " .
                "join tmp_export e on r.classmark_id = e.classmark_id " .
                "where e.entry_id > " . $min_rec_no . " and e.entry_id <= " . $max_rec_no . " ";
                        
    	$res = @mysql_query($sql, $dbc);
        EndTime($sql, $starttime);
    	if ($res)
    	{
            StartTime($starttime);
    		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    		{
                $id = $row[0];
                $replaced_by = $row[1];
                
                if (array_key_exists($id, $recordarray))
                {
                    $rec =& $recordarray[$id];
                    array_push($rec->replaced_by, $replaced_by);
                }
                else
                {
                    array_push($errors, "No record [" . $id . "] for replaced by " . $field);
                }
            }       
    		mysql_free_result($res);
            EndTime("Replaced By", $starttime);            
    	}
        
    }
  
  
    include_once('encodeexample.php');
     
    function EncodeNotations(&$dbc)
    {
        $tags = $_POST['tags'];
        $notations = explode("\n", $tags);
        
        foreach($notations as $notation)
        {
            $notation = trim($notation);
            if ($notation == "")
                continue;
                
            $starttime = 0.0;
            StartTime($starttime);
            $encoded = encodeExample($notation);
            $sql = "update classmarks set classmark_enc_tag = '". @mysql_real_escape_string($encoded) . "' where classmark_tag = '" . @mysql_real_escape_string($notation) . "' and active = 'Y'";
        	if (!@mysql_query($sql, $dbc))
            {
                echo "FAILED: " .$sql . "<br>\n";
            }
            else
            {
                echo "SUCCESS: ". $sql . "<br>\n";
            }
            EndTime($sql, $starttime);
        }                
    }
    
	require_once("DBConnectInfo.php");

	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");

    $action = $_POST['action'];
    $tagsarray = array();
    if (isset($_POST['tags']))
    {
        ProcessTags($_POST['tags'], $tagsarray);        
    }

    $specialauxarray = array();
    $sql =  "select s.type_id, s.type_type from special_aux_types s";
	$res = @mysql_query($sql, $dbc);
	if ($res)
	{
		while(($row = mysql_fetch_array($res, MYSQL_NUM)))
		{
            $key = $row[0];
            $specialauxarray[$key] = $row[1];
		}
		mysql_free_result($res);
	}
    
    // Basic classmark details
    $errors = array();
    $recordarray = array();
      
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n";
    echo "\"http://www.w3.org/TR/html4/loose.dtd\">\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "<title>E&C Exports</title>\n";
    echo "<link rel=\"stylesheet\" href=\"../reset.css\" type=\"text/css\" />\n";
    echo "<link rel=\"stylesheet\" href=\"../udc1000.css\" type=\"text/css\" />\n";
    echo "<link rel=\"StyleSheet\" href=\"dtree.css\" type=\"text/css\" />\n";
    echo "<script type=\"text/javascript\" src=\"dtree.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"udcdisplay_7.js\"></script>\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">\n";
    echo "</head>\n";
    echo "<body>\n";


    switch($action)
    {
        case 'encode':
            EncodeNotations($dbc);
            break;    
        case 'bsi':
            $min_rec_no = 0;
            if (isset($_POST['minrecno']))
                $min_rec_no = $_POST['minrecno'];
                
            $max_rec_no = "";
            if (isset($_POST['maxrecno']))
                $max_rec_no  = $_POST['maxrecno'];
        
            echo "Fetching records: " . $min_rec_no . " to " . $max_rec_no . "<br>\n";
            FetchRecord($dbc, $recordarray, $errors, $headtype, $specialauxarray, $min_rec_no, $max_rec_no, true);
            echo "Record count = " . count($recordarray) . " records fetched<br>\n";
            //flush();
            
            @mysql_close();
        
            echo "<br># ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>\n";
            
            // Now display the records
            foreach($recordarray as $rec)
            {
                $rec->OutputContents($tagsarray, $trans_chars);
            }
            
            echo "Complete<br>\n";
            break;
        case 'popext':
            $sql = "TRUNCATE TABLE tmp_export";
            echo $sql . "<br>\n";

            $res = @mysql_query($sql, $dbc);
            echo "Complete<br>\n";
            
            $sql =  "insert into tmp_export (classmark_id, hierarchy_code) select c.classmark_id, c.classmark_enc_tag " .
                    "from classmarks c where c.active = 'Y' order by c.classmark_enc_tag";
            echo $sql . "<br>\n";
            $res = @mysql_query($sql, $dbc);
            echo "Complete<br>\n";
            
            $sql =  "select count(*) from tmp_export";
            echo $sql . "<br>\n";
            $res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $rowcount = $row[0];
            	}
            	@mysql_free_result($res);
            }
            echo "Complete: " . $rowcount . " records retrieved<br>\n";
            break;
        case 'new':
            $sql = "TRUNCATE TABLE tmp_export";
            echo $sql . "<br>\n";

            $res = @mysql_query($sql, $dbc);
            echo "Complete<br>\n";
            
            $sql =  "insert into tmp_export (classmark_id, hierarchy_code) select c.classmark_id, c.classmark_enc_tag " .
                    "from classmarks c join audit_history a on c.classmark_id = a.classmark_id where a.audit_type = 'I' and a.audit_date = '" . $_POST['opdate'] . "' " .
                    " and c.active = 'Y' order by c.classmark_enc_tag";
            echo $sql . "<br>\n";
            $res = @mysql_query($sql, $dbc);
            echo "Complete<br>\n";
            
            $sql =  "select count(*) from tmp_export";
            echo $sql . "<br>\n";
            $res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $rowcount = $row[0];
            	}
            	@mysql_free_result($res);
            }
            echo "Complete: " . $rowcount . " records retrieved<br>\n";
            break;
        case 'mod':
            $sql = "TRUNCATE TABLE tmp_export";
            echo $sql . "<br>\n";

            $res = @mysql_query($sql, $dbc);
            echo "Complete<br>\n";
            
            $sql =  "insert into tmp_export (classmark_id, hierarchy_code) select c.classmark_id, c.classmark_enc_tag " .
                    "from classmarks c join audit_history a on c.classmark_id = a.classmark_id where a.audit_type = 'R' and a.audit_date = '" . $_POST['opdate'] . "' " .
                    " and c.active = 'Y' order by c.classmark_enc_tag";
            echo $sql . "<br>\n";
            $res = @mysql_query($sql, $dbc);
            echo "Complete<br>\n";
            
            $sql =  "select count(*) from tmp_export";
            echo $sql . "<br>\n";
            $res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $rowcount = $row[0];
            	}
            	@mysql_free_result($res);
            }
            echo "Complete: " . $rowcount . " records retrieved<br>\n";
            break;
        case 'can':
            $sql = "TRUNCATE TABLE tmp_export";
            echo $sql . "<br>\n";

            $res = @mysql_query($sql, $dbc);
            echo "Complete<br>\n";
            
            $sql =  "insert into tmp_export (classmark_id, hierarchy_code) select c.classmark_id, c.classmark_enc_tag " .
                    "from classmarks c join audit_history a on c.classmark_id = a.classmark_id where a.audit_type = 'C' and a.audit_date = '" . $_POST['opdate'] . "' " . 
                    " and c.active = 'N' order by c.classmark_enc_tag";
            echo $sql . "<br>\n";
            $res = @mysql_query($sql, $dbc);
            echo "Complete<br>\n";
            
            $sql =  "select count(*) from tmp_export";
            echo $sql . "<br>\n";
            $res = @mysql_query($sql, $dbc);
            if ($res)
            {
            	if(($row = @mysql_fetch_array($res, MYSQL_NUM)))
            	{
                    $rowcount = $row[0];
            	}
            	@mysql_free_result($res);
            }
            echo "Complete: " . $rowcount . " records retrieved<br>\n";
            break;
        case 'extended':
            $min_rec_no = 0;
            if (isset($_POST['minrecno']))
                $min_rec_no = $_POST['minrecno'];
                
            $max_rec_no = "";
            if (isset($_POST['maxrecno']))
                $max_rec_no  = $_POST['maxrecno'];
        
            echo "Fetching records: " . $min_rec_no . " to " . $max_rec_no . "<br>\n";
            FetchRecord($dbc, $recordarray, $errors, $headtype, $specialauxarray, $min_rec_no, $max_rec_no, false);
            echo "Record count = " . count($recordarray) . " records fetched<br>\n";
            //flush();
            
            @mysql_close();
        
            echo "<br># ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>\n";
            
            // Now display the records
            foreach($recordarray as $rec)
            {
                $rec->OutputExtendedContents($tagsarray, $trans_chars);
            }
            
            echo "Complete<br>\n";
            break;
    }
                
    echo "</body>\n";
    echo "</html>\n";
?>