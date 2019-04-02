<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\DatabaseConnection;
	use App\Objects\User;

	$dbConnection = new DatabaseConnection();
	$connetion = $dbConnection->getConnection();
 
	$user = new User($connetion);
 
 	if(isset($_GET['id']))
 	{
 		$user->id = $_GET['id'];
		$result = $user->by_id();
 	}
	else
		die;	

	if($result != null)
	{
		echo json_encode($result);
	}
	else
		echo json_encode(array("message" => "Błąd wyszukiwania."));
?>