<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	use Config\GoogleClient;
	use Google_Service_OAuth2;

	$googleClient = new GoogleClient();
	$client = $googleClient->getClient();

	if(isset($_GET['code']))
	{
		$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['token'] = $token;
	}

	$oAuthData = new Google_Service_OAuth2($client);
	$userData = $oAuthData->userinfo_v2_me->get();
	var_dump($userData);
?>