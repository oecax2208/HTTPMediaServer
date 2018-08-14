<?php
	$err = 'Unauthorized (401) - Click <a href="/">here</a> to leave';
	include './prv/auth.php';

	$a = isAuth();
	echo $a;
	if ($a > 0) {
		echo '<p>Returning...</p>';
		header("Location: index.php");
		exit;
	}

	requestAuth();
	die($err);

	function requestAuth() {
		header('WWW-Authenticate: Basic realm="Local PMS"');
        header('HTTP/1.0 401 Unauthorized');
	}

?>
