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
		body {
  			margin: 0;
  			font-family: Arial, Helvetica, sans-serif;
		}
		.topBar {
  			overflow: hidden;
 			background-color: #222;
		}

      	.topBar a {
  			float: right;
  			color: #dd4b39;
  			text-align: center;
  			padding: 14px 16px;
  			text-decoration: none;
  			font-size: 17px;
		}

		.dropdown {
  			float: right;
  			overflow: hidden;
		}

      	.dropdown .dropbtn {
  			font-size: 16px;  
  			border: none;
  			outline: none;
  			color: white;
  			padding: 14px 16px;
  			background-color: inherit;
  			font-family: inherit;
  			margin: 0;
		}

		.topBar .dropdown:hover .dropbtn {
  			background-color: #dd4b39;
		}

		.dropdown-content {
  			display: none;
  			position: absolute;
  			background-color: #f9f9f9;
  			min-width: 160px;
  			box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  			z-index: 1;
		}

		.dropdown-content a {
  			float: none;
  			color: black;
  			padding: 12px 16px;
  			text-decoration: none;
  			display: block;
  			text-align: left;
		}

		.dropdown-content a:hover {
  			background-color: #ddd;
		}

		.dropdown:hover .dropdown-content {
  			display: block;
		}

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
				<?php if($titleURL == 'Zaloguj') echo '
				<div class="dropdown">
					<button class="dropbtn">' . $titleURL . '
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="dropdown-content">
      					<a href="' . $loginURL . '"> Google </a>
    				</div>
				</div>';
				else
					echo '<a href="/user/google-logout.php">' . $titleURL . '</a>';
				?>
			</div>
		</header>
		<?php 
		if(isset($_SESSION['token'])) echo '
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
  		</section>';
  		$url = 'https://api-portalw.herokuapp.com/user/index.php';
		$data = get_content($url);
		$dane = json_decode($data);
		if (is_array($dane) || is_object($dane))
		{ 
			echo '
  			<div>
  				<select name="userList">';
					foreach ($dane->data as $item)
					{
						echo '
						<option value="' . $item->id . '">' .$item->login .'> </option>';
					}
			echo '
				</select> 
  			</div>';
  		}
  		?>
	</body>
</html>