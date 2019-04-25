<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\DatabaseConnection;
	use App\Objects\Privilege;
	use App\Objects\Cache;

	$cacheData = new Cache('../../cache/');
	$data = $cacheData->remember('privileges-list', 60);
	if($data)
		echo $data;
	else
	{
		$dbConnection = new DatabaseConnection();
		$connetion = $dbConnection->getConnection();
	 
		$privileges = new Privilege($connetion);
	 
		$result = $privileges->list();
		$num = $result->rowCount();
	
		if($num > 0)
		{
			$privilegesArray = array();
			$privilegesArray["data"] = array();
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				extract($row);
				$privilege = array(
					"name" => $Name
				);
				array_push($privilegesArray["data"], $privilege);
			}
			echo $cacheData->cacheWrite('privileges-list', json_encode($privilegesArray));
		}
		else
			echo json_encode(array("message" => "Błąd wyszukiwania."));
	}
?>