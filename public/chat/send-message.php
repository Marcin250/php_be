<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Pusher\Pusher as Pusher;

	if(isset($_POST['chat']) && isset($_POST['message']))
	{
		$data['message'] = $_POST['message'];
		$pusherOptions = array(
    		'cluster' => getenv('PUSHER_CLUSTER'),
    		'useTLS' => true
  		);
		$pusher = new Pusher(getenv('PUSHER_KEY'), getenv('PUSHER_SECRET'), getenv('PUSHER_ID'), $pusherOptions);
		$pusher->trigger($_POST['chat'], 'chat', $data);
		echo json_encode(array("message" => "Wysłano."));
	}
	else
		echo json_encode(array("message" => "Błąd wysłania."));
	die;
?>