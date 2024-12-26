<?php

/**
 * @author Chris Overfield
 * @copyright 2009
 */

    include_once("encodeexample.php");
    
	ob_start();
	include('encoded.csv');
	$formfile = ob_get_contents();
	ob_end_clean();
    
    $lines = explode("\n", $formfile);
    
    $outfile = fopen("encoded_out.csv", "w");
    
    foreach($lines as $line)
    {
        if (trim($line) == "")
            continue;
            
        echo $line . "\n";
        $splitline = explode(",", $line);
        $notation = $splitline[1];
        $encoded = encodeExample($notation);
        
        echo $notation . " = " . $encoded . "\n";
        
        $sql = "update classmarks set classmark_enc_tag = '" . $encoded . "' where classmark_id = " . $splitline[0] . ";\n";
        echo $sql;
        fwrite($outfile, $sql);
    }
    
    fclose($outfile);
?>