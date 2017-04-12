<?php

	require "/home/ahsaphobby.net/httpdocs/v2/common/ah_base.php";
	require "/home/ahsaphobby.net/httpdocs/v2/common/tables.php";

	define( "FOOTER_TEMP", SITE_INC_DIR . "footer.php" );
	define( "HEADER_TEMP", SITE_INC_DIR . "header.php" );

	$Session = new Session;
	$Session->start();

	// Login kontrolu
	$User = new User;
	$LOGGEDIN = $User->isset_sessions();
	$IS_GUEST = true;
	if( !$LOGGEDIN ){
		// Remember me
		$AutoLogin = new Login( array() );
		if( $AutoLogin->success() ){
			$User = new User( $AutoLogin->get_user_id() );
			$IS_GUEST = false;
		} else {
			// Misafir girisi yap
			$User = new Guest;
			$User->create_temp_id();
		}
	} else {
		$IS_GUEST = false;
	}
