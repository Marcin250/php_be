<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Pusher\Pusher as Pusher;
	use Config\DatabaseConnection;
	use App\Objects\Chat;

	if(isset($_POST['chat']) && isset($_POST['message']) && isset($_POST['author']))
	{
		$dbConnection = new DatabaseConnection();
		$connetion = $dbConnection->getConnection();

		$messageTime = date('Y-m-d H:i:s');

		$chat = new Chat($connetion);
		$chat->ChatName = $_POST['chat'];
		$chat->idUser = $_POST['author'];
		$chat->Message = $_POST['message'];
		$chat->createdAt = $messageTime;
		if($chat->store())
		{
			$data['author'] = $_POST['author'];
			$data['message'] = $_POST['message'];
			$data['createdAt'] = $messageTime;
			$pusherOptions = array(
    			'cluster' => getenv('PUSHER_CLUSTER'),
    			'useTLS' => true
  			);
			$pusher = new Pusher(getenv('PUSHER_KEY'), getenv('PUSHER_SECRET'), getenv('PUSHER_ID'), $pusherOptions);
			$pusher->trigger($_POST['chat'], 'chat', $data);
			echo json_encode(array("message" => "Wysłano."));
		}
		else
			echo json_encode(array("message" => "Błąd serwera."));
	}
	else
		echo json_encode(array("message" => "Błąd danych."));
	die;
?>