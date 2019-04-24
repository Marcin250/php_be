<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\DatabaseConnection;
	use App\Objects\User;
	use App\Objects\Cache;

	$cacheData = new Cache('../../cache/');

	if(isset($_GET['id']))
	{
		$data = $cacheData->remember('userid' . $_GET['id'], 60);
		if($data)
			echo $data;
		else
		{
			$dbConnection = new DatabaseConnection();
			$connetion = $dbConnection->getConnection();
	 
			$user = new User($connetion);

			$user->id = $_GET['id'];
			$result = $user->by_id();

			if($result != null)
			{
				echo $cacheData->cacheWrite('userid' . $_GET['id'], json_encode($result));
			}
			else
				echo json_encode(array("message" => "Błąd wyszukiwania."));
		}
	}
	else
		die;
?>