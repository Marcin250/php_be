<?php
	print_r(scandir($_SERVER['DOCUMENT_ROOT']));
	print_r(scandir(__DIR__ . '/..'));
	print_r(getenv('DB_DATABASE'));
?>