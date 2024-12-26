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

			$dsn = 'mysql:dbname=' . DBDATABASE . ';host=' . DBHOST;
			$database_user = DBUSER;
			$database_password = DBPASS;

			try
			{
				$this->_conn = new PDO($dsn, $database_user, $database_password);
				$this->_conn->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
				$this->_conn->query("SET CHARACTER SET UTF8");
				$this->_conn->query("SET NAMES UTF8");
			}
			catch (PDOException $e)
			{
				echo 'Cannot get a connection to the database.  Please email support.';
				exit(0);
			}
		}

		return $this->_conn;
	}
	
	public function close()
	{
		if ($this->conn != null)
		{
			$this->conn = null;
		}
	}
};
?>
