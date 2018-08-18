<?php
	//
	//###   System and Security   ###
	//

	//Path/to cache (end with /cache0/)
	//It is usually safer to use absolute paths instead of relative paths
	$cpath = "/var/www/html/mediaserver/cache0/";
	$mpath = $cpath . "metadata/";

	//Remove an accepted conversion format by taking it out of the array:
	//Formats can be added, as long as they are compatible with ffmpeg or any extensions you have loaded
	$video_formats = array("mp4", "avi", "webm", "mov", "flv", "wmv");
	$audio_formats = array("mp3", "m4a", "wav", "ogg", "flac", "wma");

	//Some media formats are not picked up by MIME detection, enter the extensions below with their tag to add them
	//Tags: "a" - audio, "v" - video, "i" - image
	$override_extensions = array("opus" => "a");

	//Banned extensions will not be permitted for upload to the webserver tree
	//You should only add extensions, do not remove any!
	$blacklist_extensions = array("html", "htm", "php", "js", "asp", "exe", "msi", "sh", "bat", "cmd", "");

	//Disallow text (non-media) files, should be enabled to further prevent harmful files from being uploaded
	$disallow_text = True;

    /* ---   WIP START   --- */
	//Grant user bypass permission to upload any file they want
	$bypass = array("admin");

	//Ban IPs if they send too many blacklisted files to the server
	$enable_bans = False;
	$bad_uploads_for_ban = 3;
	//Ban reset format: "never", "d" - days, "h" - hours, "m" - minutes
	$ban_reset = "never";

	//Set the maximum cache size (MB) for media (leave 0 for unlimited)
	//WIP: Files over 2GB may not be correctly calculated and so more/less may be uploaded.
	$max_cache_size = 0;

	//Enable or disable the store of file metadata (size, friendly name, length etc.)
	//NOTE: With this disabled, the file size, length and conversion functions will be disabled
	$enable_metadata = True;
    /* ---   WIP END   --- */

	//
	//###   Resources   ###
	//

	//Path/to webpage assets [STARTING FROM TOP OF WEBTREE] (images etc.)
	$assets = "/assets/";


	//
	//###   Shell/App calls   ###
	//

	//Enable youtube-dl
	$enable_ytdl = True;
	$path_ytdl = "/usr/local/bin/youtube-dl";

	//Enable ffmpeg (conversion) and ffprobe (media duration)
	$enable_ffmpeg = True;
	$enable_ffprobe = True;
	$path_ffmpeg = "/usr/bin/ffmpeg";
	$path_ffprobe = "/usr/bin/ffprobe";

	//Enable stat (Unix)
        $enable_stat = True;
	//NOTE: Enter format of command for filesize if stat does not exist on your system below
	//Use placeholder: !file => path & file, !name => file only
	$stat_cmd = 'stat -c %s "!file"';

	//Enable Get Image Size (PHP)
	$enable_isize = True;
