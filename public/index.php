<?php
	require_once __DIR__ . '../../vendor/autoload.php';

	use Config\GoogleClient;
	//use Dotenv\Dotenv as Dotenv;

	//$dotenv = Dotenv::create(__DIR__ . '/..');
	//$dotenv->load();

	if(!isset($_SESSION)) { session_start(); }

	if(isset($_SESSION['id']))
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
		<title>Simple chatting application</title>
		<link rel="icon" type="image/png" href="/favicon.png"/>
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
	<style>
		button:focus {outline:0;}
		
		::-webkit-scrollbar {
  			width: 4px;
		}

		::-webkit-scrollbar-track {
  			background: #ececec; 
		}

		::-webkit-scrollbar-thumb {
  			background: #ea5800; 
		}

		::-webkit-scrollbar-thumb:hover {
  			background: #ea5800;
		}

		body {
			margin: 0;
			width: 100wh;
			height: 90vh;
			color: #fff;
			background: linear-gradient(-45deg, #00bfde, #a06aff, #ff493b, #fff8bd);
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
		  	width: auto;
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
  			width: auto;
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
		 	width:550px;
		 	height:550px;
		 	margin:0 auto;
		 	background:#ececec;
		 	position:absolute;
		 	left:50%;
		 	top:50%;
		 	margin-left:-250px;
		 	margin-top:-250px;
		 	opacity: 0.9;
		}

		.wrapperbtn
		{
			border-radius: 4px;
		    background-color: #c1c1c1;
		    border: none;
		    color: black;
		    text-align: center;
		    font-size: 16px;
		    margin-left: 70px;
		    margin-top: 30px;
		    display: block;
		    float: left;
		    width: 215px;
		    transition: all 0.5s;
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

		h4 {
		    color: black;
		    font-size: 16px;
		    margin-left: 200px;
		    margin-top: 30px;
		    display: block;
		    float: initial;
		}

		h5 {
		    color: #dd4b39;
		    font-size: 18px;
		    float: initial;
		    padding-left: 14px;
		}

		img {
			max-height:50px;
    		max-width:50px;
			margin-left: 70px;
			margin-top: 30px;
		}

      	@media screen and (max-width: 500px) {
      		.topBar {margin: 0px; margin-left: 0px;} 
      		.topBar a {float: none; display: block; text-align: left; background-color: #333; text-align: center;} 
      		.topBar-right {float: none;} 
      	}

      	.sidebar {
		  	height: 100%;
		  	width: 240px;
		  	position: fixed;
		  	background-color: #ececec;
		  	margin-top: 52px;
		  	overflow: auto;
		}

		.sidebar a {
      		float: left;
		    color: #dd4b39;
		    text-align: left;
		    padding: 14px;
		    text-decoration: none;
		    font-size: 15px;
		    font-weight: 400;
		    display: block;
		    width: 240px;
		    cursor: pointer;
		}

		.sidebar a:hover {
  			background-color: #ddd;
		}

		.sidebar p {
      		float: left;
		    color: #dd4b39;
		    text-align: center;
		    text-decoration: none;
		    font-size: 18px;
		    font-weight: 450;
		    display: block;
		    width: 240px;
		    margin-top: 1px;
		    margin-bottom: 1px;
		}
	</style>
	<body>
		<header id="header">
			<div class="topBar">
				<?php
				if (isset($_SESSION['email']))
				{
				echo '
				<div style="float: left;width: 240px;height: 52px;">
					<h5>
						<i class="fas fa-users"></i>
  						Lista użytkowników
					</h5>
				</div>';
				}
				if($titleURL == 'Zaloguj') echo '
				<div class="dropdown">
					<button class="dropbtn">' . $titleURL . '
						<i class="fas fa-sign-in-alt"></i>
					</button>
					<div class="dropdown-content">
      					<a href="' . $loginURL . '"> Google </a>
    				</div>
				</div>';
				elseif(isset($_SESSION['email']))
				echo '
				<div class="dropdown">
					<button class="dropbtn">' . $_SESSION['email'] . '
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="dropdown-content">
      					<a href="/user/google-logout.php">' . $titleURL . '
      						<i class="fas fa-sign-in-alt"></i>
      					</a>
    				</div>
				</div>';
				if (isset($_SESSION['email']))
				{
				$url = getenv('APP_URL') . 'user/list-by-privilege';
				$data = get_content($url);
				$daneByPrivilege = json_decode($data);
				if ((is_array($daneByPrivilege) || is_object($daneByPrivilege)) && isset($_SESSION['email']))
				echo '
				<div class="sidebar">';
				foreach ($daneByPrivilege->data as $key => $privilegeArray)
				{
					echo '<p> ' . ucfirst($key) . ' (' . count($privilegeArray) . ') </p>';
					foreach ($privilegeArray as $item)
					{
						if($_SESSION['id'] != $item->id)
							echo '<a value="' . $item->id . '" onclick="getUser(' . $item->id . ')">' . $item->name . '</a>';
						else
							echo '<a value="' . $item->id . '">' . $item->name . '</a>';
					}
                }
                echo '
    			</div>
				';
				}
				?>
			</div>
		</header>
		<div class="w3-container w3-center w3-animate-top" style="width: 1680; float: right; padding-right: 240px; display: contents;">
			<p>Stan aplikacji:</p>
  			<h1>Aplikacja w fazie testów</h1>
  			<?php if(!isset($_SESSION['email'])) echo "<h1>Zaloguj się aby móc korzystać z aplikacji</h1>"; ?>
		</div>
		<?php 
		if(isset($_SESSION['email']))
		echo '
		<div class="wrapper">
			<h2>Informacje o użytkowniku</h2>
			<div style="overflow:auto">
				<img src="https://res.cloudinary.com/hhidlawm6/image/upload/v1544290892/users/root.png" id="userImage">
			</div>
			<div style="overflow:auto">
				<h3>Nazwa:</h3>
				<h4 id="userName"></h4>
			</div>
			<div style="overflow:auto">
				<h3>Email:</h3>
				<h4 id="userEmail"></h4>
			</div>
			<div style="overflow:auto">
				<h3>Status:</h3>
				<h4 id="userStatus"></h4>
			</div>
			<div style="overflow:auto">
				<h3>Uprawnienia:</h3>
				<h4 id="userPrivielege"></h4>
			</div>
			<div style="overflow:auto">
				<h3>Data założenia:</h3>
				<h4 id="userDate"></h4>
			</div>
			<div style="overflow:auto">
				<button type="button" class="wrapperbtn" onclick="goChat()">
					<span> Przejdź do rozmowy </span>
				</button>
			</div>
		</div>
		'; 
		?>
  	<script>
  		var userId = 1;
		var dropdown = document.getElementsByClassName("dropdown-btn");
		var i;
		for (i = 0; i < dropdown.length; i++) {
 			dropdown[i].addEventListener("click", function() {
  				this.classList.toggle("active");
  				var dropdownContent = this.nextElementSibling;
  				if (dropdownContent.style.display === "block") {
  					dropdownContent.style.display = "none";
  				} else {
  					dropdownContent.style.display = "block";
  				}
  			});
		}

		function getUser(id)
		{
			userId = id;
			fetch('/user/index?id=' +id)
  				.then((resp) => resp.json())
  				.then(function(data) {
  					document.getElementById("userImage").src = data.image;
					document.getElementById("userName").innerHTML = data.name;
					document.getElementById("userEmail").innerHTML = data.email;
					document.getElementById("userStatus").innerHTML = data.status;
					document.getElementById("userPrivielege").innerHTML = data.privielege;
					document.getElementById("userDate").innerHTML = data.createdAt;
			})
		}

		function goChat()
		{
			window.location.href = 'https://php-ws.herokuapp.com/chat?u=' + userId;
		}

		Pusher.logToConsole = false;

	    var pusher = new Pusher('ff71283c9ea50e531f55', {
	      	cluster: 'eu',
	      	forceTLS: true
	    });

	    var channel = pusher.subscribe('home');
	    channel.bind('login', function(data) {
	    	console.log(data);
	    });
	</script>
	</body>
</html>