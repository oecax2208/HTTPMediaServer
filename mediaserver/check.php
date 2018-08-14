<?php
include './prv/auth.php';
$err = 'Unauthorized (401) - Click <a href="/">here</a> to leave';

if (isAuth() > 1) {
	echo '<p id="success">Access granted to functions</p>';
} else {
	die($err);
}

function safestr(&$str) {
	$str = str_replace('(', '\\(', str_replace(')', '\\)', $str));
}

function escapeQuotes(&$str) {
	$str = str_replace("'", "&#39;", $str);
	$str = str_replace('"', "&#34;", $str);
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // $bytes /= pow(1024, $pow);
    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function precede($v, $l = 2) {
	$f = $v . "";
	while (strlen($f) < $l) {
		$f = "0" . $f;
	}
	return $f;
}

//Use echo here for debugging ffprobe (ffmpeg)
function formatSeconds($seconds) {
	$hh = intval($seconds / 3600);
	echo "<p>HH: " . $hh . "</p>";
	$mm = intval(($seconds - 3600 * $hh) / 60);
	echo "<p>MM: " . $mm . "</p>";
	$ss = intval($seconds - (3600 * $hh + 60 * $mm));
	echo "<p>SS: " . $ss . "</p>";
	$ms = intval(($seconds - floor($seconds)) * 1000);
	echo "<p>MS: " . $ms . "</p>";
	$a = array(precede($hh), precede($mm), precede($ss));
	return implode(":", $a) . "." . precede($ms, 3);
}

function iniTag($mf, $key) {
	if (file_exists($mf)) {
                $meta = parse_ini_file($mf);
                if (isset($meta[$key])) {
			return $meta[$key];
		}
        }
	return "";
}

function _CNAME($mf, $def = "") {
	$o = iniTag($mf, 'local');
	if (strlen($o) > 0) {
		return $o;
	}
	return $def;
}

function _LEN($mf, $def = "") {
        $o = iniTag($mf, 'length');
        if (strlen($o) > 0) {
                return $o;
        }
        return $def;
}

function _SIZE($mf, $def = "") {
        $o = iniTag($mf, 'size');
        if (strlen($o) > 0) {
                return $o;
        }
        return $def;
}

//Unix/Linux only, change location of ffprobe for other systems
function ffprobe($cache_file, $safe = False) {
	if ($safe) {
		safestr($cache_file);
	}
	$c = '/usr/bin/ffprobe -i "/var/www/html/mediaserver/cache0/' . $cache_file . '" -show_format | sed -n ' . "'s/duration=//p'";
	$seconds = floatval(shell_exec($c));
	if ($seconds > 0) {
		return formatSeconds($seconds);
	}
	return "00:00:00.000";
}

//Unix/Linux only, filesize alternatives exist for other systems
function stat_size($cache_file, $safe = False) {
	if ($safe) {
                safestr($cache_file);
        }
	$longsize = shell_exec('stat -c %s "/var/www/html/mediaserver/cache0/' . $cache_file . '"');
	return formatBytes(intval($longsize));
}

function writeMeta($cache_file, $custName = "replace") {
	$dur = ffprobe($cache_file);
	echo "<p>Len: " . $dur . "</p>";
	$siz = stat_size($cache_file);
	echo "<p>Size: " . $siz . "</p>";

	if ($custName == "replace" || strlen($custName) < 1) {
		$custName = $cache_file;
	}
	$res = fopen("/var/www/html/mediaserver/cache0/metadata/" . $cache_file . ".meta", "w");
	$str = '[main]' . PHP_EOL . 'local = "' . $custName . '"' . PHP_EOL . 'length = "' . $dur . '"' . PHP_EOL . 'size = "' . $siz . '"';
	fwrite($res, $str);
	fclose($res);
}

//Only write the local name variable to metadata unless the metadata file doesn't exist, and write it fully.
function writeNameMeta_Preferred($cache_file, $custName) {
	$mf = "/var/www/html/mediaserver/cache0/metadata/" . $cache_file . ".meta";
	if (file_exists($mf)) {
		$dur = _LEN($mf);
		$siz = _SIZE($mf);
		$res = fopen($mf, "w");
	        $str = '[main]' . PHP_EOL . 'local = "' . $custName . '"' . PHP_EOL . 'length = "' . $dur . '"' . PHP_EOL . 'size = "' . $siz . '"';
        	fwrite($res, $str);
	        fclose($res);
	} else {
		writeMeta($cache_file, $custName);
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Please wait...</title>
	</head>
	<body>
	</body>
</html>
