<?php
	namespace Config;

	require_once __DIR__ . '../../vendor/autoload.php';
	use PDO;
	//use Dotenv\Dotenv as Dotenv;

	class PusherConnection
	{
		private $pusherConnection;
		
		public function __construct()
		{
			// $dotenv = Dotenv::create(__DIR__ . '/..');
			// $dotenv->load();
			$options = array(
    			'cluster' => getenv('PUSHER_CLUSTER'),
    			'useTLS' => true
  			);
			$this->pusherConnection = new Pusher\Pusher(
    			getenv('PUSHER_KEY'),
    			getenv('PUSHER_SECRET'),
    			getenv('PUSHER_ID'),
    			$options
  			);
  			return $this->pusherConnection;
		}
	}
?>