<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../../vendor/autoload.php';
	
	if(!isset($_SESSION)) { session_start(); }

	session_destroy();

	header("Location: " . getenv('APP_URL'));
	exit();
?>