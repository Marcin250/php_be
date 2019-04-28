<?php
	require_once __DIR__ . '../../../vendor/autoload.php';

	//use Dotenv\Dotenv as Dotenv;
	use Pusher\Pusher as Pusher;
	use App\Objects\Chat;
	use Config\DatabaseConnection;

	if(!isset($_SESSION)) { session_start(); }

	function get_content($URL)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $URL);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	// $dotenv = Dotenv::create(__DIR__ . '/../..');
	// $dotenv->load();

	if(isset($_SESSION['id']) && isset($_GET['u']))
	{
		$_SESSION['recipient'] = $_GET['u'];
		$titleURL = 'Wyloguj';
		$_SESSION['chat'] = $_SESSION['id'] < $_SESSION['recipient'] ? 'chat' . $_SESSION['id'] . $_SESSION['recipient'] : 'chat' . $_SESSION['recipient'] . $_SESSION['id'];
	}
	elseif(isset($_SESSION['id']) && isset($_SESSION['recipient']))
	{
		$titleURL = 'Wyloguj';
		$_SESSION['chat'] = $_SESSION['id'] < $_SESSION['recipient'] ? 'chat' . $_SESSION['id'] . $_SESSION['recipient'] : 'chat' . $_SESSION['recipient'] . $_SESSION['id'];
	}
	else
	{
		header("Location:" . getenv('APP_URL'));
		die;
	}

	$urlUser = getenv('APP_URL') . '/user/?id=' . $_SESSION['recipient'];
	$dataUser = get_content($urlUser);
	$daneUser = json_decode($dataUser);
?>

<html>
	<head>
		<title>Simple chatting application - <?php echo $_SESSION['chat']; ?></title>
		<link rel="icon" type="image/png" href="/favicon.png"/>
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
	<style>
		::-webkit-scrollbar {
  			width: 4px;
		}

		::-webkit-scrollbar-track {
  			background: #ececec; 
		}

		::-webkit-scrollbar-thumb {
  			background: #c1c1c1; 
		}

		::-webkit-scrollbar-thumb:hover {
  			background: #c1c1c1;
		}

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
  			padding: 14px 8px;
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
			height: 60px;
		  	padding: 3px;
		}

		.container img {
		  	float: left;
		  	max-width: 50px;
		  	width: 100%;
		  	margin-right: 20px;
		  	border-radius: 50%;
		  	margin-top: 2px;
		}

		.darker {
			background-color: #dedede;
			text-align: right;
		}

		.darker img {
			float: right;
		  	max-width: 50px;
		  	width: 100%;
		  	margin-left: 20px;
		  	border-radius: 50%;
		  	margin-right: 0px;
		  	margin-top: 2px;
		}

		.chat {
			overflow: scroll;
		    width: 100%;
		    height: 94.3%;
		    overflow-x: hidden;;
		}

		.wrapperbtn
		{
		    background-color: #c1c1c1;
		    border: none;
		    color: black;
		    width: 100%;
		    cursor: pointer;
		}

		.wrapperbtn span 
		{
		  	cursor: pointer;
		  	display: inline-block;
		  	position: relative;
		  	transition: 0.5s;
		}

		.wrapperbtn span:after 
		{
		  	content: '\00bb';
		  	position: absolute;
		  	opacity: 0;
		  	top: 0;
		  	right: -20px;
		  	transition: 0.5s;
		}

		.wrapperbtn:hover span {
		  	padding-right: 25px;
		}

		.wrapperbtn:hover span:after {
		  	opacity: 1;
		  	right: 0;
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

		p {
    		margin: 6px;
		}

		time {
			font-size: 9px;
		}

      	@media screen and (max-width: 700px) {
      		.topBar {margin: 0px; margin-left: 0px;} 
      		.topBar a {float: none; display: block; text-align: left; background-color: #333; text-align: center;} 
      		.topBar-right {float: none;} 
      	}
	</style>
	<body onload="joinChat()">
		<header id="header">
			<div class="topBar">
				<div style="float: left">
					<?php if (is_array($daneUser) || is_object($daneUser)) echo '<b>Rozmowa z użytkownikiem: ' . $daneUser->name . '</b>'; ?>
				</div>
				<div class="dropdown">
					<button class="dropbtn"> <?php echo $_SESSION['email']; ?>
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="dropdown-content">
						<a href="/" onclick="leaveChat()"> Powrót
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
			<?php
				$dbConnection = new DatabaseConnection();
				$connetion = $dbConnection->getConnection();
				$chat = new Chat($connetion);
				$chat->ChatName = $_SESSION['chat'];
				$pastMessagesCount = $chat->getTotalPastMessages();
				if($pastMessagesCount > 0) echo '<button id="wrapperbutton" class="wrapperbtn" onclick="loadPastMessages()"> <span> Wczytaj poprzednie wiadomości </span> </button>';
			?>
			</div>
			<input class="textarea" id="chatMessage" type="text" placeholder="Napisz wiadomość" onkeypress="sendMessage(event)"/>
		</div>
  	<script>
  		Pusher.logToConsole = false;
  		var channelName = <?php echo '"' . $_SESSION['chat'] . '"'; ?>;
  		var channelUser = <?php echo '"' . $_SESSION['id'] . '"'; ?>;
  		var channelUserName = <?php echo '"' . $_SESSION['name'] . '"'; ?>;
  		var previousUser = null;
  		var dynamicId = 1;
  		var pastMessagesFrom = 0;
  		var pastMessagesCount = <?php echo '"' . $pastMessagesCount . '"'; ?>;
  		var userImage = <?php echo '"' . $_SESSION['image'] . '"'; ?>;
  		var otherImage = <?php echo '"' . $daneUser->image . '"'; ?>;
  		var dynamidPastMessagesId = 1;

	    var pusher = new Pusher('ff71283c9ea50e531f55', {
	      	cluster: 'eu',
	      	forceTLS: true
	    });

	    var channel = pusher.subscribe(channelName);
	    channel.bind('chat', function(data) {
	    	if(data.author == channelUser)
	    	{
	    		var image = userImage;
	    		var classContainer = "container darker";
	    	}
	    	else
	    	{
	    		var image = otherImage;
	    		var classContainer = "container";
	    	}

	    	var newDiv = document.createElement('div');
	    	newDiv.id = 'container' + dynamicId;
	    	newDiv.className = classContainer;
	    	document.getElementById('chatContainer').appendChild(newDiv);
	    	if(previousUser != data.author)
	    	{
	    		var newImg = document.createElement('img');
			    newImg.src = image;
			    if(classContainer == "container")
			    	newImg.className = 'right';
			    document.getElementById(newDiv.id).appendChild(newImg);
			    previousUser = data.author;
	    	}
	    	var newPar = document.createElement('p');
	    	newPar.innerHTML = data.message;
	    	document.getElementById(newDiv.id).appendChild(newPar);

	    	var newTime = document.createElement('time');
	    	newTime.innerHTML = data.createdAt;
	    	document.getElementById(newDiv.id).appendChild(newTime);
	    	dynamicId++;
	    });

	    function loadPastMessages()
	    {
	    	if(pastMessagesCount > 0)
	    	{
				fetch('/chat/get-past-messages?chat=' + channelName + '&from=' + pastMessagesFrom + '&quantity=5')
	  			.then((resp) => resp.json())
	  			.then(function(data) {
	  				messagesArray = data.messages;
	  				messagesArray.reverse();
	  				data.messages.forEach(function(entry) {
	  					currentId = 'p' + dynamidPastMessagesId;
	  					if(entry.author == channelUser)
				    	{
				    		var image = userImage;
				    		var classContainer = "container darker";
				    	}
				    	else
				    	{
				    		var image = otherImage;
				    		var classContainer = "container";
				    	}

				    	var newDiv = document.createElement('div');
				    	newDiv.id = 'container' + currentId;
				    	newDiv.className = classContainer;
				    	if(dynamidPastMessagesId == 1)
				    		document.getElementById("chatContainer").insertBefore(newDiv, document.getElementById("wrapperbutton"));
				    	else
				    		document.getElementById("chatContainer").insertBefore(newDiv, document.getElementById('p' + (pastMessagesCount - 1)));
				    	if(previousUser != entry.author)
				    	{
				    		var newImg = document.createElement('img');
						    newImg.src = image;
						    if(classContainer == "container")
						    	newImg.className = 'right';
						    document.getElementById(newDiv.id).appendChild(newImg);
						    previousUser = entry.author;
				    	}
				    	var newPar = document.createElement('p');
				    	newPar.innerHTML = entry.message;
				    	document.getElementById(newDiv.id).appendChild(newPar);

				    	var newTime = document.createElement('time');
				    	newTime.innerHTML = entry.createdAt;
				    	document.getElementById(newDiv.id).appendChild(newTime);
				    	dynamidPastMessagesId++;
					});
				})
  			}
  			pastMessagesFrom += 5;
  			pastMessagesCount -= 5;
	    }

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
				document.getElementById("chatMessage").value = "";
			}
	    }

	    function joinChat()
	    {
	    	var form = new FormData();
				form.append("chat", channelName);
				form.append("author", channelUser);
				form.append("message", channelUserName + ' dołączył do chatu.');
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

	    function leaveChat()
	    {
	    	var form = new FormData();
				form.append("chat", channelName);
				form.append("author", channelUser);
				form.append("message", channelUserName + ' opuścił chat.');
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
	</script>
	</body>
</html>