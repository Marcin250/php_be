<html lang="PL" dir="ltr">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <head>      
    <title>Marcin Murach | PHP Dev</title>
	<link rel="icon" type="image/png" href="../img/title.png" />
	<style>
      body, html {height: 100%; margin: 0;}

      .homeBackground {background-image: url('img/home_BG.png'); min-height: 100%; background-position: center; background-size: cover;}
      .aboutBackground {background-image: url('img/about_BG.png'); min-height: 100%; background-position: center; background-size: cover;}
      .contactBackground {background-image: url('img/contact_BG.png'); min-height: 100%; background-position: center; background-size: cover;}
      
      .topBar {top: 10; left: 25; right: 25; width: 97.5%; border-radius: 16px; position: fixed; display:block; transition: top 0.3s; font-family: 'Ubuntu', sans-serif;}
      .topBar-right {float: right;}
      .topBar a, u {float: left; display: block; color: #f2f2f2; text-align: center; padding: 15px; text-decoration: none; font-size: 20px;}
      .topBar a:hover {color: grey;}
      
      .bottomSM {width: 100%; text-align: center; background-color: #0d0d0d;}
      .fa {padding: 10px; font-size: 10px; width: 10px; text-align: center; text-decoration: none; margin: 5px 2px;}
      .fa:hover {opacity: 0.7;}
      .fa-facebook {background: #3B5998; color: white;}
      .fa-google {background: #dd4b39; color: white;}
      .fa-linkedin {background: #007bb5;color: white;}
      .fa-github {background: #737373;color: white;}
      .fa-stack-overflow {background: #ffa64d;color: black;}
      
      .bottomBar {width: 100%; text-align: center; background-color: #0d0d0d;}
      .bottomBar element {color: #f2f2f2; font-size: 16px;}
      
      @media screen and (max-width: 500px) {.topBar {margin: 0px; margin-left: 0px;} .topBar a {float: none; display: block; text-align: left; background-color: #333; text-align: center;} .topBar-right {float: none;} .bottomBar element {font-size: 10px;}}
	</style>
  </head>    
  <body>
	<div id="home" class="homeBackground">
		<header id="header">
			<div class="topBar" style="background-color: rgba(51, 51, 51, 0.3);">
				<div class="topBar-right">
					<a href="#home">Home</a>
						<u> </u>
					<a href="#about">About me</a>
						<u> </u>
					<a href="#contact">Contact</a>
				</div>
			</div>
		</header>
    </div>
	<section class="aboutBackground" id="about">
		<div>
		</div>
	</section>
    <section class="contactBackground" id="contact">
		<div>
		</div>
	</section>
	<footer id="footer">
		<div class="bottomSM">
				<a href="https://facebook.com" target="_blank" class="fa fa-facebook"></a>
				<a href="https://google.com" target="_blank" class="fa fa-google"></a>
				<a href="https://www.linkedin.com" target="_blank" class="fa fa-linkedin"></a>
				<a href="https://github.com/MMarcingit" target="_blank" class="fa fa-github"></a>
				<a href="https://stackoverflow.com/" target="_blank" class="fa fa-stack-overflow"></a>
		</div>
		<div class="bottomBar">
			<element>Copyright © 2018 Marcin Murach. All rights reserved.</element>
		</div>
	</footer>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
	$(document).ready(function(){
		$("a").on('click', function(event) {
		if (this.hash !== "") {
			event.preventDefault();
			var hash = this.hash;
			$('html, body').animate({
			scrollTop: $(hash).offset().top}, 800, function(){ window.location.hash = hash;});
		}});});
	</script>
  </body>
</html>	