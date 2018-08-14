<?php
include 'check.php';

if (isset($_POST['file'])) {
	$file = $_POST['file'];
	if (file_exists("./cache0/" . $file)) {
		echo "<p>Researching file " . $file . "</p>";
		$mf = "./cache0/metadata/" . $file . ".meta";
		if (file_exists($mf)) {
			echo "<p>WARN: Found meta file already, it will be overwritten</p>";
			writeMeta($file, _CNAME($mf));
		} else {
			writeMeta($file);
		}

	} else {
	echo "Cannot find file: " . $file;
	}
} else {
	echo "Error: No valid post";
}

header("Location: index.php");
?>
