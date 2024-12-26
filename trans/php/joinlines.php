<?php

/**
 * @author Chris Overfield
 * @copyright 2009
 */

	ob_start();
	include('french.txt');
	$formfile = ob_get_contents();
	ob_end_clean();
    
    $lines = explode("\n", $formfile);
    
    $outfile = fopen("french_out.txt", "w");
    
    $oldline = "";
    
    foreach($lines as $line)
    {
        if (trim($line) == "")
            continue;
            
        echo $line . "\n";
        if ($line[0] == " " || $line[0] == "\t")
        {
            $trimline = trim($line);
            if ($trimline[0] != "?")
            {
                $oldline = trim($oldline) . " " . $trimline;
            }            
        }
        else
        {
            if ($oldline != "")
            {
                fwrite($outfile, $oldline . "\n");
            }
            $oldline = trim($line);
        }
    }
    
    if ($oldline != "")
    {
        fwrite($outfile, $oldline . "\n");                
    }
    
    fclose($outfile);
?>