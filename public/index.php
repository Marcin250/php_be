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
	<link rel="stylesheet" href="styles.css" type="text/css">
	<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
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
			window.location = '/chat?u=' + userId;
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