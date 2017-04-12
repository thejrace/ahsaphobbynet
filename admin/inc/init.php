<?php

	require "/home/ahsaphobby.net/httpdocs/v2/common/ah_base.php";


	// DB tablo isimleri
	require COMMON_DIR . "tables.php";

	define( "FOOTER_TEMP", ADMIN_INC_DIR . "footer.php" );
	define( "HEADER_TEMP", ADMIN_INC_DIR . "header.php" );

	$Session = new Session;
	$Session->start();

