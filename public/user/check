<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");

	require_once __DIR__ . '../../../vendor/autoload.php';

	if(!isset($_SESSION)) { session_start(); }

	if(isset($_SESSION['id']))
	{
		echo json_encode($_SESSION);
	}
	else
		echo json_encode(array("message" => "Błąd wyszukiwania."));
?>