<?php
	print_r(scandir($_SERVER['DOCUMENT_ROOT']));
	print_r(scandir(__DIR__ . '/..'));
?>