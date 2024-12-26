<?php

include_once("DBConnectInfo.php");

class DBConnection
{
	protected static $_instance = null;
	
	protected $_conn = null;
	
	protected function __construct() 
	{
	}
	
	public static function getInstance()
	{
		if (null === self::$_instance) 
		{
			#echo "Creating new database instance<br>\n";
			self::$_instance = new self();
		}
		else
		{
			#echo "Using existing database connection<br>\n";
		}
		return self::$_instance;
	}
	
	public function getConnection() 
	{
		if ($this->_conn == null) 
		{
			#echo "Connecting to " . DBHOST . ", " .DBUSER . ", #######<br>\n";
			$this->_conn = mysql_connect(DBHOST, DBUSER, DBPASS) or die ("Could not connect to server: " . mysql_error() . "<br>\n");
			if(!$this->_conn) 
			{
				die("Cannot connect to database server"); 
			}
			else
			{
				#echo "Connected successfully<br>\n";
			}
	
			if(!@mysql_select_db(DBDATABASE, $this->_conn)) 
			{
				die("Cannot select database");
			}
			else
			{
				#echo "Selected database successfully<br>\n";
			}
			
			mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $this->_conn);
			mysql_query("SET CHARACTER SET utf8", $this->_conn);
			mysql_query("SET NAMES utf8", $this->_conn);
		}
		else
		{
			#echo "Using existing connection<br>\n";
		}
		
		return $this->_conn;
	}
	
	public function close()
	{
		if ($this->conn != null)
		{
			@mysql_close($this->conn);
			$this->conn = null;
		}
	}
};
?>
