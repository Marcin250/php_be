<?php
	namespace Config;
	
	if(!isset($_SESSION)) { session_start(); }

	require_once __DIR__ . '../../vendor/autoload.php';

	use Google_Client;

	class GoogleClient
	{
		public $client;
		
		public function getClient()
		{
			try
			{
				$this->$client = new Google_Client();
				$this->$client->setApplicationName("Authorization_API");
				$this->$client->setClientId(getenv('GOOGLE_ID'));
				$this->$client->setClientSecret(getenv('GOOGLE_SECRET'));
				$this->$client->setRedirectUri(getenv('APP_URL') . '/user/google-callback');
				$this->$client->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
			}
			catch(Google_Service_Exception $exception)
			{
				echo "Connection error: " . $exception->getErrors();
			}
			return $this->client;
		}
	}
?>