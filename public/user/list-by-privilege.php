<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\DatabaseConnection;
	use App\Objects\User;
	use App\Objects\Privilege;
	use App\Objects\Cache;

	$dbConnection = new DatabaseConnection();
	$connetion = $dbConnection->getConnection();

	$cacheData = new Cache('../../cache/');
	$data = $cacheData->remember('userslist-by-privilege', 60);
	if($data)
		echo $data;
	else
	{
		$data = $cacheData->remember('privileges-list', 60);
		if($data)

			$privilegesArray = json_decode($cacheData->remember('privileges-list', 60), true);
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
		//array_push($privilegesArray['data']['root'], [['name' => 'Admin', 'id' => 1], ['name' => 'Marcin', 'id' => 2]]);
	 
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
				array_push($privilegesArray['data'][$Privielge], ['id' => $id, 'name' => $Name]);
			}
			foreach ($privilegesArray['data'] as $key => $privilegeArray) {
				if(empty($privilegeArray))
					unset($privilegesArray['data'][$key]);
			}
			echo $cacheData->cacheWrite('userslist-by-privilege', json_encode($privilegesArray));
		}
		else
			echo json_encode(array("message" => "Błąd wyszukiwania."));
	}
?>