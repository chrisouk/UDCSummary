<?php

	class DBConnection
	{
		public static function getConnection($host, $user, $pass, $database) : PDO
		{
			$options = [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_BOTH,
				\PDO::ATTR_EMULATE_PREPARES => false,
			];

			$dsn = "mysql:host=$host;dbname=$database;";

			try
			{
				return new \PDO($dsn, $user, $pass, $options);
			}
			catch (\PDOException $e)
			{
				throw new \PDOException($e->getMessage(), (int)$e->getCode());
			}
		}
	}