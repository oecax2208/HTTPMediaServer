<?php
include 'check.php';

$target_dir = "./cache0";
$target_file = basename($_FILES["newUpload"]["name"]);
$uploadOk = 0;
$localName = $target_file . '';

if (isset($_POST["submit"])) {
	if ($_FILES["newUpload"]["error"] != 0) {
		echo "Error uploading file: " . $_FILES["newUpload"]["error"] . "<br/>";
	} else {
		if (file_exists($target_file)) {
			echo "File already exists";
		} else {
			$uploadOk = 1;
		}
	}
} else {
	echo "You should not be here! <br/>";
}

if (isset($_POST["localName"])) {
	$t = $_POST["localName"];
	if (strlen($t) > 0) $localName = $t;
}

if ($uploadOk == 1) {
	if (move_uploaded_file($_FILES["newUpload"]["tmp_name"], "$target_dir/$target_file")) {
		echo "Uploaded file " . basename($_FILES["newUpload"]["name"]) . " has been uploaded.";
		writeMeta($target_file, $localName);
	} else {
		echo "Error writing to file <br/>";
		echo "$target_dir/$target_file";
	}
	header("Location: index.php");
} else {
	echo "File was not uploaded.";
	header("Location: /index.php");
}
?>
