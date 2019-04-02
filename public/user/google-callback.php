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
 
	$users = new User($connetion);
 
	$result = $users->index();
	$num = $result->rowCount();

	$googleClient = new GoogleClient();
	$client = $googleClient->getClient();

	if(isset($_GET['code']))
	{
		$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['token'] = $token;

		$oAuthData = new Google_Service_Oauth2($client);
		$userData = $oAuthData->userinfo_v2_me->get();

		$_SESSION['name'] = $userData->name;
		$_SESSION['email'] = $userData->email;

		setcookie('name', $userData->name, time()+60*60);
		setcookie('email', $userData->email, time()+60*60);

		header("Location: " . getenv('APP_URL'));
		exit();
	}
?>