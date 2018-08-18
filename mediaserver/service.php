<?php
include './prv/auth.php';
include './prv/settings.php';

if (isAuth() < 2) {
        die($noAuth);
}

function cpath() {
        return $GLOBALS['cpath'];
}

function safestr(&$str) {
        $str = str_replace('(', '\\(', str_replace(')', '\\)', $str));
}

function safestrquote($str) {
        return str_replace('"', '\\"', str_replace("'", "\\'", $str));
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

function precede($f, $l = 2) {
        $f = $f . "";
        while (strlen($f) < $l) {
                $f = "0" . $f;
        }
        return $f;
}


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

function constructTableRow($cache_file) {
        global $mpath, $assets;
        $row = '<tr name="listing">';
        $mf = $mpath . $cache_file . '.meta';
        $row = $row 
                . '<td><a target="_blank" href="./cache0/' . $cache_file . '">' . _CNAME($mf, $cache_file) . '</a></td>'
                . '<td>' . _LEN($mf, 'Unspecified') . '</td>'
                . '<td>' . _SIZE($mf, filesize(cpath() . $cache_file)) . '</td>';
        if (isAuth() > 2) {
                $type = _TYPE($mf, "n");
                $row = $row . '<td><input type="image" src="' . $assets . 'cur.png" class="renform" onclick="jsnopen(' . "'" . safestrquote($cache_file) . "'" . ')"></td>' . PHP_EOL
                . '<td><form action="research.php" method="post" class="formfull"><input name="file" type="image" value="' . $cache_file . '" src="' . $assets . 'mag.png" class="lookupform" alt="Submit Form"></form></td>' . PHP_EOL
                . '<td><input type="image" src="' . $assets . 'convert.png" class="renform" onclick="jscopen(' . "'" . safestrquote($cache_file) . "', '" . $type . "'" . ')"></td>' . PHP_EOL
                . '<td><form action="remove.php" method="post" class="formfull"><input name="file" type="image" value="' . $cache_file . '" src="' . $assets . 'del.png" class="remform" alt="Submit Form"></form></td>' . PHP_EOL;
        }
        $row = $row . '</tr>';
        return $row;
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

function _TYPE($mf, $def = "") {
        $o = iniTag($mf, 'type');
        if (strlen($o) > 0) {
                return $o;
        }
        return $def;
}

function ffprobe($cache_file, $safe = False) {
        if ($safe) {
                safestr($cache_file);
        }
        $base = "!ffprobe! -i \"!cpath!!cache_file!\" -show_format | sed -n 's/duration=//p'";
        $c = str_replace(array('!ffprobe!', '!cpath!', '!cache_file!'), array($GLOBALS['path_ffprobe'], cpath(), $cache_file), $base);
        $seconds = floatval(shell_exec($c));
        if ($seconds > 0) {
                return formatSeconds($seconds);
        }
        return "00:00:00.000";
}

function ffmpeg_convert($cache_file, $target_file, $vn = False) {
        $base = "!ffmpeg! -i \"!cpath!!cache_file!\"!vn! \"!target_file!\"";
        $c = str_replace(array('!ffmpeg!', '!cpath!', '!cache_file!', '!vn!', '!target_file!'), array($GLOBALS['path_ffmpeg'], cpath(), $cache_file, $vn ? ' -vn' : '', $target_file), $base);
        echo $c;
        shell_exec($c . " &");
}

function stat_size($cache_file, $safe = False) {
        if ($safe) {
                safestr($cache_file);
        }
        $c = str_replace("!file", cpath() . $cache_file, $GLOBALS['stat_cmd']);
        $c = str_replace("!name", $cache_file, $c);
        $longsize = shell_exec($c);
        return formatBytes(intval($longsize));
}

function imageDims($cache_file) {
        list($width, $height, $type, $attr) = getimagesize(cpath() . $cache_file);
        return $width . "x" . $height;
}

function StartsWith($hay, $needle) {
        return strpos($hay, $needle) === 0;
}

function getMime($cache_file) {
        return mime_content_type(cpath() . $cache_file);
}

function isAudio($cf) {
        return StartsWith(getMime($cf), "audio");
}

function isVideo($cf) {
        return StartsWith(getMime($cf), "video");
}

function isImage($cf) {
        return StartsWith(getMime($cf), "image");
}

function isText($cf) {
        return StartsWith(getMime($cf), "text");
}

function isPlayable($cache_file) {
        return isAudio($cache_file) || isVideo($cache_file);
}

function arrayIncludes($needle, $haystack) {
        foreach ($haystack as $k => $v) {
                if (strval($k) == $needle || $v == $needle) {
                        return true;
                }
        }
        return false;
}

function isBlacklistExtension($ext) {
        global $blacklist_extensions;
        return arrayIncludes(strtolower($ext), $blacklist_extensions) || $ext === "" || $ext == undefined;
}

function writeMeta($cache_file, $custName = "replace") {
        global $mpath, $enable_ffprobe, $enable_isize, $enable_stat, $override_extensions;
        $ext = pathinfo(cpath() . $cache_file, PATHINFO_EXTENSION);
        echo "<p>Actual Extension: '" . $ext . "'</p>";
        if (isBlacklistExtension($ext)) {
                return;
        }
        echo "<p>Path: " . cpath() . $cache_file . "</p>";
        $type = isAudio($cache_file) ? "a" : (isVideo($cache_file) ? "v" : (isImage($cache_file) ? "i" : "n"));
        if (arrayIncludes($ext, $override_extensions)) {
                echo "<p>(Overriding type)</p>";
                $type = $override_extensions[$ext];
        }
        echo "<p>Type: " . $type . " (" . getMime($cache_file) .  ")</p>";
        $dur = $type == "i" && $enable_isize ? imageDims($cache_file) : ($type != "n" && $enable_ffprobe ? ffprobe($cache_file) : "");
        echo "<p>Len: " . $dur . "</p>";
        $siz = $enable_stat ? stat_size($cache_file) : "";
        echo "<p>Size: " . $siz . "</p>";

        if ($custName == "replace" || strlen($custName) < 1) {
                $custName = $cache_file;
        }
        $res = fopen($mpath . $cache_file . ".meta", "w");
        $str = '[main]' . PHP_EOL . 'local = "' . $custName . '"' . PHP_EOL . 'length = "' . $dur . '"' . PHP_EOL . 'size = "' . $siz . '"' . PHP_EOL . 'type = "' . $type . '"';
        fwrite($res, $str);
        fclose($res);
}

//Only write the local name variable to metadata unless the metadata file doesn't exist, and write it fully.
function writeNameMeta_Preferred($cache_file, $custName) {
        $mf = $GLOBALS['mpath'] . $cache_file . ".meta";
        if (file_exists($mf)) {
                $dur = _LEN($mf);
                $siz = _SIZE($mf);
                $type = _TYPE($mf);
                $res = fopen($mf, "w");
                $str = '[main]' . PHP_EOL . 'local = "' . $custName . '"' . PHP_EOL . 'length = "' . $dur . '"' . PHP_EOL . 'size = "' . $siz . '"' . PHP_EOL . 'type = "' . $type . '"';
                fwrite($res, $str);
                fclose($res);
        } else {
                writeMeta($cache_file, $custName);
        }
}
