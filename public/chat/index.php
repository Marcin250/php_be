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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
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

      	.dropdown .dropbtn{
  			font-size: 16px;  
  			border: none;
  			outline: none;
  			color: #dd4b39;
  			padding: 14px 16px;
  			background-color: inherit;
  			font-family: inherit;
  			margin: 0;
		}

		b {
  			float: left;
		    height: 52px;
		    font-size: 16px;
		    color: #dd4b39;
		    padding: 14px 8px;
		    background-color: inherit;
		    font-family: inherit;
		    margin: 0;
		    font-weight: 400;
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
			width: 100%;
		    height: 94.5%;
		    background: #ffffff;
		    position: absolute;
		}

		h2 {
			color: black;
			font-size: 18px;
			text-align: center;
			font-family: inherit;
			margin-top: 0;
    		margin-bottom: 0;
		}

		.container {
		  	padding: 10px;
		}

		.container img {
		  	float: left;
		  	max-width: 50px;
		  	width: 100%;
		  	margin-right: 20px;
		  	border-radius: 50%;
		}

		.darker {
			background-color: #dedede;
			text-align: right;
		}

		.darker img {
			float: right;
		  	max-width: 50px;
		  	width: 100%;
		  	margin-right: 20px;
		  	border-radius: 50%;
		}

		.chat {
			overflow: scroll;
		    width: 100%;
		    height: 94.3%;
		    overflow-x: hidden;;
		}

		input.textarea {
			position: inherit;
		    bottom: 0px;
		    left: 0px;
		    right: 0px;
		    width: 100%;
		    height: 50px;
		    z-index: 99;
		    outline: none;
		    padding-left: 20px;
		    padding-right: 20px;
		    font-weight: 500;
		    border-width: 2px;
		    border-bottom: none;
		    border-left: none;
		    border-right: none;
		    border-color: #dedede;
		}

      	@media screen and (max-width: 700px) {
      		.topBar {margin: 0px; margin-left: 0px;} 
      		.topBar a {float: none; display: block; text-align: left; background-color: #333; text-align: center;} 
      		.topBar-right {float: none;} 
      	}
	</style>
	<body>
		<header id="header">
			<div class="topBar">
				<div style="float: left">
					<?php

					// $dotenv = Dotenv::create(__DIR__ . '/..');
					// $dotenv->load();

					$url = getenv('APP_URL') . '/user/?id=' . $_SESSION['recipient'];
					$data = get_content($url);
					$dane = json_decode($data);
					if (is_array($dane) || is_object($dane)) echo '<b>Rozmowa z użytkownikiem: ' . $dane->name . '</b>';
					?>
				</div>
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
			<div class="chat" id="chatContainer">
				<div class="container darker">
				  	<img src="https://res.cloudinary.com/hhidlawm6/image/upload/v1544290892/users/root.png" alt="Avatar" style="width:100%;">
				  	<p>Wiadomość od Admin.</p>
				</div>
				<div class="container">
				  	<img src="https://lh5.googleusercontent.com/-CvoOSH2G-E4/AAAAAAAAAAI/AAAAAAAAAAA/ACevoQNNsX50cxlY-CyyCzKtTESoQK-Lug/mo/photo.jpg?sz=50" alt="Avatar" class="right" style="width:100%;">
				  	<p>Wiadomość od profesjonalnetestyapki.</p>
				</div>
			</div>
			<input class="textarea" id="chatMessage" type="text" placeholder="Type here..." onkeypress="sendMessage(event)"/>
		</div>
  	<script>
  		Pusher.logToConsole = false;
  		var channelName = <?php echo '"' . $chatName . '"'; ?>;
  		var channelUser = <?php echo '"' . $_SESSION['id'] . '"'; ?>;
  		var dynamicId = 1;

	    var pusher = new Pusher('ff71283c9ea50e531f55', {
	      	cluster: 'eu',
	      	forceTLS: true
	    });

	    var channel = pusher.subscribe(channelName);
	    channel.bind('chat', function(data) {
	    	if(data.author == channelUser) // Dark Container
	    	{
	    		var image = <?php echo '"' . $_SESSION['image'] . '"'; ?>;
	    		var classContainer = "container darker";
	    	}
	    	else // Normal Container
	    	{
	    		var image = <?php echo '"' . $dane->image . '"'; ?>;
	    		var classContainer = "container";
	    	}
	    	var chatContainer = "chatContainer";
	    	var message = data.message;

	    	var newDiv = document.createElement('div');
	    	newDiv.className = classContainer;
	    	document.getElementById('chatContainer').appendChild(newDiv);
	    });

	    function sendMessage(event)
	    {
	    	if (event.keyCode == 13) {
		    	var form = new FormData();
				form.append("chat", channelName);
				form.append("author", channelUser);
				form.append("message", document.getElementById("chatMessage").value);
				var settings = {
	  				"async": true,
	  				"processData": false,
  					"contentType": false,
					"url": "http://php-ws.herokuapp.com/chat/send-message",
					"method": "POST",
					"data": form
				};
				$.ajax(settings);
			}
	    }
	</script>
	</body>
	<?php
		$pushData['author'] = $_SESSION['id'];
		$pushData['message'] = $_SESSION['name'] . ' dołączył do chatu.';
		$pusherOptions = array(
    		'cluster' => getenv('PUSHER_CLUSTER'),
    		'useTLS' => true
  		);
		$pusher = new Pusher(getenv('PUSHER_KEY'), getenv('PUSHER_SECRET'), getenv('PUSHER_ID'), $pusherOptions);
		$pusher->trigger($chatName, 'chat', $pushData);
	?>
</html>