<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\DatabaseConnection;
	use App\Objects\User;
	use App\Objects\Privilege;
	use App\Objects\Cache;

	$cacheData = new Cache('../../cache/');
	$data = $cacheData->remember('userslist-by-privilege', 60);
	if($data)
		echo $data;
	else
	{
		$data = $cacheData->remember('privileges-list', 60);
		if($data)
			$privilegesArray;
		else
		{
			$privileges = new Privilege($connetion);
			$privilegesArray = $privileges->list();
			if($privilegesArray == null)
			{
				echo json_encode(array("message" => "Błąd wyszukiwania."));
				die;
			}
			else
				$cacheData->cacheWrite('privileges-list', json_encode($privilegesArray));
		}
		var_dump($privilegesArray);
		die;
		$dbConnection = new DatabaseConnection();
		$connetion = $dbConnection->getConnection();
	 
		$users = new User($connetion);
	 
		$result = $users->listByPrivilege();
		$num = $result->rowCount();
	
		if($num > 0)
		{
			$usersArray = array();
			$usersArray["data"] = array();
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				extract($row);
				$user = array(
					"id" => $id,
					"name" => $Name,
					"privilege" => $Privielge
				);
				array_push($usersArray["data"], $user);
			}
			echo $cacheData->cacheWrite('userslist-by-privilege', json_encode($usersArray));
		}
		else
			echo json_encode(array("message" => "Błąd wyszukiwania."));
	}
?>