<?php

	require "inc/init.php";

	if( $LOGGEDIN ) header("Location: " . MAIN_URL . "giris" );

	$Page = new Page("login");
	// $TITLE = $Page->get_title();

	// ajax degiskenleri
	$TEMPLATE = 'login';
	$AJAX_REQ = SITE_AJAX_URL . 'ajax_login.php';
	$TITLE = $Page->get_title();

	// breadcrumb olustur
	$BC = new Breadcrumb;
	$BC->set( array( array( "Giriş", "login.php" ) ) );
	$BREADCRUMB = $BC->get();

	// Login throttle baslangic kontrolleri
	$LT = new Login_Throttle;
	$LT->check_for_old_records();
	$LT->check();

	// Header
	include HEADER_TEMP;

	// Table
	include $Page->show_template();

	// // Footer
	include FOOTER_TEMP;