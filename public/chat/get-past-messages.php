<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Pusher\Pusher as Pusher;
	use Config\DatabaseConnection;
	use App\Objects\Chat;

	if(!isset($_SESSION)) { session_start(); }

	$_SESSION['chat'] = 'chat1131';

	if((isset($_GET['chat']) && $_GET['chat'] == $_SESSION['chat']) && isset($_GET['from']) && isset($_GET['quantity']))
	{
		$dbConnection = new DatabaseConnection();
		$connetion = $dbConnection->getConnection();

		$chat = new Chat($connetion);

		$chat->ChatName = $_GET['chat'];
		$result = $chat->getChatPastMessages($_GET['from'], $_GET['quantity']);

		$pastMessagesCount = $chat->getTotalPastMessages();

		$num = $result->rowCount();

		if($num > 0)
		{
			$messagesArray = array();
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				extract($row);
				$message = array(
					"author" => $idUser,
					"message" => $Message,
					"createdAt" => $created_at
				);
				array_push($messagesArray, $message);
			}

			$messagesData = [
				'messages' => $messagesArray,
				'messagesFrom' => $_GET['from'],
				'messagesQuantity' => $_GET['quantity'],
				'totalMessages' => $pastMessagesCount
			];

			echo json_encode($messagesData);
		}
		else
			echo json_encode(array("message" => "Błąd wyszukiwania."));
	}
	else
		echo json_encode(array("message" => "Brak autoryzacji."));
?>