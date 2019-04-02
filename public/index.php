<?php
	require_once __DIR__ . '../../vendor/autoload.php';

	use Config\GoogleClient;

	if(!isset($_SESSION)) { session_start(); }

	if(isset($_SESSION['token']))
		$titleURL = 'Wyloguj';
	else
		$titleURL = 'Zaloguj';

	$googleClient = new GoogleClient();
	$client = $googleClient->getClient();
	$loginURL = $client->createAuthUrl();

	function get_content($URL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $URL);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
?>

<html>
	<head>
		<title>Google Auth Test</title>
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		body, html {height: 100%; margin: 0;}
		.topBar {border-radius: 16px; display:block; transition: top 0.3s; font-family: 'Ubuntu', sans-serif;}
      	.topBar-right {float: right;}
      	.topBar a, u {float: left; display: block; color: #f2f2f2; text-align: center; padding: 15px; text-decoration: none; font-size: 20px;}
      	.topBar a:hover {color: white;}

      	.fa-google {background: #dd4b39; color: white;}

      	table {
  			border-collapse: collapse;
  			border-spacing: 0;
 			width: 100%;
  			border: 1px solid #ddd;
		}

		th, td {
  			text-align: left;
  			padding: 16px;
		}

		tr:nth-child(even) {
  			background-color: #f2f2f2
		}

      	@media screen and (max-width: 500px) {
      		.topBar {margin: 0px; margin-left: 0px;} 
      		.topBar a {float: none; display: block; text-align: left; background-color: #333; text-align: center;} 
      		.topBar-right {float: none;} 
      	}
</style>
	<body>
		<header id="header">
			<div class="topBar">
				<div class="topBar-right">
					<a href="<?php if($titleURL == 'Zaloguj') echo $loginURL; else echo '/user/google-logout.php'?>" class="fa fa-google"> <?php echo $titleURL; ?> </a>
				</div>
			</div>
		</header>
		<?php if(isset($_SESSION['token'])) echo '
		<section class="userData" id="userData">
			<div>
				<h2>Dane użytkownika:</h2>
				<table>
  					<tr>
  						<th>Name</th>
    					<th>Email</th>
    					<th>Token</th>
    				</tr>
  					<tr>
    					<td>' . $_SESSION['name'] . '</td>
   	 					<td>' . $_SESSION['email'] . '</td>
    					<td>' . $_SESSION['token']['access_token'] . '</td>
  					</tr>
  				</table>
  			</div>
  		</section>'?>
  		<div>
  			<select name="userList">
			<?php
				$url = 'https://api-portalw.herokuapp.com/user/index.php';
				$data = get_content($url);
				$dane = json_decode($data);
				if (is_array($dane) || is_object($dane)){
					foreach ($dane->data as $item) {?>
					<option value="<?php echo $item->id;?>"> <?php echo $item->login;?> </option>
			<?php }}?>
			</select> 
  		</div>
	</body>
</html>