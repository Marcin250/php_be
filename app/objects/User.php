<?php
	namespace App\Objects;

	require_once __DIR__ . '../../../vendor/autoload.php';
	use PDO;

	class User
	{
		private $dbConnection;
		private $tableName = "users";		
		private $id;
		private $name;
		private $email;
		private $provider;
		private $providerId;
		private $image;
		private $createdAt;
		private $status;
		private $privilege;
		
		public function __construct($connection)
		{
			$this->dbConnection = $connection;
		}
		
		public function __set($name, $value)
    	{
        	$this->$name = $value;
    	}

    	public function __get($name)
    	{
    		return $this->$name;
    	}
		
		function list()
		{
			$query = "SELECT id, Name" .
					" FROM " . $this->tableName .
					" ORDER BY id asc";
			$stmt = $this->dbConnection->prepare($query);
			if($stmt->execute())
				return $stmt;
			return false;
		}

		function listByPrivilege()
		{
			$query = "SELECT users.id as id, users.Name as Name, privileges.Name as Privielge" .
					" FROM " . $this->tableName .
					" LEFT JOIN privileges" .
					" ON privileges.idPrivilege = " . $this->tableName . ".idPrivilege" .
					" ORDER BY users.id asc";
			$stmt = $this->dbConnection->prepare($query);
			if($stmt->execute())
				return $stmt;
			return false;
		}

		function byId()
		{
			$query = "SELECT users.id as id, users.Name as name, users.Email as email, users.Image as image, users.created_at as createdAt, statuses.Name as status, privileges.Name as privielege, privileges.Tier as tier" .
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

		function byProviderId()
		{
			$query = "SELECT users.id as id, users.Name as name, users.Email as email, users.Image as image, users.created_at as createdAt, statuses.Name as status, privileges.Name as privielege, privileges.Tier as tier" .
					" FROM " . $this->tableName .
					" LEFT JOIN statuses" .
					" ON statuses.idStatus = " . $this->tableName . ".idStatus" .
					" LEFT JOIN privileges" .
					" ON privileges.idPrivilege = " . $this->tableName . ".idPrivilege" .
					" WHERE users.provider_id=:ProviderId";
			$stmt = $this->dbConnection->prepare($query);
			$stmt->bindParam(":ProviderId", $this->providerId);
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
			return false;
		}
		function store()
		{
			$query = "insert into " . $this->tableName . 
			" SET Name=:name, Email=:email, Image=:image, provider=:providerName, provider_id=:providerId, created_at=NOW()";
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