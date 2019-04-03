<?php
	namespace App\Objects;

	require_once __DIR__ . '../../../vendor/autoload.php';
	use PDO;

	class User
	{
		private $dbConnection;
		private $tableName = "users";		
		public $id;
		public $name;
		public $email;
		public $provider;
		public $providerId;
		public $image;
		public $createdAt;
		public $status;
		public $privilege;
		
		public function __construct($connection)
		{
			$this->dbConnection = $connection;
		}
		
		function index()
		{
			$query = "SELECT id, Name, created_at" .
					" FROM " . $this->tableName .
					" ORDER BY id asc";
			$stmt = $this->dbConnection->prepare($query);
			$stmt->execute();
			return $stmt;
		}
		function by_id()
		{
			$query = "SELECT users.Name as Name, users.Email as Email, users.Image as Image, users.created_at as createdAt, statuses.Name as Status, privileges.Name as Privielege, privileges.Tier as Tier" .
					" FROM " . $this->tableName .
					" LEFT JOIN statuses" .
					" ON statuses.idStatus = " . $this->tableName . ".idStatus" .
					" LEFT JOIN privileges" .
					" ON privileges.idPrivilege = " . $this->tableName . ".idPrivilege" .
					" WHERE users.id=:idUser";
			$stmt = $this->dbConnection->prepare($query);
			$stmt->bindParam(":idUser", $this->id);
			if($stmt->execute())
			{
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				return $row;
			}
			else
				return false;
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
		function store()
		{
			$query = "insert into " . $this->tableName . " SET Name=:name, Email=:email, Image=:image, provider=:providerName, provider_id=:providerId, created_at=NOW()";
			$stmt = $this->dbConnection->prepare($query);
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":email", $this->email);
			$stmt->bindParam(":image", $this->image);
			$stmt->bindParam(":providerName", $this->provider);
			$stmt->bindParam(":providerId", $this->providerId);
			if($stmt->execute())
				return true;
			return false;
		}
		function update()
		{

		}
		function delete()
		{

		}
	}
?>