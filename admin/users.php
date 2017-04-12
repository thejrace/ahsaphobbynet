<?php

	require "inc/init.php";

	$TEMPLATE = "users";
	$AJAX_REQ = "ajax/ajax_users.php";

	// Sayfanin bilgilerini al
	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Default datatable ayarlari
	$DTSettings = array( "page" => 1, "direction" => "ASC", "rrp" => 50, "orderby" => "id" );
	// Datatable init
	$Table = new DataTable($TEMPLATE);
	$Table->set_settings( $DTSettings );

	$Fetch = new Fetch($TEMPLATE);
	$Fetch->get_data( "default", $Table->get_settings(), false );

	$Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
	$DATA_TABLE = $Table->show_table(); 
	$PAGIN = $Table->show_pagination(); 

	// Header
	include "inc/header.php";

	// Table
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";