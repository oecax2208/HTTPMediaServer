<?php
include 'service.php';

if (isset($_POST['file']) && isset($_POST['format'])) {
        $file = $_POST['file'];
        $format = $_POST['format'];
        if (file_exists(cpath() . $file)) {
		$nv = False;
		if (StartsWith($format, "?")) {
			$format = substr($format, 1);
			$nv = True;
		}
		$name = pathinfo(cpath() . $file, PATHINFO_FILENAME);
		ffmpeg_convert($file, cpath() . $name . "." . $format, $nv);
        } else {
        echo "Cannot find file: " . $file;
        }
} else {
        echo "Error: No valid post";
}

header("Location: index.php");
