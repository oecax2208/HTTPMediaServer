<?php
include 'service.php';

if (isset($_POST['file']) && isset($_POST['local'])) {
        $file = $_POST['file'];
	$newname = $_POST['local'];
        if (file_exists($cpath . $file) && strlen($newname) > 0) {
                echo "<p>Renaming " . $file . " to " . $newname . "</p>";
                $mf = $mpath . $file . ".meta";
                if (file_exists($mf)) {
                        echo "<p>INFO: Found meta file already, it will be changed</p>";
                        writeNameMeta_Preferred($file, $newname);
                } else {
                        writeMeta($file, $newname);
                }

        } else {
        echo "Cannot find file: " . $file;
        }
} else {
        echo "Error: No valid post";
}

header("Location: index.php");
