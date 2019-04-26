<?php
	namespace App\Objects;

	require_once __DIR__ . '../../../vendor/autoload.php';
	use PDO;

	class Chat
	{
		private $dbConnection;
		private $tableName = "chats";		
		public $idChat;
		public $ChatName;
		public $idUser;
		public $Message;
		private $createdAt;

		public function __construct($connection)
		{
			$this->dbConnection = $connection;
		}
		
		function store()
		{
			$query = "insert into " . $this->table_name . 
			" SET ChatName=:ChatName, idUser=:idUser, Message=:Message, created_at=:createdAt";
			$stmt = $this->db_connection->prepare($query);
			$stmt->bindParam(":ChatName", $this->ChatName);
			$stmt->bindParam(":idUser", $this->idUser);
			$stmt->bindParam(":Message", $this->Message);
			$stmt->bindParam(":createdAt", $this->createdAt);
			if($stmt->execute())
			{
				return true;
			}
			else
				return false;
		}
	}
?>