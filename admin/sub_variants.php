<?php

	require "inc/init.php";

	$TEMPLATE = "sub_variants";
	$AJAX_REQ = "ajax/ajax_variants.php";
	
	$GET_ID = "varid";
	// Sayfanin bilgilerini al
	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Default datatable ayarlari
	$DTSettings = array( "page" => 1, "direction" => "ASC", "rrp" => 50, "orderby" => "id" );

	if( Input::get($GET_ID ) == "" ) die;
	// Datatable init
	$V_Table = new DataTable($TEMPLATE);
	$V_Table->set_settings( $DTSettings );
	$Fetch = new Fetch($TEMPLATE);
	
	// Üst varyantın datasını al
	$PARENT = Input::get($GET_ID);
	$ParentVariant = new Variant(false, $PARENT);
	$ParentDetails = $ParentVariant->get_details();

	$Fetch->get_data( "search", $V_Table->get_settings(), array( " WHERE parent = ?" , array($PARENT) ) );

	$VARIANT_HEADER = $ParentDetails["variant_name"] . " alt varyantları. <a href='variants.php'>[ Ana varyantlara dön ]</a>";
	
	$V_Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
	$DATA_TABLE = $V_Table->show_table(); 
	$PAGIN = $V_Table->show_pagination(); 

	// Header
	include "inc/header.php";

	// Table
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";
