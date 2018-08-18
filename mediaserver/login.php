<?php
	include './prv/auth.php';

	$a = isAuth();
	echo $a;
	if ($a > 0) {
		echo '<p>Returning...</p>';
		header("Location: index.php");
		exit;
	}

	requestAuth();
	die($noAuth);

	function requestAuth() {
		header('WWW-Authenticate: Basic realm="Media Server"');
        	header('HTTP/1.0 401 Unauthorized');
	}

