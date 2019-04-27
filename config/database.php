<?php
	namespace Config;

	require_once __DIR__ . '../../vendor/autoload.php';
	use PDO;
	use Dotenv\Dotenv as Dotenv;

	class DatabaseConnection
	{
		private $dbConnection;
		
		public function getConnection()
		{
			$this->dbConnection = null;
			try
			{
				$dotenv = Dotenv::create(__DIR__ . '/..');
				$dotenv->load();
				
				$this->dbConnection = new PDO("mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
				$this->dbConnection->exec("set names utf8");
			}
			catch(PDOException $exception)
			{
				echo "Connection error: " . $exception->getMessage();
			}
			return $this->dbConnection;
		}
	}
?>