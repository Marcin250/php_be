<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\GoogleClient;
	use Config\DatabaseConnection;
	//use Dotenv\Dotenv as Dotenv;
	use Pusher\Pusher as Pusher;
	use App\Objects\User;

	if(!isset($_SESSION)) { session_start(); }

	$dbConnection = new DatabaseConnection();
	$connetion = $dbConnection->getConnection();
 
	$user = new User($connetion);

	$googleClient = new GoogleClient();
	$client = $googleClient->getClient();

	if(isset($_GET['code']))
	{
		$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['token'] = $token;

		$oAuthData = new Google_Service_Oauth2($client);
		$userData = $oAuthData->userinfo_v2_me->get();

		$user->name = explode('@', $userData->email)[0];
		$user->email = $userData->email;
		$user->provider = 'GOOGLE';
		$user->providerId = $userData->id;
		$user->image = $userData->picture;
		if(!$user->verify())
		{
			if($user->store())
				$sessionUserData = $user->byProviderId();
			else
				echo json_encode(array("message" => "Błąd serwera."));
		}
		else
		{
			$user->providerId = $userData->id;
			$sessionUserData = $user->byProviderId();
		}

		$_SESSION['id'] = $sessionUserData['id'];
		$_SESSION['name'] = $sessionUserData['name'];
		$_SESSION['email'] = $sessionUserData['email'];
		$_SESSION['image'] = $sessionUserData['image'];
		$_SESSION['createdAt'] = $sessionUserData['createdAt'];
		$_SESSION['status'] = $sessionUserData['status'];
		$_SESSION['privielege'] = $sessionUserData['privielege'];
		$_SESSION['tier'] = $sessionUserData['tier'];
		$_SESSION['chat'] = 'chat' . $sessionUserData['id'];

		$data['name'] = $_SESSION['name'];

		// $dotenv = Dotenv::create(__DIR__ . '/../..');
		// $dotenv->load();

		$pusherOptions = array(
    		'cluster' => getenv('PUSHER_CLUSTER'),
    		'useTLS' => true
  		);
		$pusher = new Pusher(getenv('PUSHER_KEY'), getenv('PUSHER_SECRET'), getenv('PUSHER_ID'), $pusherOptions);
		$pusher->trigger('home', 'login', $data);

		header("Location: " . getenv('APP_URL'));
		exit();
	}
?>