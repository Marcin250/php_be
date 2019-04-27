<?php
	namespace App\Objects;

	require_once __DIR__ . '../../../vendor/autoload.php';
	use PDO;

	class Chat
	{
		private $dbConnection;
		private $tableName = "chats";		
		private $idChat;
		private $ChatName;
		private $idUser;
		private $Message;
		private $createdAt;

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
		
		function store()
		{
			$query = "insert into " . $this->tableName . 
			" SET ChatName=:ChatName, idUser=:idUser, Message=:Message, created_at=:createdAt";
			$stmt = $this->dbConnection->prepare($query);
			$stmt->bindParam(":ChatName", $this->ChatName);
			$stmt->bindParam(":idUser", $this->idUser);
			$stmt->bindParam(":Message", $this->Message);
			$stmt->bindParam(":createdAt", $this->createdAt);
			if($stmt->execute())
				return true;
			return false;
		}
	}
?>