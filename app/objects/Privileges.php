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

		public function __construct($connection)
		{
			$this->dbConnection = $connection;
		}
		
		function list()
		{
			$query = "SELECT Name" .
					" FROM " . $this->tableName .
					" ORDER BY id asc";
			$stmt = $this->dbConnection->prepare($query);
			$stmt->execute();

			$num = $stmt->rowCount();
	
			if($num > 0)
			{
				$privilegesArray = array();
				$privilegesArray["data"] = array();
				while($row = $result->fetch(PDO::FETCH_ASSOC))
				{
					extract($row);
					$privilege[$Name] = array();
					array_push($privilegesArray["data"], $privilege[$Name]);
				}
				return $privilegesArray;
			}
			else
				return null;
		}
	}
?>