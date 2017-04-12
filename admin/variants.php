<?php

	require "inc/init.php";

	$TEMPLATE = "variants";
	$AJAX_REQ = "ajax/ajax_variants.php";

	// Sayfanin bilgilerini al
	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Default datatable ayarlari
	$DTSettings = array( "page" => 1, "direction" => "ASC", "rrp" => 50, "orderby" => "id" );

	// Datatable init
	$V_Table = new DataTable($TEMPLATE);
	$V_Table->set_settings( $DTSettings );
	// Ana varyantlar
	$Fetch = new Fetch($TEMPLATE);
	$Fetch->get_data( "default", $V_Table->get_settings(), false );

	$VARIANT_HEADER = "Ana Varyantlar";

	$V_Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
	$DATA_TABLE = $V_Table->show_table(); 
	$PAGIN = $V_Table->show_pagination(); 

	// Header
	include "inc/header.php";

	// Table
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";
