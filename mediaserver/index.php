<?php
include './prv/auth.php';
if (isAuth() > 0) {
	echo '<p id="success">Logged in</p>';
} else {
	echo '<html><head><title>Login</title><link rel="stylesheet" href="/css/advanced/upload.css"></head><body><a href="login.php"><button class="bigbtn"><h3>Login</h3></button></a><a href="/"><button class="bigbtn"><h3>Return to homepage</h3></button></a></body></html>';
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Media Server Dashboard</title>
		<link rel="stylesheet" type="text/css" href="/css/upload.css" />
        <!--   Add your favicon here   -->
        <!--link rel="shortcut icon" type="image/png" href="/favicon.png" /-->
		<script type="text/javascript" src="/js/ilpms.js"></script>
	</head>
	<body>
		<h1>Media Server Dashboard</h1>
		<div id="web-stream">
			<h4 class="inline">Search:</h4>
			<input type="text" id="stream-search" value="" />
			<table id="tlog">
				<tr>
					<th>Media</th>
					<th>Length</th>
					<th>Size</th>
				<?php
					if (isAuth() > 2) {
						echo '<th class="t">Rename</th>' . PHP_EOL . '<th class="t">Reload Meta</th>' . PHP_EOL . '<th class="t">Delete</th>' . PHP_EOL;
					}
					echo "</tr>";

					function escapeQuotes($str) {
					        $str = str_replace("'", "\&#39;", $str);
					        $str = str_replace('"', "\&#34;", $str);
                            return $str;
					}

					$scan = scandir("./cache0");
					foreach($scan as $file) {
						if (is_dir("./cache0/$file")) {
							continue;
						}
						echo '<tr name="listing">';
						$mf = "./cache0/metadata/" . $file . ".meta";
						if (file_exists($mf)) {
							$meta = parse_ini_file($mf);
							echo '<td><a target="_blank" href="./cache0/' . $file . '">'. $meta['local'] . "</a></td>";
							echo "<td>" . $meta['length'] . "</td>";
							echo "<td>" . $meta['size'] . "</td>";
						} else {
							echo '<td><a href="./cache0/' . $file . '">' . $file . "</a></td>";
							echo "<td>Unspecified</td>";
							$size = filesize("./cache0/$file");
							echo "<td>$size</td>";
						}
						if (isAuth() > 2) {
							escapeQuotes($file);
							echo '<td><input type="image" src="/assets/cur.png" class="renform" onclick="jsnopen(' . "'" . escapeQuotes($file) . "'" . ')"></td>' . PHP_EOL;
							echo '<td><form action="research.php" method="post" class="formfull"><input name="file" type="image" value="' . $file . '" src="/assets/mag.png" class="lookupform" alt="Submit Form"></form></td>' . PHP_EOL;
							echo '<td><form action="remove.php" method="post" class="formfull"><input name="file" type="image" value="' . $file . '" src="/assets/del.png" class="remform" alt="Submit Form"></form></td>' . PHP_EOL;
						}
						echo "</tr>";
					}
				echo "</table></div>";
				if (isAuth() > 2) {
					echo '<div id="jsn-layer" style="display: none;">' . 
					'<div id="jsn-opacity"></div>' .
					'<div id="fixed-container">' .
					'<form action="ren.php" method="post">' .
					'<p class="f-label">Filename:</p>' .
					'<input id="ren-file" name="file" type="text" value="" readonly required>' .
					'<p class="f-label">New name:</p>' .
					'<input id="ren-name" name="local" type="text" value="">' .
					'<input name="submit" type="submit" value="Rename..."></form>' .
					'<button id="btn-close" onclick="jsnnone()">Close</button>' .
					'</div></div>';
				}
				if (isAuth() > 1) {
					echo '<div id="web-upload">' .
					'<div class="upload-fl">' .
					'<form action="upload.php" method="post" enctype="multipart/form-data">' .
					'<h2>Select file to upload:</h2>' .
					'<h4>Choose file:</h4>' .
					'<input type="file" name="newUpload" id="newUpload" required><br />' .
					'<h4>Enter New Name:</h4>' .
					'<input type="text" name="localName" id="localName"><br />' .
					'<input type="submit" value="Upload..." name="submit">' .
					'</form></div><div class="upload-fl2">' .
					'<h2>Enter youtube-dl direct link:</h2>' .
					'<form action="ytdl.php" method="post">' .
					'<h4>Enter URL:</h4>' .
					'<input type="text" name="url" id="ytUrl"><br />' .
					'<h4>And new name to save as (don\'t include extension):</h4>' .
					'<input type="text" name="saveas" id="ytSA"><br />' .
					'<input type="submit" value="Download..." name="submit">' .
					'</form></div><div class="clear" /></div>';
				}
		?>
	</body>
</html>
