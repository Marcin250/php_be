<?php
	require_once __DIR__ . '../../vendor/autoload.php';

	use Config\GoogleClient;

	$googleClient = new GoogleClient();
	$client = $googleClient->getClient();
	$loginURL = $client->createAuthurl();
?>

<html>
	<head>
		<title>Google Auth Test</title>
		<link rel="icon" type="image/png" href=""/>
	</head>
	<body>
		<form>
			<input type="button"> onclick="window.location = '<?php echo $loginURL; ?>'" Google+ </input>
		</form>
	</body>
</html>