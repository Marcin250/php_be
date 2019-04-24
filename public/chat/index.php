<?php
	require_once __DIR__ . '../../../vendor/autoload.php';

	//use Dotenv\Dotenv as Dotenv;
	use Pusher\Pusher as Pusher;

	if(!isset($_SESSION)) { session_start(); }

	if(isset($_SESSION['id']) && isset($_GET['u']))
	{
		$_SESSION['recipient'] = $_GET['u'];
		$titleURL = 'Wyloguj';
		if($_SESSION['id'] < $_SESSION['recipient'])
			$chatName = 'chat' . $_SESSION['id'] . $_SESSION['recipient'];
		else
			$chatName = 'chat' . $_SESSION['recipient'] . $_SESSION['id'];
	}
	elseif(isset($_SESSION['id']) && isset($_SESSION['recipient']))
	{
		$titleURL = 'Wyloguj';
		if($_SESSION['id'] < $_SESSION['recipient'])
			$chatName = 'chat' . $_SESSION['id'] . $_SESSION['recipient'];
		else
			$chatName = 'chat' . $_SESSION['recipient'] . $_SESSION['id'];
	}
	else
	{
		// $dotenv = Dotenv::create(__DIR__ . '/../..');
		// $dotenv->load();
		header("Location:" . getenv('APP_URL'));
		die;
	}

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
		<title>Stan aplikacji: Projektowanie</title>
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
	<style>
		body {
			margin: 0;
			background: linear-gradient(-45deg, #EE7752, #E73C7E, #23A6D5, #23D5AB);
			background-size: 400% 400%;
			-webkit-animation: Gradient 15s ease infinite;
			-moz-animation: Gradient 15s ease infinite;
			animation: Gradient 15s ease infinite;
		}

		@-webkit-keyframes Gradient {
			0% {
				background-position: 0% 50%
			}
			0% {
				background-position: 100% 50%
			}
			100% {
				background-position: 0% 50%
			}
		}

		@-moz-keyframes Gradient {
			0% {
				background-position: 0% 50%
			}
			50% {
				background-position: 100% 50%
			}
			100% {
				background-position: 0% 50%
			}
		}

		@keyframes Gradient {
			0% {
				background-position: 0% 50%
			}
			50% {
				background-position: 100% 50%
			}
			100% {
				background-position: 0% 50%
			}
		}

		.topBar {
  			overflow: hidden;
 			background: #ececec;
		}

      	.topBar a {
      		float: right;
  			color: #dd4b39;
  			text-align: center;
  			padding: 14px 16px;
  			text-decoration: none;
  			font-size: 17px;
		}

		.topBar a.left {
  			float: left;
		}

		.dropdown {
  			float: right;
  			overflow: hidden;
		}

      	.dropdown .dropbtn {
  			font-size: 16px;  
  			border: none;
  			outline: none;
  			color: #dd4b39;
  			padding: 14px 16px;
  			background-color: inherit;
  			font-family: inherit;
  			margin: 0;
		}

		.topBar .dropdown:hover .dropbtn {
  			background-color: #dd4b39;
  			color: white;
  			.topBar a {background-color: grey;}
		}

		.topBar a:hover {
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
  			cursor: pointer;
		}

		.dropdown-content a:hover {
  			background-color: #ddd;
		}

		.dropdown:hover .dropdown-content {
  			display: block;
		}

		.fa-caret-down {
  			float: right;
  			padding-right: 8px;
		}

		.wrapper
		{
			border-radius: 8px;
		 	width:1820px;
		 	height:800px;
		 	margin:0 auto;
		 	background:#ececec;
		 	position:absolute;
		 	top:50%;
		 	margin-left:50px;
		 	margin-top:-375px;
		 	opacity: 0.9;
		}

		h2 {
			color: black;
			font-size: 18px;
			text-align: center;
		}

		h3 {
			color: black;
			font-size: 16px;
			margin-left: 70px;
			margin-top: 30px;
			display:block;
 			float:left;
		}

		img {
			max-height:50px;
    		max-width:50px;
			margin-left: 70px;
			margin-top: 30px;
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

		hr {
	    	border: 1px solid #f1f1f1;
	    	margin-bottom: 25px;
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
				<div class="dropdown">
					<button class="dropbtn"> <?php echo $_SESSION['email']; ?>
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="dropdown-content">
						<a href="/"> Powrót
      						<i class="fas fa-sign-in-alt"></i>
      					</a>
      					<a href="/user/google-logout.php"> <?php echo $titleURL; ?>
      						<i class="fas fa-sign-in-alt"></i>
      					</a>
    				</div>
				</div>
			</div>
		</header>
		<div class="wrapper">
		<?php

			// $dotenv = Dotenv::create(__DIR__ . '/..');
			// $dotenv->load();

			$url = getenv('APP_URL') . '/user/?id=' . $_SESSION['recipient'];
			$data = get_content($url);
			$dane = json_decode($data);
			if (is_array($dane) || is_object($dane)) echo '<h2>Rozmowa z użytkownikiem: ' . $dane->name . '</h2>';
		?>
			<div style="overflow:auto">
			</div>
		</div>
  	<script>
  		Pusher.logToConsole = false;

	    var pusher = new Pusher('ff71283c9ea50e531f55', {
	      	cluster: 'eu',
	      	forceTLS: true
	    });

	    var channel = pusher.subscribe(<?php echo '"' . $chatName . '"'; ?>);
	    channel.bind('chat', function(data) {
	    	console.log(data);
	    });
	</script>
	</body>
	<?php
		$pushData['message'] = $_SESSION['name'] . ' dołączył do chatu.';
		$pusherOptions = array(
    		'cluster' => getenv('PUSHER_CLUSTER'),
    		'useTLS' => true
  		);
		$pusher = new Pusher(getenv('PUSHER_KEY'), getenv('PUSHER_SECRET'), getenv('PUSHER_ID'), $pusherOptions);
		$pusher->trigger($chatName, 'chat', $pushData);
	?>
</html>