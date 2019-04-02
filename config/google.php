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
			$this->client = null;
			try
			{
				$this->client = new Google_Client();
				$this->client->setApplicationName("portal-wertykalny");
				$this->client->setClientId(getenv('GOOGLE_ID'));
				$this->client->setClientSecret(getenv('GOOGLE_SECRET'));
				$this->client->setRedirectUri(getenv('/user/google-callback');
				$this->client->addScope("https://www.googleapis.com/auth/plus.login");
				$this->client->addScope("https://www.googleapis.com/auth/userinfo.email");
			}
			catch(Google_Service_Exception $exception)
			{
				echo "Error: " . $exception->getErrors();
			}
			return $this->client;
		}
	}
?>