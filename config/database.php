<?php
	namespace Config;

	require_once __DIR__ . '../../vendor/autoload.php';
	use PDO;
	use Dotenv\Dotenv as Dotenv;
	
	class DatabaseConnection
	{
		public $db_connection;
		
		public function getConnection()
		{
			$this->db_connection = null;
			try
			{
				$dotenv = Dotenv::create(__DIR__ . '/..');
				$dotenv->load();
				$this->db_connection = new PDO("mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
				$this->db_connection->exec("set names utf8");
			}
			catch(PDOException $exception)
			{
				echo "Connection error: " . $exception->getMessage();
			}
			return $this->db_connection;
		}
	}
?>