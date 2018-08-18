<?php
include 'service.php';
include './prv/settings.php';

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
        $base = '!ytdl! -o "!cpath!!saveas!.%(ext)s" !geturl! &';
        $c = str_replace(array('!ytdl!', '!cpath!', '!saveas!', '!geturl!'), array($path_ytdl, $cpath, $sa, $url), $base);
        passthru($c);
} else {
        echo "Error: No valid post";
}

header("Location: index.php");
