<?php

	echo "Connecting to memcached<br>\n";
	$m = new Memcache();
	echo "Memcached created<br>\n";
	
	if ($m->addServer('localhost', 11211) == true)
	{
		echo "Connected to memcached<br>\n";
		#var_dump($m->getStats());
	}
	else
	{
		echo "Connection to memcached failed<br>\n";
	}
	
	# Initially, just clear all data from the cache
	echo "Clearning the cache<br>\n";
	$m->flush();
	
	require_once("DBConnectInfo.php");

	$dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
	mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
	
    $classmarks = array();
    
    echo "Loading classmark numbers...<br>\n";
	$sql = "select classmark_tag from classmarks where active = 'Y' order by classmark_id";
	$res = @mysql_query($sql, $dbc);
    if ($res)
    {
        while(($row = mysql_fetch_array($res, MYSQL_NUM)))
    	{
    		array_push($classmarks, $row[0]);
    	}
    	mysql_free_result($res);
    }
	
    echo "Loaded " . count($classmarks) . " classmarks<br>\n";
    
    # Now load each record and store it in the memcache cache
    include('udcform.php');
    
    $udcform = new UDCForm();
    foreach($classmarks as $classmark_tag)
    {
    	echo "Loading " . $classmark_tag . "<br>\n";
    	$udcform->notation = $classmark_tag;
    	$udcform->LoadClassmark($dbc);
    	echo "Adding to cache<br>\n";
    	$cache_key = "PE_" . $udcform->mfn;
    	$m->add($cache_key, $buffer);
    	echo "Added to cache<br>\n";
    	break;
    }
    
    echo "Cache loaded<br>\n";
    
    $buffer = $m->get($cache_key);
    
    echo $cache_key . "<br><br>\n";
    echo $buffer . "<br>\n";
    
    @mysql_close($dbc);
?>
	