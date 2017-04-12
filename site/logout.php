<?php
	
	require "inc/init.php";

	if( !$LOGGEDIN ) header("Location: index.php");

	$Logout = new Logout;
	$Logout->action();
	header("Location: " . MAIN_URL . Input::get("return_url") );