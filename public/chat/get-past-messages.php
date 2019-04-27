<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Pusher\Pusher as Pusher;
	use Config\DatabaseConnection;
	use App\Objects\Chat;

	if(!isset($_SESSION)) { session_start(); }

	if((isset($_GET['chat']) && $_GET['chat'] == $_SESSION['chat']) && isset($_GET['from']) && isset($_GET['quantity']))
	{
		$dbConnection = new DatabaseConnection();
		$connetion = $dbConnection->getConnection();

		$chat = new Chat($connetion);

		$chat->ChatName = $_GET['chat'];
		$result = $chat->getChatPastMessages($_GET['from'], $_GET['quantity']);

		var_dump($result);
	}
?>