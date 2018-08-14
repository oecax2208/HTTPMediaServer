<?php

    //Add your user accounts here AND change your passwords!
	$uas = array('admin' => 'loginadmin', 'upload' => 'loginmedia', 'guest' => 'watchonline');
    
    //Change permissions below (see authentication types)
	$perms = array('admin' => 3, 'upload' => 2, 'guest' => 1);

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
?>
