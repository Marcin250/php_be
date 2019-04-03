<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	require_once __DIR__ . '../../vendor/autoload.php';

	use App\Objects\Cache;
	if(isset($_GET['name']))
	{
		$cacheData = new Cache('../cache/');
		$data = $cacheData->remember($_GET['name'], 60);
		if($data)
			echo $data;
		else
			echo $cacheData->cacheWrite($_GET['name'], $_GET['name']);
	}
	else
		echo json_encode(array("message" => "Brak danych"));
?>