<?php
	namespace App\Objects;

	require_once __DIR__ . '../../../vendor/autoload.php';
	use PDO;

	class Cache
	{
		private $expirationTime;
		private $cacheDirectory;
		
		public function __construct($cachePath)
		{
			$this->cacheDirectory = $cachePath;
		}
		
		public function remember($dataIndex, $expirationTime)
		{
			$data = json_encode(array("message" => "Błąd."));
			$this->expirationTime = $expirationTime;
			$cacheFile = $this->cacheDirectory . $dataIndex;
			$fileTime = (file_exists($cacheFile)) ? @filemtime($cacheFile) : 0;
			if($fileTime == 0)
				return false;
			else
			{
				if((time() - $this->expirationTime) < $fileTime)
					$data = $this->cacheRead($cacheFile);
				else
					return false;
			}
			return $data;
		}

		public function cacheRead($cacheFile)
		{
			if(file_exists($cacheFile))
				return file_get_contents($cacheFile);
			else
				return json_encode(array("message" => "Błąd."));
		}

		public function cacheWrite($dataIndex, $data)
		{
			$cacheFile = $this->cacheDirectory . $dataIndex;
			if(file_exists($cacheFile))
				unlink($cacheFile);
			$openFile = fopen($cacheFile, 'w');
			fwrite($openFile, $data);
			fclose($openFile);
			return $this->cacheRead($cacheFile);
		}
	}
?>