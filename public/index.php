<?php
	require_once __DIR__ . '../../vendor/autoload.php';

	use Config\GoogleClient;

	$googleClient = new GoogleClient();
	$client = $googleClient->getClient();
	$loginURL = $client->createAuthUrl();
?>

<html>
	<head>
		<title>Google Auth Test</title>
	</head>
	<body>
		<a href="'<?php echo $loginURL; ?>'"></a>
	</body>
</html>