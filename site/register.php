<?php

	require "inc/init.php";

	if( $LOGGEDIN ) header("Location: index.php");

	$Page = new Page("register");
	$TITLE = $Page->get_title();

	// ajax degiskenleri
	$TEMPLATE = 'register';
	$AJAX_REQ = SITE_AJAX_URL . 'ajax_register.php';

	// breadcrumb olustur
	$BC = new Breadcrumb;
	$BC->set( array( array( "Kayıt", "register.php" ) ) );
	$BREADCRUMB = $BC->get();


	// Header
	include HEADER_TEMP;

	// Table
	include $Page->show_template();

	// // Footer
	include FOOTER_TEMP;