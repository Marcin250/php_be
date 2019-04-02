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
  			color: #f2f2f2;
  			
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

		.sidenav {
  			height: 100%;
  			width: 200px;
  			position: fixed;
 			z-index: 1;
  			top: 0;
  			left: 0;
  			background-color: #222;
  			overflow-x: hidden;
		}

		.sidenav a, .dropdown-btn {
  			padding: 16px 8px 14px 16px;
  			text-decoration: none;
  			font-size: 14px;
  			color: white;
  			display: block;
  			border: none;
  			background: none;
  			width: 100%;
  			text-align: left;
  			cursor: pointer;
  			outline: none;
		}

		.sidenav a:hover {
  			color: #aaa;
		}

		.main {
  			margin-left: 200px; /* Same as the width of the sidenav */
  			font-size: 20px; /* Increased text to enable scrolling */
  			padding: 0px 10px;
		}

		.active {
  			background-color: #dd4b39;
  			color: white;
		}

		.dropdown-container {
  			display: none;
  			background-color: #262626;
  			padding-left: 8px;
		}

		.fa-caret-down {
  			float: right;
  			padding-right: 8px;
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
		$url = 'https://api-portalw.herokuapp.com/user/index.php';
		$data = get_content($url);
		$dane = json_decode($data);
		if (is_array($dane) || is_object($dane))
		{ 
			echo '
			<section class="userList" id="userList">
  				<div class="sidenav">
  					<button class="dropdown-btn"> Lista użytkowników 
    					<i class="fa fa-caret-down"></i>
  					</button>
  					<div class="dropdown-container">';
					foreach ($dane->data as $item)
					{
						echo '<a href="#">' . $item->login . '</a>';
					}
					echo '
					</div>
  				</div>
  			</section>';
  		}
		if(isset($_SESSION['token'])) echo '
		<section class="userData" id="userData">
			<div class="main">
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
  		?>
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
	</script>
	</body>
</html>