<?php

	require "inc/init.php";

	$TEMPLATE = "showcase";
	$AJAX_REQ = "ajax/ajax_showcase.php";

	$PAGE     = new Page($TEMPLATE);
	$TITLE    = $PAGE->get_title();
	$SUBTITLE = $PAGE->get_subtitle();

	// vitrini tipi default home
	$SHOWCASE_TYPE = "showcase_home";
	// kategori vitrini icin kategori select
	$SEARCH_CATEGORIES = "";

	// showcase tipini varsa getten al
	// bunu da gecerli mi kontrol etmek lazim
	if( Input::get("type") != "" ) $SHOWCASE_TYPE = Input::get("type");

	//eger kategori vitrini ise, kategori id yoksa gene patla
	if( $SHOWCASE_TYPE == "showcase_category" ) {
		// BURDA KATEGORI SELECT yapilacak
		$C_Tree = new Category_Tree;
		if( Input::get("category_id") != "" ){
			// Eğer kategori sayfasından gelindiyse select i seçili yap
			$SEARCH_CATEGORIES = $C_Tree->categories_select_menu_selected( Input::get("category_id") );
		} else {
			$SEARCH_CATEGORIES = $C_Tree->categories_select_menu_default();
		}
		
	}

	// Showcase init
	// category_id boşsa yeni veya anasayfa vitrini
	$SC = new Showcase( $SHOWCASE_TYPE, Input::get("category_id") );
	$SC->get_showcase_data();

	// Default datatable ayarlari
	$DTSettings = array( "page" => 1, "direction" => "ASC", "rrp" => 50, "orderby" => "id" );
	// Datatable init
	$Table = new DataTable($TEMPLATE);
	$Table->set_settings( $DTSettings );
	
	// Vitrindeki urunleri listeleyip, tabloyu olusturu
	$SC_Data = $SC->get_products();
	$Table->create( $SC_Data, count($SC_Data) );

	// Template degiskenleri
	$DATA_TABLE = $Table->show_table(); 
	$PAGIN = $Table->show_pagination();

	// Eger kategori vitrini degilse headerlari classtan aliyorum
	$SHOWCASE_HEADER = $SC->get_header();

	
	include HEADER_TEMP;

	include $PAGE->show_template();

	include FOOTER_TEMP;