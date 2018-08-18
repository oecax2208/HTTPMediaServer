<?php
include 'service.php';

if (isset($_POST["file"])) {
	$file = $_POST["file"];
	if (file_exists($cpath . $file)) {
		if (unlink($cpath . $file)) {
			echo "<p>Deleted file " . $file . "</p>";
			$md = $mpath . $file . ".meta";
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
