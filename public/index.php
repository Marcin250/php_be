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
		<title>Stan aplikacji: Projektowanie</title>
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<style>
		body {
			margin: 0;
			width: 100wh;
			height: 90vh;
			color: #fff;
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
 			opacity: 0.9;
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
		 	width:500px;
		 	height:500px;
		 	margin:0 auto;
		 	background:#f7f7f7;
		 	position:absolute;
		 	left:50%;
		 	top:50%;
		 	margin-left:-250px;
		 	margin-top:-250px;
		 	opacity: 0.9;
		}

		h2 {
			color: black;
			font-size: 14px;
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
				<?php 
				$url = 'https://api-portalw.herokuapp.com/user/list.php';
				$data = get_content($url);
				$dane = json_decode($data);
				if (is_array($dane) || is_object($dane))
				{
				echo '
				<div class="dropdown" style="float: left">
					<button class="dropbtn">
						<i class="fas fa-users"></i>
  						Lista użytkowników
						<i class="fa fa-caret-down"></i>
					</button>
					<div id="userList" class="dropdown-content">';
						foreach ($dane->data as $item)
						{
						echo '
      					<a value="' . $item->id . '" onclick="getUser(' . $item->id . ')">' . $item->name . '</a>';
      					}
      					echo '
    				</div>
				</div>';
				}
				?>
				<?php 
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
				?>
			</div>
		</header>
		<div class="w3-container w3-center w3-animate-top">
			<p>Stan aplikacji:</p>
  			<h1>Projektowanie</h1>
		</div>
		<div class="wrapper">
			<h2>Informacje o użytkowniku</h2>
			<div style="overflow:auto">
				<img src="https://res.cloudinary.com/hhidlawm6/image/upload/v1544290892/users/root.png" id='userImage'>
			</div>
			<div style="overflow:auto">
				<h3 id='userName'>Nazwa</h3>
			</div>
			<div style="overflow:auto">
				<h3 id='userEmail'>Email</h3>
			</div>
			<div style="overflow:auto">
				<h3 id='userStatus'>Status</h3>
			</div>
			<div style="overflow:auto">
				<h3 id='userPrivielege'>Uprawnienia</h3>
			</div>
			<div style="overflow:auto">
				<h3 id='userDate'>Data założenia</h3>
			</div>
		</div>
  	<script>
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
			fetch('/user/index.php?id='+id)
  				.then((resp) => resp.json())
  				.then(function(data) {
  					document.getElementById("userImage").src = data.Image;
					document.getElementById("userName").innerHTML = data.Name;
					document.getElementById("userEmail").innerHTML = data.Email;
					document.getElementById("userStatus").innerHTML = data.Status;
					document.getElementById("userPrivielege").innerHTML = data.Privielege;
					document.getElementById("userDate").innerHTML = data.createdAt;
				})
		}
	</script>
	</body>
</html>