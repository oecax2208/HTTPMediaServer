<?php
include 'service.php';

$target_dir = $cpath;
$target_file = basename($_FILES["newUpload"]["name"]);
$uploadOk = 0;
$localName = $target_file . '';

if (isset($_POST["submit"])) {
	echo "<p>Found post!</p>";
	if ($_FILES["newUpload"]["error"] != 0) {
		echo "<p>Error uploading file: " . $_FILES["newUpload"]["error"] . "</p>";
	} else {
		if (file_exists($target_file)) {
			echo "<p>File already exists</p>";
		} else {
                        if (isBlacklistExtension(strtolower(pathinfo($_FILES["newUpload"]["name"], PATHINFO_EXTENSION)))) {
                                echo "<p>File is not allowed</p>";
                        } else {
                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                $mime = finfo_file($finfo, $_FILES['newUpload']['tmp_name']);
                                finfo_close($finfo);
                                if (StartsWith($mime, 'text')) {
                                    echo "<p>File is not allowed</p>";
                                } else {
                                    $uploadOk = 1;
                                }
                        }
		}
	}
} else {
	echo "Error: No valid post";
}

if (isset($_POST["localName"])) {
	$t = $_POST["localName"];
	if (strlen($t) > 0) {
            $localName = $t;
        }
}

if ($uploadOk == 1) {
	if (move_uploaded_file($_FILES["newUpload"]["tmp_name"], $target_dir . $target_file)) {
        echo "Uploaded file " . basename($_FILES["newUpload"]["name"]) . " has been saved.";
        writeMeta($target_file, $localName);
        header("Location: index.php");
	} else {
		echo "Error writing to file <br/>";
		echo $target_dir . $target_file;
	}
} else {
        echo "<p>File was not uploaded</p>";
}
header("refresh:2;url=index.php");
