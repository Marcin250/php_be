<?php
	namespace App\Objects;
	
	class User
	{
		private $dbConnection;
		private $tableName = "users";		
		public $id;
		public $login;
		public $providerId;
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
		function verify()
		{
			$query = "SELECT count(id) as userCount" .
					" FROM " . $this->tableName .
					" WHERE provider_id=:providerId";
			$stmt = $this->dbConnection->prepare($query);
			$stmt->bindParam(":providerId", $this->providerId);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$queryUserCount = $row["userCount"];
			if($queryUserCount > 0)
				return true;
			else
				return false;
		}
	}
?>