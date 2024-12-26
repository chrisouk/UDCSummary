<?php

/**
 * @author Chris Overfield
 * @copyright 2009
 */

# The languages
define("ENGLISH", 1);
define("DUTCH", 2);
define("SPANISH", 3);
define("FRENCH", 4);
define("SWEDISH", 5);
define("GERMAN", 6);
define("CROATIAN", 7);
define("RUSSIAN", 8);
define("SLOVENIAN", 9);
define("FINNISH", 10);
define("ITALIAN", 11);
define("GEORGIAN", 12);

$menu_translations =    array(ENGLISH => 'TRANSLATIONS', DUTCH => 'VERTALING', SPANISH => 'TRANSLATIONS', FRENCH => 'TRANSLATIONS', SWEDISH => utf8_encode('ÖVERSÄTTNINGAR'), 
                        GERMAN => "&#220;BERSETZUNGEN", CROATIAN => 'PRIJEVODI', RUSSIAN => 'TRANSLATIONS', SLOVENIAN => 'TRANSLATIONS', FINNISH => 'TRANSLATIONS', ITALIAN => 'TRANSLATIONS', 
                        GEORGIAN => 'TRANSLATIONS'); 
                        
$menu_mappings =        array(ENGLISH => 'MAPPINGS', DUTCH => 'MAPPINGS', SPANISH => 'MAPPINGS', FRENCH => 'MAPPINGS', SWEDISH => 'MAPPNING', 
                        GERMAN => 'MAPPINGS', CROATIAN => 'MAPIRANJE', RUSSIAN => 'MAPPINGS', SLOVENIAN => 'MAPPINGS', FINNISH => 'MAPPINGS', ITALIAN => 'MAPPINGS', GEORGIAN => 'MAPPINGS'); 
                        
$menu_exports =         array(ENGLISH => 'EXPORTS', DUTCH => 'EXPORTEER', SPANISH => 'EXPORTS', FRENCH => 'EXPORTS', SWEDISH => 'EXPORT', 
                        GERMAN => 'DATENEXPORT', CROATIAN => 'ISPISI', RUSSIAN => 'EXPORTS', SLOVENIAN => 'EXPORTS', FINNISH => 'EXPORTS', ITALIAN => 'EXPORTS', GEORGIAN => 'EXPORTS'); 
                        
$menu_index =           array(ENGLISH => 'ABC INDEX', DUTCH => 'ALFABETISCH REGISTER', SPANISH => 'ABC INDEX', FRENCH => 'ABC INDEX', SWEDISH => 'REGISTER', 
                        GERMAN => 'ABC REGISTER', CROATIAN => 'ABC KAZALO', RUSSIAN => 'ABC INDEX', SLOVENIAN => 'ABC INDEX', FINNISH => 'ABC INDEX', ITALIAN => 'ABC INDEX', GEORGIAN => 'ABC INDEX'); 
                        
$menu_guide =           array(ENGLISH => 'GUIDE', DUTCH => 'HANDLEIDING', SPANISH => 'GUIDE', FRENCH => 'GUIDE', SWEDISH => 'GUIDE', 
                        GERMAN => 'MANUAL', CROATIAN => 'UVOD', RUSSIAN => 'GUIDE', SLOVENIAN => 'GUIDE', FINNISH => 'GUIDE', ITALIAN => 'GUIDE', GEORGIAN => 'GUIDE'); 
                        
$menu_about =           array(ENGLISH => 'ABOUT', DUTCH => 'OVER', SPANISH => 'ABOUT', FRENCH => 'ABOUT', SWEDISH => 'OM', 
                        GERMAN => 'INFO', CROATIAN => 'INFO', RUSSIAN => 'ABOUT', SLOVENIAN => 'ABOUT', FINNISH => 'ABOUT', ITALIAN => 'ABOUT', GEORGIAN => 'ABOUT'); 

$menu_top =             array(ENGLISH => 'TOP', DUTCH => 'TOPKLASSEN', SPANISH => 'TOP', FRENCH => 'TOP', SWEDISH => 'UPP', 
                        GERMAN => 'OBEN', CROATIAN => 'PO&#268;ETNA', RUSSIAN => 'TOP', SLOVENIAN => 'TOP', FINNISH => 'TOP', ITALIAN => 'TOP', GEORGIAN => 'TOP'); 
                        
$menu_signs =           array(ENGLISH => 'SIGNS', DUTCH => 'TEKENS', SPANISH => 'SIGNS', FRENCH => 'SIGNS', SWEDISH => 'SYMBOLER', 
                        GERMAN => 'ZEICHEN', CROATIAN => 'ZNAKOVI', RUSSIAN => 'SIGNS', SLOVENIAN => 'SIGNS', FINNISH => 'SIGNS', ITALIAN => 'SIGNS', GEORGIAN => 'SIGNS');
                         
$menu_auxiliaries =     array(ENGLISH => 'AUXILIARIES', DUTCH => 'HULPGETALLEN', SPANISH => 'AUXILIARIES', FRENCH => 'AUXILIARIES', SWEDISH => utf8_encode('TILLÄGG'), 
                        GERMAN => 'HILFSTAFEL', CROATIAN => 'POMO&#262;NI BR.', RUSSIAN => 'AUXILIARIES', SLOVENIAN => 'AUXILIARIES', FINNISH => 'AUXILIARIES', ITALIAN => 'AUXILIARIES', 
                        GEORGIAN => 'AUXILIARIES'); 

$trans_expandall =      array(ENGLISH => 'expand all', DUTCH => 'vouw alle uit', SPANISH => 'expand all', FRENCH => 'expand all', SWEDISH => utf8_encode('expand all'), 
                        GERMAN => 'alle ausklappen', CROATIAN => utf8_encode('otvori sve'), RUSSIAN => 'expand all', SLOVENIAN => 'expand all', FINNISH => 'expand all', ITALIAN => 'expand all', 
                        GEORGIAN => 'expand all');
                                               
$trans_collapseall =    array(ENGLISH => 'collapse all', DUTCH => 'vouw alle op', SPANISH => 'collapse all', FRENCH => 'collapse all', SWEDISH => utf8_encode('collapse all'), 
                        GERMAN => 'alle zusammenklappen', CROATIAN => utf8_encode('zatvori sve'), RUSSIAN => 'collapse all', SLOVENIAN => 'collapse all', FINNISH => 'collapse all', ITALIAN => 'collapse all', 
                        GEORGIAN => 'collapse all');
                         
$trans_rootclasses =    array(ENGLISH => 'TOP', DUTCH => 'TOP', SPANISH => 'TOP', FRENCH => 'TOP', SWEDISH => utf8_encode('TOP'), 
                        GERMAN => 'TOP', CROATIAN => utf8_encode('TOP'), RUSSIAN => 'TOP', SLOVENIAN => 'TOP', FINNISH => 'TOP', ITALIAN => 'TOP', GEORGIAN => 'TOP'); 
                        
$trans_click =          array(  ENGLISH => 'click on a class to the left to display records', 
                                DUTCH => 'Klik op een klasse links om de records te tonen', 
                                SPANISH => 'pulse sobre una clase a la izquierda para mostrar los registros', 
                                FRENCH => 'click on a class to the left to display records', 
                                SWEDISH => utf8_encode('klick över en klass till vänster för att visa alla underavdelningar'), 
                                GERMAN => 'klicken Sie auf die Klassen links um ihre Inhalte anzuzeigen', 
                                CROATIAN => utf8_encode('klikni na link razreda u lijevom okviru za prikaz'), 
                                RUSSIAN => 'click on a class to the left to display records',
                                SLOVENIAN => 'click on a class to the left to display records',
                                FINNISH => 'click on a class to the left to display records',
                                ITALIAN => 'click on a class to the left to display records', 
                                GEORGIAN => htmlentities("??????????? ??????????? ???????? ??????–????????????? ???????? ??????"));

$trans_including =      array(ENGLISH => 'Including', DUTCH => 'Inclusief', SPANISH => 'Incluye', FRENCH => 'Including', SWEDISH => 'Inkluderar', 
                        GERMAN => "Einschlie&#223;lich", CROATIAN => "Uklju&#269;uju&#263;i", RUSSIAN => 'Including', SLOVENIAN => 'Including', FINNISH => 'Including', ITALIAN => 'Including', 
                        GEORGIAN => 'Including');
                                   
$trans_scopenote =      array(ENGLISH => 'Scope Note', DUTCH => 'Toelichting', SPANISH => 'Nota de alcance', FRENCH => 'Scope Note', SWEDISH => "F&#246;rklaring", 
                        GERMAN => "Geltungshinweis", CROATIAN => 'Bilje&#353;ka o sadr&#382;aju', RUSSIAN => 'Scope Note', SLOVENIAN => 'Scope Note', FINNISH => 'Scope Note', ITALIAN => 'Scope Note', 
                        GEORGIAN => 'Scope Note');           
                        
$trans_appnote =        array(ENGLISH => 'Application Note', DUTCH => 'Gebruik', SPANISH => 'Note de aplicaci&#243;n', FRENCH => 'Application Note', SWEDISH => "Till&#228;mpas", 
                        GERMAN => "Anmerkung zur Anwendung", CROATIAN => 'Uputa', RUSSIAN => 'Application Note', SLOVENIAN => 'Application Note', FINNISH => 'Application Note', ITALIAN => 'Application Note', 
                        GEORGIAN => 'Application Note');
                                   
$trans_derivedfrom =    array(ENGLISH => 'Derived from', DUTCH => 'Ontleend aan', SPANISH => 'Derivado de', FRENCH => 'Derived from', SWEDISH => "H&#228;rstammar fr&#229;n", 
                        GERMAN => "Abgeleitet aus", CROATIAN => 'Izvedeno od', RUSSIAN => 'Derived from', SLOVENIAN => 'Derived from', FINNISH => 'Derived from', ITALIAN => 'Derived from', GEORGIAN => 'Derived from');      
$trans_examples =       array(ENGLISH => 'Examples of Combination(s)', DUTCH => 'Combinatievoorbeeld(en)', SPANISH => 'Ejemplo(s) de combinaci&#243;n', FRENCH => 'Examples of Combination(s)', 
                        SWEDISH => "Exempel p&#229; sammans&#228;ttningar", GERMAN => "Beispiel(e) der verkn&#252;pfung", CROATIAN => 'Primjer(i)', RUSSIAN => 'Examples of Combination(s)', 
                        SLOVENIAN => 'Examples of Combination(s)', FINNISH => 'Examples of Combination(s)', ITALIAN => 'Examples of Combination(s)', GEORGIAN => 'Examples of Combination(s)');          
?>
