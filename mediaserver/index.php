<?php
include 'service.php';
if (isAuth() > 0) {
    
} else {
    echo '<html><head><title>Login</title><link rel="stylesheet" href="../css/upload.css"></head><body><a href="login.php"><button class="bigbtn"><h3>Login</h3></button></a><a href="/"><button class="bigbtn"><h3>Return to homepage</h3></button></a></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Media Server Dashboard</title>
        <link type="text/css" rel="stylesheet" href="../css/upload.css" />
        <link rel="shortcut icon" type="image/png" href="../assets/favicon.png" />
        <script type="text/javascript" src="../js/ilpms.js"></script>

        <?php
        $af = implode(",", $audio_formats);
        $vf = implode(",", $video_formats);
        if (strlen($af) > 1) {
            echo '<meta name="audio-f" content="' . $af . '">';
        }
        if (strlen($af) > 1) {
            echo '<meta name="video-f" content="' . $vf . '">';
        }
        ?>
    </head>
    <body>
        <h1>Media Server Dashboard</h1>
        <div id="web-stream">
            <h4 class="inline">Search:</h4>
            <input type="text" id="stream-search" value="">
            <table id="tlog">
                <tr>
                    <th>Media</th>
                    <th>Length</th>
                    <th>Size</th>

                    <?php
                    if (isAuth() > 2) {
                    echo '<th class="t">Rename</th>' . PHP_EOL . '<th class="t">Reload Meta</th>' . PHP_EOL . '<th class="t">Convert</th>' . PHP_EOL . '<th class="t">Delete</th>' . PHP_EOL;
                    }
                    echo "</tr>" . PHP_EOL;
                    $scan = scandir(cpath());
                    foreach($scan as $file) {
                        if (is_dir(cpath() . $file)) {
                            continue;
                        }
                        echo(constructTableRow($file));
                    }
                    
                    echo "</table></div>";
                      if (isAuth() > 2) {
                      echo '<div id="jsn-layer" style="display: none;">' .
                      '<div class="jsn-opacity"></div>' .
                      '<div id="fixed-container-a">' .
                      '<form action="ren.php" method="post">' .
                      '<p class="f-label">Filename:</p>' .
                      '<input id="ren-file" name="file" type="text" value="" readonly required>' .
                      '<p class="f-label">New name:</p>' .
                      '<input id="ren-name" name="local" type="text" value="">' .
                      '<input name="submit" type="submit" value="Rename..."></form>' .
                      '<button class="btn-close" onclick="jsnnone()">Close</button>' .
                      '</div></div>' .
                      '<div id="jsc-layer" style="display: none;">' .
                      '<div class="jsn-opacity"></div>' .
                      '<div id="fixed-container-b">' .
                      '<form action="convert.php" method="post">' .
                      '<p class="f-label">Filename:</p>' .
                      '<input id="convert-file" name="file" type="text" value="" readonly required>' .
                      '<p class="f-label">New format:</p>' .
                      '<select id="convert-format" name="format" required></select>' .
                      '<input name="submit" type="submit" value="Convert..."></form>' .
                      '<button class="btn-close" onclick="jscnone()">Close</button>' .
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
                </tr>
            </table>
        </div>
    </body>
</html>
