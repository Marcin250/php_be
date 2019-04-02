<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\GoogleClient;
	use Config\DatabaseConnection;
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

		$user->name = explode('@', $userData->email)[0],
		$user->email = $userData->email;
		$user->provider = 'GOOGLE';
		$user->providerId = $userData->id;
		$user->image = $userData->picture;
		if($user->verify() == false)
			$user->store();

		$_SESSION['name'] = $userData->name;
		$_SESSION['email'] = $userData->email;

		setcookie('name', $userData->name, time()+60*60);
		setcookie('email', $userData->email, time()+60*60);

		header("Location: " . getenv('APP_URL'));
		exit();
	}
?>