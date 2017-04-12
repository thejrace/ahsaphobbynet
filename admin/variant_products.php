<?php

	require "inc/init.php";

	$TEMPLATE = "variant_products";
	$AJAX_REQ = "ajax/ajax_variant_products.php";

	// Sayfanin bilgilerini al
	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// Ürünü tanımla ve kontrol et, yoksa die
	$Product = new Product( Input::get("pid") );
	if( !$Product->exists() ) die;
	// Ürünün varyantı var mı tanımlı, yoksa die
	$Product->variant_defs();
	if( !$Product->has_defined_variant() ) die;
	$P_ID = $Product->get_details("id");
	$P_NAME = $Product->get_details("product_name");
	$P_KDV = $Product->get_details("kdv_percentage");

	$Variant = new Variant(false);

	// Default datatable ayarlari
	$DTSettings = array( "page" => 1, "direction" => "ASC", "rrp" => 50, "orderby" => "id" );

	// Datatable init
	$PV_Table = new DataTable($TEMPLATE);
	$PV_Table->set_settings( $DTSettings );
	// Ana varyantlar
	$Fetch = new Fetch($TEMPLATE);
	$Fetch->get_data( "search", $PV_Table->get_settings(), array( "WHERE parent = ?", array( Input::get("pid"))) );

	$PV_Table->create( $Fetch->get_results(), $Fetch->get_record_count() );
	$DATA_TABLE = $PV_Table->show_table(); 
	$PAGIN = $PV_Table->show_pagination(); 

	// Tanımlı varyantları yukarıda listeledim, select fonksyionunda tekrar query yapma haziri al
	$VARIANT_SELECT = $Variant->list_all_as_select( $Product->get_product_variant_defined() );

	// Header
	include "inc/header.php";
	// echo $POST_OUTPUT;
	// var_dump( Session::get("log") );
	// Template
	include $PAGE->show_template();

	// Footer
	include "inc/footer.php";