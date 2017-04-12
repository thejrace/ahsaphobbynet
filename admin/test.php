<?php
	// phpinfo();
	require "inc/init.php";

	// echo '<pre>';
	// print_r ( AHBase_Log::get() );


	session_destroy();
	Cookie::destroy("ahguest");