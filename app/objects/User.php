<?php
	namespace App\Objects;
	
	class User
	{
		private $dbConnection;
		private $tableName = "users";		
		public $id;
		public $login;
		public $createdAt;
		
		public function __construct($connection)
		{
			$this->dbConnection = $connection;
		}
		
		function index()
		{
			$query = "SELECT id, Name, created_at" .
					" FROM " . $this->tableName;
			$stmt = $this->dbConnection->prepare($query);
			$stmt->execute();
			return $stmt;
		}
	}
?>