# HTTP Media Server
##### Simple PHP scripts for hosting, downloading, uploading and managing files on your webserver.

**Version 1**
* User-friendly dashboard interface
* Easy to debug
* Basic HTTP Authentication for multiple users
* File management including renaming, deleting, loading file size (UNIX stat) and media duration (requires ffmpeg/ffprobe) information.
* Simple and lightweight metadata storage
* Search feature for finding files easily
* Friendly naming, avoiding file extensions on the dashboard
* Upload files from any device
* Download from multiple sites with [youtube-dl](https://github.com/rg3/youtube-dl) (Requires youtube-dl to be installed)
* Use, copy, change, share without licence

**Patches and Changes**
* File checking before uploading
* Better stability
* All settings in one place
* Broken less things

**Note:**
This "interface" is not as useful as other options when it comes to hosting files online, and potentially has security flaws in the PHP scripts which will be addressed in future updates, so use at your own risk!
Depending on your hardware and use for the server, the process of uploading and modifying files may not be optimised for many devices at once, however streaming/downloading files is not moderated by scripts meaning anyone with a URL to a file can access it.
##### Setup will require some knowledge of PHP/HTML
1. Clone/Download repository
2. Move files into your webserver tree
3. Install PHP if you do not already have it installed
4. (Linux) ensure that the cache directory has read and write permissions/is owned by the webserver (usually www-data)
5. Open your webserver configuration to limit upload size:
> in nginx: client_max_body_size to a larger size, e.g. 500M
> in apache: <Directory "/path/to/cache"> LimitRequestBody 500000000 </Directory>
6. Open your PHP configuration to limit upload size:
> post_max_size = 500M [must be larger than upload_max_filesize]
> upload_max_filesize = 490M
7. Open index.php in browser

You can also change further settings:
* Site favicon (index.php)
* HTTP authentication users
* /path/to ffprobe, youtube-dl, or alternate file size methods (note on 32-bit systems PHP's filesize function can not read files larger than about 2GB, so 'stat' is called by exec. instead)