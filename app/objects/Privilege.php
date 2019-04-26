<?php
	namespace App\Objects;

	require_once __DIR__ . '../../../vendor/autoload.php';
	use PDO;

	class Privilege
	{
		private $dbConnection;
		private $tableName = "privileges";		
		private $idPrivielege;
		private $Name;
		private $Tier;
		private $createdAt;

		public function __construct($connection)
		{
			$this->dbConnection = $connection;
		}
		
		function list()
		{
			$query = "SELECT Name" .
					" FROM " . $this->tableName .
					" ORDER BY Tier desc";
			$stmt = $this->dbConnection->prepare($query);
			$stmt->execute();

			$num = $stmt->rowCount();

			if($num > 0)
			{
				$privilegesArray = array();
				$privilegesArray["data"] = array();
				while($row = $stmt->fetch(PDO::FETCH_ASSOC))
				{
					extract($row);
					$privilege[$Name] = array();
					$privilegesArray["data"][$Name] = array();
				}
				return $privilegesArray;
			}
			else
				return null;
		}
	}
?>