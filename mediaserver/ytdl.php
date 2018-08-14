<?php
include 'check.php';

if (isset($_POST['url'])) {
	$url = $_POST['url'];
	$sa = "%(title)s";
	if (isset($_POST['saveas'])) {
		if (strlen($_POST['saveas']) > 0) {
			$sa = $_POST['saveas'];
		}
	}
	echo "<p>Trying to download " . $url . "</p>";
	if ($sa != "%(title)s") {
		safestr($sa);
	}
    //Enter path to youtube-dl executable
    passthru('/usr/local/bin/youtube-dl -o "/var/www/html/mediaserver/cache0/' . $sa . '.%(ext)s" ' . $url
    
    //Enable debug logging (enter log path)
	//passthru('/usr/local/bin/youtube-dl -o "/var/www/html/mediaserver/cache0/' . $sa . '.%(ext)s" ' . $url . " > /var/www/html/mediaserver/log 2>&1 &");
} else {
        echo "Error: No valid post";
}

header("Location: index.php");
?>
