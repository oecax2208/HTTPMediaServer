<?php
include 'check.php';

if (isset($_POST["file"])) {
	$file = $_POST["file"];
	if (file_exists("./cache0/" . $file)) {
		if (unlink("./cache0/" . $file)) {
			echo "<p>Deleted file " . $file . "</p>";
			$md = "./cache0/metadata/" . $file . ".meta";
			if (file_exists($md)) {
				if (unlink($md)) {
					echo "<p>Removed metadata file " . $md . "</p>";
				} else {
					echo "Failed to remove metadata file " . $md;
				}
			}
		} else {
			echo "Failed to remove file: " . $file;
		}
	} else {
		echo "File does not exist: " . $file;
	}
} else {
	echo "Error: No valid post";
}

header("Location: index.php");
?>
