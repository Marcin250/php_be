<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\DatabaseConnection;
	use App\Objects\User;
	use App\Objects\Cache;

	$cacheData = new Cache('../../cache/');
	$data = $cacheData->remember('userslist', 60);
	if($data)
		echo $data;
	else
	{
		$dbConnection = new DatabaseConnection();
		$connetion = $dbConnection->getConnection();
	 
		$users = new User($connetion);
	 
		$result = $users->index();
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
					"createdAt" => $created_at
				);
				array_push($usersArray["data"], $user);
			}
			echo $cacheData->cacheWrite('userslist', json_encode($usersArray));
		}
		else
			echo json_encode(array("message" => "Błąd wyszukiwania."));
	}
?>