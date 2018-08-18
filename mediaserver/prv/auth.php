<?php
	$uas = array('admin' => 'logmein', 'upload' => 'upload', 'guest' => 'guest');
	$perms = array('admin' => 3, 'upload' => 2, 'guest' => 1);

	$noAuth = '<p>Unauthorized (401) - Click <a href="/">here</a> to leave</p>';
    $success = '<p id="success">Access granted to functions</p>';

	//Return Values:
	//-1: No authentication set
	//0: Invalid authentication
	//1: Authentication for guest user
	//2: Authentication for upload user
	//3: Authentication for admin user
	function isAuth() {
		if (isset($_SERVER['PHP_AUTH_USER'])) {
			global $uas; global $perms;
			$u = $_SERVER['PHP_AUTH_USER'];
        	        $p = $_SERVER['PHP_AUTH_PW'];
	                foreach ($uas as $usr => $pwd) {
        	                if ($usr == $u && $pwd == $p) {
                	                return $perms[$u];
	                        }
			}
			return 0;
                }
		return -1;
	}
