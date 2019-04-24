<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Pusher\Pusher as Pusher;

	if(isset($_GET['chat']) && isset($_GET['message']))
	{
		$data['message'] = $_GET['message'];
		$pusherOptions = array(
    		'cluster' => getenv('PUSHER_CLUSTER'),
    		'useTLS' => true
  		);
		$pusher = new Pusher(getenv('PUSHER_KEY'), getenv('PUSHER_SECRET'), getenv('PUSHER_ID'), $pusherOptions);
		$pusher->trigger($_GET['chat'], 'chat', $data);
	}
	else
		die;
?>